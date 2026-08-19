<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingActionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'parent_action_item_id',
        'item_number',
        'issue',
        'discussion',
        'category',
        'pic_id',
        'pic_name',
        'priority',
        'due_date',
        'progress_percent',
        'status',
        'latest_update',
        'completed_at',
        'link_type',
        'link_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'progress_percent' => 'integer',
        'item_number' => 'integer',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function parentActionItem()
    {
        return $this->belongsTo(MeetingActionItem::class, 'parent_action_item_id');
    }

    public function childActionItems()
    {
        return $this->hasMany(MeetingActionItem::class, 'parent_action_item_id');
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    public function logs()
    {
        return $this->hasMany(MeetingActionItemLog::class, 'action_item_id')->latest();
    }

    public function getEffectivePicNameAttribute()
    {
        return $this->pic ? $this->pic->nama_lengkap : ($this->pic_name ?: '-');
    }

    public function isOverdue()
    {
        if (in_array($this->status, ['Completed', 'Cancelled'])) {
            return false;
        }
        return $this->due_date && $this->due_date->isPast();
    }

    public function getPriorityBadgeAttribute()
    {
        return match($this->priority) {
            'Critical' => 'bg-danger text-white',
            'High' => 'bg-warning text-dark',
            'Medium' => 'bg-info text-white',
            'Low' => 'bg-secondary text-white',
            default => 'bg-secondary text-white',
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'Completed' => 'bg-success-lt text-success',
            'In Progress' => 'bg-primary-lt text-primary',
            'Waiting Part' => 'bg-warning-lt text-warning',
            'Cancelled' => 'bg-muted-lt text-muted',
            default => 'bg-danger-lt text-danger', // Open
        };
    }
}
