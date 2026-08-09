<?php
namespace App\Models;

use App\Traits\BelongsToSite;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Part extends Model {
    use BelongsToSite, Hashidable, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('part')
            ->setDescriptionForEvent(fn(string $eventName) => "Part {$this->part_number} has been {$eventName}");
    }

    protected $fillable = [
        'site_id',
        'part_number', 'part_description', 'satuan', 'cost',
        'kategori_1', 'kategori_2', 'kategori_3', 'kategori_4',
    ];

    protected $casts = ['cost' => 'decimal:2'];
}