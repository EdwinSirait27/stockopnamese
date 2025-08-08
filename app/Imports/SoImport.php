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
