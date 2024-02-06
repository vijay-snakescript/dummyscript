<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Nicolaslopezj\Searchable\SearchableTrait;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Property extends Model implements TranslatableContract
{
    use HasFactory, Translatable, InteractsWithViews, SearchableTrait;

    /**
     * Array with the fields translated in the Translation table.
     *
     * @var array
     */
    public $translatedAttributes = ['name', 'property_id', 'description'];

    /**
     * The columns that are fillable
     *
     * @var array
     */
    protected $fillable = ['status', 'type', 'field_type', 'prop_key'];

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
            'property_translations.name' => 10,
        ],
        'joins' => [
            'property_translations' => ['properties.id', 'property_translations.property_id'],
        ],
        'groupBy' => [
            'properties.id'
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

    public function planProperties()
    {
        return $this->hasMany(PlanProperty::class, 'property_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
