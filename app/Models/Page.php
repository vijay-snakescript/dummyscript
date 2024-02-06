<?php

namespace App\Models;

use App\Traits\Linkable;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Spatie\Sitemap\Contracts\Sitemapable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Page extends Model implements TranslatableContract, Viewable, Sitemapable
{
    use HasFactory, Translatable, InteractsWithViews, SearchableTrait, SoftDeletes, Linkable;

    /**
     * The columns that are translateable
     *
     * @var array
     */
    public $translatedAttributes = ['title', 'slug', 'content', 'excerpt', 'meta_title', 'meta_description', 'og_title', 'og_description', 'og_image'];

    /**
     * The columns that are fillable
     *
     * @var array
     */
    protected $fillable = ['author_id', 'published'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['status' => 'boolean'];

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
            'page_translations.title' => 10,
            'users.name' => 5,
        ],
        'joins' => [
            'page_translations' => ['pages.id', 'page_translations.page_id'],
            'users' => ['users.id', 'pages.author_id']
        ],
        'groupBy' => [
            'pages.id'
        ]
    ];

    /**
     * to sitemap generator
     *
     * @return Url|string|array
     */
    public function toSitemapTag(): Url | string | array
    {
        return route('pages.show', ['slug' => $this->slug]);
    }

    /**
     * The post views
     *
     * @return bollval
     */
    public function getHasViews()
    {
        return \Setting::get('page_views', false);
    }

    /**
     * Page belongs to user
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Scope to get page by author
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int                                   $author_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthor($query, $author_id)
    {
        return $query->where("author_id", $author_id);
    }

    /**
     * Scope to Get the page by published status
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param bool                                  $published
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $published)
    {
        return $query->where("published", $published);
    }

    /**
     * Scope to Get the page by published status
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param bool                                  $published
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where("published", 1);
    }

    /**
     * Scope to Get the page by slug
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $slug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSlug($query, $slug)
    {
        return $query->whereTranslation("slug", $slug);
    }
}
