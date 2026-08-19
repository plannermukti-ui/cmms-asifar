<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingActionItemLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_item_id',
        'user_id',
        'progress_percent',
        'status',
        'note',
    ];

    public function actionItem()
    {
        return $this->belongsTo(MeetingActionItem::class, 'action_item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
