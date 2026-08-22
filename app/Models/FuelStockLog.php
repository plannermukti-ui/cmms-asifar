<?php

namespace App\Models;

use App\Traits\BelongsToSite;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelStockLog extends Model
{
    use HasFactory, BelongsToSite, Hashidable;

    protected $guarded = ['id'];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getDeviceNameAttribute(): string
    {
        if ($this->reference_type === 'fuel_storage') {
            $s = FuelStorage::find($this->reference_id);
            return $s ? ($s->code . ' - ' . $s->name) : 'Storage #' . $this->reference_id;
        }
        $t = FuelTruck::with('masterUnit')->find($this->reference_id);
        return $t ? ('Fuel Truck: ' . ($t->masterUnit->nomor_unit ?? 'FT #' . $this->reference_id)) : 'Fuel Truck #' . $this->reference_id;
    }
}
