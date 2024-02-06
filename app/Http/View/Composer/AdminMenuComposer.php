<?php

namespace App\Http\View\Composer;

use Illuminate\Support\Str;
use App\Helpers\Facads\Menu;
use App\Helpers\Classes\MenuItems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class AdminMenuComposer
{
    /**
     * Bind data to the view.
     *
     * @return collection
     */
    public function register()
    {
        $this->registerAdminMenu();
    }

    /**
     * Register Admin Menu for the AvoRed E commerce package.
     *
     * @return void
     */
    public function registerAdminMenu()
    {
        if (Auth::check()) {
            Menu::make(
                'dashboard',
                function (MenuItems $menu) {
                    $menu->label('admin.dashboard')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-dashboard')
                        ->route('admin.dashboard');
                }
            );

            // Pages Menu Items
            Menu::make(
                'pages',
                function (MenuItems $menu) {
                    $menu->label('admin.pages')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-write')
                        ->route('admin.pages');
                }
            );
            $pagesMenu = Menu::get('pages');
            $pagesMenu->subMenu(
                'add-page',
                function (MenuItems $menu) {
                    $menu->key('create')
                        ->label('admin.createPage')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-chevron-right')
                        ->route('admin.pages.create');
                }
            );
            $pagesMenu->subMenu(
                'manage-pages',
                function (MenuItems $menu) {
                    $menu->key('index')
                        ->label('admin.managePages')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-chevron-right')
                        ->route('admin.pages');
                }
            );

            // Blog Menu Items
            Menu::make(
                'posts',
                function (MenuItems $menu) {
                    $menu->label('admin.posts')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-pin')
                        ->route('admin.posts');
                }
            );
            $postsMenu = Menu::get('posts');
            $postsMenu->subMenu(
                'manage-posts',
                function (MenuItems $menu) {
                    $menu->key('create')
                        ->label('admin.allPosts')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-chevron-right')
                        ->route('admin.posts');
                }
            );
            $postsMenu->subMenu(
                'create-posts',
                function (MenuItems $menu) {
                    $menu->key('create')
                        ->label('admin.newPost')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-chevron-right')
                        ->route('admin.posts.create');
                }
            );
            $postsMenu->subMenu(
                'manage-categories',
                function (MenuItems $menu) {
                    $menu->key('categories-index')
                        ->label('admin.categories')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-chevron-right')
                        ->route('admin.categories', ['type'  => "post"]);
                }
            );

            $postsMenu->subMenu(
                'manage-tags',
                function (MenuItems $menu) {
                    $menu->key('tags-index')
                        ->label('admin.tags')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-chevron-right')
                        ->route('admin.tags');
                }
            );

            Menu::make(
                'users',
                function (MenuItems $menu) {
                    $menu->label('admin.users')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-users')
                        ->route('admin.users');
                }
            );

            $usersMenu = Menu::get('users');
            $usersMenu->subMenu(
                'manage-users',
                function (MenuItems $menu) {
                    $menu->key('create')
                        ->label('admin.manageUsers')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-chevron-right')
                        ->route('admin.users');
                }
            );
            if (Route::has('admin.users.trashed')) {
                $usersMenu->subMenu(
                    'manage-deleted-users',
                    function (MenuItems $menu) {
                        $menu->key('deleted-users')
                            ->label('admin.deletedUsers')
                            ->type(MenuItems::ADMIN)
                            ->icon('nav-icon lni lni-chevron-right')
                            ->route('admin.users.trashed');
                    }
                );
            }
            $usersMenu->subMenu(
                'manage-roles',
                function (MenuItems $menu) {
                    $menu->key('create')
                        ->label('admin.roles')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-chevron-right')
                        ->route('admin.roles');
                }
            );
            Menu::make(
                'appearance',
                function (MenuItems $menu) {
                    $menu->label('common.appearance')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-brush-alt')
                        ->route('admin.menus');
                }
            );
            $appearanceMenu = Menu::get('appearance');
            $appearanceMenu->subMenu(
                'manage-menu',
                function (MenuItems $menu) {
                    $menu->key('menus')
                        ->label('common.menus')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-chevron-right')
                        ->route('admin.menus');
                }
            );
            $appearanceMenu->subMenu(
                'manage-widgets',
                function (MenuItems $menu) {
                    $menu->key('widgets')
                        ->label('widgets.widgets')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-chevron-right')
                        ->route('admin.widgets.index');
                }
            );
            Menu::make(
                'tools',
                function (MenuItems $menu) {
                    $menu->label('common.tools')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-grid-alt')
                        ->route('admin.tools');
                }
            );
            $toolsMenu = Menu::get('tools');
            $toolsMenu->subMenu(
                'manage-tools',
                function (MenuItems $menu) {
                    $menu->key('tools-index')
                        ->label('common.tools')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-chevron-right')
                        ->route('admin.tools');
                }
            );
            if (Route::has('admin.tools.home-page')) {
                $toolsMenu->subMenu(
                    'manage-homepage-tools',
                    function (MenuItems $menu) {
                        $menu->key('homepage-tools')
                            ->label('common.toolsHomepage')
                            ->type(MenuItems::ADMIN)
                            ->icon('nav-icon lni lni-chevron-right')
                            ->route('admin.tools.home-page');
                    }
                );
            }
            $toolsMenu->subMenu(
                'manage-categories',
                function (MenuItems $menu) {
                    $menu->key('categories-index')
                        ->label('admin.categories')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-chevron-right')
                        ->route('admin.categories', ['type'  => "tool"]);
                }
            );


            Menu::make(
                'plans',
                function (MenuItems $menu) {
                    $menu->label('admin.plans')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-invest-monitor')
                        ->route('admin.tools');
                }
            );
            $toolsMenu = Menu::get('plans');
            $toolsMenu->subMenu(
                'manage-plans',
                function (MenuItems $menu) {
                    $menu->key('plans-index')
                        ->label('admin.plans')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-chevron-right')
                        ->route('admin.plans');
                }
            );
            $toolsMenu->subMenu(
                'manage-plans-create',
                function (MenuItems $menu) {
                    $menu->key('plans-create')
                        ->label('admin.createPlan')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-chevron-right')
                        ->route('admin.plans.create');
                }
            );
            $toolsMenu->subMenu(
                'manage-faqs',
                function (MenuItems $menu) {
                    $menu->key('manage-faqs')
                        ->label('admin.faqs')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-chevron-right')
                        ->route('admin.faqs.index');
                }
            );
            $toolsMenu->subMenu(
                'manage-plans-transactions',
                function (MenuItems $menu) {
                    $menu->key('transactions-list')
                        ->label('admin.transactionList')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-chevron-right')
                        ->route('admin.transactions.list');
                }
            );

            if (setting('bank_transfer_allow') == 1 && Route::has('admin.transactions.bankTransfer')) {
                Menu::make(
                    'banktransfer',
                    function (MenuItems $menu) {
                        $menu->label('admin.bankTransfers')
                            ->type(MenuItems::ADMIN)
                            ->icon('nav-icon lni lni-revenue')
                            ->route('admin.transactions.bankTransfer');
                    }
                );
            }
            ////ads
            Menu::make(
                'advertisements',
                function (MenuItems $menu) {
                    $menu->label('admin.advertisements')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-ticket-alt')
                        ->route('admin.advertisements');
                }
            );
            $advertisementsMenu = Menu::get('advertisements');
            $advertisementsMenu->subMenu(
                'manage-advertisements',
                function (MenuItems $menu) {
                    $menu->key('advertisements-index')
                        ->label('admin.manageAdvertisements')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-chevron-right')
                        ->route('admin.advertisements');
                }
            );
            $advertisementsMenu->subMenu(
                'create-advertisements',
                function (MenuItems $menu) {
                    $menu->key('advertisements-create')
                        ->label('admin.createAdvertisements')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-chevron-right')
                        ->route('admin.advertisements.create', ['type'  => "1"]);
                }
            );
            Menu::make(
                'settings',
                function (MenuItems $menu) {
                    $menu->label('admin.settings')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-cog')
                        ->route('admin.settings');
                }
            );
            Menu::make(
                'patches',
                function (MenuItems $menu) {
                    $version = setting('version');
                    $applied = setting("patches-" . Str::slug($version) . "-applied", '{}');
                    $applied = json_decode($applied, true);
                    $available = count(Cache::get('patches-available', []) ?? []);
                    $available = $available - count($applied) < 0 ? 0 : $available - count($applied);

                    $menu->label('admin.patches')
                        ->badge($available)
                        ->badgeClass('danger')
                        ->type(MenuItems::ADMIN)
                        ->icon('nav-icon lni lni-bricks')
                        ->route('system.patches');
                }
            );
            // Menu::make(
            //     'check-updates',
            //     function (MenuItems $menu) {
            //         $menu->label('admin.checkUpdate')
            //         ->type(MenuItems::ADMIN)
            //             ->icon('nav-icon lni lni-cloud-download')
            //             ->route('update.checkUpdates');
            //     }
            // );
        }
    }
}
