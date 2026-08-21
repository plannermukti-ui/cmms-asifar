<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Public route - accessible without login
Route::get('/guide', [\App\Http\Controllers\GuideController::class, 'index'])->name('guide');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Users
    Route::resource('/users', \App\Http\Controllers\UserController::class);

    // Master Data
    Route::get('/master-data', [\App\Http\Controllers\MasterDataController::class, 'index'])->name('master-data.index');
    Route::get('/kpi-master-data/export', [\App\Http\Controllers\KpiController::class, 'exportMasterData'])->name('kpi.master-data.export');
    Route::get('/database-relations', [\App\Http\Controllers\DatabaseRelationController::class, 'index'])->name('database-relations.index');
    Route::get('/database-relations/data', [\App\Http\Controllers\DatabaseRelationController::class, 'getSchemaData'])->name('database-relations.data');
    Route::get('/database-relations/sample/{table}', [\App\Http\Controllers\DatabaseRelationController::class, 'getTableSample'])->name('database-relations.sample');
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
    // Notulen Meeting & Continuous Action Items Tracker
    Route::get('/meetings/get-open-action-items', [\App\Http\Controllers\MeetingController::class, 'getOpenActionItems'])->name('meetings.get-open-action-items');
    Route::post('/meetings/action-items/{item}/update', [\App\Http\Controllers\MeetingController::class, 'updateActionItem'])->name('meetings.update-action-item');
    Route::get('/meetings/{meeting}/export-pdf', [\App\Http\Controllers\MeetingController::class, 'exportPdf'])->name('meetings.export-pdf');
    Route::resource('/meetings', \App\Http\Controllers\MeetingController::class);

    // Administrasi ToolRoom
    Route::resource('/mechanics', \App\Http\Controllers\MechanicController::class);
    Route::resource('/tool-categories', \App\Http\Controllers\ToolCategoryController::class);
    Route::resource('/tools', \App\Http\Controllers\ToolController::class);
    Route::resource('/tool-stocks', \App\Http\Controllers\ToolStockController::class);
    Route::resource('/tool-transactions', \App\Http\Controllers\ToolTransactionController::class);
    Route::post('/incident-reports/{incidentReport}/upload-document', [\App\Http\Controllers\IncidentReportController::class, 'uploadDocument'])->name('incident-reports.upload-document');
    Route::resource('/incident-reports', \App\Http\Controllers\IncidentReportController::class);
    Route::post('/stock-opnames/{stockOpname}/upload', [\App\Http\Controllers\StockOpnameController::class, 'uploadDocument'])->name('stock-opnames.upload');
    Route::post('/stock-opnames/{stockOpname}/approve', [\App\Http\Controllers\StockOpnameController::class, 'approve'])->name('stock-opnames.approve');
    Route::post('/stock-opnames/{stockOpname}/reject', [\App\Http\Controllers\StockOpnameController::class, 'reject'])->name('stock-opnames.reject');
    Route::resource('/stock-opnames', \App\Http\Controllers\StockOpnameController::class);
    
    // Approval Stok Tool
    Route::resource('/tool-stock-requests', \App\Http\Controllers\ToolStockRequestController::class)->only(['index', 'show', 'store']);
    Route::post('/tool-stock-requests/{toolStockRequest}/approve', [\App\Http\Controllers\ToolStockRequestController::class, 'approve'])->name('tool-stock-requests.approve');
    Route::post('/tool-stock-requests/{toolStockRequest}/reject', [\App\Http\Controllers\ToolStockRequestController::class, 'reject'])->name('tool-stock-requests.reject');

    // Work Order
    Route::get('/work-orders-kanban', [\App\Http\Controllers\WorkOrderController::class, 'kanban'])->name('work-orders.kanban');
    Route::get('/work-orders/export', [\App\Http\Controllers\WorkOrderController::class, 'export'])->name('work-orders.export');
    Route::get('/work-orders/export-dmbd', [\App\Http\Controllers\WorkOrderController::class, 'exportDmbd'])->name('work-orders.export-dmbd');
    Route::resource('/work-orders', \App\Http\Controllers\WorkOrderController::class);
    Route::post('parts/category', [\App\Http\Controllers\PartController::class, 'storeCategory'])->name('parts.category.store');
    Route::resource('parts', \App\Http\Controllers\PartController::class);
    Route::get('/work-orders/{workOrder}/comments', [\App\Http\Controllers\WoCommentController::class, 'index'])->name('work-orders.comments.index');
    Route::post('/work-orders/{workOrder}/comments', [\App\Http\Controllers\WoCommentController::class, 'store'])->name('work-orders.comments.store');
    Route::delete('/work-orders/{workOrder}/comments/{comment}', [\App\Http\Controllers\WoCommentController::class, 'destroy'])->name('work-orders.comments.destroy');

    // Preventive Maintenance
    Route::resource('/pm-templates', \App\Http\Controllers\PmTemplateController::class);
    Route::post('/pm-templates/bulk-copy', [\App\Http\Controllers\PmTemplateController::class, 'bulkCopy'])->name('pm-templates.bulk-copy');
    Route::post('/pm-templates/bulk-destroy', [\App\Http\Controllers\PmTemplateController::class, 'bulkDestroy'])->name('pm-templates.bulk-destroy');
    Route::post('/pm-templates/{pmTemplate}/copy', [\App\Http\Controllers\PmTemplateController::class, 'copy'])->name('pm-templates.copy');
    Route::resource('/pm-schedules', \App\Http\Controllers\PmScheduleController::class);
    Route::post('/pm-schedules/{pmSchedule}/generate-wo', [\App\Http\Controllers\PmScheduleController::class, 'generateWorkOrder'])->name('pm-schedules.generate-wo');
    Route::get('/pm-schedules/{pmSchedule}/history', [\App\Http\Controllers\PmScheduleController::class, 'historyIndex'])->name('pm-schedules.history');
    Route::post('/pm-schedules-history/import', [\App\Http\Controllers\PmScheduleController::class, 'importHistory'])->name('pm-schedules.import-history');
    Route::get('/pm-schedules-history/download-template', [\App\Http\Controllers\PmScheduleController::class, 'downloadHistoryTemplate'])->name('pm-schedules.download-history-template');
    Route::get('/pm-schedules-history', [\App\Http\Controllers\PmScheduleController::class, 'allHistory'])->name('pm-schedules.all-history');
    Route::post('/pm-schedules/{pmSchedule}/history', [\App\Http\Controllers\PmScheduleController::class, 'historyStore'])->name('pm-schedules.history.store');

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
    Route::get('/hse/jsa/{jsa}/edit', [\App\Http\Controllers\HseController::class, 'editJsa'])->name('hse.jsa.edit');
    Route::put('/hse/jsa/{jsa}', [\App\Http\Controllers\HseController::class, 'updateJsa'])->name('hse.jsa.update');
    Route::post('/hse/jsa/{jsa}/approve', [\App\Http\Controllers\HseController::class, 'approveJsa'])->name('hse.jsa.approve');
    Route::delete('/hse/jsa/{jsa}', [\App\Http\Controllers\HseController::class, 'destroyJsa'])->name('hse.jsa.destroy');

    Route::post('/work-orders/{workOrder}/ptw', [\App\Http\Controllers\HseController::class, 'storePtw'])->name('hse.ptw.store');
    Route::get('/hse/ptw/{ptw}/edit', [\App\Http\Controllers\HseController::class, 'editPtw'])->name('hse.ptw.edit');
    Route::put('/hse/ptw/{ptw}', [\App\Http\Controllers\HseController::class, 'updatePtw'])->name('hse.ptw.update');
    Route::post('/hse/ptw/{ptw}/approve', [\App\Http\Controllers\HseController::class, 'approvePtw'])->name('hse.ptw.approve');
    Route::delete('/hse/ptw/{ptw}', [\App\Http\Controllers\HseController::class, 'destroyPtw'])->name('hse.ptw.destroy');

    Route::post('/work-orders/{workOrder}/loto', [\App\Http\Controllers\HseController::class, 'storeLoto'])->name('hse.loto.store');
    Route::get('/hse/loto/{loto}/edit', [\App\Http\Controllers\HseController::class, 'editLoto'])->name('hse.loto.edit');
    Route::put('/hse/loto/{loto}', [\App\Http\Controllers\HseController::class, 'updateLoto'])->name('hse.loto.update');
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
    Route::resource('/swap-components', \App\Http\Controllers\SwapComponentController::class)->only(['index', 'edit', 'update', 'destroy']);
    
    // Plan Budget Bulanan
    Route::resource('plan-budgets', \App\Http\Controllers\PlanBudgetController::class);

    // Plan & Strategy
    Route::prefix('plan-strategy')->name('plan-strategy.')->group(function () {
        Route::get('/pcr', [\App\Http\Controllers\PCRController::class, 'index'])->name('pcr.index');
        Route::post('/pcr/update-manual', [\App\Http\Controllers\PCRController::class, 'updateManual'])->name('pcr.updateManual');
    });

    // Master Vendor & JWO
    Route::resource('vendors', \App\Http\Controllers\VendorController::class)->except(['create', 'show', 'edit']);
    Route::resource('jwos', \App\Http\Controllers\JwoController::class);
    Route::patch('/jwos/{jwo}/status', [\App\Http\Controllers\JwoController::class, 'updateStatus'])->name('jwos.status');

    // Document Signatures
    Route::post('/signatures/sign', [\App\Http\Controllers\SignatureController::class, 'sign'])->name('signatures.sign');

    // Laporan Produksi (Fleet Production)
    Route::resource('productions', \App\Http\Controllers\ProductionController::class);

    // Notifications Management
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/clear-all', [\App\Http\Controllers\NotificationController::class, 'clearAll'])->name('notifications.clear-all');
});
require __DIR__.'/auth.php';
