<?php

namespace App\Providers;

use Setting;
use App\Models\Tool;
use Illuminate\Support\Str;
use Spatie\SchemaOrg\Schema;
use Butschster\Head\MetaTags\Meta;
use App\Helpers\Classes\ArtisanApi;
use App\Helpers\Classes\DynamicCss;
use Illuminate\Support\Facades\Config;
use Butschster\Head\Facades\Meta as MetaTag;
use Butschster\Head\MetaTags\Entities\Webmaster;
use Butschster\Head\Contracts\MetaTags\MetaInterface;
use Butschster\Head\MetaTags\Entities\GoogleAnalytics;
use Butschster\Head\MetaTags\Entities\GoogleTagManager;
use Butschster\Head\Packages\Entities\OpenGraphPackage;
use Butschster\Head\Contracts\Packages\ManagerInterface;
use Butschster\Head\Packages\Entities\TwitterCardPackage;
use Butschster\Head\Providers\MetaTagsApplicationServiceProvider as ServiceProvider;

class MetaTagsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        if (!Config::get('artisan.installed')) {
            return;
        }
        $this->initMacros();
        $this->setDefaults();
    }

    protected function packages()
    {
        // Create your own packages here
    }

    // if you don't want to change anything in this method just remove it
    protected function registerMeta(): void
    {
        $this->app->singleton(MetaInterface::class, function () {
            $meta = new Meta(
                $this->app[ManagerInterface::class],
                $this->app['config']
            );

            // add favicon if it exists
            if (setting('favicon')) {
                $meta->setFavicon(url(setting('favicon')));
            }

            if (setting('google_webmaster') != '') {
                $meta->addWebmaster(Webmaster::GOOGLE, setting('google_webmaster'));
            }
            if (setting('yandex_webmaster') != '') {
                $meta->addWebmaster(Webmaster::YANDEX, setting('yandex_webmaster'));
            }
            if (setting('bing_webmaster') != '') {
                $meta->addWebmaster(Webmaster::BING, setting('bing_webmaster'));
            }
            if (setting('pinterest_webmaster') != '') {
                $meta->addWebmaster(Webmaster::PINTEREST, setting('pinterest_webmaster'));
            }
            if (setting('alexa_webmaster') != '') {
                $meta->addWebmaster(Webmaster::ALEXA, setting('alexa_webmaster'));
            }
            if (setting('facebook_webmaster') != '') {
                $meta->addWebmaster(Webmaster::FACEBOOK, setting('facebook_webmaster'));
            }

            $meta->initialize();

            return $meta;
        });
    }

    protected function initMacros()
    {
        $this->initMeta();
        $this->initFortMacro();
    }

    protected function setDefaults()
    {
        if (!Config::get('artisan.installed')) {
            return;
        }

        $dynamicCss = new DynamicCss();
        $dynamicCss->registerFont();
        $this->googleAnalytics();
        $this->registerMeta();
        $this->generatorMeta();
        $this->tokenMeta();
    }

    protected function generatorMeta()
    {
        MetaTag::addMeta('generator', [
            'content' => __('MonsterTools v:version', ['version' => Setting::get('version', '1.0.0')])
        ]);
    }

    protected function tokenMeta()
    {
        $token = app(ArtisanApi::class)->getToken();

        if ($token && Str::of($this->app->request->getRequestUri())->contains(config('artisan.admin_prefix'))) {
            MetaTag::addMeta('artisan-token', ['content' => $token]);
        }
    }

    protected function googleAnalytics()
    {
        if (setting('google_analytics_id', false) && !Str::of($this->app->request->getRequestUri())->contains(config('artisan.admin_prefix'))) {
            $analytics = new GoogleTagManager(setting('google_analytics_id'));
            MetaTag::addTag('google.tagmanager', $analytics, 'head');
        }
    }

    /**
     * Set defautl metas
     */
    public function initMeta()
    {
        Meta::macro(
            'setMeta',
            function ($meta = null, $is_post = false) {
                $locale = app()->getLocale();
                $app_name = Config::get('app.name');
                $title = $meta->title ?? $meta->meta_title ?? Setting::get('meta_title');
                $description = $meta->meta_description ?? $meta->description ?? Setting::get('meta_description');

                $og_title = $meta->og_title ?? $title;
                $og_description = $meta->og_description ?? $description;
                $og_image = !empty(Setting::get('og_image')) ? url(Setting::get('og_image')) : null;
                if ($meta && $meta instanceof Tool) {
                    $og_image = $meta->getFirstMediaUrl("{$locale}-og-image");
                } else if ($meta && method_exists($meta, 'getFirstMediaUrl')) {
                    $og_image = $meta->getFirstMediaUrl('og-image');
                }

                $site_twiitter = Str::start(Setting::get('twitter_username', 'dotartisan'), '@');
                $url = $meta->url ?? \Request::fullUrl();

                //canonical
                if (!empty($url)) {
                    $this->setCanonical($url);
                }

                if ($is_post) {
                    if ($meta->author) {
                        MetaTag::addTag('author', \Butschster\Head\MetaTags\Entities\Tag::meta([
                            'name' => 'author',
                            'content' => $meta->author->name
                        ]));
                    }
                    MetaTag::addTag('published', \Butschster\Head\MetaTags\Entities\Tag::meta([
                        'name' => 'article:published_time',
                        'content' => $meta->created_at->toISOString()
                    ]));
                    MetaTag::addTag('modified', \Butschster\Head\MetaTags\Entities\Tag::meta([
                        'name' => 'article:modified_time',
                        'content' => $meta->updated_at->toISOString()
                    ]));
                    if ($category = $meta->categories->first()) {
                        MetaTag::addTag('category', \Butschster\Head\MetaTags\Entities\Tag::meta([
                            'name' => 'article:section',
                            'content' => $category->name
                        ]));
                    }
                }

                //escape title and description
                $escOGTitle = e(strip_tags($og_title));
                $escOGDescription = e(strip_tags($og_description));

                //facebook OG
                $og = new OpenGraphPackage('pageOg');
                $og->setType('website')
                    ->setSiteName($app_name)
                    ->setTitle($escOGTitle)
                    ->setDescription($escOGDescription)
                    ->setUrl($url);

                if (!empty($og_image)) {
                    $og->addImage(url($og_image));
                }

                //twitter card
                $card = new TwitterCardPackage('pageTwitter');
                $card->setType('summary')
                    ->setSite($site_twiitter)
                    ->setTitle($escOGTitle)
                    ->setDescription($escOGDescription);
                if (!empty($site_twiitter)) {
                    $card->setCreator($site_twiitter);
                }
                if (!empty($og_image)) {
                    $card->setImage(url($og_image));
                }

                if (setting('append_sitename', 1) == 1) {
                    $this->prependTitle($title);
                } else {
                    $this->setTitle($title);
                }

                $this->setDescription($description)
                    ->registerPackage($card);
                $this->registerPackage($og);
                $this->registerPackage($card);
            }
        );
    }

    protected function registerSchema()
    {
        $schema = Schema::localBusiness()
            ->name(config('app.name'))
            ->email(setting('website_email'))
            ->contactPoint(Schema::contactPoint()->areaServed('Worldwide'));
    }

    protected function initFortMacro()
    {
        Meta::macro(
            'setFont',
            function ($font) {
                $body_font = $font['body_family'] ?? 'Inter';
                $body_variant = $font['body_variant'] ?? 'regular,300,600,700';
                $heading_font = $font['heading_font'] ?? 'Inter';
                $heading_variant = $font['heading_variant'] ?? 'regular,700';

                if ($body_font == $heading_font) {
                    $font_varient = implode(",", array_unique(array_merge(explode(",", $body_variant), explode(",", $heading_variant))));

                    $font = 'https://fonts.googleapis.com/css?family=' . $body_font . ':' . $font_varient . '&display=swap';
                } else {
                    $font = 'https://fonts.googleapis.com/css?family=' . $body_font . ':' . $body_variant . '|' . $heading_font . ':' . $heading_variant . '&display=swap';
                }

                $this->addLink('stylesheet', ['href' => $font]);
            }
        );
    }
}
