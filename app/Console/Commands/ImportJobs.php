<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Job;
use App\Modules\WorkOrders\Models\WorkOrder;
use PhpOffice\PhpWord\IOFactory;
use Carbon\Carbon;

class ImportJobs extends Command
{
    protected $signature = 'import:jobs {file : Ruta al archivo .docx}';
    protected $description = 'Importa jobs desde el archivo Word del cliente';

    public function handle()
    {
        $path = $this->argument('file');

        if (!file_exists($path)) {
            $this->error("Archivo no encontrado: $path");
            return 1;
        }

        $phpWord = IOFactory::load($path);
        $rows = [];

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if ($element instanceof \PhpOffice\PhpWord\Element\Table) {
                    foreach ($element->getRows() as $row) {
                        $cells = [];
                        foreach ($row->getCells() as $cell) {
                            $text = '';
                            foreach ($cell->getElements() as $el) {
                                if ($el instanceof \PhpOffice\PhpWord\Element\TextRun) {
                                    foreach ($el->getElements() as $textEl) {
                                        if (method_exists($textEl, 'getText')) {
                                            $text .= $textEl->getText();
                                        }
                                    }
                                } elseif ($el instanceof \PhpOffice\PhpWord\Element\Text) {
                                    $text .= $el->getText();
                                }
                            }
                            $cells[] = trim($text);
                        }
                        $rows[] = $cells;
                    }
                }
            }
        }

        // Saltar header
        $imported = 0;
        $skipped  = 0;

        foreach ($rows as $index => $row) {
            // Saltar filas vacías o headers
            if (empty($row) || count($row) < 3) {
                $skippedRows[] = [
                    'index'  => $index,
                    'reason' => 'fila vacía o header',
                    'data'   => $row
                ];
                continue;
            }

            $storeNumber = trim($row[0] ?? '');
            $site        = trim($row[1] ?? '');
            $service     = trim($row[2] ?? '');
            $dayDone     = trim($row[3] ?? '');
            $name        = trim($row[4] ?? '');
            $invoiceNo   = trim($row[5] ?? '');

            // Saltar fila de header o filas sin datos útiles
            if (strtoupper($storeNumber) === 'NUMBER' || empty($storeNumber)) {
                $skippedRows[] = [
                    'index'  => $index,
                    'reason' => 'salta fila de header o filas sin datos utiles',
                    'data'   => $row
                ];
                continue;
            }

            // Extraer Work Order ID del campo service (patrón WOT...)
            $workOrderId = null;
            if (preg_match('/WOT\w+/i', $service, $matches)) {
                $workOrderId = rtrim($matches[0], '.');
                // Limpiar la descripción quitando el WOT
                $serviceDesc = trim(preg_replace('/WOT\w+\.?/i', '', $service));
            } else {
                $serviceDesc = $service;
            }

            // Si no hay work_order_id, generar uno único para no violar constraint
            if (empty($workOrderId)) {
                $workOrderId = 'IMPORT-' . strtoupper(uniqid());
            }

            // Parsear fecha
            $parsedDate = null;
            if (!empty($dayDone) && !in_array(strtoupper($dayDone), [
                'PENDIENTE',
                'CANCELED',
                'CANCELADA',
                'NNEED ORDER',
                'IN PROCCESS',
                'WILL BE COMPLETE'
            ])) {
                // Limpiar texto extra en la fecha
                $cleanDate = preg_replace('/[^0-9\/\-].*$/', '', $dayDone);
                $cleanDate = trim($cleanDate);
                try {
                    if (preg_match('/^\d{2}[\/\-]\d{2}$/', $cleanDate)) {
                        // Formato 02/21 → asumir año actual
                        $parsedDate = Carbon::createFromFormat('m/d', $cleanDate)
                            ->year(2026);
                    } elseif (preg_match('/^\d{2}[\/\-]\d{2}[\/\-]\d{2,4}$/', $cleanDate)) {
                        $parsedDate = Carbon::parse($cleanDate);
                    }
                } catch (\Exception $e) {
                    $parsedDate = null;
                }
            }

            // Determinar status
            $status = 'pending';
            if ($parsedDate) {
                $status = 'completed';
            } elseif (in_array(strtoupper($dayDone), ['CANCELADA', 'CANCELED'])) {
                $status = 'completed'; // No hay status cancelado, marcar completado con nota
                $serviceDesc .= ' [CANCELADA]';
            }

            // Limpiar invoice number
            $invoice = preg_replace('/[^0-9]/', '', $invoiceNo);
            $invoice = empty($invoice) ? null : $invoice;

            // Limpiar nombre (a veces tiene montos mezclados como "JAVIER $450")
            $cleanName = preg_replace('/\$[\d,\.]+.*$/', '', $name);
            $cleanName = trim($cleanName);

            // Verificar si el work_order_id ya existe
            if (WorkOrder::where('work_order_id', $workOrderId)->exists()) {
                $this->warn("Duplicado omitido: $workOrderId");
                $skippedRows[] = [
                    'index'  => $index,
                    'reason' => 'duplicado omitido',
                    'data'   => $row
                ];
                continue;
            }

            try {
                WorkOrder::create([
                    'store_number'        => $storeNumber,
                    'site'                => $site,
                    'service_description' => $serviceDesc,
                    'work_order_id'       => $workOrderId,
                    'day_done'            => $parsedDate,
                    'assigned_name'       => $cleanName ?: null,
                    'invoice_number'      => $invoice,
                    'status'              => $status,
                ]);
                $imported++;
                $this->line("✓ $workOrderId — $site");
            } catch (\Exception $e) {
                $this->error("Error en fila $index: " . $e->getMessage());
                $skippedRows[] = [
                    'index'  => $index,
                    'reason' => 'fila vacía o error',
                    'data'   => $row
                ];
                continue;
            }
        }

        $this->newLine();
        $this->info("Importación completa: $imported importados, " . count($skippedRows) . " omitidos.");

        if (!empty($skippedRows)) {
            $this->newLine();
            $this->warn("Registros omitidos:");
            foreach ($skippedRows as $r) {
                $this->line("  Fila {$r['index']}: [{$r['reason']}] → " . implode(' | ', array_filter($r['data'])));
            }
        }
        return 0;
    }
}
