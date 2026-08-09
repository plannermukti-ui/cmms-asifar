<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToSite;

class Vendor extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    use HasFactory, BelongsToSite;

    protected $fillable = [
        'name',
        'address',
        'contact_person',
        'phone',
        'email',
        'site_id',
    ];

    public function jwos()
    {
        return $this->hasMany(Jwo::class);
    }
}
