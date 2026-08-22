<?php

namespace App\Models;

use App\Traits\BelongsToSite;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FuelDistributionShift extends Model
{
    use HasFactory, BelongsToSite, Hashidable, LogsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'date',
        'closed_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function fuelTruck()
    {
        return $this->belongsTo(FuelTruck::class);
    }

    public function distributions()
    {
        return $this->hasMany(FuelDistribution::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function closer()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public static function generateShiftDocNumber(): string
    {
        $prefix = 'FDS-' . date('Ym') . '-';
        $last = self::withoutGlobalScopes()->where('shift_doc_number', 'LIKE', $prefix . '%')->orderBy('id', 'desc')->first();
        if ($last) {
            $lastNum = (int) substr($last->shift_doc_number, -4);
            $newNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNum = '0001';
        }
        return $prefix . $newNum;
    }
}
