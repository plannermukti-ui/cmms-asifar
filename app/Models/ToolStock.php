<?php
namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
class ToolStock extends Model {
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    protected $fillable = ['tool_id', 'location_type', 'mechanic_id', 'quantity'];
    public function tool() { return $this->belongsTo(Tool::class); }
    public function mechanic() { return $this->belongsTo(Mechanic::class); }
}