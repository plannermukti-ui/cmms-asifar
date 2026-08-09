<?php

namespace App\Traits;

use Vinkla\Hashids\Facades\Hashids;

trait Hashidable
{
    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return Hashids::encode($this->getKey());
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // If field is not specified (which means we are resolving by primary key)
        if (empty($field) || $field === $this->getRouteKeyName()) {
            $decoded = Hashids::decode($value);

            if (empty($decoded)) {
                return null;
            }

            return $this->where($this->getRouteKeyName(), $decoded[0])->first();
        }

        // Fallback for custom fields
        return $this->where($field, $value)->first();
    }
}
