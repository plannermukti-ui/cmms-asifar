<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'body',
        'read_at',
        'attachment_path',
        'attachment_name',
        'attachment_type',
        'attachment_size'
    ];

    protected $appends = [
        'attachment_url',
        'formatted_attachment_size'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'attachment_size' => 'integer',
    ];

    public function getAttachmentUrlAttribute()
    {
        if ($this->attachment_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->attachment_path)) {
            return asset('storage/' . $this->attachment_path);
        }
        return null;
    }

    public function getFormattedAttachmentSizeAttribute()
    {
        if (!$this->attachment_size) return null;
        $bytes = $this->attachment_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 0) . ' KB';
        }
        return $bytes . ' B';
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
