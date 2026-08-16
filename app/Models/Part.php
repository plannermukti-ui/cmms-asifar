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
        'part_number', 'part_description', 'satuan', 'cost', 'expenditure_type',
        'kategori_1_id', 'kategori_2_id', 'kategori_3_id', 'kategori_4_id',
    ];

    public function kategori1()
    {
        return $this->belongsTo(PartCategory::class, 'kategori_1_id');
    }

    public function kategori2()
    {
        return $this->belongsTo(PartCategory::class, 'kategori_2_id');
    }

    public function kategori3()
    {
        return $this->belongsTo(PartCategory::class, 'kategori_3_id');
    }

    public function kategori4()
    {
        return $this->belongsTo(PartCategory::class, 'kategori_4_id');
    }

    protected $casts = ['cost' => 'decimal:2'];
}