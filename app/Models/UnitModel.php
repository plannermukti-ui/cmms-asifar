<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitModel extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    use HasFactory;

    protected $fillable = ['unit_type_id', 'name'];

    public function type()
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    public function masterUnits()
    {
        return $this->hasMany(MasterUnit::class, 'unit_model_id');
    }

    public function parts()
    {
        return $this->belongsToMany(Part::class, 'part_unit_models');
    }
}
