<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::saved(
            function ($model) {
                $model->removeMenuFromCache($model);
            }
        );

        static::deleted(
            function ($model) {
                $model->removeMenuFromCache($model);
            }
        );
    }

    /**
     * Menu has many items
     */
    public function items()
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort');
    }

    /**
     * Menu parent item also has many items
     */
    public function parent_items()
    {
        return $this->items()->whereNull('parent')->orderBy('sort');
    }

    /**
     * Scope to exclude a menu item
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param bool                                  $published
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExcept($query, $menu)
    {
        return $query->where("id", '!=', $menu);
    }

    /**
     * Display menu.
     *
     * @param string      $menuName
     * @param string|null $type
     * @param array       $options
     *
     * @return string
     */
    public static function display($menuName, $type = 'bootstrap', array $options = [])
    {
        // GET THE MENU - sort collection in blade
        $sessionId = Session::getId();
        $menyKey = $sessionId . '.app.menu.' . (Str::camel($menuName)) . '.' . $type;
        // $menyKey .= \Str::random(5);
        $items = Cache::rememberForever(
            $menyKey,
            function () use ($menuName, $type) {
                if (is_string($menuName)) {
                    $menu = static::findByName($menuName);
                }

                if (is_int($menuName)) {
                    $menu = static::findById($menuName);
                }

                // Check for Menu Existence
                if (!isset($menu)) {
                    return false;
                }

                $items = $menu->parent_items;
                $items = static::processItems($items);

                return $items;
            }
        );
        // Check for Menu Existence
        if (!$items) {
            return false;
        }

        $items = static::setActiveItem($items);

        // Convert options array into object
        $options = (object) $options;

        $type = 'menu.' . $type;
        if (!view()->exists($type)) {
            $type = 'menu.default';
        }

        if ($type === '_json') {
            return $items;
        }

        return new HtmlString(
            View::make($type, ['items' => $items, 'options' => $options])->render()
        );
    }

    /**
     * Find a menu by its name.
     *
     * @param  string $name
     * @return App\Models\Menu
     */
    public static function findByName(string $name)
    {
        $menu = static::where('name', '=', $name)
            ->with(['items', 'parent_items.child'])
            ->first();

        return $menu;
    }

    /**
     * Find a menu by its id.
     *
     * @param  string $name
     * @return App\Models\Menu
     */
    public static function findById(int $id)
    {
        $menu = static::where('id', '=', $id)
            ->with(['items', 'parent_items.child'])
            ->first();

        return $menu;
    }

    /**
     * Clear use based menu cache
     *
     * @return void
     */
    public function removeMenuFromCache($model)
    {
        $sessionId = Session::getId();
        if (isset($model->menu_id)) {
            $menu_id = $model->menu_id;
            $model = Menu::findById($menu_id);
        }

        $menuName = $model->name ? Str::camel($model->name) : '';
        $menyKey = $sessionId . '.app.menu.' . $menuName . '.';

        Cache::forget($menyKey . 'bootstrap');
    }

    /**
     * Process all menu items, translate them dynamically
     *
     * @param Illuminate\Database\Eloquent\Collection $items
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    private static function processItems($items)
    {
        $items = $items->transform(
            function ($item) {
                $item = static::transformDynamicItem($item);

                if (Lang::hasForLocale($item->label)) {
                    $trans_title = __($item->label);
                    $item->label = !is_array($trans_title) ? $trans_title : $item->label;
                }

                // Resolve URL/Route
                $item->href = $item->link(true);

                if ($item->child->count() > 0) {
                    $item->setRelation('child', static::processItems($item->child));
                }

                return $item;
            }
        );

        // Filter items by permission
        // $items = $items->filter(
        //     function ($item) {
        //         if (Auth::guard()->check() && $item->auth) {
        //             return !$item->child->isEmpty() || Auth::user()->can($item->route);
        //         } elseif ($item->auth) {
        //             return false;
        //         }

        //         return true;
        //     }
        // )->filter(
        //     function ($item) {
        //         // Filter out empty menu-items
        //         if (!$item->link && $item->child->count() == 0) {
        //             return false;
        //         }

        //         return true;
        //     }
        // );

        return $items->values();
    }

    /**
     * Activate current item.
     *
     * @param Illuminate\Database\Eloquent\Collection $items
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    private static function setActiveItem($items)
    {
        $items = $items->transform(
            function ($item) {
                if (!empty($item->href) && active($item->href)) {
                    $item->active = true;
                }

                if ($item->child->count() > 0) {
                    $item->setRelation('child', static::setActiveItem($item->child));
                }

                return $item;
            }
        );

        return $items->values();
    }

    /**
     * Check if item has model and model has
     * method to process link and its translation.
     *
     * @param Illuminate\Database\Eloquent\Collection $items
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    private static function transformDynamicItem($item)
    {
        $parameters = $item->parameters;
        if (!is_null($parameters)) {
            $parameters = isParams($parameters);
            if (isset($parameters['model'])) {
                $model = $parameters['model'];
                $model = Str::studly($model);
                $model = Str::start($model, "\\App\\Models\\");
                if (class_exists($model) && method_exists($model, 'link')) {
                    $item = app($model)->link($item, $parameters);
                }
            }
        }

        return $item;
    }
}
