<?php
namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
class StockOpnameDetail extends Model {
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    protected $fillable = ['stock_opname_id', 'tool_id', 'stok_sistem', 'stok_fisik', 'selisih'];
    public function stockOpname() { return $this->belongsTo(StockOpname::class); }
    public function tool() { return $this->belongsTo(Tool::class); }
}