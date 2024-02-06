<?php

namespace App\Models;

use Spatie\Sitemap\Tags\Url;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Spatie\Sitemap\Contracts\Sitemapable;
use Nicolaslopezj\Searchable\SearchableTrait;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Tag extends Model implements TranslatableContract, Viewable, Sitemapable
{
    use HasFactory, Translatable, InteractsWithViews, SearchableTrait;

    /**
     * Array with the fields translated in the Translation table.
     *
     * @var array
     */
    public $translatedAttributes = ['name', 'slug', 'title', 'description'];

    /**
     * The columns that are fillable
     *
     * @var array
     */
    protected $fillable = ['status'];

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
            'tag_translations.name' => 10,
            'tag_translations.description' => 10,
        ],
        'joins' => [
            'tag_translations' => ['tags.id', 'tag_translations.tag_id'],
        ],
        'groupBy' => [
            'tags.id'
        ]
    ];

    /**
     * to sitemap generator
     *
     * @return Url|string|array
     */
    public function toSitemapTag(): Url | string | array
    {
        return route('blog.tag', ['tag' => $this->slug]);
    }

    /**
     * The post views
     *
     * @return bollval
     */
    public function getHasViews()
    {
        return \Setting::get('tags_views', false);
    }

    /**
     * Tags have many posts
     */
    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    /**
     * Tags have many tools
     */
    public function tools()
    {
        return $this->morphedByMany(Tool::class, 'taggable');
    }

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

    /**
     * Scope a query to find category by slug.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSlug($query, $slug)
    {
        return $query->whereTranslation("slug", $slug);
    }

    /**
     * Dynamicaly build tag url's for menu
     *
     * @return collection
     */
    public function link($item, $params)
    {
        if (!isset($params['id'])) {
            return $item;
        }

        $id = $params['id'];
        $tag = $this->with('translations')->find($id);
        if (!$tag) {
            $item->url = '#';
            $item->route = null;

            return $item;
        }

        $translate = $tag->translateOrDefault();

        $item->title = $translate->name;
        $item->icon_class = $translate->icon;
        $item->parameters = ['slug' => $translate->slug];

        return $item;
    }
}
