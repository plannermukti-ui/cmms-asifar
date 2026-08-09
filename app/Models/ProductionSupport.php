<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionSupport extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    use HasFactory;

    protected $fillable = [
        'production_id',
        'support_id',
        'hm_awal',
        'hm_akhir',
    ];

    public function production()
    {
        return $this->belongsTo(Production::class, 'production_id');
    }

    public function support()
    {
        return $this->belongsTo(MasterUnit::class, 'support_id');
    }
}
