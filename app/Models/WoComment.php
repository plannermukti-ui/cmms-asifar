<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class WoComment extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'work_order_id',
        'parent_id',
        'user_id',
        'body',
        'attachment_path',
        'attachment_name',
        'attachment_type'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['work_order_id', 'parent_id', 'user_id', 'body', 'attachment_name'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(WoComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(WoComment::class, 'parent_id');
    }

    public function getAttachmentUrlAttribute()
    {
        if ($this->attachment_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->attachment_path)) {
            return asset('storage/' . $this->attachment_path);
        }
        return null;
    }
}
