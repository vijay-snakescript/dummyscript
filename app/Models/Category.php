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

class Category extends Model implements TranslatableContract, Viewable, Sitemapable
{
    use HasFactory, Translatable, InteractsWithViews, SearchableTrait;

    /**
     * Array with the fields translated in the Translation table.
     *
     * @var array
     */
    public $translatedAttributes = ['name', 'slug', 'meta_title', 'meta_description', 'title', 'description', 'icon'];

    /**
     * The columns that are fillable
     *
     * @var array
     */
    protected $fillable = ['status', 'parent', 'type'];

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
            'category_translations.name' => 5,
            'category_translations.description' => 5,
        ],
        'joins' => [
            'category_translations' => ['categories.id', 'category_translations.category_id'],
        ],
        'groupBy' => [
            'categories.id'
        ]
    ];

    /**
     * to sitemap generator
     *
     * @return Url|string|array
     */
    public function toSitemapTag(): Url | string | array
    {
        return $this->type == 'post' ? route('blog.category', ['category' => $this->slug]) : route('tool.category', ['category' => $this->slug]);
    }

    /**
     * Each category may have multiple children
     */
    public function children()
    {
        return $this->hasMany($this, 'parent')->withCount('posts')->with('translations');
    }

    /**
     * Scope a query to only include parent categories.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeParents($query)
    {
        return $query->whereNull("parent");
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

    public function scopePost($query)
    {
        return $query->where("type", "Post");
    }

    public function scopeTool($query)
    {
        return $query->where("type", "tool");
    }

    /**
     * Categories have many posts
     */
    public function posts()
    {
        return $this->morphedByMany(Post::class, 'catable');
    }
    /**
     * Categories have many tools
     */
    public function tools()
    {
        return $this->morphedByMany(Tool::class, 'catable');
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
        $page = is_numeric($id) ? $this->with('translations')->find($id) : $this->with('translations')->slug($id)->first();
        if (!$page || !$page->hasTranslation()) {
            $item->link = null;

            return $item;
        }

        $item->label = $page->name;
        $item->parameters = ['category' => $page->slug];

        return $item;
    }
}
