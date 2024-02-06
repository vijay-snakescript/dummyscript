<?php

use App\Helpers\Classes\ArtisanApi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PlansController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\ToolsController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\UpdateController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SitemapController;
use App\Http\Controllers\Admin\WidgetsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\AdvertisementsController;

require __DIR__ . '/admin_auth.php';
Route::group(
    ['middleware' => ['AdminTheme', 'auth', 'verified']],
    function () {
        Route::post('admin/register-item', [ArtisanApi::class, 'register'])->name('system.register-item')->can('application operations');
    }
);

Route::group(
    ['prefix' => env('APP_ADMIN_PREFIX', 'admin'),  'middleware' => ['AdminTheme', 'auth', 'verified' , '2fa']],
    function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.home')->can('manage dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard')->can('manage dashboard');
        Route::get('pages', [PageController::class, 'index'])->name('admin.pages')->can('manage page');
        Route::get('pages/create', [PageController::class, 'create'])->name('admin.pages.create')->can('create page');
        Route::post('pages/create', [PageController::class, 'store'])->name('admin.pages.store')->can('create page');
        Route::get('pages/{page}/edit', [PageController::class, 'edit'])->name('admin.pages.edit')->can('edit page');
        Route::post('pages/{page}/edit', [PageController::class, 'update'])->name('admin.pages.update')->can('edit page');
        Route::delete('pages/{page}', [PageController::class, 'destroy'])->name('admin.pages.destroy')->can('delete page');

        Route::get('posts', [PostsController::class, 'index'])->name('admin.posts')->can('manage post');
        Route::get('posts/create', [PostsController::class, 'create'])->name('admin.posts.create')->can('create post');
        Route::post('posts/create', [PostsController::class, 'store'])->name('admin.posts.store')->can('create post');
        Route::get('posts/{post}/edit', [PostsController::class, 'edit'])->name('admin.posts.edit')->can('edit post');
        Route::post('posts/{post}/edit', [PostsController::class, 'update'])->name('admin.posts.update')->can('edit post');
        Route::delete('posts/{post}', [PostsController::class, 'destroy'])->name('admin.posts.destroy')->can('delete post');
        Route::get('posts/{post}/{id}', [PostsController::class, 'featured'])->name('admin.posts.featured')->can('manage post');

        Route::get('tags', [TagsController::class, 'index'])->name('admin.tags')->can('view tag');
        Route::post('tags/create', [TagsController::class, 'store'])->name('admin.tags.store')->can('create tag');
        Route::get('tags/{tag}/edit', [TagsController::class, 'edit'])->name('admin.tags.edit')->can('edit tag');
        Route::post('tags/{tag}/edit', [TagsController::class, 'update'])->name('admin.tags.update')->can('edit tag');
        Route::delete('tags/{tag}', [TagsController::class, 'destroy'])->name('admin.tags.destroy')->can('delete tag');

        Route::get('categories/{type?}', [CategoryController::class, 'index'])->name('admin.categories')->can('view category');
        Route::post('categories/create', [CategoryController::class, 'store'])->name('admin.categories.store')->can('create category');
        Route::post('categories/{category}/edit', [CategoryController::class, 'update'])->name('admin.categories.update')->can('edit category');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy')->can('delete category');
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit')->can('edit category');
        Route::put('categories/sort', [CategoryController::class, 'sort'])->name('admin.categories.sort')->can('edit category');

        Route::get('roles/{role?}', [RolesController::class, 'index'])->name('admin.roles')->can('manage roles');
        Route::post('roles/create', [RolesController::class, 'store'])->name('admin.roles.store')->can('create roles');
        Route::post('roles/{role}/update', [RolesController::class, 'update'])->name('admin.roles.update')->can('edit roles');
        Route::post('roles/edit', [RolesController::class, 'edit'])->name('admin.roles.edit')->can('edit roles');
        Route::delete('roles/{role}', [RolesController::class, 'destroy'])->name('admin.roles.destroy')->can('delete roles');
        Route::post('roles/unassign/action', [RolesController::class, 'roleAction'])->name('admin.role.action')->can('manage roles');
        Route::post('roles/users/get/{role}', [RolesController::class, 'getUsers'])->name('admin.role.getUsers')->can('manage roles');

        Route::get('users', [UserController::class, 'index'])->name('admin.users')->can('manage users');
        Route::get('users/trashed', [UserController::class, 'trashed'])->name('admin.users.trashed')->can('manage users');
        Route::post('users/create', [UserController::class, 'store'])->name('admin.users.store')->can('create users');
        Route::post('users/update', [UserController::class, 'update'])->name('admin.users.update')->can('edit users');
        Route::post('users/edit', [UserController::class, 'edit'])->name('admin.users.edit')->can('edit users');
        Route::get('users/status/change/{id}/{status}', [UserController::class, 'statusChange'])->name('admin.users.status.change')->can('manage users');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy')->can('delete users');
        Route::put('users/{user}', [UserController::class, 'restore'])->name('admin.users.restore')->can('edit users');
        Route::delete('users/{user}/delete', [UserController::class, 'delete'])->name('admin.users.delete')->can('delete users');

        Route::get('permissions/{user?}', [PermissionsController::class, 'index'])->name('admin.permissions')->can('manage permissions');
        Route::post('permissions/create', [PermissionsController::class, 'store'])->name('admin.permissions.store')->can('manage permissions');
        Route::post('permissions/{user}/edit', [PermissionsController::class, 'update'])->name('admin.permissions.update')->can('manage permissions');
        Route::get('permissions/{user}/edit', [PermissionsController::class, 'update'])->name('admin.permissions.edit')->can('manage permissions');

        // Menu Manager
        Route::get('/menus/{menu?}', [MenuController::class, 'index'])->name('admin.menus')->can('manage menus');
        Route::post('/menu/create', [MenuController::class, 'store'])->name('admin.menus.create')->can('create menus');
        Route::post('/menus/{menu}/add-items', [MenuController::class, 'addItems'])->name('admin.menus.add-items')->can('manage menus');
        Route::post('/menus/{menu}/update', [MenuController::class, 'update'])->name('admin.menus.update')->can('edit menus');
        Route::delete('/menu/{menu}/delete', [MenuController::class, 'destroy'])->name('admin.menus.destroy')->can('delete menus');
        Route::delete('/menu/{menu}/{item}/delete', [MenuController::class, 'destroyItem'])->name('admin.menus.item.destroy')->can('delete menus');

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings')->can('manage settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('admin.settings.update')->can('manage settings');

        //tools
        Route::get('/tools', [ToolsController::class, 'index'])->name('admin.tools')->can('manage tools');
        Route::get('/tools/home-page', [ToolsController::class, 'homePage'])->name('admin.tools.home-page')->can('manage tools');
        Route::get('tools/{tool}/edit', [ToolsController::class, 'edit'])->name('admin.tools.edit')->can('edit tools');
        Route::post('tools/{tool}/edit', [ToolsController::class, 'update'])->name('admin.tools.update')->can('edit tools');
        Route::get('tools/status/change/{id}/{status}', [ToolsController::class, 'statusChange'])->name('admin.tools.status.change')->can('manage tools');

        //profile
        Route::get('user/profile', [ProfileController::class, 'index'])->name('admin.profile')->can('manage profile');
        Route::get('user/password', [ProfileController::class, 'password'])->name('admin.password')->can('manage profile');
        Route::post('profile/update', [ProfileController::class, 'update'])->name('admin.profile.update')->can('manage profile');
        Route::get('profile/2fa', [ProfileController::class, 'twofactorauth'])->name('admin.mfa')->can('manage profile');
        Route::post('profile/twofactor/update', [ProfileController::class, 'twofactorUpdate'])->name('admin.twofactor.update')->can('manage profile');
        Route::get('profile/twofactor/disable', [ProfileController::class, 'twofactorDisable'])->name('admin.twofactor.disable')->can('manage profile');
        Route::post('admin-authenticate', [ProfileController::class, 'authenticate'])->name('admin.authenticate');
        //plans
        Route::get('/plans', [PlansController::class, 'index'])->name('admin.plans')->can('manage plans');
        Route::get('/plans/create', [PlansController::class, 'create'])->name('admin.plans.create')->can('create plans');
        Route::post('/plans/store', [PlansController::class, 'store'])->name('admin.plans.store')->can('create plans');
        Route::get('plans/{plan}/edit', [PlansController::class, 'edit'])->name('admin.plans.edit')->can('edit plans');
        Route::post('plans/{plan}/update', [PlansController::class, 'update'])->name('admin.plans.update')->can('edit plans');
        Route::delete('plans/{plan}', [PlansController::class, 'destroy'])->name('admin.plans.destroy')->can('delete plans');
        Route::get('plans/status/change/{id}/{status}', [PlansController::class, 'statusChange'])->name('admin.plans.status.change')->can('manage plans');
        Route::get('/newplan', [PlansController::class, 'createPlanSusbcription'])->name('admin.createPlanSusbcription')->can('manage plans');
        Route::get('plans/transactions/bank-transfer', [PlansController::class, 'bankTransfer'])->name('admin.transactions.bankTransfer')->can('manage transactions');
        Route::get('banktransfer/status/change/{id}/{status}', [PlansController::class, 'banktransferStatusChange'])->name('admin.banktransfer.status.change')->can('manage plans');

        //adds
        Route::get('/advertisements', [AdvertisementsController::class, 'index'])->name('admin.advertisements')->can('manage advertisements');
        Route::get('/advertisements/create/{type}', [AdvertisementsController::class, 'create'])->name('admin.advertisements.create')->can('create advertisements');
        Route::post('/advertisements/store', [AdvertisementsController::class, 'store'])->name('admin.advertisements.store')->can('create advertisements');
        Route::get('advertisements/{advertisement}/edit', [AdvertisementsController::class, 'edit'])->name('admin.advertisements.edit')->can('edit advertisements');
        Route::post('advertisements/{advertisement}/update', [AdvertisementsController::class, 'update'])->name('admin.advertisements.update')->can('edit advertisements');
        Route::get('advertisements/status/change/{id}/{status}', [AdvertisementsController::class, 'statusChange'])->name('admin.advertisements.status.change')->can('manage advertisements');
        Route::delete('advertisements/{advertisement}', [AdvertisementsController::class, 'destroy'])->name('admin.advertisements.destroy')->can('delete advertisements');

        // faqs admin
        Route::get('faq', [FaqController::class, 'index'])->name('admin.faqs.index')->can('manage faqs');
        Route::get('faq/create', [FaqController::class, 'create'])->name('admin.faqs.create')->can('create faqs');
        Route::post('faq', [FaqController::class, 'store'])->name('admin.faqs.store')->can('create faqs');
        Route::get('faq/change-status/{faq}/{status}', [FaqController::class, 'changeStatus'])->name('admin.faqs.changeStatus')->can('edit faqs');
        Route::get('faq/edit/{faq}', [FaqController::class, 'edit'])->name('admin.faqs.edit')->can('edit faqs');
        Route::post('faq/{faq}', [FaqController::class, 'update'])->name('admin.faqs.update')->can('edit faqs');
        Route::delete('faq/destroy/{faq}', [FaqController::class, 'destroy'])->name('admin.faqs.destroy')->can('delete faqs');

        //Widgets Routes
        Route::resource('/widgets', WidgetsController::class, ['as' => 'admin', 'only' => ['index', 'store', 'update', 'destroy']]);
        Route::post('/widgets/sort', [WidgetsController::class, 'sort'])->name('admin.widgets.sort')->can('manage widgets');
        Route::get('plans/transactions', [PlansController::class, 'transactions'])->name('admin.transactions.list')->can('manage transactions');

        // Update routes
        Route::get('/check-updates', [UpdateController::class, 'checkUpdates'])->name('update.checkUpdates')->can('manage updates');
        Route::get('/verify-updates', [UpdateController::class, 'verifyUpdates'])->name('update.verifyUpdates')->can('manage updates');

        // System tools
        Route::get('/rebuild', [SystemController::class, 'rebuild'])->name('system.rebuild')->can('application operations');
        Route::get('/optimize', [SystemController::class, 'optimize'])->name('system.optimize')->can('application operations');
        Route::get('/clear-cache', [SystemController::class, 'cache'])->name('system.cache')->can('application operations');
        Route::get('/clear-view-cache', [SystemController::class, 'view'])->name('system.view')->can('application operations');
        Route::get('/clear-route-cache', [SystemController::class, 'route'])->name('system.route')->can('application operations');
        Route::get('/sitemap', [SitemapController::class, 'generate'])->name('sitemap.generate')->can('application operations');
        Route::get('/update', [UpdateController::class, 'show'])->name('system.update')->can('application operations');
        Route::post('/update', [UpdateController::class, 'update'])->name('system.update.run')->can('application operations');

        Route::get('/patches', [UpdateController::class, 'patches'])->name('system.patches')->can('application operations');
        Route::post('/patches/{id}', [UpdateController::class, 'applyPatches'])->name('system.patches.apply')->can('application operations');
    }
);
