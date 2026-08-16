<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToSite;

class HseLoto extends Model
{
    use LogsActivity, BelongsToSite;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'applied_at' => 'datetime',
        'removed_at' => 'datetime',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function applier()
    {
        return $this->belongsTo(User::class, 'applied_by');
    }

    public function remover()
    {
        return $this->belongsTo(User::class, 'removed_by');
    }
}
