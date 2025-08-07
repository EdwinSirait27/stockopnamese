<?php
namespace App\Imports;
use App\Models\Posopnamesublocation;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\MessageBag;
// class SoImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
// {
//      use SkipsFailures, Importable;

//     protected $errors;

//     public function __construct(&$errors)
//     {
//         $this->errors = &$errors;
//     }

//     public function model(array $row)
//     {
//         // Cek duplikasi berdasarkan opname_id
//         $posopnamesublocation = Posopnamesublocation::with('opname.location', 'sublocation', 'users')
//             ->where('opname_id', $row['opname_id'])
//             ->first();
//         $date = null;
//         if (!empty($row['date'])) {
//             $date = is_numeric($row['date'])
//                 ? Date::excelToDateTimeObject($row['date'])->format('Y-m-d')
//                 : Carbon::parse($row['date'])->format('Y-m-d');
//         }
//          do {
//         $time = now()->timestamp;
//         $rand = mt_rand(100, 999); 
//         $id = (int) ($time . $rand); 
//     } while (Posopnamesublocation::where('opname_sub_location_id', $id)->exists());

//     // Format tanggal
//     $date = null;
//     if (!empty($row['date'])) {
//         $date = is_numeric($row['date'])
//             ? Date::excelToDateTimeObject($row['date'])->format('Y-m-d')
//             : Carbon::parse($row['date'])->format('Y-m-d');
//     }

//         // Simpan data baru
//         return new Posopnamesublocation([
//             'opname_sub_location_id' => $id,
//             'opname_id'              => $row['opname_id'] ?? null,
//             'sub_location_id'        => $row['sub_location_id'] ?? null,
//             'status'                 => $row['status'] ?? null,
//             'user_id'                => $row['user_id'] ?? null,
//             'form_number'            => $row['form_number'] ?? null,
//             'date'                   => $date,
//         ]);
//     }

//     public function rules(): array
//     {
//         return [
//             '*.opname_id'         => ['required'],
//             '*.sub_location_id'   => ['required'],
//             '*.user_id'           => ['required'],
//             '*.form_number'       => ['required'],
//             '*.status'            => ['required'],
//         ];
//     }

//     public function chunkSize(): int
//     {
//         return 500;
//     }
// }
class SoImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures, Importable;

    protected $errors;
    protected $defaultOpnameId;
    protected $defaultSubLocationId;
    protected $defaultDate;
    // protected $defaultUserId;
    protected $defaultStatus;

    public function __construct(&$errors, $opname_id, $sub_location_id, $date, $status = 'DRAFT')
    {
        $this->errors = &$errors;
        $this->defaultOpnameId = $opname_id;
        $this->defaultSubLocationId = $sub_location_id;
        $this->defaultDate = Carbon::parse($date)->format('Y-m-d');
        // $this->defaultUserId = $user_id;
        $this->defaultStatus = $status;
    }

    // public function model(array $row)
    // {
    //     do {
    //         $time = now()->timestamp;
    //         $rand = mt_rand(100, 999);
    //         $id = (int) ($time . $rand);
    //     } while (Posopnamesublocation::where('opname_sub_location_id', $id)->exists());

    //     return new Posopnamesublocation([
    //         'opname_sub_location_id' => $id,
    //         'opname_id'              => $this->defaultOpnameId,
    //         'sub_location_id'        => $this->defaultSubLocationId,
    //         'status'                 => $this->defaultStatus,
    //         'user_id'                => $this->defaultUserId,
    //         'form_number'            => $row['form_number'] ?? null,
    //         'date'                   => $this->defaultDate,
    //     ]);
    // }
public function model(array $row)
{
    // Cek form_number wajib
    if (empty($row['form_number'])) {
        $this->errors[] = 'Form number wajib diisi.';
        return null;
    }

    // Cek duplikat form_number untuk kombinasi opname_id + sub_location_id
    $isDuplicate = Posopnamesublocation::where('form_number', $row['form_number'])
        ->where('opname_id', $this->defaultOpnameId)
        ->where('sub_location_id', $this->defaultSubLocationId)
        ->exists();

    if ($isDuplicate) {
        $this->errors[] = "Form number '{$row['form_number']}' sudah ada di lokasi dan opname ini.";
        return null;
    }

    // Generate unik ID
    do {
        $time = now()->timestamp;
        $rand = mt_rand(100, 999);
        $id = (int) ($time . $rand);
    } while (Posopnamesublocation::where('opname_sub_location_id', $id)->exists());

    return new Posopnamesublocation([
        'opname_sub_location_id' => $id,
        'opname_id'              => $this->defaultOpnameId,
        'sub_location_id'        => $this->defaultSubLocationId,
        'status'                 => $this->defaultStatus,
        'form_number'            => $row['form_number'],
        'date'                   => $this->defaultDate,
    ]);
}

    public function rules(): array
    {
        return [
            '*.form_number' => ['required'],
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
