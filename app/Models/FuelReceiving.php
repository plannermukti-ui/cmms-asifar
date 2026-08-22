<?php

namespace App\Models;

use App\Traits\BelongsToSite;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FuelReceiving extends Model
{
    use HasFactory, SoftDeletes, BelongsToSite, Hashidable, LogsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'date_receive' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function storage()
    {
        return $this->belongsTo(FuelStorage::class, 'fuel_storage_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function supplierTruck()
    {
        return $this->belongsTo(FuelSupplierTruck::class, 'fuel_supplier_truck_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function intendedApprover()
    {
        return $this->belongsTo(User::class, 'intended_approver_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public static function generateReceivingNumber(): string
    {
        $prefix = 'FR-' . date('Ym') . '-';
        $last = self::withoutGlobalScopes()->where('receiving_number', 'LIKE', $prefix . '%')->orderBy('id', 'desc')->first();
        if ($last) {
            $lastNum = (int) substr($last->receiving_number, -4);
            $newNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNum = '0001';
        }
        return $prefix . $newNum;
    }
}
