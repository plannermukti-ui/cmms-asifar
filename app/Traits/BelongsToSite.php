<?php

namespace App\Traits;

use App\Models\Site;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToSite
{
    /**
     * Boot the trait for a model.
     * Laravel convention: boot[TraitName] or booted[TraitName]
     */
    protected static function bootedBelongsToSite()
    {
        static::addGlobalScope('site_scope', function (Builder $builder) {
            if (Auth::check()) {
                $user = Auth::user();
                
                // Gunakan Role jika ada, namun jika saat ini patokannya site_id == null, kita pertahankan.
                // Lebih baik kedepannya menggunakan: if (!$user->hasRole('superadmin'))
                if (!is_null($user->site_id)) {
                    $table = $builder->getModel()->getTable();
                    $builder->where($table . '.site_id', $user->site_id);
                }
            }
        });

        static::creating(function ($model) {
            if (Auth::check()) {
                $user = Auth::user();
                
                // Ganti empty() dengan is_null() atau !isset() agar lebih presisi
                if (!isset($model->site_id) && !is_null($user->site_id)) {
                    $model->site_id = $user->site_id;
                }
            }
        });
    }

    /**
     * Relationship to the Site model.
     */
    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
