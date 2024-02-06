<?php

namespace App\Models;

use Auth;
use App\Models\Tag;
use App\Models\Category;
use App\Traits\DailyUsage;
use Spatie\Sitemap\Tags\Url;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Nicolaslopezj\Searchable\SearchableTrait;
use CyrildeWit\EloquentViewable\Support\Period;
use Overtrue\LaravelFavorite\Traits\Favoriteable;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Tool extends Model implements TranslatableContract, HasMedia, Viewable, Sitemapable
{
    use HasFactory, Translatable, InteractsWithMedia, InteractsWithViews, DailyUsage, Favoriteable, SearchableTrait;

    /**
     * The columns that are translateable
     *
     * @var array
     */
    public $translatedAttributes = ['name', 'content',  'description', 'meta_title', 'meta_description', 'og_title', 'og_description', 'index_content'];
    /**
     * The columns that are fillable
     *
     * @var array
     */
    protected $fillable = ['slug', 'display', 'status', 'settings', 'properties', 'icon_class', 'icon_type', 'is_home'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
        'is_home' => 'boolean',
        'settings' => 'object',
        'properties' => 'json'
    ];

    /**
     * Remove views on delete
     *
     * @var boolean
     */
    protected $removeViewsOnDelete = true;

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
            'tools.slug' => 1,
            'tool_translations.name' => 5,
            'tool_translations.description' => 5,
        ],
        'joins' => [
            'tool_translations' => ['tools.id', 'tool_translations.tool_id'],
        ],
        'groupBy' => [
            'tools.id',
        ]
    ];

    /**
     * to sitemap generator
     *
     * @return Url|string|array
     */
    public function toSitemapTag(): Url | string | array
    {
        return route('tool.show', ['tool' => $this->slug]);
    }

    /**
     * The post views
     *
     * @return bollval
     */
    public function getHasViews()
    {
        return (bool) \Setting::get('tool_views', true);
    }

    public function thisWeek()
    {
        $period = Period::create(now()->startOfWeek(), now()->endOfWeek());
        return $this->views()->withinPeriod($period);
    }

    public function lastWeek()
    {
        $period = Period::create(now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek());
        return $this->views()->withinPeriod($period);
    }

    /**
     * Tool have many tags
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Tool have many categories
     */
    public function category()
    {
        return $this->morphToMany(Category::class, 'catable')->with('translations');
    }

    /**
     * tool have many properties
     */
    public function PlanProperties()
    {
        return $this->hasMany(PlanProperty::class)
            ->leftJoin('properties', function ($join) {
                $join->on('properties.id', '=', 'plan_properties.property_id')
                    ->where('status', true);
            })
            ->select('plan_properties.*', 'properties.prop_key');
    }

    /**
     * Scope to Get the tool by slug
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $slug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSlug($query, $slug)
    {
        return $query->where("slug", $slug);
    }

    /**
     * Scope to Get the tool by active
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where("status", true);
    }

    /**
     * Scope to Get the tool for index page
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIndex($query)
    {
        return $query->where("is_home", true);
    }

    /**
     * return tool daily usage
     *
     * @return null|int
     */
    public function getDuToolAttribute()
    {
        return $this->getDefaultProperties('du-tool');
    }

    /**
     * return word count for tool
     *
     * @return null|int
     */
    public function getWcToolAttribute()
    {
        return $this->getDefaultProperties('wc-tool');
    }

    /**
     * return word count for tool
     *
     * @return null|int
     */
    public function getFsToolAttribute()
    {
        return $this->getDefaultProperties('fs-tool');
    }

    /**
     * return number of files for tool
     *
     * @return null|int
     */
    public function getNoFileToolAttribute()
    {
        return $this->getDefaultProperties('no-file-tool');
    }

    /**
     * return number of files for tool
     *
     * @return null|int
     */
    public function getRemoveAdsAttribute()
    {
        return Auth::check() ? Auth::user()->subscription->is_ads : false;
    }

    /**
     * return number of domains
     *
     * @return null|int
     */
    public function getNoDomainToolAttribute()
    {
        return $this->getDefaultProperties('no-domain-tool');
    }

    public function getDefaultProperties($name = null)
    {
        return Auth::check() ? $this->authProperty($name) : $this->guestProperty($name);
    }

    public function authProperty($key = null)
    {
        if (Auth::user()->subscription) {
            return $this->planProperty($key);
        }

        if ($key && isset($this->properties['auth'][$key])) {
            return $this->properties['auth'][$key];
        }

        return null;
    }


    public function guestProperty($key = null)
    {
        if ($key && isset($this->properties['guest'][$key])) {
            return $this->properties['guest'][$key];
        }

        return null;
    }


    public function planProperty($key = null, $plan_id = null)
    {
        $value = null;
        if ($key) {
            if (!$plan_id) {
                $plan_id = Auth::user()->subscription->plan_id;
            }

            $value = $this->PlanProperties
                ->where('prop_key', $key)
                ->where('plan_id', $plan_id)
                ->first();
            if ($value) {
                return $value->value;
            }
        }

        return $value;
    }

    /**
     * Dynamicaly build page url's for menu
     *
     * @return collection
     */
    public function link($item, $params)
    {
        if (!isset($params['id'])) {
            return $item;
        }

        $id = $params['id'];
        $tool = is_numeric($id) ? $this->with('translations')->find($id) : $this->with('translations')->slug($id)->first();
        if (!$tool || !$tool->hasTranslation()) {
            $item->link = null;

            return $item;
        }

        $item->label = $tool->name;
        $item->parameters = ['tool' => $tool->slug];

        return $item;
    }
}
