<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$wos = \App\Models\WorkOrder::with(['componentGroup'])->where('opportunity', false)->take(10)->get();
$out = [];
foreach ($wos as $wo) {
    $out[] = [
        'wo' => $wo->no_wo,
        'bd_type' => $wo->breakdown_type_id,
        'comp_group' => $wo->componentGroup ? $wo->componentGroup->name : null,
        'downtime_code' => $wo->downtime_code
    ];
}
echo json_encode($out, JSON_PRETTY_PRINT);
