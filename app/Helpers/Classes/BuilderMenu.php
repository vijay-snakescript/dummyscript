<?php

namespace App\Helpers\Classes;

use App\Models\Tag;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tool;
use App\Models\Category;
use App\Helpers\Facads\MenuBuilder;
use App\Helpers\Classes\Menu\MenuContainer;
use App\Helpers\Classes\Menu\RegisterMenuItem;

class BuilderMenu
{
    public function __construct()
    {
        $this->registerBuilderItems();
    }

    protected function customPages()
    {
        $pages = collect([]);
        $pages->push((object)[
            'id' => 'pricing',
            'title' => __('admin.pricing'),
            'target' => '_self',
            'type' => 'route',
            'params' => [],
            'route' => 'plans.list',
        ]);
        $pages->push((object)[
            'id' => 'remove-ads',
            'title' => __('common.removeAds'),
            'target' => '_self',
            'type' => 'route',
            'params' => [],
            'route' => 'ads.remove',
        ]);
        $pages->push((object)[
            'id' => 'blog',
            'title' => __('common.blog'),
            'target' => '_self',
            'type' => 'route',
            'params' => [],
            'route' => 'blog.show',
        ]);
        $pages->push((object)[
            'id' => 'tools-page',
            'title' => __('common.tools'),
            'target' => '_self',
            'type' => 'route',
            'params' => [],
            'route' => 'front.tools',
        ]);

        return $pages;
    }

    protected function registerBuilderItems()
    {
        // Pages
        MenuBuilder::register(
            'pages',
            function (MenuContainer $menu) {
                $menu->name(trans('admin.pages'))
                    ->key('pages-menu');
            },
            10
        );
        $pages = MenuBuilder::get('pages');
        Page::with('translations')->published()->get()->each(function ($page) use ($pages) {
            $pages->item("page-{$page->id}", function (RegisterMenuItem $menu) use ($page) {
                $menu->label($page->title)
                    ->key("page-{$page->id}")
                    ->target('_self')
                    ->type('route')
                    ->params([
                        'model' => 'Page',
                        'id' => $page->id
                    ])
                    ->route('pages.show');
            });
        });
        $this->customPages()->each(function ($page) use ($pages) {
            $pages->item("page-{$page->id}", function (RegisterMenuItem $menu) use ($page) {
                $menu->label($page->title)
                    ->key("page-{$page->id}")
                    ->target($page->target)
                    ->type($page->type)
                    ->route($page->route);
            });
        });

        // posts
        MenuBuilder::register(
            'posts',
            function (MenuContainer $menu) {
                $menu->name(trans('admin.posts'))
                    ->key('posts-menu');
            },
            60
        );
        $posts = MenuBuilder::get('posts');
        Post::with('translations')->published()->get()->each(function ($post) use ($posts) {
            $posts->item("post-{$post->id}", function (RegisterMenuItem $menu) use ($post) {
                $menu->label($post->title)
                    ->key("post-{$post->id}")
                    ->target('_self')
                    ->type('route')
                    ->params([
                        'model' => 'Post',
                        'id' => $post->id
                    ])
                    ->route('posts.show');
            });
        });

        // Post categories
        MenuBuilder::register(
            'post-categories',
            function (MenuContainer $menu) {
                $menu->name(trans('admin.postCategories'))
                    ->key('post-categories-menu');
            },
            40
        );
        $categories = MenuBuilder::get('post-categories');
        Category::with('translations')->active()->post()->get()->each(function ($category) use ($categories) {
            $categories->item("post-category-{$category->id}", function (RegisterMenuItem $menu) use ($category) {
                $category->translateOrDefault();
                $menu->label($category->name ?? __('common.noTitle'))
                    ->key("category-{$category->id}")
                    ->target('_self')
                    ->type('route')
                    ->params([
                        'model' => 'Category',
                        'id' => $category->id
                    ])
                    ->route('blog.category');
            });
        });
        MenuBuilder::register(
            'tags',
            function (MenuContainer $menu) {
                $menu->name(trans('admin.tags'))
                    ->key('tags-menu');
            },
            50
        );
        $tags = MenuBuilder::get('tags');
        Tag::with('translations')->active()->get()->each(function ($tag) use ($tags) {
            $tags->item("tag-{$tag->id}", function (RegisterMenuItem $menu) use ($tag) {
                $menu->label($tag->name)
                    ->key("tag-{$tag->id}")
                    ->target('_self')
                    ->type('route')
                    ->params([
                        'model' => 'Tag',
                        'id' => $tag->id
                    ])
                    ->route('blog.tag');
            });
        });

        // Tool categories
        MenuBuilder::register(
            'tool-categories',
            function (MenuContainer $menu) {
                $menu->name(trans('admin.toolCategories'))
                    ->key('tool-categories-menu');
            },
            100
        );
        $categories = MenuBuilder::get('tool-categories');
        Category::with('translations')->active()->tool()->get()->each(function ($category) use ($categories) {
            $categories->item("tool-category-{$category->id}", function (RegisterMenuItem $menu) use ($category) {
                $category->translateOrDefault();
                $menu->label($category->name ?? __('common.noTitle'))
                    ->key("category-{$category->id}")
                    ->target('_self')
                    ->type('route')
                    ->params([
                        'model' => 'Category',
                        'id' => $category->id
                    ])
                    ->route('tool.category');
            });
        });

        // Tool categories
        MenuBuilder::register(
            'tools',
            function (MenuContainer $menu) {
                $menu->name(trans('admin.tools'))
                    ->key('tools-menu');
            },
            110
        );
        $tools = MenuBuilder::get('tools');
        Tool::with('translations')->active()->get()->each(function ($tool) use ($tools) {
            $tools->item("tool-item-{$tool->id}", function (RegisterMenuItem $menu) use ($tool) {
                $tool->translateOrDefault();
                $menu->label($tool->name ?? __('common.noTitle'))
                    ->key("tool-{$tool->id}")
                    ->target('_self')
                    ->type('route')
                    ->params([
                        'model' => 'Tool',
                        'id' => $tool->id
                    ])
                    ->route('tool.show');
            });
        });
    }
}
