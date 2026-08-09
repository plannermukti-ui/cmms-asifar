<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_backup')->only(['index']);
        $this->middleware('permission:create_backup')->only(['create']);
        $this->middleware('permission:download_backup')->only(['download']);
        $this->middleware('permission:delete_backup')->only(['destroy']);
    }

    public function index()
    {
        // Tampilkan daftar file backup yang sudah ada
        $backups = [];
        if (Storage::exists('backups')) {
            $files = Storage::files('backups');
            foreach ($files as $file) {
                $backups[] = [
                    'name' => basename($file),
                    'size' => Storage::size($file),
                    'date' => date('Y-m-d H:i:s', Storage::lastModified($file)),
                    'path' => $file,
                ];
            }
            usort($backups, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
        }
        return view('backup.index', compact('backups'));
    }

    public function create()
    {
        try {
            $dbConfig = config('database.connections.' . config('database.default'));
            $filename = 'backups/backup_' . date('Y-m-d_H-i-s') . '_' . Str::random(6) . '.sql';
            $localPath = storage_path('app/' . $filename);

            // Pastikan direktori ada
            if (!file_exists(dirname($localPath))) {
                mkdir(dirname($localPath), 0755, true);
            }

            $dbPassword = $dbConfig['password'] ?? '';
            $passwordOption = $dbPassword ? '--password=' . escapeshellarg($dbPassword) : '';

            // Buat perintah mysqldump
            $command = sprintf(
                'mysqldump --user=%s %s --host=%s --port=%s %s > "%s" 2>&1',
                escapeshellarg($dbConfig['username']),
                $passwordOption,
                escapeshellarg($dbConfig['host']),
                escapeshellarg($dbConfig['port'] ?? '3306'),
                escapeshellarg($dbConfig['database']),
                $localPath
            );

            exec($command, $output, $returnCode);

            if ($returnCode === 0 && file_exists($localPath)) {
                return redirect()->route('backup.index')
                    ->with('success', 'Backup berhasil dibuat: ' . basename($localPath));
            }

            return redirect()->route('backup.index')
                ->with('error', 'Backup gagal. Pastikan mysqldump tersedia di PATH sistem.');
        } catch (\Exception $e) {
            return redirect()->route('backup.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->download($path);
    }

    public function destroy($filename)
    {
        Storage::delete('backups/' . $filename);
        return redirect()->route('backup.index')->with('success', 'File backup dihapus.');
    }
}
