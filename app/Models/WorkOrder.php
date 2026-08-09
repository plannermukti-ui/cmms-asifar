<?php
namespace App\Models;

use App\Traits\BelongsToSite;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class WorkOrder extends Model {
    use BelongsToSite, Hashidable, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('work_order')
            ->setDescriptionForEvent(fn(string $eventName) => "Work Order {$this->no_wo} has been {$eventName}");
    }

    protected $fillable = [
        'site_id',
        'no_wo', 'status_wo', 'tipe_wo', 'downtime_code', 'opportunity',
        'master_unit_id', 'hours_meter', 'lokasi_kerusakan', 'pm_schedule_id',
        'waktu_bd', 'waktu_rfu',
        'breakdown_type_id', 'component_group_id',
        'wo_category_1_id', 'wo_category_2_id', 'wo_category_3_id',
        'wo_category_4_id', 'wo_category_5_id',
        'created_by',
    ];

    protected $casts = [
        'waktu_bd' => 'datetime',
        'waktu_rfu' => 'datetime',
        'hours_meter' => 'decimal:1',
        'opportunity' => 'boolean',
    ];

    // Auto-generate no_wo: WO-MM-YY-0001
    public static function generateNoWo(): string
    {
        $prefix = 'WO-' . date('m') . '-' . date('y') . '-';
        $last = static::where('no_wo', 'like', $prefix . '%')->orderBy('no_wo', 'desc')->first();
        if ($last) {
            $lastNum = (int) substr($last->no_wo, -4);
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }
        return $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }

    // Durasi: auto-hitung. Jika RFU kosong, hitung sampai sekarang.
    public function getDurasiHrsAttribute(): ?float
    {
        if (!$this->waktu_bd) return null;
        $end = $this->waktu_rfu ?? Carbon::now();
        $start = $this->waktu_bd instanceof Carbon ? $this->waktu_bd : Carbon::parse($this->waktu_bd);
        $end = $end instanceof Carbon ? $end : Carbon::parse($end);
        return round($start->diffInMinutes($end) / 60, 1);
    }

    public function unit() { return $this->belongsTo(MasterUnit::class, 'master_unit_id')->withoutGlobalScope('active'); }
    public function breakdownType() { return $this->belongsTo(BreakdownType::class); }
    public function componentGroup() { return $this->belongsTo(ComponentGroup::class); }
    public function category1() { return $this->belongsTo(WoCategory::class, 'wo_category_1_id'); }
    public function category2() { return $this->belongsTo(WoCategory::class, 'wo_category_2_id'); }
    public function category3() { return $this->belongsTo(WoCategory::class, 'wo_category_3_id'); }
    public function category4() { return $this->belongsTo(WoCategory::class, 'wo_category_4_id'); }
    public function category5() { return $this->belongsTo(WoCategory::class, 'wo_category_5_id'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function tasks() { return $this->hasMany(WoTask::class); }
    public function pmSchedule() { return $this->belongsTo(PmSchedule::class, 'pm_schedule_id'); }
    
    // HSE Relations
    public function jsas() { return $this->hasMany(HseJsa::class); }
    public function ptws() { return $this->hasMany(HsePtw::class); }
    public function lotos() { return $this->hasMany(HseLoto::class); }
    // Inter-connected Document Relations
    public function fars() { return $this->hasMany(Far::class, 'work_order_id'); }
    public function jwos() { return $this->hasMany(Jwo::class, 'work_order_id'); }

    public function signatures() { return $this->morphMany(DocumentSignature::class, 'document'); }
}