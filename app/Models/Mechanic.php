<?php
namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use App\Traits\BelongsToSite;
use Illuminate\Database\Eloquent\Model;
class Mechanic extends Model {
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    use BelongsToSite;

    protected $fillable = [
        'site_id','nama_lengkap', 'jabatan_id', 'is_active'];
    public function jabatan() { return $this->belongsTo(Jabatan::class); }
}