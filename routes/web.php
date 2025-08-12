<?php

use App\Http\Controllers\dashboardController;
use App\Http\Controllers\ScanbarcodeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ButtonsController;
use App\Http\Controllers\dashboardAdminController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\dasboardPenginputController;
use App\Http\Controllers\CurrentdbController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\DbController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::redirect('/', '/dashboard-general-dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/', function (LoginController $controller) {
        return Auth::check() ? redirect('/dashboard') : $controller->index();
    })->middleware('throttle:5,1')->name('login');
    Route::post('/auth-login', [LoginController::class, 'login'])->name('auth-login.login');

});
Route::middleware(['auth', 'role:Bos'])->group(function () {
    Route::post('/auth-register', [RegisterController::class, 'register'])->name('auth-register.register');
    Route::get('/auth-register', [RegisterController::class, 'index'])->name('auth-register.auth-register');
    Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard');
    Route::get('/posopname/posopname', [dashboardController::class, 'getPosopnames'])->name('posopname.posopname');
    Route::get('/showdashboard/{opname_id}', [dashboardController::class, 'show'])->name('pages.showdashboard');
    Route::get('/posopnamesublocations/posopnamesublocations', [dashboardController::class, 'getPosopnamesublocations'])->name('posopnamesublocations.posopnamesublocations');


    Route::post('/scan-barcode', [ScanbarcodeController::class, 'scanBarcode'])->name('scan.barcode');
    Route::get('/scanbarcode', function () {
        return view('pages.scanbarcode');
    })->name('scan.page');
    Route::get('/blank-page', function () {
        return view('pages.blank-page', ['type_menu' => '']);
    });
    Route::get('/importso/use/{opname_id}', [dashboardController::class, 'indexso'])->name('importso.use');
    Route::post('/Importso/{opname_id}', [dashboardController::class, 'Importso'])->name('Importso.use');
    Route::get('/Importso/downloadso/{filename}', [dashboardController::class, 'downloadso'])->name('Importso.downloadso');
    Route::get('/roles', [RoleController::class, 'index'])
        ->name('roles.index');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::get('/role/role', [RoleController::class, 'getRoles'])->name('role.role');
    Route::get('/permissions', [PermissionController::class, 'index'])
        ->name('permissions.index');
    Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/edit/{id}', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::get('/permissions/permissions', [PermissionController::class, 'getPermissions'])->name('permissions.permissions');

    Route::match(['get', 'post'], '/DB', [DbController::class, 'index'])->name('DB.index');
    Route::get('/mstock/mstock', [DbController::class, 'getMstock'])->name('mstock.mstock');
    Route::post('/import-stock', [DbController::class, 'import'])->name('stock.import');
    //   Route::get('/import-stock', [DbController::class, 'import'])->name('stock.import');

    Route::get('/buttons', [ButtonsController::class, 'index'])->name('buttons.index');
    Route::get('/buttons/buttons', [ButtonsController::class, 'getButtons'])->name('buttons.buttons');
    Route::get('/buttons/edit/{id}', [ButtonsController::class, 'edit'])->name('buttons.edit');
    Route::put('/buttons/{id}', [ButtonsController::class, 'update'])->name('buttons.update');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/users', [UserController::class, 'getUsers'])->name('users.users');
    Route::get('/users/edit/{hashedId}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{hashedId}', [userController::class, 'update'])->name('users.update');

    Route::get('/Currentdb', [CurrentdbController::class, 'index'])->name('Currentdb.index');
    Route::get('/currentdb/currentdb', [CurrentdbController::class, 'getCurrentdb'])->name('currentdb.currentdb');
    Route::get('/import-progress', function () {
        return response()->json([
            'progress' => Cache::get('import_progress', 0),
            'done' => Cache::get('import_progress_done', false),
        ]);
    })->name('import.progress');

    // Halaman Show Item berdasarkan form_number
    Route::get('/opname/showitem/{form_number}', [dashboardController::class, 'showitem'])
        ->name('opname.showitem');

    // Endpoint DataTables untuk item berdasarkan form_number
    Route::get('/opname/getshowitem', [dashboardController::class, 'getshowitem'])
        ->name('opname.getshowitem');
    // routes/web.php
// Route::get('/printitem/{form_number}', [dashboardController::class, 'printitem'])->name('printitem.print');
    Route::get('/opname/printitem/{form_number}', [dashboardController::class, 'printitem'])
        ->name('opname.printitem');
});
Route::middleware(['auth', 'role:Admin'])->group(function () {

    Route::get('/dashboardadmin', [dashboardAdminController::class, 'index'])->name('dashboardadmin');
    Route::get('/posopnameadmin/posopnameadmin', [dashboardAdminController::class, 'getPosopnamesadmin'])->name('posopnameadmin.posopnameadmin');
    // Route::get('/admin/show/{opname_id}', [dashboardAdminController::class, 'showadmin'])->name('pages.showdashboardadmin');

    Route::get('/showdashboardadmin/{opname_id}', [dashboardAdminController::class, 'showadmin'])->name('pages.showdashboardadmin');
    // Route::get('/showdashboardadmin/{opname_id}', [dashboardAdminController::class, 'showadmin'])->name('pages.showdashboardadmin');
    Route::get('/posopnamesublocationsadmin/posopnamesublocationsadmin', [dashboardAdminController::class, 'getPosopnamesublocationsadmin'])->name('posopnamesublocationsadmin.posopnamesublocationsadmin');
    Route::get('/importsoadmin/use/{opname_id}', [dashboardAdminController::class, 'indexsoadmin'])->name('importsoadmin.use');
    Route::post('/importsoadmin/{opname_id}', [dashboardAdminController::class, 'Importsoadmin'])->name('Importsoadmin.use');
    Route::get('/Importsoadmin/downloadsoadmin/{filename}', [dashboardAdminController::class, 'downloadsoadmin'])->name('Importsoadmin.downloadsoadmin');
    Route::get('/opname/showitemadmin/{form_number}', [dashboardAdminController::class, 'showitemadmin'])
        ->name('opname.showitemadmin');
    // Endpoint DataTables untuk item berdasarkan form_number
    Route::get('/opname/getshowitemadmin', [dashboardAdminController::class, 'getshowitemadmin'])
        ->name('opname.getshowitemadmin');
    // routes/web.php
// Route::get('/printitem/{form_number}', [dashboardController::class, 'printitem'])->name('printitem.print');
    Route::get('/opname/printitemadmin/{form_number}', [dashboardAdminController::class, 'printitemadmin'])
        ->name('opname.printitemadmin');
});
Route::middleware(['auth', 'role:Admin|Bos|Penginput'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::put('/features-profile/update', [UserRoleController::class, 'updatePassword'])->name('features-profile.update');
    Route::put('/features-profile', [UserRoleController::class, 'index'])->name('features-profile');
    Route::get('/features-profile', function () {
        return view('pages.features-profile', ['type_menu' => 'features']);
    });
});
Route::middleware(['auth', 'role:Penginput'])->group(function () {
    Route::get('/dashboardpenginput/{opname_id}', [dasboardPenginputController::class, 'show'])->name('dashboardpenginput');
    Route::get('/posopnamesublocationpenginput/posopnamesublocationpenginput', [dasboardPenginputController::class, 'getPosopnamesublocationspenginput'])->name('posopnamesublocationpenginput.posopnamesublocationpenginput');
    Route::get('/scan/{opname_sub_location_id}', [dasboardPenginputController::class, 'scan'])->name('scan');
    Route::post('/scan/{opname_sub_location_id}', [dasboardPenginputController::class, 'scanPost'])->name('scan.post');
  Route::get('/posopname/items/{id}', [dasboardPenginputController::class, 'getPosopnameItems'])
    ->name('posopname.items');
Route::post('/posopname/{opname_sub_location_id}/scan-preview', [dasboardPenginputController::class, 'scanBarcodePreview'])
    ->name('posopname.scanBarcodePreview');
Route::post('/save-scanned-item/{opname_sub_location_id}', [dasboardPenginputController::class, 'saveScannedItem']);
Route::get('/posopname-item/{id}', [dasboardPenginputController::class, 'showposopnameitem']);
Route::put('/posopname-item/{id}', [dasboardPenginputController::class, 'update']);
Route::post('/posopnamesublocation/{id}/req-print', [dasboardPenginputController::class, 'reqPrint'])
    ->name('posopnamesublocation.reqPrint');

});
Route::get('/redirect-by-role', function () {
    $user = Auth::user();

    if ($user->hasRole('Bos')) {
        return redirect('/dashboard');
    } elseif ($user->hasRole('Admin')) {
        return redirect('/dashboardadmin');
    } elseif ($user->hasRole('Penginput')) {
        $opnameId = $user->location_id; // ambil dari kolom location_id user

        if (!$opnameId) {
            // Jika tidak ada location_id, redirect ke halaman lain atau beri error
            return redirect('/')->withErrors(['error' => 'Location ID tidak ditemukan.']);
        }

        return redirect("/dashboardpenginput/{$opnameId}");
    }

    return redirect('/dashboard'); // fallback
})->middleware('auth');


        //   @foreach($posopnameitems_by_location as $item)
        //             <tr>
        //                 <td>{{ $item->opname_item_id }}</td>
        //                 <td>{{ $item->item->name ?? 'N/A' }}</td>
        //                 <td>{{ $item->item->barcode ?? 'N/A' }}</td>
        //                 <td>{{ $item->qty_system }}</td>
        //                 <td>{{ $item->qty_real }}</td>
        //                 <td>{{ $item->note ?? '-' }}</td>
        //             </tr>
        //         @endforeach