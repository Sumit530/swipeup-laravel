<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\HelpCenterController;
use App\Http\Controllers\VideosController;
use App\Http\Controllers\VideoLikesController;
use App\Http\Controllers\SongsController;
use App\Http\Controllers\BannerImagesController;
use App\Http\Controllers\VideoReportReqestController;
use App\Http\Controllers\VideoEffectsController;
use App\Http\Controllers\SingersController;
use App\Http\Controllers\InterestsController;
use App\Http\Controllers\VideoReportTypeController;
use App\Http\Controllers\AccountVerificationController;
use Illuminate\Http\Request;
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

// Route::get('/', function () {
//     return view('auth.login');
// });
 
// Route::get('/', [DashboardController::class, 'index']);

// Auth::routes();

// cache clear
Route::get('/cache', function () {
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('event:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('optimize');
    dd("Cachec id cleared");
});

// Route::get('/db', [LoginController::class, 'db']);
Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('admin.login');
    
    Route::post('/dologin', [LoginController::class, 'dologin'])->name('admin.dologin');
    Route::get('/logout', [LoginController::class, 'logout'])->name('admin.logouts');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/profile',[DashboardController::class, 'profile_details'])->name('admin.profile');
    Route::post('/profile',[DashboardController::class, 'profile_update'])->name('admin.profile-update');
    Route::get('/settings',[DashboardController::class, 'setting_details'])->name('admin.settings');
    Route::post('/setting',[DashboardController::class, 'setting_update'])->name('admin.setting-update');
    
    // users
    Route::resource('/users',UserController::class,['only' => ['index', 'create', 'store','show','edit','update']]);
    Route::post('/users/status',[UserController::class, 'status'])->name('admin.users-status'); 
    Route::post('/users/delete',[UserController::class, 'delete'])->name('admin.users-delete');
    Route::get('/users/notificationsetting/{id}',[UserController::class, 'notificationsetting'])->name('admin.users-notificationsetting');
    Route::get('/users/privacy/{id}',[UserController::class, 'privacy'])->name('admin.users-privacy');
    Route::get('/users/safety/{id}',[UserController::class, 'safety'])->name('admin.users-safety');

    // country
    Route::resource('/country',CountryController::class,['only' => ['index', 'create', 'store','edit','update']]);
    Route::post('/country/status',[CountryController::class, 'status'])->name('admin.country-status');
    Route::post('/country/delete',[CountryController::class, 'delete'])->name('admin.country-delete');

    // language
    Route::resource('/language', LanguageController::class,['only' => ['index', 'create', 'store','edit','update']]);
    Route::post('/language/status',[LanguageController::class, 'status'])->name('admin.language-status');
    Route::post('/language/delete',[LanguageController::class, 'delete'])->name('admin.language-delete');

    // category
    Route::resource('/category', CategoriesController::class,['only' => ['index', 'create', 'store','edit','update']]);
    Route::post('/category/status',[CategoriesController::class, 'status'])->name('admin.category-status');
    Route::post('/category/delete',[CategoriesController::class, 'delete'])->name('admin.category-delete');

    // helpcenter
    Route::resource('/helpcenter', HelpCenterController::class,['only' => ['index', 'create', 'store','edit','update']]);
    Route::post('/helpcenter/status',[HelpCenterController::class, 'status'])->name('admin.helpcenter-status');
    Route::post('/helpcenter/delete',[HelpCenterController::class, 'delete'])->name('admin.helpcenter-delete');

    // videos
    Route::resource('/videos', VideosController::class,['only' => ['index', 'create', 'store','show','edit','update']]);
    // Route::get('/videos/details/{id}', [VideosController::class, 'details'])->name('admin.videos-details');
    Route::post('/videos/status',[VideosController::class, 'status'])->name('admin.videos-status');
    Route::post('/videos/video_status',[VideosController::class, 'video_status'])->name('admin.videos-data-status');
    Route::post('/videos/delete',[VideosController::class, 'delete'])->name('admin.videos-delete');
    Route::post('/videos/video_delete',[VideosController::class, 'video_delete'])->name('admin.videos-data-delete');

    // video likes
    Route::resource('/videolikes', VideoLikesController::class,['only' => ['index', 'create', 'store','show','edit','update']]);
    Route::post('/videolikes/delete',[VideosController::class, 'delete'])->name('admin.videoslike-delete');

    // song
    Route::resource('/songs', SongsController::class,['only' => ['index', 'create', 'store','edit','update']]);
    Route::post('/songs/status',[SongsController::class, 'status'])->name('admin.song-status');
    Route::post('/songs/delete',[SongsController::class, 'delete'])->name('admin.song-delete');

    // Banner Image
    Route::resource('/bannerimages', BannerImagesController::class,['only' => ['index', 'create', 'store','edit','update']]);
    Route::post('/bannerimages/status',[BannerImagesController::class, 'status'])->name('admin.bannerimages-status');
    Route::post('/bannerimages/delete',[BannerImagesController::class, 'delete'])->name('admin.bannerimages-delete');

    // Video Report request
    Route::resource('/videoreportreqest', VideoReportReqestController::class,['only' => ['index']]);
    Route::post('/videoreportreqest/status',[VideoReportReqestController::class, 'status'])->name('admin.videoreportreqest-status');
    Route::post('/videoreportreqest/delete',[VideoReportReqestController::class, 'delete'])->name('admin.videoreportreqest-delete');

    // video Effects
    Route::resource('/effects', VideoEffectsController::class,['only' => ['index', 'create', 'store','edit','update']]);
    Route::post('/effects/status',[VideoEffectsController::class, 'status'])->name('admin.effects-status');
    Route::post('/effects/delete',[VideoEffectsController::class, 'delete'])->name('admin.effects-delete');

    // singer
    Route::resource('/singers', SingersController::class,['only' => ['index', 'create', 'store','edit','update']]);
    Route::post('/singer/status',[SingersController::class, 'status'])->name('admin.singer-status');
    Route::post('/singer/delete',[SingersController::class, 'delete'])->name('admin.singer-delete');

    // interests
    Route::resource('/interests', InterestsController::class,['only' => ['index', 'create', 'store','edit','update']]);
    Route::post('/interests/status',[InterestsController::class, 'status'])->name('admin.interests-status');
    Route::post('/interests/delete',[InterestsController::class, 'delete'])->name('admin.interests-delete');

    // Video Report Type
    Route::resource('/videoreporttype', VideoReportTypeController::class,['only' => ['index', 'create', 'store','edit','update']]);
    Route::post('/videoreporttype/status',[VideoReportTypeController::class, 'status'])->name('admin.videoreporttype-status');
    Route::post('/videoreporttype/delete',[VideoReportTypeController::class, 'delete'])->name('admin.videoreporttype-delete');

    // Account Verification
    Route::resource('/account_verifications', AccountVerificationController::class,['only' => ['index', 'create', 'store','edit','update']]);
    Route::post('/account_verifications/status',[AccountVerificationController::class, 'status'])->name('admin.account_verifications-status');
    Route::post('/account_verifications/delete',[AccountVerificationController::class, 'delete'])->name('admin.account_verifications-delete');
});




