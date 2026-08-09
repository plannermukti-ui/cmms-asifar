<?php

namespace App\Models;

use App\Traits\BelongsToSite;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MasterUnit extends Model
{
    use BelongsToSite;
    use HasFactory;
    use LogsActivity;

    protected static function booted()
    {
        static::addGlobalScope('active', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->where('active', 1);
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'nomor_unit',
        'unit_type_id',
        'unit_model_id',
        'sn_chassis',
        'engine_model',
        'sn_engine',
        'engine_make',
        'capacity',
        'no_polisi',
        'attachments',
        'hp',
        'kw',
        'perakitan',
        'date_receive',
        'dari',
        'location',
        'remarks',
        'service',
        'active',
        'site_id',
    ];

    public function type()
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    public function model()
    {
        return $this->belongsTo(UnitModel::class, 'unit_model_id');
    }

    public function siteRelation()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
