<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Hashidable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class HourMeter extends Model
{
    use HasFactory;
    use \App\Traits\BelongsToSite;
    use Hashidable, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('hour_meter')
            ->setDescriptionForEvent(fn(string $eventName) => "Hour Meter {$this->id} has been {$eventName}");
    }

    protected $fillable = [
        'site_id',
        'master_unit_id',
        'date',
        'hm'
    ];

    public function masterUnit()
    {
        return $this->belongsTo(MasterUnit::class)->withoutGlobalScope('active');
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
