<?php

namespace App\Models;

use App\Traits\BelongsToSite;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FuelSupplierTruck extends Model
{
    use HasFactory, BelongsToSite, Hashidable, LogsActivity;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function receivings()
    {
        return $this->hasMany(FuelReceiving::class);
    }
}
