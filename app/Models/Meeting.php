<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'meeting_number',
        'title',
        'meeting_type',
        'meeting_date',
        'start_time',
        'end_time',
        'location',
        'leader_name',
        'notetaker_name',
        'attendees',
        'agenda',
        'general_notes',
        'status',
        'created_by',
    ];

    protected $casts = [
        'meeting_date' => 'date',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function actionItems()
    {
        return $this->hasMany(MeetingActionItem::class)->orderBy('item_number', 'asc');
    }

    public function openActionItems()
    {
        return $this->hasMany(MeetingActionItem::class)->whereIn('status', ['Open', 'In Progress', 'Waiting Part']);
    }

    public function completedActionItems()
    {
        return $this->hasMany(MeetingActionItem::class)->where('status', 'Completed');
    }

    public static function generateMeetingNumber($date = null)
    {
        $d = $date ? \Carbon\Carbon::parse($date) : now();
        $year = $d->format('Y');
        $month = $d->format('m');
        
        $count = self::whereYear('meeting_date', $year)
            ->whereMonth('meeting_date', $month)
            ->count() + 1;
            
        return sprintf('NOT/%s/%s/%04d', $year, $month, $count);
    }
}
