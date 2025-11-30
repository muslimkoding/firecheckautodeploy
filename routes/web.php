<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AparController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ZoneController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\FloorController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\HydrantController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SummaryController;
use App\Http\Controllers\Admin\AparHoseController;
use App\Http\Controllers\Admin\AparTypeController;
use App\Http\Controllers\Admin\BuildingController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\AparCheckController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AparHandleController;
use App\Http\Controllers\Admin\CompetencyController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\AparPinSealController;
use App\Http\Controllers\Admin\HydrantDoorController;
use App\Http\Controllers\Admin\HydrantHoseController;
use App\Http\Controllers\Admin\HydrantTypeController;
use App\Http\Controllers\Admin\AparCylinderController;
use App\Http\Controllers\Admin\AparPressureController;
use App\Http\Controllers\Admin\CobaController;
use App\Http\Controllers\Admin\EmployeeTypeController;
use App\Http\Controllers\Admin\HydrantCheckController;
use App\Http\Controllers\Admin\HydrantGuideController;
use App\Http\Controllers\Admin\HydrantNozzleController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\DashboardPersonil;
use App\Http\Controllers\Admin\ZoneAssignmentController;
use App\Http\Controllers\Admin\HydrantCouplingController;
use App\Http\Controllers\Admin\HydrantMainValveController;
use App\Http\Controllers\Admin\HydrantSafetyMarkingController;
use App\Http\Controllers\Admin\ExtinguisherConditionController;
use App\Http\Controllers\Admin\GroupSettingController;
use App\Http\Controllers\Admin\PositionSettingController;
use App\Http\Controllers\User\AparHistoryController;
use App\Http\Controllers\User\HydrantHistoryController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [AparHistoryController::class, 'indexStats'])->name('/');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role_or:superadmin,admin'])->group(function () {
    Route::get('masters', [MasterController::class, 'index'])->name('master.index');

    Route::resource('zones', ZoneController::class);
    Route::resource('buildings', BuildingController::class);
    Route::resource('apar-types', AparTypeController::class);
    Route::resource('floors', FloorController::class);
    Route::resource('hydrant-types', HydrantTypeController::class);
    Route::resource('extinguisher-conditions', ExtinguisherConditionController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('statuses', StatusController::class);
    Route::resource('employee-types', EmployeeTypeController::class);
    Route::resource('positions', PositionController::class);
    Route::resource('competencies', CompetencyController::class);
    Route::resource('apar-pressures', AparPressureController::class);
    Route::resource('apar-cylinders', AparCylinderController::class);
    Route::resource('apar-pin-seals', AparPinSealController::class);
    Route::resource('apar-hoses', AparHoseController::class);
    Route::resource('apar-handles', AparHandleController::class);
    Route::resource('hydrant-doors', HydrantDoorController::class);
    Route::resource('hydrant-couplings', HydrantCouplingController::class);
    Route::resource('hydrant-main-valve', HydrantMainValveController::class);
    Route::resource('hydrant-hoses', HydrantHoseController::class);
    Route::resource('hydrant-nozzles', HydrantNozzleController::class);
    Route::resource('hydrant-safety-markings', HydrantSafetyMarkingController::class);
    Route::resource('hydrant-guides', HydrantGuideController::class);

    // history/summary apar & hydrant
    Route::get('summary-apar', [SummaryController::class, 'indexApar'])->name('summary-apar.index');
    // Route::get('group-setting/create', [GroupSettingController::class, 'create'])->name('user.group.create');
    // Route::post('group-setting/store', [GroupSettingController::class, 'store'])->name('user.group.store');
    // Route::get('group-setting/edit/{id}', [GroupSettingController::class, 'edit'])->name('user.group.edit');
    // Route::post('group-setting/update/{id}', [GroupSettingController::class, 'update'])->name('user.group.update');
    // Route::delete('group-setting/delete/{id}', [GroupSettingController::class, 'destroy'])->name('user.group.delete');

    Route::get('summary-hydrant', [SummaryController::class, 'indexHydrant'])->name('summary-hydrant.index');

    Route::get('group-setting', [GroupSettingController::class, 'index'])->name('user.group');
    // Route::get('group-setting/create', [GroupSettingController::class, 'create'])->name('user.group.create');
    // Route::post('group-setting/store', [GroupSettingController::class, 'store'])->name('user.group.store');
    Route::get('group-setting/edit/{id}', [GroupSettingController::class, 'edit'])->name('user.group.edit');
    Route::post('group-setting/update/{id}', [GroupSettingController::class, 'update'])->name('user.group.update');
    // Route::delete('group-setting/delete/{id}', [GroupSettingController::class, 'destroy'])->name('user.group.delete');

    Route::get('position-setting', [PositionSettingController::class, 'index'])->name('user.position');
    // Route::get('position-setting/create', [PositionSettingController::class, 'create'])->name('user.position.create');
    // Route::post('position-setting/store', [PositionSettingController::class, 'store'])->name('user.position.store');
    Route::get('position-setting/edit/{id}', [PositionSettingController::class, 'edit'])->name('user.position.edit');
    Route::post('position-setting/update/{id}', [PositionSettingController::class, 'update'])->name('user.position.update');
    // Route::delete('position-setting/delete/{id}', [PositionSettingController::class, 'destroy'])->name('user.position.delete');
});

Route::middleware('auth')->group(function () {

    Route::resource('coba', CobaController::class);
    

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('admin/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard.admin');
    Route::get('personil/dashboard', [DashboardPersonil::class, 'index'])->name('dashboard.personil');


    // Route::resource('zones', ZoneController::class);
    // Route::resource('buildings', BuildingController::class);
    // Route::resource('apar-types', AparTypeController::class);
    // Route::resource('floors', FloorController::class);
    // Route::resource('hydrant-types', HydrantTypeController::class);
    // Route::resource('extinguisher-conditions', ExtinguisherConditionController::class);
    // Route::resource('groups', GroupController::class);
    // Route::resource('brands', BrandController::class);
    // Route::resource('statuses', StatusController::class);
    // Route::resource('employee-types', EmployeeTypeController::class);
    // Route::resource('positions', PositionController::class);
    // Route::resource('competencies', CompetencyController::class);
    // Route::resource('apar-pressures', AparPressureController::class);
    // Route::resource('apar-cylinders', AparCylinderController::class);
    // Route::resource('apar-pin-seals', AparPinSealController::class);
    // Route::resource('apar-hoses', AparHoseController::class);
    // Route::resource('apar-handles', AparHandleController::class);
    // Route::resource('hydrant-doors', HydrantDoorController::class);
    // Route::resource('hydrant-couplings', HydrantCouplingController::class);
    // Route::resource('hydrant-main-valve', HydrantMainValveController::class);
    // Route::resource('hydrant-hoses', HydrantHoseController::class);
    // Route::resource('hydrant-nozzles', HydrantNozzleController::class);
    // Route::resource('hydrant-safety-markings', HydrantSafetyMarkingController::class);
    // Route::resource('hydrant-guides', HydrantGuideController::class);

    Route::resource('apar', AparController::class);
    Route::get('/apar/{apar}/download-qrcode-svg', [AparController::class, 'downloadQrCodeSvg'])
    ->name('apar.download-qrcode-svg');

    Route::resource('hydrant', HydrantController::class);
    Route::get('/hydrant/{hydrant}/download-qrcode-svg', [HydrantController::class, 'downloadQrCodeSvg'])
    ->name('hydrant.download-qrcode-svg');

    Route::resource('user', UserController::class);
    Route::resource('permission', PermissionController::class);
    Route::resource('role', RoleController::class);
    Route::get('/roles/{role}/give-permissions', [RoleController::class, 'givePermissions'])->name('roles.give-permissions');
    Route::put('/roles/{role}/update-permissions', [RoleController::class, 'updatePermissions'])->name('roles.update-permissions');

    // routes/web.php
    // Route::resource('apar-check', AparCheckController::class);
    // Route::get('apar-check', [AparCheckController::class, 'index'])->name('apar-check.index');
    // Route::put('apar-check/{aparCheck}', [AparCheckController::class, 'edit'])->name('apar-check.edit');
    // Route::delete('apar-check/{aparCheck}', [AparCheckController::class, 'destroy'])->name('apar-check.destroy');
    // // Route::get('apar-check', [AparCheckController::class, 'edit'])->name('apar-check.edit');
    // Route::get('/apar-check/create/{apar}', [AparCheckController::class, 'create'])->name('apar-check.create');
    // Route::post('/apar-check/{apar}', [AparCheckController::class, 'store'])->name('apar-check.store');
    // Route::get('/apar-check/scan', [AparCheckController::class, 'scan'])->name('apar-check.scan');
    // Route::post('/apar-check/validate', [AparCheckController::class, 'validateApar'])->name('apar-check.validate');
    // Route::get('/apar-check/{aparCheck}', [AparCheckController::class, 'show'])->name('apar-check.show');

     // Halaman daftar pengecekan APAR
    Route::get('apar-check/', [AparCheckController::class, 'index'])->name('apar-check.index');
    Route::get('apar-check/edit/{aparCheck}', [AparCheckController::class, 'edit'])->name('apar-check.edit');
    Route::get('apar-check/scan', [AparCheckController::class, 'scan'])->name('apar-check.scan');
    Route::post('apar-check/validate', [AparCheckController::class, 'validateApar'])->name('apar-check.validate');
    Route::get('apar-check/create/{apar}', [AparCheckController::class, 'create'])->name('apar-check.create');
    Route::post('apar-check/{apar}', [AparCheckController::class, 'store'])->name('apar-check.store');
    Route::put('apar-check/{aparCheck}', [AparCheckController::class, 'update'])->name('apar-check.update');
    Route::delete('apar-check/{aparCheck}', [AparCheckController::class, 'destroy'])->name('apar-check.destroy');
    Route::get('apar-check/{aparCheck}', [AparCheckController::class, 'show'])->name('apar-check.show');

     
     // Halaman daftar pengecekan Hydrant
    Route::get('hydrant-check/', [HydrantCheckController::class, 'index'])->name('hydrant-check.index');
    Route::get('hydrant-check/edit/{hydrantCheck}', [HydrantCheckController::class, 'edit'])->name('hydrant-check.edit');
    Route::get('hydrant-check/scan', [HydrantCheckController::class, 'scan'])->name('hydrant-check.scan');
    Route::post('hydrant-check/validate', [HydrantCheckController::class, 'validateHydrant'])->name('hydrant-check.validate');
    Route::get('hydrant-check/create/{hydrant}', [HydrantCheckController::class, 'create'])->name('hydrant-check.create');
    Route::post('hydrant-check/{hydrant}', [HydrantCheckController::class, 'store'])->name('hydrant-check.store');
    Route::put('hydrant-check/{hydrantCheck}', [HydrantCheckController::class, 'update'])->name('hydrant-check.update');
    Route::delete('hydrant-check/{hydrantCheck}', [HydrantCheckController::class, 'destroy'])->name('hydrant-check.destroy');
    Route::get('hydrant-check/{hydrantCheck}', [HydrantCheckController::class, 'show'])->name('hydrant-check.show');
    Route::get('hydrant-to-check', [HydrantCheckController::class, 'hydrantToCheck'])->name('hydrant-check.to-check');


    // zone assignment
    Route::resource('zone-assignments', ZoneAssignmentController::class);
    Route::get('/zone-assignments/bulk-assign', [ZoneAssignmentController::class, 'showBulkAssign'])->name('zone-assignments.bulk-assign');
    Route::post('/zone-assignments/bulk-assign', [ZoneAssignmentController::class, 'bulkAssign'])->name('zone-assignments.bulk-assign');
    Route::get('/zone-assignments/{groupId}/zones', [ZoneAssignmentController::class, 'getAssignedZones'])->name('zone-assignments.assigned-zones');

    

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/password', [ProfileController::class, 'editPassword'])->name('profile.edit-password');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    

    

    // coba
    Route::get('/scan', [AparCheckController::class, 'showScan'])->name('apar-check.scan');
    Route::post('/validate-barcode', [AparCheckController::class, 'validateBarcode'])->name('apar-check.validate-barcode');
    Route::get('/create/{apar}', [AparCheckController::class, 'create'])->name('apar-check.create');
    Route::post('/{apar}', [AparCheckController::class, 'store'])->name('apar-check.store');
    Route::get('/history', [AparCheckController::class, 'history'])->name('apar-check.history');
    Route::get('/apar-to-check', [AparCheckController::class, 'aparToCheck'])->name('apar-check.to-check');
    Route::get('/unchecked', [AparCheckController::class, 'uncheckedApar'])->name('apar-check.unchecked');
    // Route::get('/progress', [AparCheckController::class, 'todayProgress'])->name('apar-check.progress');
    Route::get('/{aparCheck}', [AparCheckController::class, 'show'])->name('apar-check.show');
    
    

    
    
});




// Route::middleware(['auth'])->prefix('profile')->group(function () {
//     Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
//     Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
//     Route::get('/password', [ProfileController::class, 'editPassword'])->name('profile.edit-password');
//     Route::put('/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
// });

// Route::get('apar/scan', [AparHistoryController::class, 'scanForm'])->name('public.apar.scan');
//     Route::post('apar/scan', [AparHistoryController::class, 'showHistory'])->name('public.apar.scan.submit');
//     Route::get('apar/history/{qrCode}', [AparHistoryController::class, 'showHistory'])->name('public.apar.history');

Route::prefix('history')->group(function() {
    Route::get('apar/scan', [AparHistoryController::class, 'scanForm'])->name('public.apar.scan');
    Route::post('apar/scan', [AparHistoryController::class, 'showHistory'])->name('public.apar.scan.submit');
    Route::get('/history/{qrCode}', [AparHistoryController::class, 'showHistory'])->name('public.apar.history');
    Route::get('/last-checks', [AparHistoryController::class, 'getLatestChecks'])->name('public.apar.latest');
    Route::get('/emergency-contact', [AparHistoryController::class, 'index'])->name('public.apar.contacts');
});

Route::prefix('history')->group(function() {
    Route::get('hydrant/scan', [HydrantHistoryController::class, 'scanForm'])->name('public.hydrant.scan');
    Route::post('hydrant/scan', [HydrantHistoryController::class, 'showHistory'])->name('public.hydrant.scan.submit');
});
