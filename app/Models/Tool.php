<?php
namespace App\Models;

use App\Traits\BelongsToSite;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Tool extends Model {
    use BelongsToSite, Hashidable, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('tool')
            ->setDescriptionForEvent(fn(string $eventName) => "Tool {$this->name} has been {$eventName}");
    }

    protected $fillable = [
        'site_id','tool_category_id', 'name', 'spesifikasi', 'foto'];
    public function category() { return $this->belongsTo(ToolCategory::class, 'tool_category_id'); }
    public function stocks() { return $this->hasMany(ToolStock::class); }
}