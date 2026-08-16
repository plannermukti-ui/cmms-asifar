<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->call('migrate', ['--force' => true, '--path' => 'database/migrations']);
echo "<pre>Migrate status: $status\n\n";
echo $kernel->output();
echo "</pre>";
