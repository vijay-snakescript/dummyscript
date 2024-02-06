<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faqs extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'answer', 'pricing', 'status'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['status' => 'boolean', 'pricing' => 'boolean'];

    /**
     * Scope to Get active reactions
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope to Get active reactions
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePricing($query)
    {
        return $query->where('pricing', true);
    }

    /**
     * Scope to order reactions
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDisplay($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
