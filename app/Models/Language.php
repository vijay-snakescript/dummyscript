<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Language extends Model
{
    use HasFactory;

    /**
     * The application locale
     *
     * @var null|string
     */
    public $default_locale;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_default', 'locale', 'status'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     *  Boot class statically
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->removeLanguageFromCache();
        });

        static::saved(function ($model) {
            $model->removeLanguageFromCache();
        });

        static::deleted(function ($model) {
            $model->removeLanguageFromCache();
        });
    }

    /**
     * The class constructor
     *
     * @return void
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->default_locale = Config::get('app.locale');
    }

    /**
     * Clear the Languages Cache
     *
     * @return void
     */
    public function removeLanguageFromCache()
    {
        Cache::forget('artisan.locale.all');
        Cache::forget('artisan.locale.default');
        $locales = $this->get();
        foreach ($locales as $locale) {
            Cache::forget('artisan.locale.' . $locale->locale);
        }
    }

    /**
     * Scope to Get the Default Languages
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDefault($query)
    {
        return $query->where("default", true);
    }

    /**
     * Scope to Get the Default Languages
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where("status", true);
    }

    /**
     * Scope to Get the given locale
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $locale
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLocale($query, $locale)
    {
        return $query->where("locale", $locale);
    }

    /**
     * Cache and return all Languages
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getLocales()
    {
        return Cache::rememberForever('artisan.locale.all', function () {
            return self::select('name', 'locale', 'is_default', 'status')->active()->orderByRaw('FIELD(is_default, 1, 0)')->get();
        });
    }

    /**
     * Cache and return current Language
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getCurrentLocale()
    {
        return Cache::rememberForever('artisan.locale.' . $this->default_locale, function () {
            return self::select('name', 'locale', 'is_default')->locale($this->default_locale)->first();
        });
    }

    /**
     * Cache and return Default Language
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getDefaultLocale()
    {
        return Cache::rememberForever('artisan.locales.default', function () {
            return self::select('name', 'locale', 'is_default')->default()->first();
        });
    }

    /**
     * Generate language links for menu items
     * and its children
     *
     * @return void
     */
    public function generateLinks($menu)
    {
        if (!$menu) {
            return false;
        }

        $languages = $this->getLocales();

        $parentArray = [
            'title' => $languages[0]->locale,
            'menu_id' => $menu->id,
            'target' => '_self',
            'order' => app('App\Models\MenuItem')->highestOrderMenuItem(),
            'icon_class' => '',
        ];
        $parent = MenuItem::firstOrCreate($parentArray);

        if (isset($languages[0])) {
            $item = $languages[0];
            $parent->route = 'switch.lang';
            $parent->parameters = ["locale" => $item->locale];
            $parent->save();
        }

        $this->generateChildren($languages, $parent, $menu);
    }

    /**
     * Generate language children menu items
     *
     * @return void
     */
    private function generateChildren($languages, $parent, $menu)
    {
        $languages->transform(function ($item) use ($menu, $parent) {
            $menuArray = [
                'title' => $item->name,
                'menu_id' => $menu->id,
                'route' => 'switch.lang',
                'target' => '_self',
                'parent_id' => $parent->id,
            ];

            $parent = MenuItem::updateOrCreate($menuArray);
            $parent->order = $item->order;
            $parent->parameters = ["locale" => $item->locale];;
            $parent->save();
        });
    }

    /**
     * Menu dynamic link collection for menu.
     *
     * @return collection
     */
    public function link($item, $params)
    {
        if (!$locale = $this->locale) {
            return $item;
        }

        $language = $this->Locale($locale)->first();
        if (!$language) {
            $item->url = '#';
            $item->route = null;
            return $item;
        }

        $item->title = $language->name;
        $item->parameters = ['locale' => $language->locale];

        return $item;
    }
}
