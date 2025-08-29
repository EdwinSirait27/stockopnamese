<?php
namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;

class SoImport implements OnEachRow, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures, Importable;
    protected $errors;
    protected $db; // koneksi database dinamis cui

    public function __construct(&$errors, $db)
    {
        $this->errors = &$errors;
        $this->db     = $db;
    }

    public function onRow(Row $row)
    {
        $row = $row->toArray();

        if (empty($row['kdtoko']) || empty($row['kettoko'])) {
            $this->errors[] = "Kolom kdtoko dan kettoko wajib diisi.";
            return;
        }

      try {
    DB::connection($this->db)
        ->table('mtoko_soglo')
        ->insert([
            'kdtoko'   => $row['kdtoko'],
            'kettoko'  => $row['kettoko'],
            'kddept'   => null,
            'masuk'    => 0,
            'personil' => null,
            'inpmasuk' => null,
            'balik'     => 0,
        ]);
} catch (\Illuminate\Database\QueryException $e) {
    if ($e->getCode() == 23000) { // duplicate entry
        $this->errors[] = "Data dengan kdtoko {$row['kdtoko']} sudah ada.";
    } else {
        throw $e; 
    }
}

    }

    public function rules(): array
    {
        return [
            '*.kdtoko'  => ['required'],
            '*.kettoko' => ['required'],
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
