<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advertisement extends Model
{
    use HasFactory, SearchableTrait;

    protected $fillable = ['title', 'status', 'click_counts', 'type', 'options', 'name'];
    protected $casts = [
        'options' => 'json',
    ];

    /**
     * Advert types
     * @var string
     */
    const AD_TEXT = 'text';
    const AD_IMAGE = 'image';
    const AD_CODE = 'code';

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'title' => 10,
        ],
    ];

    public static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            Cache::forget('cache_advert_model');
        });
        self::updated(function ($model) {
            Cache::forget('cache_advert_model');
        });
        self::deleted(function ($model) {
            Cache::forget('cache_advert_model');
        });
    }

    public function adType(): Attribute
    {
        return new Attribute(
            get: fn () =>  $this->type == 1 ? ucfirst(static::AD_TEXT) : ($this->type == 2 ? ucfirst(static::AD_IMAGE) : ucfirst(static::AD_CODE)),
        );
    }

    /**
     * Scope a query to get active categories.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where("status", true);
    }
}
