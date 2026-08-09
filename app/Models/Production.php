<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    use HasFactory;

    protected $fillable = [
        'date',
        'shift',
        'notes',
    ];

    public function fleets()
    {
        return $this->hasMany(ProductionFleet::class, 'production_id');
    }

    public function supports()
    {
        return $this->hasMany(ProductionSupport::class, 'production_id');
    }

    public function delays()
    {
        return $this->hasMany(ProductionDelay::class, 'production_id');
    }
}
