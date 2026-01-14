<?php

namespace App\Console\Commands;

use App\Models\InventoryItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ImportInventoryInbox extends Command
{
    protected $signature = 'inventory:import-inbox';
    protected $description = 'Importa CSV de inventario desde storage/app/inbox/inventory (inbox pattern)';

    public function handle(): int
    {
        $disk = Storage::build([
            'driver' => 'local',
            'root' => storage_path('app'),
        ]);


        $inboxDir = 'inbox/inventory';
        $processedDir = "{$inboxDir}/processed";
        $failedDir = "{$inboxDir}/failed";

        foreach ([$inboxDir, $processedDir, $failedDir] as $dir) {
            if (!$disk->exists($dir)) {
                $disk->makeDirectory($dir);
            }
        }

        $files = collect($disk->files($inboxDir))
            ->filter(fn ($p) => str_ends_with(strtolower($p), '.csv'))
            ->values();

        if ($files->isEmpty()) {
            return self::SUCCESS;
        }

        foreach ($files as $path) {
            $base = basename($path);
            $processing = "{$inboxDir}/.processing_{$base}";

            if (!$disk->move($path, $processing)) {
                continue;
            }

            try {
                $fullPath = $disk->path($processing);
                [$rows, $count] = $this->parseCsv($fullPath);

                
                $skus = collect($rows)->pluck('sku')->all();

                $existing = InventoryItem::query()
                    ->whereIn('sku', $skus)
                    ->get()
                    ->keyBy('sku');

                $finalRows = [];

                foreach ($rows as $row) {
                    $sku = $row['sku'];

                    if (isset($existing[$sku])) {
                        $row['stock'] = $existing[$sku]->stock + $row['stock'];
                        $row['created_at'] = $existing[$sku]->created_at;
                    }

                    $row['updated_at'] = now();
                    $finalRows[] = $row;
                }

                InventoryItem::upsert(
                    $finalRows,
                    ['sku'],
                    ['name', 'price', 'stock', 'updated_at']
                );


                $final = "{$processedDir}/" . preg_replace('/\.csv$/i', '', $base)
                    . "_ok_{$count}.csv";

                $disk->move($processing, $final);

                $this->info("✅ Imported {$base} ({$count} rows)");
            } catch (Throwable $e) {
                Log::warning('inventory_inbox_import_failed', [
                    'file' => $base,
                    'error' => $e->getMessage(),
                ]);

                $failedFile = "{$failedDir}/" . preg_replace('/\.csv$/i', '', $base) . "_failed.csv";
                $disk->move($processing, $failedFile);

                $disk->put("{$failedDir}/" . preg_replace('/\.csv$/i', '', $base) . ".error.txt", $e->getMessage());

                $this->error("❌ Failed {$base}: " . $e->getMessage());
                continue;
            }
        }

        return self::SUCCESS;
    }

    /**
     * @return array{0: array<int, array<string, mixed>>, 1: int}
     */
    private function parseCsv(string $fullPath): array
    {
        $fh = fopen($fullPath, 'rb');
        if (!$fh) {
            throw new \RuntimeException('cannot_open_file');
        }

        $header = fgetcsv($fh);
        if (!$header) {
            fclose($fh);
            throw new \RuntimeException('empty_csv');
        }

        $header = array_map(fn ($h) => strtolower(trim((string)$h)), $header);

        $required = ['sku', 'name', 'stock', 'price'];
        foreach ($required as $r) {
            if (!in_array($r, $header, true)) {
                fclose($fh);
                throw new \RuntimeException("missing_column_{$r}");
            }
        }

        $idx = array_flip($header);
        $now = now();

        $rows = [];
        $n = 0;

        while (($row = fgetcsv($fh)) !== false) {
            if (count($row) < count($header)) {
                continue;
            }

            $sku = strtoupper(trim((string)($row[$idx['sku']] ?? '')));
            if ($sku === '') {
                throw new \RuntimeException('invalid_sku');
            }

            $name = trim((string)($row[$idx['name']] ?? ''));
            $stock = (int) ($row[$idx['stock']] ?? 0);
            $price = (float) ($row[$idx['price']] ?? 0);

            if ($stock < 0) {
                throw new \RuntimeException("invalid_stock_for_{$sku}");
            }

            $rows[] = [
                'sku' => $sku,
                'name' => $name,
                'stock' => $stock,
                'price' => $price,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $n++;
        }

        fclose($fh);

        if ($n === 0) {
            throw new \RuntimeException('no_data_rows');
        }

        return [$rows, $n];
    }
}
