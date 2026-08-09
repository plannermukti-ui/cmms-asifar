<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use App\Traits\BelongsToSite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmSchedule extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    use HasFactory, BelongsToSite;

    protected $guarded = [];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function masterUnit()
    {
        return $this->belongsTo(MasterUnit::class)->withoutGlobalScope('active');
    }

    public function pmTemplate()
    {
        return $this->belongsTo(PmTemplate::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }
}
