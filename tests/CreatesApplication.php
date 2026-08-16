<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // PENTING: arahkan test ke database terpisah (cmms_aisfar_test)
        // agar RefreshDatabase / migrate:fresh tidak pernah menghapus data
        // pada database produksi (cmms_aisfar).
        $app->make('config')->set('database.connections.mysql.database', 'cmms_aisfar_test');
        DB::purge('mysql');

        return $app;
    }
}
