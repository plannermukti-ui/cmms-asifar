<?php
namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Hashidable;

class ToolTransaction extends Model {
    use LogsActivity, Hashidable;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    protected $fillable = [
        'tool_id', 'mechanic_id', 'user_id', 'tipe_transaksi', 'tanggal_pinjam', 
        'borrow_qty', 'tanggal_kembali', 'returned_good_qty', 'returned_broken_qty', 
        'returned_lost_qty', 'status', 'catatan'
    ];
    public function tool() { return $this->belongsTo(Tool::class); }
    public function mechanic() { return $this->belongsTo(Mechanic::class); }
    public function admin() { return $this->belongsTo(User::class, 'user_id'); }
}