<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToSite;
use App\Traits\Hashidable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Jwo extends Model
{
    use HasFactory, BelongsToSite, Hashidable, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('jwo')
            ->setDescriptionForEvent(fn(string $eventName) => "JWO {$this->no_jwo} has been {$eventName}");
    }

    protected $fillable = [
        'no_jwo',
        'vendor_id',
        'work_order_id',
        'unit_id',
        'component_group_id',
        'part_id',
        'problem_description',
        'request_action',
        'status',
        'date_sent',
        'date_expected',
        'date_returned',
        'cost',
        'remarks',
        'photo_1',
        'photo_2',
        'site_id',
        'created_by',
    ];

    protected $casts = [
        'date_sent' => 'date',
        'date_expected' => 'date',
        'date_returned' => 'date',
    ];

    public static function generateNoJwo(): string
    {
        $prefix = 'JWO-' . date('m') . '-' . date('y') . '-';
        $last = static::where('no_jwo', 'like', $prefix . '%')->orderBy('no_jwo', 'desc')->first();
        if ($last) {
            $lastNum = (int) substr($last->no_jwo, -4);
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }
        return $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }

    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function workOrder() { return $this->belongsTo(WorkOrder::class); }
    public function unit() { return $this->belongsTo(MasterUnit::class, 'unit_id')->withoutGlobalScope('active'); }
    public function componentGroup() { return $this->belongsTo(ComponentGroup::class); }
    public function part() { return $this->belongsTo(Part::class); }
    public function signatures()
    {
        return $this->morphMany(DocumentSignature::class, 'document');
    }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
