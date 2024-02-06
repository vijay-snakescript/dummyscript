<?php

namespace App\Models;

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'label',
        'link',
        'parameters',
        'parent',
        'sort',
        'condition',
        'target',
        'class',
        'icon',
        'is_route',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'parameters' => 'json',
        'sort' => 'int',
        'is_route' => 'boolean',
    ];


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::created(
            function ($model) {
                $model->menu->removeMenuFromCache($model);
            }
        );

        static::saved(
            function ($model) {
                $model->menu->removeMenuFromCache($model);
            }
        );

        static::deleted(
            function ($model) {
                $model->menu->removeMenuFromCache($model);
            }
        );
    }

    /**
     * Menu item belongs to Menu
     */
    public function menu()
    {
        return $this->belongsTo('App\Models\Menu');
    }

    public function scopeGetson($query, $id)
    {
        return $query->where("parent", $id);
    }

    public function getall($query, $id)
    {
        return $query->where("menu_id", $id)->orderBy("sort", "asc");
    }

    public static function getNextSortRoot($menu)
    {
        return self::where('menu_id', $menu)->max('sort') + 1;
    }

    public function parent_menu()
    {
        return $this->belongsTo(Menu::class, 'menu');
    }

    public function child()
    {
        return $this->hasMany(MenuItem::class, 'parent')->with('child')->orderBy('sort', 'ASC');
    }

    /**
     * Prepare meny item link
     *
     * @param bool $absolute
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function link($absolute = false)
    {
        return $this->prepareLink($absolute, $this->is_route, $this->parameters, $this->link);
    }

    /**
     * Prepare meny item link
     *
     * @param bool   $absolute
     * @param string $is_route
     * @param array  $parameters
     * @param string $link
     *
     * @return url|#
     */
    protected function prepareLink($absolute, $is_route, $parameters, $routeOrLink)
    {
        $parameters = isParams($parameters);

        if ($is_route) {
            if (!Route::has($routeOrLink)) {
                return '#';
            }

            return route($routeOrLink, $parameters, $absolute);
        }

        if ($absolute) {
            return url($routeOrLink);
        }

        return $routeOrLink;
    }
}
