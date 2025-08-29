<?php
use App\Http\Controllers\dashboardController;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SOController;
use App\Http\Controllers\Api\PosopnameController;
use Illuminate\Support\Facades\Route;
use App\Models\Posopnamesublocation;
use Illuminate\Support\Facades\Response;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('jwt.auth')->group(function () {
    Route::middleware(['role:Bos|Admin'])->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::apiResource('index', PosopnameController::class);
        Route::get('/check-print', [SOController::class, 'checkPrint']);
        Route::post('/select-db', [AuthController::class, 'selectDb']);
    });
});

// Route::get('/check-print', function () {
//     $printJob = Posopnamesublocation::where('status', 'REQ PRINT')->first();

//     if ($printJob) {
//         // update status jadi "PRINTING" biar ga dobel
//         $printJob->update(['status' => 'PRINTED']);

//         return Response::json([
//             'id' => $printJob->id,
//             'form_number' => $printJob->form_number,
//             'html' => view('printitem', compact('printJob'))->render()
//         ]);
//     }

//     return Response::json(['status' => 'NO JOB']);
// });


