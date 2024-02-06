<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Nicolaslopezj\Searchable\SearchableTrait;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Plan extends Model implements TranslatableContract, Viewable
{
    use HasFactory, Translatable, InteractsWithViews, SearchableTrait;

    /**
     * Array with the fields translated in the Translation table.
     *
     * @var array
     */
    public $translatedAttributes = ['name', 'plan_id', 'description'];

    /**
     * The columns that are fillable
     *
     * @var array
     */
    protected $fillable = ['id', 'status', 'monthly_price', 'yearly_price', 'discount', 'is_api_allowed', 'is_ads', 'recommended'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['status' => 'boolean', 'recommended' => 'boolean', 'is_ads' => 'boolean', 'is_api_allowed' => 'boolean'];

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
            'plan_translations.name' => 10,
            'plan_translations.description' => 10,
        ],
        'joins' => [
            'plan_translations' => ['plans.id', 'plan_translations.plan_id'],
        ],
        'groupBy' => [
            'plans.id'
        ]
    ];

    /**
     * Scope to get active tags
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where("status", true);
    }

    public function properties()
    {
        return $this->hasMany(PlanProperty::class)
            ->leftJoin('properties', function ($join) {
                $join->on('properties.id', '=', 'plan_properties.property_id')
                    ->where('status', true);
            })
            ->select('plan_properties.*', 'properties.prop_key');
    }

    /**
     * return tool daily usage
     *
     * @return null|int
     */
    public function getDuToolAttribute()
    {
        return $this->getPlanValues('du-tool');
    }

    /**
     * return word count for tool
     *
     * @return null|int
     */
    public function getWcToolAttribute()
    {
        return $this->getPlanValues('wc-tool');
    }

    /**
     * return word count for tool
     *
     * @return null|int
     */
    public function getFsToolAttribute()
    {
        return $this->getPlanValues('fs-tool');
    }

    /**
     * return number of files for tool
     *
     * @return null|int
     */
    public function getNoFileToolAttribute()
    {
        return $this->getPlanValues('no-file-tool');
    }

    /**
     *
     */
    public function getPlanValues($key)
    {
        if ($this->relationLoaded('properties')) {
            $property = $this->properties->where('prop_key', $key)->pluck('value');

            return $property;
        }
    }
}
