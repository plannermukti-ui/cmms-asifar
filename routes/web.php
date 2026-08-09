<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/guide', [\App\Http\Controllers\GuideController::class, 'index'])->name('guide');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Users
    Route::resource('/users', \App\Http\Controllers\UserController::class);

    // Master Data
    Route::get('/master-data', [\App\Http\Controllers\MasterDataController::class, 'index'])->name('master-data.index');
    Route::get('/kpi-master-data/export', [\App\Http\Controllers\KpiController::class, 'exportMasterData'])->name('kpi.master-data.export');
    Route::resource('/departments', \App\Http\Controllers\DepartmentController::class);
    Route::resource('/jabatans', \App\Http\Controllers\JabatanController::class);
    Route::resource('/roles', \App\Http\Controllers\RoleController::class);
    Route::resource('/modules', \App\Http\Controllers\ModuleController::class);

    // Units
    Route::resource('/unit-types', \App\Http\Controllers\UnitTypeController::class);
    Route::resource('/unit-models', \App\Http\Controllers\UnitModelController::class);
    Route::resource('/master-units', \App\Http\Controllers\MasterUnitController::class);
    Route::post('/hour-meters/import', [\App\Http\Controllers\HourMeterController::class, 'import'])->name('hour-meters.import');
    Route::get('/hour-meters/download-template', [\App\Http\Controllers\HourMeterController::class, 'downloadTemplate'])->name('hour-meters.download-template');
    Route::resource('/hour-meters', \App\Http\Controllers\HourMeterController::class);
    
    // Settings (General + SMTP)
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/test-email', [\App\Http\Controllers\SettingController::class, 'testEmail'])->name('settings.test-email');
    
    // Sites
    Route::resource('/sites', \App\Http\Controllers\SiteController::class);

    // Activity Log
    Route::get('/activity-log', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-log.index');

    // Backup
    Route::get('/backup', [\App\Http\Controllers\BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/create', [\App\Http\Controllers\BackupController::class, 'create'])->name('backup.create');
    Route::get('/backup/download/{filename}', [\App\Http\Controllers\BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/{filename}', [\App\Http\Controllers\BackupController::class, 'destroy'])->name('backup.destroy');

    // Approval Matrix
    Route::get('/approval-matrix', [\App\Http\Controllers\ApprovalMatrixController::class, 'index'])->name('approval-matrix.index');
    Route::post('/approval-matrix', [\App\Http\Controllers\ApprovalMatrixController::class, 'store'])->name('approval-matrix.store');
    Route::delete('/approval-matrix/{id}', [\App\Http\Controllers\ApprovalMatrixController::class, 'destroy'])->name('approval-matrix.destroy');

    // Live Chat
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/users', [\App\Http\Controllers\ChatController::class, 'getUsers'])->name('chat.users');
    Route::get('/chat/messages/{userId}', [\App\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/unread-count', [\App\Http\Controllers\ChatController::class, 'unreadCount'])->name('chat.unread-count');
    Route::get('/chat/search-document', [\App\Http\Controllers\ChatController::class, 'searchDocument'])->name('chat.search-document');
    Route::delete('/chat/clear/{userId}', [\App\Http\Controllers\ChatController::class, 'clearChat'])->name('chat.clear');
    // Administrasi ToolRoom
    Route::resource('/mechanics', \App\Http\Controllers\MechanicController::class);
    Route::resource('/tool-categories', \App\Http\Controllers\ToolCategoryController::class);
    Route::resource('/tools', \App\Http\Controllers\ToolController::class);
    Route::resource('/tool-stocks', \App\Http\Controllers\ToolStockController::class);
    Route::resource('/tool-transactions', \App\Http\Controllers\ToolTransactionController::class);
    Route::resource('/incident-reports', \App\Http\Controllers\IncidentReportController::class);
    Route::resource('/stock-opnames', \App\Http\Controllers\StockOpnameController::class);

    // Work Order
    Route::get('/work-orders-kanban', [\App\Http\Controllers\WorkOrderController::class, 'kanban'])->name('work-orders.kanban');
    Route::get('/work-orders/export', [\App\Http\Controllers\WorkOrderController::class, 'export'])->name('work-orders.export');
    Route::resource('/work-orders', \App\Http\Controllers\WorkOrderController::class);
    Route::resource('/parts', \App\Http\Controllers\PartController::class);

    // Preventive Maintenance
    Route::resource('/pm-templates', \App\Http\Controllers\PmTemplateController::class);
    Route::resource('/pm-schedules', \App\Http\Controllers\PmScheduleController::class);
    Route::post('/pm-schedules/{pm_schedule}/generate-wo', [\App\Http\Controllers\PmScheduleController::class, 'generateWorkOrder'])->name('pm-schedules.generate-wo');

    // Pra-Work Order (Request)
    Route::resource('fars', \App\Http\Controllers\FarController::class);
    Route::get('/pra-work-orders', [\App\Http\Controllers\PraWorkOrderController::class, 'index'])->name('pra-work-orders.index');
    Route::post('/pra-work-orders', [\App\Http\Controllers\PraWorkOrderController::class, 'store'])->name('pra-work-orders.store');
    Route::post('/pra-work-orders/{praWorkOrder}/cancel', [\App\Http\Controllers\PraWorkOrderController::class, 'cancel'])->name('pra-work-orders.cancel');
    Route::post('/pra-work-orders/{praWorkOrder}/generate', [\App\Http\Controllers\PraWorkOrderController::class, 'generate'])->name('pra-work-orders.generate');

    // HSE (Safety, Health, and Environment)
    Route::get('/work-orders/{workOrder}/jsa-template', [\App\Http\Controllers\HseController::class, 'printJsaTemplate'])->name('hse.jsa.template');
    Route::get('/work-orders/{workOrder}/ptw-template', [\App\Http\Controllers\HseController::class, 'printPtwTemplate'])->name('hse.ptw.template');
    Route::post('/work-orders/{workOrder}/jsa', [\App\Http\Controllers\HseController::class, 'storeJsa'])->name('hse.jsa.store');
    Route::post('/hse/jsa/{jsa}/approve', [\App\Http\Controllers\HseController::class, 'approveJsa'])->name('hse.jsa.approve');
    Route::delete('/hse/jsa/{jsa}', [\App\Http\Controllers\HseController::class, 'destroyJsa'])->name('hse.jsa.destroy');

    Route::post('/work-orders/{workOrder}/ptw', [\App\Http\Controllers\HseController::class, 'storePtw'])->name('hse.ptw.store');
    Route::post('/hse/ptw/{ptw}/approve', [\App\Http\Controllers\HseController::class, 'approvePtw'])->name('hse.ptw.approve');
    Route::delete('/hse/ptw/{ptw}', [\App\Http\Controllers\HseController::class, 'destroyPtw'])->name('hse.ptw.destroy');

    Route::post('/work-orders/{workOrder}/loto', [\App\Http\Controllers\HseController::class, 'storeLoto'])->name('hse.loto.store');
    Route::post('/hse/loto/{loto}/remove', [\App\Http\Controllers\HseController::class, 'removeLoto'])->name('hse.loto.remove');

    // API endpoints for WO cascading dropdowns & inline-add & status update
    Route::get('/api/wo/unit-types', [\App\Http\Controllers\Api\WorkOrderApiController::class, 'unitTypes'])->name('api.wo.unit-types');
    Route::get('/api/wo/units', [\App\Http\Controllers\Api\WorkOrderApiController::class, 'units'])->name('api.wo.units');
    Route::get('/api/wo/unit-detail', [\App\Http\Controllers\Api\WorkOrderApiController::class, 'unitDetail'])->name('api.wo.unit-detail');
    Route::post('/api/wo/inline-add', [\App\Http\Controllers\Api\WorkOrderApiController::class, 'inlineAdd'])->name('api.wo.inline-add');
    Route::post('/api/wo/update-status', [\App\Http\Controllers\Api\WorkOrderApiController::class, 'updateStatus'])->name('api.wo.update-status');

    // KPI & Reporting
    Route::get('/kpi/master-data', [\App\Http\Controllers\KpiController::class, 'masterData'])->name('kpi.master-data');
    Route::get('/reports/breakdown', [\App\Http\Controllers\ReportController::class, 'breakdown'])->name('reports.breakdown');
    
    // Plan Budget Bulanan
    Route::resource('plan-budgets', \App\Http\Controllers\PlanBudgetController::class);

    // Master Vendor & JWO
    Route::resource('vendors', \App\Http\Controllers\VendorController::class)->except(['create', 'show', 'edit']);
    Route::resource('jwos', \App\Http\Controllers\JwoController::class);
    Route::patch('/jwos/{jwo}/status', [\App\Http\Controllers\JwoController::class, 'updateStatus'])->name('jwos.status');

    // Document Signatures
    Route::post('/signatures/sign', [\App\Http\Controllers\SignatureController::class, 'sign'])->name('signatures.sign');

    // Laporan Produksi (Fleet Production)
    Route::resource('productions', \App\Http\Controllers\ProductionController::class);
});

require __DIR__.'/auth.php';
