<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToSite;
use App\Traits\Hashidable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Far extends Model
{
    use HasFactory, BelongsToSite, Hashidable, LogsActivity;

    protected $fillable = [
        'no_far',
        'site_id',
        'work_order_id',
        'master_unit_id',
        'reported_by',
        'date_reported',
        'date_of_failure',
        'smu_at_failure',
        'component_part_no',
        'component_description',
        'part_no_causing_failure',
        'last_comp_date',
        'last_comp_smu',
        'hours_of_component',
        'last_oil_date_taken',
        'last_oil_date_sent',
        'last_oil_date_received',
        'last_oil_eval',
        'failure_outline',
        'background',
        'failure_analysis',
        'conclusion',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function siteRelation()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }

    public function masterUnit()
    {
        return $this->belongsTo(MasterUnit::class, 'master_unit_id')->withoutGlobalScope('active');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function attachments()
    {
        return $this->hasMany(FarAttachment::class, 'far_id');
    }

    public function signatures()
    {
        return $this->morphMany(DocumentSignature::class, 'document');
    }
}
