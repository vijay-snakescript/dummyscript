<?php

namespace App\Helpers\Classes;

use Theme;
use Setting;
use Storage;
use Illuminate\View\View;
use Illuminate\Support\Str;

use Butschster\Head\Facades\Meta;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class DynamicCss
{
    protected $defaults;
    protected $theme;
    protected $current;
    protected $info;
    protected $prefix = 'bs-';

    public function __construct()
    {
        $this->current = Config::get('artisan.front_theme');
        $this->info = Theme::find($this->current);

        $this->setDefaults();
        $this->themeOptions();
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function build()
    {
        $dynamic_css = '';
        switch ($this->current) {
            case 'canvas':
                $dynamic_css = $this->themeCanvas();
                break;
        }

        $path = "css/" . $this->current . "-css.css";
        if (!empty(trim($dynamic_css))) {
            Storage::disk('public')->put($path, $dynamic_css);
        } elseif (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        return $dynamic_css;
    }


    /*
    * Canvas Theme dynamic CSS
    *
    *
    */
    private function themeCanvas()
    {
        $dynamicCSS  = ":root {";
        if (!$this->is_theme_default('primary_color', 'light')) {
            $dynamicCSS .= "
                             --{$this->prefix}primary: {$this->theme->light->primary_color};
                             --{$this->prefix}link-color: {$this->theme->light->primary_color};
                             --{$this->prefix}link-hover-color: {$this->theme->light->primary_color};
                             --{$this->prefix}btn-border-color: {$this->theme->light->primary_color};
                        ";
        }
        if (!$this->is_theme_default('border_color', 'light')) {
            $dynamicCSS .= "
                            --{$this->prefix}border-color: {$this->theme->light->border_color};
                        ";
        }
        if (!$this->is_theme_default('background_color', 'light')) {
            $dynamicCSS .= "
                            --{$this->prefix}body-bg: {$this->theme->light->background_color};
                        ";
        }
        if (!$this->is_theme_default('body_color', 'light')) {
            $dynamicCSS .= "
                              --{$this->prefix}body-color: {$this->theme->light->body_color};
                        ";
        }

        if (!$this->is_theme_default('primary_color', 'dark')) {
            $dynamicCSS .= "
                            --{$this->prefix}primary-dark: {$this->theme->dark->primary_color};
                        ";
        }
        if (!$this->is_theme_default('secondary_color', 'dark')) {
            $dynamicCSS .= "
                            --{$this->prefix}secondary-dark: {$this->theme->dark->secondary_color};
                        ";
        }
        if (!$this->is_theme_default('body_text_color', 'dark')) {
            $dynamicCSS .= "
                          --{$this->prefix}dark-text-color: {$this->theme->dark->body_text_color};
                        ";
        }
        if (!$this->is_theme_default('border_color', 'dark')) {
            $dynamicCSS .= "
                          --{$this->prefix}border-color: {$this->theme->dark->border_color};
                          --{$this->prefix}dark-border-color: {$this->theme->dark->border_color};
                        ";
        }
        if (!$this->is_default('body_font_size')) {
            $dynamicCSS .= "
                        --{$this->prefix}body-font-size: {$this->theme->body_font_size};
                        ";
        }
        if (!$this->is_default('body_line_height')) {
            $dynamicCSS .= "
                        --{$this->prefix}body-line-height: {$this->theme->body_line_height};
                        ";
        }
        if (!$this->is_default('body_font_family')) {
            $dynamicCSS .= "
                        --{$this->prefix}body-font-family: {$this->theme->body_font_family};
                        --{$this->prefix}font-monospace: {$this->theme->body_font_family};
                        ";
        }
        $dynamicCSS .= "}";


        if (!$this->is_theme_default('h1_color') || !$this->is_default('h1_font_size')) {
            $dynamicCSS .= 'h1,.h1{';
            if (!$this->is_theme_default('h1_color')) {
                $dynamicCSS .= 'color:' . $this->theme->light->h1_color . ';';
            }
            if (!$this->is_default('h1_font_size')) {
                $dynamicCSS .= 'font-size:' . $this->theme->h1_font_size . ';';
            }
            $dynamicCSS .= '}';
        }

        if (!$this->is_theme_default('h2_color') || !$this->is_default('h2_font_size')) {
            $dynamicCSS .= 'h2,.h2{';
            if (!$this->is_theme_default('h2_color')) {
                $dynamicCSS .= 'color:' . $this->theme->light->h2_color . ';';
            }
            if (!$this->is_default('h2_font_size')) {
                $dynamicCSS .= 'font-size:' . $this->theme->h2_font_size . ';';
            }
            $dynamicCSS .= '}';
        }

        if (!$this->is_theme_default('h3_color') || !$this->is_default('h3_font_size')) {
            $dynamicCSS .= 'h3,.h3{';
            if (!$this->is_theme_default('h3_color')) {
                $dynamicCSS .= 'color:' . $this->theme->light->h3_color . ';';
            }
            if (!$this->is_default('h3_font_size')) {
                $dynamicCSS .= 'font-size:' . $this->theme->h3_font_size . ';';
            }
            $dynamicCSS .= '}';
        }

        if (!$this->is_theme_default('h4_color') || !$this->is_default('h4_font_size')) {
            $dynamicCSS .= 'h4,.h4{';
            if (!$this->is_theme_default('h4_color')) {
                $dynamicCSS .= 'color:' . $this->theme->light->h4_color . ';';
            }
            if (!$this->is_default('h4_font_size')) {
                $dynamicCSS .= 'font-size:' . $this->theme->h4_font_size . ';';
            }
            $dynamicCSS .= '}';
        }

        if (!$this->is_theme_default('h5_color') || !$this->is_default('h5_font_size')) {
            $dynamicCSS .= 'h5,.h5{';
            if (!$this->is_theme_default('h5_color')) {
                $dynamicCSS .= 'color:' . $this->theme->light->h5_color . ';';
            }
            if (!$this->is_default('h5_font_size')) {
                $dynamicCSS .= 'font-size:' . $this->theme->h5_font_size . ';';
            }
            $dynamicCSS .= '}';
        }

        if (!$this->is_theme_default('h6_color') || !$this->is_default('h6_font_size')) {
            $dynamicCSS .= 'h6,.h6{';
            if (!$this->is_theme_default('h6_color')) {
                $dynamicCSS .= 'color:' . $this->theme->light->h6_color . ';';
            }
            if (!$this->is_default('h6_font_size')) {
                $dynamicCSS .= 'font-size:' . $this->theme->h6_font_size . ';';
            }
            $dynamicCSS .= '}';
        }

        if (!$this->is_theme_default('primary_color', 'light')) {
            $dynamicCSS .= ".btn-primary {
                          --{$this->prefix}btn-border-color: " . $this->theme->light->primary_color . ";
                          --{$this->prefix}btn-bg: " . $this->theme->light->primary_color . ";
                          --{$this->prefix}btn-hover-bg: " . color_luminance($this->theme->light->primary_color, 0.05) . ";
                          --{$this->prefix}btn-active-bg: " . color_luminance($this->theme->light->primary_color, 0.05) . ";
                          --{$this->prefix}btn-hover-border-color: " . color_luminance($this->theme->light->primary_color, 0.05) . ";
                          --{$this->prefix}btn-btn-active-border-color: " . color_luminance($this->theme->light->primary_color, 0.05) . ";
                        }
                        .btn-outline-primary {
                          --{$this->prefix}btn-border-color: {$this->theme->light->primary_color};
                          --{$this->prefix}btn-color: {$this->theme->light->primary_color};
                          --{$this->prefix}btn-hover-bg: " . color_luminance($this->theme->light->primary_color, 0.05) . ";
                          --{$this->prefix}btn-active-bg: " . color_luminance($this->theme->light->primary_color, 0.05) . ";
                          --{$this->prefix}btn-hover-border-color: " . color_luminance($this->theme->light->primary_color, 0.05) . ";
                          --{$this->prefix}btn-active-border-color: " . color_luminance($this->theme->light->primary_color, 0.05) . ";
                        }";
        }

        if (!$this->is_theme_default('header_background_color', 'light')) {
            $dynamicCSS .= ".navbar
                            { background: {$this->theme->light->header_background_color} !important; }";
        }

        if ($dynamicCSS == ':root {}') {
            $dynamicCSS = '';
        }

        $authBG = setting('auth_pages_image');
        if (!Str::contains($authBG, 'auth-bg.jpg')) {
            $dynamicCSS .= empty($authBG) ? ".auth-body{--{$this->prefix}auth-bg: none}" : ".auth-body{--{$this->prefix}auth-bg: url('" . url($authBG) . "')}";
        }

        if (!$this->is_default('heading_font_family')) {
            $heading_font_family = $this->theme->heading_font_family;
            $dynamicCSS .= 'pre,code,kbd,samp,h1,h2,h3,h4,h5,h6,.h1,.h2,.h3,.h4,.h5,.h6,.text-monospace{font-family: "' . $heading_font_family . '",sans-serif;}';
        }

        return  $dynamicCSS;
    }

    /*
    * Compare option with default value
    *
    * @since v1.0.0
    */
    private function is_default($option)
    {
        if (!isset($this->defaults->$option) || !isset($this->theme->$option)) {
            return true;
        }

        return ($this->defaults->$option == $this->theme->$option);
    }

    /*
    * Compare option with default value
    *
    * @since v1.0.0
    */
    private function is_theme_default($option, $mode = 'light')
    {
        if (!isset($this->defaults->$mode->$option) || !isset($this->theme->$mode->$option)) {
            return true;
        }
        return ($this->defaults->$mode->$option == $this->theme->$mode->$option);
    }

    /*
    * Current active theme options
    *
    * @since v1.0.0
    */
    private function themeOptions()
    {
        $theme = $this->info;
        $themeOptions = Setting::get($this->current, false);

        if ($themeOptions) {
            $this->theme = json_decode($themeOptions);
            $settings = $this->defaults;

            $this->theme->body_font_family = $this->theme->body_font->family ?? $settings->body_font_family ?? 'Source Sans Pro';
            $this->theme->heading_font_family = $this->theme->heading_font->family ?? $settings->heading_font_family ?? 'PT Sans';
            $this->theme->body_font_variant = (isset($this->theme->body_font->variant) && is_array($this->theme->body_font->variant) ? implode(',', $this->theme->body_font->variant) : null) ?? $settings->defaults->body_font_variant ?? 'regular,300,600,700';
            $this->theme->heading_font_variant = (isset($this->theme->heading_font->variant) && is_array($this->theme->heading_font->variant) ? implode(',', $this->theme->heading_font->variant) : null) ?? $settings->defaults->heading_font_variant ?? 'regular,700';
        }
    }

    /*
    * Call set font macro to init Google Font.
    *
    * @since v1.5.0
    */
    public function registerFont()
    {
        $font = array();
        $font['body_family'] = $this->theme->body_font_family ?? '';
        $font['heading_font'] = $this->theme->heading_font_family ?? '';
        $font['body_variant'] = $this->theme->body_font_variant ?? '';
        $font['heading_variant'] = $this->theme->heading_font_variant ?? '';
        if (isset($this->theme->body_font_family) || isset($this->theme->heading_font_family)) {
            Meta::setFont($font);
        }
    }

    /*
    * Set defauts from theme.json file
    *
    * @since v1.0.0
    */
    private function setDefaults()
    {
        $theme = $this->info;

        $settings = (object) $theme->settings ?? [];
        $default_vars = $settings->defaults ?? [];

        $this->defaults = arrayToObject($default_vars);
    }
}
