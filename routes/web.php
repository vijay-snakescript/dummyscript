<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';

/*Installation Routes*/
Route::group(
    ['prefix' => 'install',  'middleware' => ['FrontTheme', 'RedirectIfInstalled']],
    function () {
        Route::get('/pre-installation', [InstallController::class, 'preInstallation'])->name('preinstall');
        Route::get('/verify', [InstallController::class, 'verifyPurchase'])->name('verifypurchase');
        Route::get('/verify/redirect', [InstallController::class, 'redirectPurchase'])->name('verify.redirect');
        Route::get('/verify/return', [InstallController::class, 'returnPurchase'])->name('verify.return');
        Route::get('/verify/cancel', [InstallController::class, 'cancelPurchase'])->name('verify.cancel');
        Route::get('/configuration', [InstallController::class, 'getConfiguration'])->name('installconfig.get');
        Route::post('/configuration', [InstallController::class, 'postConfiguration'])->name('installconfig.post');
        Route::get('/complete', [InstallController::class, 'complete'])->name('installcomplete');
    }
);

Route::group(
    ['middleware' => ['FrontTheme', 'auth', '2fa', 'verified']],
    function () {
        Route::prefix('connector')->group(function () {
            Route::patch('/', [UploadController::class, 'chunk'])->name('uploader.chunk');
            Route::post('/process', [UploadController::class, 'upload'])->name('uploader.upload');
            Route::delete('/process', [UploadController::class, 'delete'])->name('uploader.delete');
        });

        //users profile
        Route::get('user/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::get('user/password', [UserController::class, 'password'])->name('user.password');
        Route::get('user/delete-user-account', [UserController::class, 'delete'])->name('user.deleteAccount');
        Route::get('user/plan', [UserController::class, 'plan'])->name('user.plan');
        Route::delete('user/delete-user-account', [UserController::class, 'destroy'])->name('user.deleteAccount.action');
        Route::get('user/twofactorauth', [UserController::class, 'twofactorauth'])->name('user.twofactor');
        Route::post('user/profile/update', [UserController::class, 'profileUpdate'])->name('user.profile.update');
        Route::post('user/password/update', [UserController::class, 'passwordUpdate'])->name('user.password.update');
        Route::post('user/twofactor/update', [UserController::class, 'twofactorUpdate'])->name('user.twofactor.update');
        Route::get('user/twofactor/disable', [UserController::class, 'twofactorDisable'])->name('user.twofactor.disable');
        Route::post('authenticate', [UserController::class, 'authenticate'])->name('user.authenticate');

        // payments
        Route::get('payments/checkout/{plan_id}/{type}', [PaymentsController::class, 'checkout'])->name('payments.checkout');
        Route::post('payments/process', [PaymentsController::class, 'process'])->name('payments.process');
        Route::get('payments/success/{transaction_id}', [PaymentsController::class, 'success'])->name('payments.success');
        Route::get('payments/cancel/{transaction_id}', [PaymentsController::class, 'cancel'])->name('payments.cancel');
        Route::get('payments/finish', [PaymentsController::class, 'finish'])->name('payments.finish');
        Route::post('gateway/view', [PaymentsController::class, 'getGatewayView'])->name('payments.gateway-view');
        Route::get('cancel-subscription', [UserController::class, 'cancleSubscription'])->name('plans.cancel.subscription');
        Route::get('payments/pending/{transaction_id}', [PaymentsController::class, 'pending'])->name('payments.pending');

        //transactions
        Route::get('user/transactions', [PaymentsController::class, 'transactions'])->name('payments.transactions');
        Route::get('transaction/invoice/{transaction}', [PaymentsController::class, 'invoice'])->name('transaction.invoice');
        Route::get('transaction/invoice/download/{transaction}', [PaymentsController::class, 'invoiceDownload'])->name('transaction.invoice.download');
    }
);

Route::group(
    ['middleware' => ['FrontTheme']],
    function () {
        Route::get('/search', SearchController::class)->name('search');
        Route::get('/re-activate/{token}', [UserController::class, 'userReActivate'])->name('user.reactivate');
        Route::get('lang/{locale}', [LangController::class, 'changeLocale'])->name('locale.change');

        Route::get('/tool/view/{slug}', [ToolController::class, 'details'])->name('tools.show.details');
        //contactus
        Route::get('/contact', [ContactController::class, 'show'])->name('contact');
        Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

        Route::get('/', [HomeController::class, 'home'])->name('front.index');
        Route::post('/', [HomeController::class, 'homeTool'])->name('front.index.action');
        Route::get('/tools', [HomeController::class, 'tools'])->name('front.tools');
        Route::get('page/{slug}', [PageController::class, 'show'])->name('pages.show');
        // Blog
        Route::get('post/{slug}', [BlogController::class, 'show'])->name('posts.show');
        Route::get('blog', [BlogController::class, 'index'])->name('blog.show');
        Route::get('blog/category/{category}', [BlogController::class, 'category'])->name('blog.category');
        Route::get('blog/tag/{tag}', [BlogController::class, 'tag'])->name('blog.tag');

        //plans
        Route::get('plans', [PlansController::class, 'plans'])->name('plans.list');
        Route::get('post/category/{category}', [CategoryController::class, 'posts'])->name('post.category');

        //social
        Route::get('social/{provider}', [SocialController::class, 'redirect'])->name('social.login.redirect');
        Route::get('social/{provider}/callback', [SocialController::class, 'Callback'])->name('social.login.callback');

        // tools
        Route::get('{tool}', [ToolController::class, 'index'])->name('tool.show');
        Route::post('{tool}', [ToolController::class, 'handle'])->name('tool.handle');
        Route::get('{tool}/{process_id}/{action}', [ToolController::class, 'action'])->name('tool.action');
        Route::post('{tool}/{action}', [ToolController::class, 'postAction'])->name('tool.postAction');
        Route::post('tool/favourite/action/update', [ToolController::class, 'favouriteAction'])->name('tool.favouriteAction');
        Route::get('category/{category}', [CategoryController::class, 'show'])->name('tool.category');

        // advertisements
        Route::get('ads/remove', [AdsController::class, 'remove'])->name('ads.remove');

        //webhok
        Route::get('payments/paystack/callback', [PaymentsController::class, 'paystackcallback'])->name('payments.paystackcallback');
        Route::get('payments/webhook/{gateway}', [PaymentsController::class, 'webhookListener'])->name('payments.webhook-listener');

    }
);

Route::get('test/pdf', [TestController::class, 'test']);
