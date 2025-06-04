<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

use App\Models\Mstock;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;

class ImportMstockDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $sourceConnection;

    public function __construct($sourceConnection)
    {
        $this->sourceConnection = $sourceConnection;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
   
// public function handle()
// {
//     $namaDB = DB::connection($this->sourceConnection)->getDatabaseName();
//     $lokasi = DB::table('bo')->where('bo', $namaDB)->first();

//     if (!$lokasi) {
//         logger()->error("Lokasi dengan BO $namaDB tidak ditemukan.");
//         return;
//     }

//     $bo_id = $lokasi->id;
//     $total = DB::connection($this->sourceConnection)->table('mstock')->count();
//     static $processed = 0;

//     DB::connection($this->sourceConnection)
//         ->table('mstock')
//         ->orderBy('BARA')
//         ->chunk(1500, function ($rows) use ($bo_id, $total, &$processed) {
//             foreach ($rows as $row) {
//                 Mstock::updateOrCreate(
//                     [
//                         'BARA' => $row->BARA,
//                         'bo_id' => $bo_id,
//                     ],
//                     [
//                         'BARA2' => $row->BARA2,
//                         'NAMA' => $row->NAMA,
//                         'AVER' => $row->AVER,
//                         'AWAL' => $row->AWAL,
//                         'MASUK' => $row->MASUK,
//                         'KELUAR' => $row->KELUAR,
//                         'SATUAN' => $row->SATUAN,
//                     ]
//                 );
//             }

//             $processed += count($rows);
//             logger()->info("Progress import: " . round($processed / $total * 100) . "%");
//         });
// }
// public function handle()
// {
//     $namaDB = DB::connection($this->sourceConnection)->getDatabaseName();
//     $lokasi = DB::table('bo')->where('bo', $namaDB)->first();
//     if (!$lokasi) {
//         logger()->error("Lokasi dengan BO $namaDB tidak ditemukan.");
//         return;
//     }
//     $bo_id = $lokasi->id;
//     $total = DB::connection($this->sourceConnection)->table('mstock')->count();
//     static $processed = 0;
//     DB::connection($this->sourceConnection)
//         ->table('mstock')
//         ->orderBy('BARA')
//         ->chunk(3000, function ($rows) use ($bo_id, $total, &$processed) {
//             $dataToUpsert = [];

//             foreach ($rows as $row) {
//                 $dataToUpsert[] = [
//                     'BARA' => $row->BARA,
//                     'bo_id' => $bo_id,
//                     'BARA2' => $row->BARA2,
//                     'NAMA' => $row->NAMA,
//                     'AVER' => $row->AVER,
//                     'AWAL' => $row->AWAL,
//                     'MASUK' => $row->MASUK,
//                     'KELUAR' => $row->KELUAR,
//                     'SATUAN' => $row->SATUAN,
//                 ];
//             }
//             DB::transaction(function () use ($dataToUpsert) {
//                 Mstock::withoutEvents(function () use ($dataToUpsert) {
//                     Mstock::upsert(
//                         $dataToUpsert,
//                         ['BARA', 'bo_id'],
//                         ['BARA2', 'NAMA', 'AVER', 'AWAL', 'MASUK', 'KELUAR', 'SATUAN']
//                     );
//                 });
//             });
//             $processed += count($rows);
//             logger()->info("Progress import: " . round($processed / $total * 100) . "%");
//         });
// }
public function handle()
{
    $namaDB = DB::connection($this->sourceConnection)->getDatabaseName();
    $lokasi = DB::table('bo')->where('bo', $namaDB)->first();
    if (!$lokasi) {
        logger()->error("Lokasi dengan BO $namaDB tidak ditemukan.");
        return;
    }
    $bo_id = $lokasi->id;
    $total = DB::connection($this->sourceConnection)->table('mstock')->count();
    static $processed = 0;
    DB::connection($this->sourceConnection)
        ->table('mstock')
        ->orderBy('BARA')
        ->chunk(3000, function ($rows) use ($bo_id, $total, &$processed) {
            $dataToUpsert = [];

            foreach ($rows as $row) {
                $dataToUpsert[] = [
                    'BARA'   => $row->BARA,
                    'bo_id'  => $bo_id,
                    'BARA2'  => $row->BARA2,
                    'NAMA'   => $row->NAMA,
                    'AVER'   => $row->AVER,
                    'AWAL'   => $row->AWAL,
                    'MASUK'  => $row->MASUK,
                    'KELUAR' => $row->KELUAR,
                    'SATUAN' => $row->SATUAN,
                ];
            }
            DB::transaction(function () use ($dataToUpsert) {
                Mstock::withoutEvents(function () use ($dataToUpsert) {
                    Mstock::upsert(
                        $dataToUpsert,
                        ['BARA', 'bo_id'],
                        ['BARA2', 'NAMA', 'AVER', 'AWAL', 'MASUK', 'KELUAR', 'SATUAN']
                    );
                });
            });
            $processed += count($rows);
            $percentage = round($processed / $total * 100);

            logger()->info("Progress import: {$percentage}%");

            // Simpan ke cache untuk dibaca oleh Livewire
            Cache::put('import_progress', $percentage);
        });

    // Optional: tandai bahwa job sudah selesai
    Cache::put('import_progress_done', true);
}


}