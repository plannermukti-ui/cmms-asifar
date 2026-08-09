<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simulate request
$request = Illuminate\Http\Request::create('/reports/breakdown', 'GET', [
    '_generate' => 1,
    'date_range' => '2026-08-01 - 2026-08-31',
    'card_unit_type_1' => [2] // Assuming type ID 2 has data
]);
app()->instance('request', $request);

$controller = new \App\Http\Controllers\ReportController();
$response = $controller->breakdown($request);

echo $response->render();
