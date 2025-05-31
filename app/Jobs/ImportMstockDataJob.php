<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
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
    //     $sourceData = DB::connection($this->sourceConnection)->table('mstock')->get();
    //     foreach ($sourceData as $row) {
    //         Mstock::updateOrCreate(
    //             ['BARA' => $row->BARA],
    //             [
    //                 'BARA2' => $row->BARA2,
    //                 'NAMA' => $row->NAMA,
    //                 'AVER' => $row->AVER,
    //                 'AWAL' => $row->AWAL,
    //                 'MASUK' => $row->MASUK,
    //                 'KELUAR' => $row->KELUAR,
    //                 'SATUAN' => $row->SATUAN,
    //             ]
    //         );
    //     }
    // }
    public function handle()
{
    // 1. Ambil nama database dari koneksi sumber
    $namaDB = DB::connection($this->sourceConnection)->getDatabaseName();

    // 2. Cari lokasi_id berdasarkan kolom 'bo' == nama database
    $lokasi = DB::table('bo')->where('bo', $namaDB)->first();

    if (!$lokasi) {
        logger()->error("Lokasi dengan BO $namaDB tidak ditemukan.");
        return;
    }

    $lokasi_id = $lokasi->id;
    // 3. Ambil data dari koneksi sumber
    $sourceData = DB::connection($this->sourceConnection)->table('mstock')->get();

    // 4. Simpan ke mstock lokal dengan menyertakan lokasi_id
    foreach ($sourceData as $row) {
        Mstock::updateOrCreate(
            [
                'BARA' => $row->BARA,
                'lokasi_id' => $lokasi_id, // Gunakan ini juga di bagian kondisi
            ],
            [
                'BARA2' => $row->BARA2,
                'NAMA' => $row->NAMA,
                'AVER' => $row->AVER,
                'AWAL' => $row->AWAL,
                'MASUK' => $row->MASUK,
                'KELUAR' => $row->KELUAR,
                'SATUAN' => $row->SATUAN,
                'lokasi_id' => $lokasi_id, // Dan di data update
            ]
        );
    }
}

}