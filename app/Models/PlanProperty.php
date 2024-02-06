<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanProperty extends Model
{
    use HasFactory;

    /**
     * The columns that are fillable
     *
     * @var array
     */
    protected $fillable = ['tool_id', 'plan_id', 'property_id', 'value'];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function scopeGetProperty($query, $name = null)
    {
        return $query->property()->where('prop_key', $name);
    }

    // public function getAttribute($key)
    // {
    //     $value = parent::getAttribute($key);

    //     if (!$value) {
    //         dd($key);
    //     }

    //     return $value;
    // }
}
