<?php

namespace App\Helpers\Classes;

use Exception;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class WordpressThemeEngine
{
    /**
     * HTML of page
     */
    protected $html;

    /**
     * Premium themes list
     *
     * @var array
     */
    protected $themes;

    /**
     * Tags to find in css files.
     *
     * @var array
     */
    protected $themeVariables = [
        'name'               => 'Theme Name',
        'title'              => 'Title',
        'license'            => 'License',
        'license_uri'        => 'License URI',
        'theme_uri'          => 'Theme URI',
        'description'        => 'Description',
        'author'             => 'Author',
        'author_name'        => 'Author Name',
        'parent_theme'       => 'Parent Theme',
        'author_uri'         => 'Author URI',
        'version'            => 'Version',
        'template'           => 'Template',
        'screenshot_url'     => 'Screenshot',
        'tags'               => 'Tags',
        'slug'               => 'Text Domain',
        'domain_path'        => 'Domain Path',
        'download_link'      => null,
        'last_updated'       => null,
        'last_updated_time'  => null,
        'rating'             => null,
        'num_ratings'        => null,
        'downloaded'         => null,
    ];

    /**
     * Hostname from URL
     *
     * @var string
     */
    public $url = null;

    /**
     * Hostname from URL
     *
     * @var string
     */
    public $host = null;

    /**
     * Error message during curl
     *
     * @var [type]
     */
    public $error = null;

    /**
     * Actual details found in site CSS file
     *
     * @var bool|Object
     */
    public $themeInfo = false;

    /**
     * Theme info from Wordpress
     *
     * @var bool|Object
     */
    public $wordpress = false;

    /**
     * Merged theme info.
     *
     * @var bool|Object
     */
    public $theme = false;

    /**
     * Discovered plugins list.
     *
     * @var bool|Object
     */
    public $plugins = false;

    /**
     * theme directory
     *
     * @var bool|Object
     */
    public $directory = false;

    public function __construct($url)
    {
        $this->url = $url;
        $this->host =  extractHostname($url) ?? '';

        list($html, $error) = Cache::remember(md5($url), job_cache_time(), function () {
            try {
                $client = new Client();
                $response = $client->request('GET', $this->url, [
                    'curl' => guzzleCurlOptions()
                ]);

                return [$response->getBody()->getContents(), null];
            } catch (ConnectException $e) {
                return [null, $e->getHandlerContext()['error'] ?? $e->getMessage()];
            } catch (ClientException $e) {
                return [null, $this->error = $e->getMessage()];
            } catch (Exception $e) {
                return [null, $this->error = $e->getMessage()];
            }
        });

        $this->html = $html;
        $this->error = $error;
    }

    public function fetch()
    {
        preg_match_all("/href=['\"](?P<css>([^'\"]+?.*\/style\.css)[^'\"]*)/i", $this->html, $stylesheets);
        if (count($stylesheets['css']) == 0) {
            preg_match_all("/href=['\"](?P<css>([^'\"]+?\/themes\/[^'\"]+?.css)[^'\"]*)/i", $this->html, $stylesheets);
        }
        if (count($stylesheets['css']) == 0) {
            preg_match_all("/href=['\"](?P<css>([^'\"]+?.css)[^'\"]*)/i", $this->html, $stylesheets);
        }

        $stylesheets = $stylesheets['css'];
        foreach ($stylesheets as $url) {
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                continue;
            }

            $parseMeta = parseMetaFromUrl($url, $this->themeVariables);
            if (!empty($parseMeta['name'])) {
                $this->themeInfo = $parseMeta;
                $this->theme = (object) $parseMeta;
                break;
            }
        }
        $this->searchOnWordpress();
        $this->discoverPremiumThemes();
        if (!(array) $this->theme) {
            $this->theme = false;
        }

        $this->mergeIntoTheme();
        $this->processTags();
        $this->discoverScreenshot();
        $this->discoverPlugins();
    }

    protected function processTags()
    {
        if ($this->theme && !empty($this->theme->tags) && !is_array($this->theme->tags)) {
            $this->theme->tags = explode(',', $this->theme->tags);
        }
    }

    protected function searchOnWordpress()
    {
        preg_match_all("/href=['\"](?P<theme>([^'\"]+?\/themes\/[^'\"]+?)[^'\"]*)/i", $this->html, $stylesheets);
        if (count($stylesheets['theme']) == 0) {
            preg_match_all("/src=['\"](?P<theme>([^'\"]+?\/themes\/[^'\"]+?)[^'\"]*)/i", $this->html, $stylesheets);
        }

        if (count($stylesheets['theme']) > 0) {
            $dir = Str::of($stylesheets['theme'][0])->explode('/themes/')->last();
            $this->directory = Str::of($dir)->explode('/')->first();
        }

        if (!empty($this->directory)) {
            $query = "https://api.wordpress.org/themes/info/1.1/?action=theme_information&request[slug]={$this->directory}";
            $response = Cache::remember(md5($query), job_cache_time(), function () use ($query) {
                $resp = fetchAsGoogle($query);

                return json_decode($resp, true);
            });

            if ($response) {
                $this->wordpress = [
                    'name'               => $response['name'],
                    'license'            => 'GNU General Public License (GPL)',
                    'license_uri'        => 'https://www.gnu.org/licenses/gpl-3.0.en.html',
                    'theme_uri'          => $response['homepage'],
                    'description'        => isset($response['sections']['description']) ? $response['sections']['description'] : '',
                    'author'             => $response['author'],
                    'version'            => $response['version'],
                    'screenshot_url'     => $response['screenshot_url'],
                    'tags'               => $response['tags'],
                    'slug'               => $response['slug'],
                    'download_link'      => $response['download_link'],
                    'last_updated'       => $response['last_updated'],
                    'last_updated_time'  => $response['last_updated_time'],
                    'rating'             => $response['rating'],
                    'num_ratings'        => $response['num_ratings'],
                    'downloaded'         => $response['downloaded'],
                ];

                if (!$this->theme) {
                    $this->theme = (object) $this->wordpress;
                }
            }
        }
    }

    protected function mergeIntoTheme()
    {
        if (!isset($this->theme->author_name)) {
            return;
        }

        $this->theme->author_name = !empty($this->theme->author_name) ? $this->theme->author_name : $this->theme->author;
        $this->theme->license = empty($this->theme->license) && isset($this->wordpress['license']) ? $this->wordpress['license'] : 'GNU General Public License (GPL)';
        $this->theme->license_uri = empty($this->theme->license_uri) && isset($this->wordpress['license_uri']) ? $this->wordpress['license_uri'] : 'https://www.gnu.org/licenses/gpl-3.0.en.html';
        $this->theme->author = !empty($this->wordpress['author']) ? $this->wordpress['author'] : $this->theme->author;
        $this->theme->download_link = !empty($this->wordpress['download_link']) ? $this->wordpress['download_link'] : $this->theme->download_link;
        $this->theme->last_updated = !empty($this->wordpress['last_updated']) ? Carbon::parse($this->wordpress['last_updated']) : false;
        $this->theme->last_updated_time = !empty($this->wordpress['last_updated_time']) ? Carbon::parse($this->wordpress['last_updated_time']) : false;
        $this->theme->rating = $this->wordpress['rating'] ?? false;
        $this->theme->num_ratings = $this->wordpress['num_ratings'] ?? false;
        $this->theme->downloaded = $this->wordpress['downloaded'] ?? false;
        $this->theme->tags = isset($this->wordpress['tags']) && is_array($this->wordpress['tags']) ? $this->wordpress['tags'] : $this->theme->tags;
        $this->theme->screenshot_url = !empty($this->wordpress['screenshot_url']) ? $this->wordpress['screenshot_url'] : $this->theme->screenshot_url;
        $this->theme->description = $this->wordpress['description'] ?? $this->theme->description;
    }

    protected function discoverScreenshot()
    {
        if ($this->theme && isset($this->theme->author) && empty($this->theme->screenshot_url)) {
            $url = trim($this->url, '/');
            $this->theme->screenshot_url = "https://s0.wp.com/mshots/v1/{$url}?w=600";
            if (!empty($this->theme->slug)) {
                $fallbackUrl = Str::of($this->url)->finish('/')->finish("wp-content/themes/{$this->theme->slug}/screenshot.png")->toString();
                if (isHttpStatusCode200($fallbackUrl)) {
                    $this->theme->screenshot_url = $fallbackUrl;
                }
            }
        }
    }

    protected function discoverPlugins()
    {
        $pluginEngine = new WordpressPluginEngine($this->html);
        $this->plugins = $pluginEngine->parse();
    }

    protected function discoverPremiumThemes()
    {
        $premiumEngine = new WordpressPremumThemes($this->html, $this->theme, $this->directory);
        $premiumEngine->parse();
        if ($premiumEngine->theme) {
            if (!isset($this->theme->author)) {
                $this->theme = $premiumEngine->theme;
            } else {
                $this->theme = $premiumEngine->theme;
            }
        }
    }

    public function lastError()
    {
        return $this->error;
    }

    public function results()
    {
        return [
            'url' => $this->url,
            'hostname' => $this->host,
        ];
    }
}
