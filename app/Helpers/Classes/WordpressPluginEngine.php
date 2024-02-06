<?php

namespace App\Helpers\Classes;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class WordpressPluginEngine
{
    protected $commentsPlugins = [
        'elementor-section' => 'elementor-pro',
        'MonsterInsights' => 'google-analytics-for-wordpress',
        'WP Rocket' => 'wp-rocket',
        'All in One SEO' => 'all-in-one-seo-pack',
        'OptinMonster' => 'optinmonster',
        'Yoast SEO' => 'wordpress-seo',
        'Powered by WPBakery Page Builder' => 'wpbakery-page-builder-for-wordpress',
        'Powered by LayerSlider' => 'kreatura-slider-plugin-for-wordpress',
        'Cache Enabler by KeyCDN' => 'cache-enabler',
    ];

    protected $searchByString = [
        'elementor-section' => 'elementor',
        'Powered by WPBakery Page Builder' => 'wpbakery-page-builder-for-wordpress',
        'Powered by LayerSlider' => 'kreatura-slider-plugin-for-wordpress',
        'Powered by Slider Revolution' => 'revslider',
        'elementskit_' => 'elementskit-lite',
        'metform-' => 'metform',
    ];

    /**
     * Page HTML
     *
     * @var string
     */
    protected $html;

    /**
     * List of known plugins
     *
     * @var collect
     */
    protected $pluginsList;

    /**
     * List of found plugins.
     *
     * @var collect
     */
    protected $slugs;

    /**
     * List of found plugins
     *
     * @var collect
     */
    public $plugins;

    public function __construct($html)
    {
        $this->html = $html;
        $this->plugins = collect([]);
        $this->pluginsList = $this->premiumPlugins();
    }

    public function parse()
    {
        $this->discoverPlugins();

        return $this->plugins;
    }

    protected function discoverPlugins()
    {
        $this->searchPluginsInHtml();
        $this->searchInLib();
        $this->notDiscoveredPlugins();
    }

    protected function searchInLib()
    {
        $slugs = $this->slugs;
        foreach ($slugs as $index => $slug) {
            $plugin = Cache::remember("wordpress-plugins-{$slug}", 600000, function () use ($slug) {
                $plugin = $this->pluginsList->where('slug', $slug)->first();
                if (!$plugin) {
                    $plugin = $this->searchOnWP($slug);
                }

                return $plugin;
            });

            if ($plugin) {
                unset($this->slugs[$index]);
                $this->plugins->push((object) $plugin);
            }
        }
    }

    public function searchOnWP($slug)
    {
        $action = "https://api.wordpress.org/plugins/info/1.0/{$slug}.json";
        $response = fetchAsGoogle($action);
        $response = json_decode($response, true);
        if (!isset($response['error']) && isset($response['name'])) {
            $screenshot_url = isHttpStatusCode200("https://ps.w.org/{$slug}/assets/banner-772x250.jpg") ? "https://ps.w.org/{$slug}/assets/banner-772x250.jpg" : "https://ps.w.org/{$slug}/assets/banner-772x250.png";
            $response['screenshot_url'] = $screenshot_url;
            $response['type'] = __('common.free');
            $description = strip_tags($response['sections']['description']);
            $response['description'] = Str::of($description)->limit(100, '...')->toString();
            $response['last_updated'] = Carbon::parse($response['last_updated']);

            return $response;
        }

        return null;
    }

    protected function notDiscoveredPlugins()
    {
        foreach ($this->slugs as $index => $slug) {
            $this->plugins->push($this->emptyPlugin($slug));
        }
    }

    protected function emptyPlugin($slug)
    {
        $name = Str::of($slug)->replace('-', ' ')->title()->toString();
        return (object) [
            'name' => $name,
            'slug' => $slug,
            'description' => __('tools.pluginDescriptionNotAvailable'),
            'author' => null,
            'rating' => null,
            'download_link' => null,
            'type' => null,
            'screenshot_url' => theme_url('themes/default/images/no-image-cover.jpg'),
        ];
    }

    protected function searchPluginsInHtml()
    {
        preg_match_all("/src=['\"](?P<js>([^'\"]+?\.js)[^'\"]*)/", $this->html, $javascripts);
        preg_match_all("/href=['\"](?P<css>([^'\"]+?\.css)[^'\"]*)/i", $this->html, $stylesheets);
        $comments = $this->searchInComments();
        $stringSearch = $this->searchByString();

        $resources = collect(array_merge($javascripts['js'], $stylesheets['css']));
        $this->slugs = $resources->filter(function ($item) {
            return filter_var($item, FILTER_VALIDATE_URL) && Str::of($item)->contains('/wp-content/plugins/');
        })->map(function ($item) {
            return Str::of($item)->betweenFirst('/wp-content/plugins/', '/')->toString();
        })->merge($comments)->merge($stringSearch)->unique();
    }

    protected function searchInComments()
    {
        $plugins = [];
        foreach ($this->commentsPlugins as $regex => $slug) {
            preg_match_all('/<!--[^\'"]+?(?P<comments>(' . $regex . '))[^\'"]+?-->*/i', $this->html, $matches);
            if (count($matches['comments']) > 0) {
                $plugins[] = $slug;
            }
        }

        return $plugins;
    }

    protected function searchByString()
    {
        $plugins = [];
        foreach ($this->searchByString as $regex => $slug) {
            preg_match_all('/(?P<comments>(' . $regex . '))/i', $this->html, $matches);
            if (count($matches['comments']) > 0) {
                $plugins[] = $slug;
            }
        }

        return $plugins;
    }

    protected function premiumPlugins()
    {
        return collect(array(
            [
                'name' => "WPBakery Page Builder for WordPress",
                'slug' => "js_composer",
                'author' => '<a href="https://codecanyon.net/user/wpbakery">wpbakery</a>',
                'description' => "Drag and drop page builder for WordPress. Take full control over your WordPress site, build any layout you can imagine no programming knowledge required.",
                'rating' => "95",
                'download_link' => "https://codecanyon.net/item/wpbakery-page-builder-for-wordpress/242431",
                'type' => "Premium",
                'screenshot_url' => "https://s3.envato.com/files/267808475/wpb-code-canyon.png",
            ],
            [
                'name' => "Slider Revolution",
                'slug' => "revslider",
                'author' => '<a href="https://codecanyon.net/user/themepunch">themepunch</a>',
                'description' => "Slider Revolution - More than just a WordPress Slider",
                'rating' => "",
                'download_link' => "https://codecanyon.net/item/slider-revolution-responsive-wordpress-plugin/2751380",
                'type' => "Premium",
                'screenshot_url' => "https://s3.envato.com/files/304879452/ccfeaturedimage3.png",
            ],
            [
                'name' => "WP Rocket",
                'slug' => "wp-rocket",
                'author' => '<a href="https://wp-rocket.me">WPRocket</a>',
                'description' => "Much more than just a caching plugin. A powerful solution to boost your loading time, improve your PageSpeed score, and optimize your Core Web Vitals.",
                'rating' => "",
                'download_link' => "https://wp-rocket.me/",
                'type' => "Premium",
                'screenshot_url' => theme_url('themes/default/images/wprocket.jpg'),
                'logo' => "https://v3b4d4f5.rocketcdn.me/wp-content/themes/V4/assets/images/logo/wp-rocket.svg",
            ],
            [
                'name' => "Elementor Pro",
                'slug' => "elementor-pro",
                'author' => "<a href=\"https://elementor.com\">Elementor.com</a>",
                'description' => "Elementor Pro empowers Elementor Page Builder with more professional tools that speed up your workflow, and allow you to get more conversions and sales.",
                'version' => "",
                'rating' => "",
                'download_link' => "https://elementor.com/pro",
                'type' => "Premium",
                'screenshot_url' => "https://ps.w.org/elementor/assets/banner-1544x500.png",
            ],
            [
                'name' => "Kreatura Slider Plugin for WordPress",
                'slug' => "LayerSlider",
                'author' => '<a href="https://codecanyon.net/user/kreatura">kreatura</a>',
                'description' => "LayerSlider is a premium multi-purpose content creation and animation platform. Easily create sliders, image galleries, slideshows with mind-blowing effects.",
                'rating' => "",
                'top_count' => "15",
                'download_link' => "https://codecanyon.net/item/kreatura-slider-plugin-for-wordpress/1362246",
                'type' => "Premium",
                'screenshot_url' => "https://s3.envato.com/files/280719146/kreatura-slider-plugin-for-wordpress.jpg",
            ],
            [
                'name' => "WPML",
                'slug' => "wpml",
                'author' => '<a href="https://wpml.org">WPML</a>',
                'description' => "What can really make the difference in conversions and amount of sales is, without a doubt, the freedom to share your own wishlist, even on social networks.",
                'rating' => "",
                'download_link' => "https://wpml.org/",
                'type' => "Premium",
                'screenshot_url' => "https://cdn.wpml.org/wp-content/themes/sitepress/images/svg/logo-wpml-otgs.svg",
                'logo' => "https://cdn.wpml.org/wp-content/themes/sitepress/images/svg/logo-wpml-otgs.svg",
            ],
            [
                'name' => "Wp Smush",
                'slug' => "wp-smush-pro",
                'author' => "<a href=\"https://profiles.wordpress.org/wpmudev/\">WPMU DEV</a>",
                'description' => "Bulk optimize, compress, and resize unlimited images in a matter of clicks. All from one easy-to-use dashboard.",
                'rating' => "4.7",
                'download_link' => "https://wpmudev.com/project/wp-smush-pro/",
                'type' => "Premium",
                'screenshot_url' => "https://www.wpthemedetector.com/ad/addir/themes/WPTD2/images/nd_plugin.png",
            ],
            // [
            //     'name' => "OneSignal - Web Push Notifications",
            //     'slug' => "onesignal-free-web-push-notifications",
            //     'author' => '',
            //     'description' => "Increase engagement and drive more repeat traffic to your WordPress site with push notifications. Now a Wordpress VIP Gold Partner. ",
            //     'rating' => "",
            //     'download_link' => "http://wordpress.org/plugins/onesignal-free-web-push-notifications",
            //     'type' => "Free",
            //     'screenshot_url' => "https://ps.w.org/onesignal-free-web-push-notifications/assets/banner-772x250.png",
            // ],
            // [
            //     'name' => "Smash Balloon Social Photo Feed",
            //     'slug' => "instagram-feed",
            //     'author' => '',
            //     'description' => "Formerly 'Instagram Feed'. Display clean, customizable, and responsive Instagram feeds from multiple accounts. Supports Instagram oEmbeds.",
            //     'rating' => "",
            //     'download_link' => "http://wordpress.org/plugins/instagram-feed",
            //     'type' => "Free",
            //     'screenshot_url' => "https://ps.w.org/instagram-feed/assets/banner-772x250.png",
            // ],
            // [
            //     'name' => "WP-PageNavi",
            //     'slug' => "wp-pagenavi",
            //     'author' => '',
            //     'description' => "Adds a more advanced paging navigation interface.",
            //     'rating' => "",
            //     'download_link' => "https://wordpress.org/plugins/wp-pagenavi",
            //     'type' => "Free",
            //     'screenshot_url' => "https://ps.w.org/wp-pagenavi/assets/banner-772x250.jpg",
            // ],
            // [
            //     'name' => "Site Kit by Google – Analytics, Search Console, AdSense, Speed",
            //     'slug' => "google-site-kit",
            //     'author' => '',
            //     'description' => "Site Kit is a one-stop solution for WordPress users to use everything Google has to offer to make them successful on the web.",
            //     'rating' => "",
            //     'download_link' => "https://wordpress.org/plugins/google-site-kit",
            //     'type' => "Free",
            //     'screenshot_url' => "https://ps.w.org/google-site-kit/assets/banner-772x250.png",
            // ],
            // [
            //     'name' => "YITH WooCommerce Wishlist",
            //     'slug' => "yith-woocommerce-wishlist",
            //     'author' => '',
            //     'description' => "What can really make the difference in conversions and amount of sales is, without a doubt, the freedom to share your own wishlist, even on social networks.",
            //     'rating' => "",
            //     'download_link' => "https://www.wpthemedetector.com/plugins/out/yith-woocommerce-wishlist.php",
            //     'type' => "Free",
            //     'screenshot_url' => "https://ps.w.org/yith-woocommerce-wishlist/assets/banner-772x250.jpg",
            // ],
        ));
    }
}
