<?php

namespace App\Helpers\Classes;


class WordpressPremumThemes
{
    /**
     * Page HTML
     *
     * @var bool|String
     */
    protected $html;

    /**
     * Premium themes list with regex
     *
     * @var array
     */
    protected $regexLookups = [
        'theme-jupiterx' => 'Jupiter',
        'jupiterx-main' => 'Jupiter',
        'woodmart-logo' => 'WoodMart',
        'woodmart-style-fonts-css' => 'WoodMart',
        'theme-woodmart' => 'WoodMart',
        'woodmart-nav-link' => 'WoodMart',
        'the7-body' => 'The7',
        'the7-page-content-style' => 'The7',
        'the7-box-grid-wrapper' => 'The7',
        '"slug":"salient"' => 'Salient',
        'salient-wp-menu-dynamic-css' => 'Salient',
        '\/themes\/enfold[^\'"]+?\/' => 'Enfold',
        'tdw-css-placeholder' => 'Newspaper',
        'tdb-global-fonts' => 'Newspaper',
        'fusion-wrapper' => 'Avada',
        'genesis-content' => 'Genesis',
    ];

    /**
     * Collection of known premium themes
     *
     * @var collect
     */
    protected $themes;

    /**
     * Theme information objject
     *
     * @var null|object
     */
    public $theme;

    /**
     * Is theme premium
     *
     * @var bool
     */
    public $is_premium = false;

    protected $directory;

    public function __construct($html, $theme, $directory)
    {
        $this->html = $html;
        $this->theme = $theme;
        $this->directory = $directory;
        $this->themes = $this->themesList();
    }

    public function parse()
    {
        foreach ($this->regexLookups as $regex => $theme) {
            preg_match_all('/(?P<theme>(' . $regex . '))/i', $this->html, $matches);
            if (count($matches['theme']) == 0) {
                preg_match_all('/[\'"](?P<theme>(' . $regex . ')[^\'"]*)/i', $this->html, $matches);
            }
            if (count($matches['theme']) > 0) {
                $this->is_premium = true;
                $detail = $this->themes->where('name', $theme)->first();
                if ($detail) {
                    $this->theme = (object) $detail;
                }
                break;
            }
        }

        if (!empty($this->theme->name) && !$this->is_premium) {
            $theme = $this->themes->where('name', $this->theme->name)->first();
            $this->mergeIntoTheme($theme);
        } elseif (!empty($this->directory) && !$this->is_premium) {
            $detail = $this->themes->where('slug', $this->directory)->first();
            if (!isset($this->theme->author) && $detail) {
                $this->theme = (object) $detail;
            } elseif ($detail) {
                $this->mergeIntoTheme($detail);
            }
        }
    }

    protected function mergeIntoTheme($detail)
    {
        if (!$this->theme || !$detail) {
            return;
        }

        $this->theme->author_name = !empty($this->theme->author_name) ? $this->theme->author_name : $this->theme->author;
        $this->theme->license = empty($this->theme->license) ? $detail['license'] : $this->theme->license;
        $this->theme->license_uri = empty($this->theme->license_uri) ? $detail['license_uri'] : $this->theme->license_uri;
        $this->theme->author = !empty($detail['author']) ? $detail['author'] : $this->theme->author;
        $this->theme->download_link = !empty($detail['download_link']) ? $detail['download_link'] : $this->theme->download_link;
        $this->theme->screenshot_url = !empty($detail['screenshot_url']) ? $detail['screenshot_url'] : $this->theme->screenshot_url;
        $this->theme->description = $detail['description'] ?? $this->theme->description;
    }

    protected function themesList()
    {
        return collect([
            [
                'name' => "Genesis",
                'slug' => "genesis",
                'description' => "The industry standard in design frameworks for WordPress. The Genesis Framework empowers you to quickly and easily build incredible websites with WordPress.",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://www.studiopress.com/themes/genesis",
                'theme_uri' => "https://www.studiopress.com/themes/genesis",
                'type' => "Free",
                'screenshot_url' => "https://www.studiopress.com/wp-content/themes/genesis/screenshot.png",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "studiopress",
                'author_name' => "StudioPress",
                'author_uri' => "https://www.studiopress.com/",
            ],
            [
                'name' => "Genesis Pro",
                'slug' => "genesis-pro",
                'description' => "The industry standard in design frameworks for WordPress. The Genesis Framework empowers you to quickly and easily build incredible websites with WordPress.",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://www.studiopress.com/themes/genesis-pro",
                'theme_uri' => "https://www.studiopress.com/themes/genesis-pro",
                'type' => "Premium",
                'screenshot_url' => "https://www.studiopress.com/wp-content/themes/genesis/screenshot.png",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "studiopress",
                'author_name' => "StudioPress",
                'author_uri' => "https://www.studiopress.com/",
            ],
            [
                'name' => "Divi",
                'slug' => "divi",
                'description' => "Smart. Flexible. Beautiful. Divi is the most powerful theme in our collection.",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://www.elegantthemes.com/gallery/divi/",
                'theme_uri' => "https://www.elegantthemes.com/gallery/divi/",
                'type' => "Premium",
                'screenshot_url' => "https://www.elegantthemes.com/preview/Divi/wp-content/themes/Divi/screenshot.jpg",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "elegantthemes",
                'author_name' => "Elegant Themes",
                'author_uri' => "https://www.elegantthemes.com/",
            ],
            [
                'name' => "Astra",
                'slug' => "astra",
                'description' => "Astra is fast, fully customizable & beautiful theme suitable for blog, personal portfolio, business website and WooCommerce storefront.",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://wpastra.com/",
                'theme_uri' => "https://wpastra.com/",
                'type' => "Premium",
                'screenshot_url' => "https://wpastra.com/wp-content/themes/astra/screenshot.jpg",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "brainstormforce",
                'author_name' => "Brainstorm Force",
                'author_uri' => "https://wpastra.com/about/",
            ],
            [
                'name' => "Newspaper",
                'slug' => "newspaper",
                'description' => "Newspaper is an excellent responsive WordPress theme for blog, news and magazine website. With countless features, Newspaper is a clean, flexible and easy to use theme.",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://themeforest.net/item/newspaper/5489609",
                'theme_uri' => "https://demo.tagdiv.com/select_demo/select_demo_newspaper/",
                'type' => "Premium",
                'screenshot_url' => "https://demo.tagdiv.com/newspaper/wp-content/themes/011/screenshot.png",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "tagDiv",
                'author_name' => "Tag Div",
                'author_uri' => "https://themeforest.net/user/tagdiv",
            ],
            [
                'name' => "Avada",
                'slug' => "avada-responsive-multipurpose-theme",
                'description' => "Avada is so clean, super flexible and fully responsive it sets the new standard with endless possibilities! Very intuitive to use and completely ready to operate out of the box.",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://themeforest.net/item/avada-responsive-multipurpose-theme/",
                'theme_uri' => "https://avada.theme-fusion.com/prebuilt-websites/",
                'type' => "Premium",
                'screenshot_url' => "https://avada.theme-fusion.com/wp-content/themes/Avada/screenshot.jpg",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "themefusion",
                'author_name' => "Theme Fusion",
                'author_uri' => "https://themeforest.net/user/themefusion",
            ],
            [
                'name' => "Flatsome",
                'slug' => "flatsome",
                'description' => "Flatsome is the perfect theme for your shop or company website, or for all your client websites if you are an agency or freelancer.",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://themeforest.net/item/flatsome-multipurpose-responsive-woocommerce-theme/5484319",
                'theme_uri' => "https://flatsome3.uxthemes.com/",
                'type' => "Premium",
                'screenshot_url' => "http://flatsome.uxthemes.com/wp-content/themes/flatsome/screenshot.png",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "ux-themes",
                'author_name' => "UX Themes",
                'author_uri' => "https://themeforest.net/user/ux-themes",
            ],
            [
                'name' => "Enfold",
                'slug' => "enfold",
                'description' => "Enfold is a clean, super flexible and fully responsive WordPress Theme (try resizing your browser), suited for business websites, shop websites, and users who want to showcase their work on a neat portfolio site.",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://themeforest.net/item/enfold-responsive-multipurpose-theme/4519990",
                'theme_uri' => "https://kriesi.at/themes/enfold-overview/",
                'type' => "Premium",
                'screenshot_url' => "https://kriesi.at/themes/enfold/wp-content/themes/enfold/screenshot.png",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "ux-themes",
                'author_name' => "UX Themes",
                'author_uri' => "https://themeforest.net/user/kriesi",
            ],
            [
                'name' => "Hello Elementor",
                'slug' => "hello-elementor",
                'description' => "Say howdy to Hello, the fastest WordPress theme ever created. Hello theme works out of the box and offers consistent compatibility with Elementor.",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://elementor.com/products/hello-theme",
                'theme_uri' => "https://elementor.com/products/hello-theme",
                'type' => "Free",
                'screenshot_url' => "https://wpmayor.com/wp-content/themes/hello-elementor/screenshot.png",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "elemntor",
                'author_name' => "Elemntor",
                'author_uri' => "https://elementor.com/",
            ],
            [
                'name' => "OceanWP",
                'slug' => "oceanwp",
                'description' => "OceanWP is the perfect theme for your project.",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://oceanwp.org/",
                'theme_uri' => "https://oceanwp.org/",
                'type' => "Free",
                'screenshot_url' => "https://oceanwp.org/wp-content/themes/oceanwp/screenshot.png",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "oceanwp",
                'author_name' => "Oceanwp",
                'author_uri' => "https://oceanwp.org/about-oceanwp/",
            ],
            [
                'name' => "Betheme",
                'slug' => "betheme",
                'description' => "Responsive Multi-Purpose WordPress Theme. It comes with 290+ prebuilt websites and with the most intuitive website installer ever.",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://themeforest.net/item/betheme-responsive-multipurpose-wordpress-theme/7758048",
                'theme_uri' => "https://muffingroup.com/betheme/",
                'type' => "Free",
                'screenshot_url' => "https://themes.muffingroup.com/be/home/wp-content/themes/betheme/screenshot.png",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "muffingroup",
                'author_name' => "Muffin group",
                'author_uri' => "https://themeforest.net/user/muffingroup",
            ],
            [
                'name' => "GeneratePress",
                'slug' => "generatepress",
                'description' => "GeneratePress is a fast, lightweight (less than 1MB zipped), mobile responsive WordPress theme built with speed, SEO and usability in mind.",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://generatepress.com/",
                'theme_uri' => "https://generatepress.com/",
                'type' => "Free",
                'screenshot_url' => "https://generatepress.com/wp-content/themes/generatepress/screenshot.png",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "edge22",
                'author_name' => "Tom Usborne",
                'author_uri' => "https://wordpress.org/themes/author/edge22/",
            ],
            [
                'name' => "Sahifa",
                'slug' => "sahifa",
                'description' => "Sahifa is a Clean Responsive Magazine, News and Blog Template. Comes with a built-in Review System, Drag & Drop Homepage Builder, fullscreen backgrounds and much more.",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://themeforest.net/item/sahifa-responsive-wordpress-news-magazine-newspaper-theme",
                'theme_uri' => "https://themes.tielabs.com/sahifa/",
                'type' => "Premium",
                'screenshot_url' => "https://themes.tielabs.com/sahifa/wp-content/themes/sahifa/screenshot.png",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "tielabs",
                'author_name' => "Tie Labs",
                'author_uri' => "https://themeforest.net/user/tielabs",
            ],
            [
                'name' => "Salient",
                'slug' => "salient",
                'description' => "Salient comes packaged with a highly tailored version of visual composer. Enjoy building the gorgeous pages you´ve been waiting for all with drag & drop simplicity.",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://themeforest.net/item/salient-responsive-multipurpose-theme/4363266",
                'theme_uri' => "https://themenectar.com/salient/",
                'type' => "Premium",
                'screenshot_url' => "https://themenectar.com/demo/salient/wp-content/themes/salient/screenshot.png",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "tielabs",
                'author_name' => "ThemeNectar",
                'author_uri' => "https://themeforest.net/user/themenectar",
            ],
            [
                'name' => "Bridge",
                'slug' => "bridge",
                'description' => "Creative Multi-Purpose WordPress Theme with full website solutions: each Bridge demo is a fully equiped, beatifully designed and easily customizable website of its own.",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://themeforest.net/item/bridge-creative-multipurpose-wordpress-theme/7315054",
                'theme_uri' => "https://demo.qodeinteractive.com/bridge/",
                'type' => "Premium",
                'screenshot_url' => "https://bridgelanding.qodeinteractive.com/wp-content/themes/bridge/screenshot.png",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "qode",
                'author_name' => "Qode Themes",
                'author_uri' => "https://themeforest.net/user/qode",
            ],
            [
                'name' => "The7",
                'slug' => "the7",
                'description' => "The7 is perfectly scalable, performance and SEO optimized, responsive, retina ready multipurpose WordPress theme. It will fit every site – big or small.",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://themeforest.net/item/the7-responsive-multipurpose-wordpress-theme/5556590",
                'theme_uri' => "https://the7.io/",
                'type' => "Premium",
                'screenshot_url' => "https://the7.io/main/wp-content/themes/dt-the7/screenshot.jpg",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "dream-theme",
                'author_name' => "Dream Theme",
                'author_uri' => "https://themeforest.net/user/dream-theme",
            ],
            [
                'name' => "WoodMart",
                'slug' => "woodmart",
                'description' => "WoodMart is a premium theme optimized for creating WooCommerce online stores that provides a super-fast interface for the ultimate user experience",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://themeforest.net/item/woodmart-woocommerce-wordpress-theme/20264492",
                'theme_uri' => "https://woodmart.xtemos.com/",
                'type' => "Premium",
                'screenshot_url' => "https://z9d7c4u6.rocketcdn.me/wp-content/themes/woodmart/screenshot.png",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "xtemos",
                'author_name' => "XTemos Studio",
                'author_uri' => "https://themeforest.net/user/xtemos",
            ],
            [
                'name' => "Uncode",
                'slug' => "uncode",
                'description' => "Uncode is a pixel-perfect creative WordPress Theme for any kind of website (portfolio, agency, freelance, blog) and a top WooCommerce Theme for shops (eCommerce, online store, business). Uncode is designed with incredible attention to detail, flexibility, and performance. In addition, the Uncode Website Builder includes responsive templates and allows you to edit your pages without touching a single line of code: anything you can think of can be built!",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://themeforest.net/item/uncode-creative-multiuse-wordpress-theme/13373220",
                'theme_uri' => "https://undsgn.com/uncode/",
                'type' => "Premium",
                'screenshot_url' => "https://undsgn.com/uncode/wp-content/themes/uncode/screenshot.png",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "undsgn",
                'author_name' => "Undsgn",
                'author_uri' => "https://themeforest.net/user/undsgn",
            ],
            [
                'name' => "Jupiter",
                'slug' => "jupiterx",
                'description' => "For almost a decade, Jupiter has been a go-to WordPress website builder for businesses, online shops and agencies and also a home to some of the most creative web designers and developers in the world.",
                'version' => "-",
                'rating' => "",
                'download_link' => "https://themeforest.net/item/jupiter-multipurpose-responsive-theme/5177775",
                'theme_uri' => "https://jupiterx.com/",
                'type' => "Premium",
                'screenshot_url' => "https://jupiterx.com/wp-content/themes/jupiterx/screenshot.png",
                'license'            => 'GNU General Public License (GPL)',
                'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                'tags' => [],
                'author' => "artbees",
                'author_name' => "Artbees",
                'author_uri' => "https://themeforest.net/user/artbees",
            ],
        ]);
    }
}
