<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Hashidable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StockOpname extends Model {
    use Hashidable, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('stock_opname')
            ->setDescriptionForEvent(fn(string $eventName) => "Stock Opname {$this->id} has been {$eventName}");
    }
    protected $fillable = ['tanggal_audit', 'tipe_audit', 'mechanic_id', 'auditor_user_id'];
    public function mechanic() { return $this->belongsTo(Mechanic::class); }
    public function auditor() { return $this->belongsTo(User::class, 'auditor_user_id'); }
    public function details() { return $this->hasMany(StockOpnameDetail::class); }
}