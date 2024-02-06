<?php

use App\Models\Tag;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Plan;
use App\Models\Post;
use App\Models\Role;
use App\Models\Tool;
use App\Models\Category;
use App\Models\Advertisement;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('admin', function (BreadcrumbTrail $trail) {
    $trail->push(__('admin.home'));
});
Breadcrumbs::for('admin.dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('admin');
    $trail->push(__('admin.dashboard'), route('admin.dashboard'), ['container' => 'container-fluid']);
});
Breadcrumbs::for('admin.home', function (BreadcrumbTrail $trail) {
    $trail->parent('admin');
    $trail->push(__('admin.dashboard'), route('admin.dashboard'), ['container' => 'container-fluid']);
});
Breadcrumbs::for('admin.settings', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('admin.settings'), route('admin.settings'), ['container' => 'container-fluid']);
});
Breadcrumbs::for('system.patches', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('admin.patches'), route('system.patches'));
});

// Pages Breadcrumbs
Breadcrumbs::for('admin.pages', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('admin.managePages'), route('admin.pages'));
});
Breadcrumbs::for('admin.pages.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.pages');
    $trail->push(__('admin.createPage'), route('admin.pages.create'), ['container' => 'container-fluid']);
});
Breadcrumbs::for('admin.pages.edit', function (BreadcrumbTrail $trail, Page $page) {
    $trail->parent('admin.pages');
    $trail->push(__('admin.editPage'), route('admin.pages.edit', $page));
});

// Posts Breadcrumbs
Breadcrumbs::for('admin.posts', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('admin.managePosts'), route('admin.posts'));
});
Breadcrumbs::for('admin.posts.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.posts');
    $trail->push(__('admin.createPost'), route('admin.posts.create'), ['container' => 'container-fluid']);
});
Breadcrumbs::for('admin.posts.edit', function (BreadcrumbTrail $trail, Post $post) {
    $trail->parent('admin.posts');
    $trail->push($post->title, route('admin.posts.edit', $post), ['container' => 'container-fluid']);
});

// Tags
Breadcrumbs::for('admin.tags', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('admin.manageTags'), route('admin.tags'));
});
Breadcrumbs::for('admin.tags.edit', function (BreadcrumbTrail $trail, Tag $tag) {
    $trail->parent('admin.tags');
    $trail->push($tag->name, route('admin.tags.edit', $tag));
});

// Categories
Breadcrumbs::for('admin.categories', function (BreadcrumbTrail $trail, $type = null) {
    $trail->parent('admin.dashboard');
    $trail->push(__('admin.manageCategories'), route('admin.categories', ['type' => $type]));
});
Breadcrumbs::for('admin.categories.edit', function (BreadcrumbTrail $trail, Category $category) {
    $trail->parent('admin.categories');
    $trail->push($category->name, route('admin.categories.edit', $category));
});

// users
Breadcrumbs::for('admin.users', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('admin.users'), route('admin.users'));
});
Breadcrumbs::for('admin.users.trashed', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('admin.deletedUsers'), route('admin.users.trashed'));
});

// roles
Breadcrumbs::for('admin.roles', function (BreadcrumbTrail $trail, Role $role = null) {
    $trail->parent('admin.dashboard');
    $trail->push(__('admin.roles'), route('admin.roles'));

    if ($role) {
        $trail->push($role->name, route('admin.roles', $role));
    }
});

// roles
Breadcrumbs::for('admin.menus', function (BreadcrumbTrail $trail, Menu $menu = null) {
    $trail->parent('admin.dashboard');
    $trail->push(__('common.menus'), route('admin.menus'), ['container' => 'container-fluid']);

    if ($menu) {
        $trail->push($menu->name, route('admin.menus'), ['container' => 'container-fluid']);
    }
});

// tools
Breadcrumbs::for('admin.tools', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('common.tools'), route('admin.tools'));
});
Breadcrumbs::for('admin.tools.home-page', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('common.tools'), route('admin.tools'));
    $trail->push(__('common.toolsHomepage'), route('admin.tools.home-page'));
});
Breadcrumbs::for('admin.tools.edit', function (BreadcrumbTrail $trail, Tool $tool) {
    $trail->parent('admin.tools');
    $trail->push($tool->name, route('admin.tools.edit', $tool));
});

// profile
Breadcrumbs::for('admin.profile', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('common.profile'), route('admin.profile'), ['container' => 'container-fluid']);
});
Breadcrumbs::for('admin.password', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('common.password'), route('admin.password'), ['container' => 'container-fluid']);
});
Breadcrumbs::for('admin.mfa', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('common.2fa'), route('admin.mfa'), ['container' => 'container-fluid']);
});

// plan
Breadcrumbs::for('admin.plans', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('admin.plans'), route('admin.plans'), ['container' => 'container-fluid']);
});
Breadcrumbs::for('admin.plans.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.plans');
    $trail->push(__('admin.plansCreate'), route('admin.plans.create'), ['container' => 'container-fluid']);
});
Breadcrumbs::for('admin.plans.edit', function (BreadcrumbTrail $trail, Plan $plan) {
    $trail->parent('admin.plans');
    $trail->push($plan->name, route('admin.plans.edit', $plan));
});
Breadcrumbs::for('admin.transactions.list', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.plans');
    $trail->push(__('admin.transactionList'), route('admin.transactions.list'), ['container' => 'container-fluid']);
});
Breadcrumbs::for('admin.transactions.bankTransfer', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.plans');
    $trail->push(__('common.bankTransfer'), route('admin.transactions.bankTransfer'), ['container' => 'container-fluid']);
});

// FAQS
Breadcrumbs::for('admin.faqs.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('admin.faqs'), route('admin.faqs.index'));
});
Breadcrumbs::for('admin.faqs.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.faqs.index');
    $trail->push(__('admin.createFaq'), route('admin.faqs.create'));
});
Breadcrumbs::for('admin.faqs.edit', function (BreadcrumbTrail $trail, $faq) {
    $trail->parent('admin.faqs.index');
    $trail->push(__('admin.editFaq'), route('admin.faqs.edit', $faq));
});

// ads
Breadcrumbs::for('admin.advertisements', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('admin.advertisements'), route('admin.advertisements'), ['container' => 'container-fluid']);
});
Breadcrumbs::for('admin.advertisements.create', function (BreadcrumbTrail $trail, $type = null) {
    $trail->parent('admin.advertisements');
    $trail->push(__('admin.createAdvertisements'), route('admin.advertisements.create', ['type' => $type]), ['container' => 'container-fluid']);
});
Breadcrumbs::for('admin.advertisements.edit', function (BreadcrumbTrail $trail, Advertisement $advertisement) {
    $trail->parent('admin.advertisements');
    $trail->push($advertisement->title, route('admin.advertisements.edit', $advertisement));
});

// Widgets Management
Breadcrumbs::for('admin.widgets.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('widgets.widgets'), route('admin.widgets.index'), ['container' => 'container-fluid']);
});


// Frontend breadcrumbs
Breadcrumbs::for('front', function (BreadcrumbTrail $trail) {
    $trail->push(__('common.home'), route('front.index'));
});
Breadcrumbs::for('pages.show', function (BreadcrumbTrail $trail,  $page) {
    $trail->parent('front');
    $trail->push($page->title, route('pages.show', ['slug' => $page->slug]));
});
// Blog
Breadcrumbs::for('blog.show', function (BreadcrumbTrail $trail) {
    $trail->parent('front');
    $trail->push(trans('common.blog'), route('blog.show'));
});
Breadcrumbs::for('blog.category', function (BreadcrumbTrail $trail,  $category) {
    $trail->parent('blog.show');
    $trail->push($category->name, route('blog.category', ['category' => $category->slug]));
});
Breadcrumbs::for('blog.tag', function (BreadcrumbTrail $trail,  $tag) {
    $trail->parent('blog.show');
    $trail->push($tag->name, route('blog.tag', ['tag' => $tag->slug]));
});
Breadcrumbs::for('posts.show', function (BreadcrumbTrail $trail,  $post) {
    $trail->parent('blog.show');
    foreach ($post->categories as $category) {
        $trail->push($category->name, route('blog.category', ['category' => $category->slug]));
    }
    $trail->push($post->title, route('blog.show', ['slug' => $post->slug]));
});
//profile
Breadcrumbs::for('user.profile', function (BreadcrumbTrail $trail) {
    $trail->parent('front');
    $trail->push(trans('common.profile'), route('user.profile'));
});
Breadcrumbs::for('user.password', function (BreadcrumbTrail $trail) {
    $trail->parent('front');
    $trail->push(trans('profile.password'), route('user.password'));
});
Breadcrumbs::for('payments.transactions', function (BreadcrumbTrail $trail) {
    $trail->parent('front');
    $trail->push(trans('profile.payments'), route('payments.transactions'));
});
Breadcrumbs::for('user.plan', function (BreadcrumbTrail $trail) {
    $trail->parent('front');
    $trail->push(trans('profile.plan'), route('user.plan'));
});
Breadcrumbs::for('user.deleteAccount', function (BreadcrumbTrail $trail) {
    $trail->parent('front');
    $trail->push(trans('profile.deleteAccount'), route('user.deleteAccount'));
});
Breadcrumbs::for('user.twofactor', function (BreadcrumbTrail $trail) {
    $trail->parent('front');
    $trail->push(trans('profile.2FA'), route('user.twofactor'));
});
Breadcrumbs::for('transaction.invoice', function (BreadcrumbTrail $trail, $transaction) {
    $trail->parent('payments.transactions');
    $trail->push(trans('profile.invoice'), route('transaction.invoice', $transaction));
});

//
Breadcrumbs::for('tool.category', function (BreadcrumbTrail $trail,  $category) {
    $trail->parent('front');
    $trail->push($category->name, route('tool.category', ['category' => $category->slug]));
});
Breadcrumbs::for('tool.show', function (BreadcrumbTrail $trail,  $tool = null) {
    $trail->parent('front');
    foreach ($tool->category as $category) {
        $trail->push($category->name, route('tool.category', ['category' => $category->slug]));
    }
    $trail->push($tool->name, route('tool.show', ['tool' => $tool->slug]));
});
Breadcrumbs::for('tool.handle', function (BreadcrumbTrail $trail,  Tool $tool) {
    $trail->parent('front');
    foreach ($tool->category as $category) {
        $trail->push($category->name, route('tool.category', ['category' => $category->slug]));
    }
    $trail->push($tool->name, route('tool.show', ['tool' => $tool->slug]));
});
//plan
Breadcrumbs::for('plans.list', function (BreadcrumbTrail $trail) {
    $trail->parent('front');
    $trail->push(trans('admin.plans'), route('plans.list'));
});
Breadcrumbs::for('ads.remove', function (BreadcrumbTrail $trail) {
    $trail->parent('front');
    $trail->push(trans('tools.removeAdsTitle'), route('ads.remove'));
});
Breadcrumbs::for('payments.checkout', function (BreadcrumbTrail $trail, $plan_id, $type) {
    $plan_id != 0 ? $trail->parent('plans.list') : $trail->parent('ads.remove');
    $trail->push(trans('tools.checkoutTitle'), route('payments.checkout', ['plan_id' => $plan_id, 'type' => $type]));
});

//contact
Breadcrumbs::for('contact', function (BreadcrumbTrail $trail) {
    $trail->parent('front');
    $trail->push(trans('contact.contactUs'), route('contact'));
});
