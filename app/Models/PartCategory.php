<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartCategory extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'name'];

    public function partsAsKategori1()
    {
        return $this->hasMany(Part::class, 'kategori_1_id');
    }

    public function partsAsKategori2()
    {
        return $this->hasMany(Part::class, 'kategori_2_id');
    }

    public function partsAsKategori3()
    {
        return $this->hasMany(Part::class, 'kategori_3_id');
    }

    public function partsAsKategori4()
    {
        return $this->hasMany(Part::class, 'kategori_4_id');
    }
}
