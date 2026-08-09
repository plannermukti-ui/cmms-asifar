<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Jabatan extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['nama_jabatan'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_jabatan'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
