<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\HelpCenterController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\VideosController;
use App\Http\Controllers\Api\SearchHistoryController;
use App\Http\Controllers\Api\VideoCommentLikesController;
use App\Http\Controllers\Api\VideoBookmarkController;
use App\Http\Controllers\Api\VideoFavoriteController;
use App\Http\Controllers\Api\VideoReportController;
use App\Http\Controllers\Api\VideoNotInterestedController;
use App\Http\Controllers\Api\VideoDuetsController;
use App\Http\Controllers\Api\VideoWatchHistoryController;
use App\Http\Controllers\Api\VideoCommentPinnedController;
use App\Http\Controllers\Api\SongsController;
use App\Http\Controllers\Api\VideoEffectsController;
use App\Http\Controllers\Api\SoundBookmarksController;
use App\Http\Controllers\Api\HashtagBookmarksController;
use App\Http\Controllers\Api\EffectBookmarksController;
use App\Http\Controllers\Api\BlockUsersController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\RecentEmojisController;
use App\Http\Controllers\Api\InterestsController;
use App\Http\Controllers\Api\RestrictAccountsController;
use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\Api\AccountVerificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

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


Route::get('/', function () {
    // return view('welcome');
    return response()->json(['msg'=>'Toke invalid.', 'status' =>'2']);
})->name('login');

Route::post('/registration', [UserController::class, 'registration']);
Route::post('/social_signup', [UserController::class, 'social_signup']);
Route::post('/login', [UserController::class, 'login']);
// Route::post('/verifyOtp', [UserController::class, 'verifyOtp']);
Route::post('/reset_password', [UserController::class, 'reset_password']);
Route::post('/check_otp', [UserController::class, 'check_otp']);
Route::post('/update_location', [UserController::class, 'update_location']);
Route::post('/update_dob', [UserController::class, 'update_dob']);
Route::post('/update_password', [UserController::class, 'update_password']);
Route::post('/user_details', [UserController::class, 'user_details']);
Route::post('/get_all_users', [UserController::class, 'get_all_users']);
Route::post('/getprofile', [UserController::class, 'getProfile']);
Route::get('/delete_all_users', [UserController::class, 'delete_all_users']);
Route::post('/resend_otp', [UserController::class, 'resend_otp']);
Route::post('/send_otp', [UserController::class, 'send_otp']);
Route::get('/get_firebase_users', [FirebaseController::class, 'get_users']);
//Categories
Route::get('/get_categories', [SongsController::class, 'get_categories']);


// delete all videos and data 
Route::get('/delete_all_videos', [VideosController::class, 'delete_all_videos']);



// Languages
Route::prefix('language')->group(function () {
    Route::get('/', [LanguageController::class, 'index']);
    // Route::post('store', [LanguageController::class, 'store']);
    // Route::get('edit/{id}', [LanguageController::class, 'edit']);
    // Route::post('update/{id}', [LanguageController::class, 'update']);
    // Route::post('delete/{id}', [LanguageController::class, 'destroy']);
});

// Setting
Route::get('/terms_of_use', [SettingController::class, 'terms_of_use']);
Route::get('/privacy_policy', [SettingController::class, 'privacy_policy']);
Route::get('/copyright_policy', [SettingController::class, 'copyright_policy']);


Route::group(['middleware' => 'auth:api'], function(){
    Route::post('/getmyqrcode', [QrCodeController::class, 'getmyqrcode']);
    Route::post('/update_privacy', [UserController::class, 'update_privacy']);
    Route::post('/update_safeties', [UserController::class, 'update_safeties']);
    Route::post('/get_notification_settings', [UserController::class, 'get_notification_settings']);
    Route::post('/update_notification_settings', [UserController::class, 'update_notification_settings']);
    Route::post('/update_language', [UserController::class, 'update_language']);
    Route::post('/following_list', [UserController::class, 'following_list']);
    Route::post('/follow_list', [UserController::class, 'follow_list']);
    Route::post('/get_my_accounts', [UserController::class, 'get_my_accounts']);

    // HelpCenter
	Route::get('/gethelp', [HelpCenterController::class, 'gethelp']);
	Route::post('/gethelpbyid', [HelpCenterController::class, 'gethelpbyid']);
    Route::post('/add_help_center_problem_resolved', [HelpCenterController::class, 'add_help_center_problem_resolved']);

    // Notification
    Route::post('/notification', [NotificationController::class, 'notification']);
    Route::post('/follower_notification_list', [NotificationController::class, 'follower_notification_list']);
    Route::post('/mentions_notification_list', [NotificationController::class, 'mentions_notification_list']);
    Route::post('/like_notification_list', [NotificationController::class, 'like_notification_list']);
    Route::post('/send_notification', [NotificationController::class, 'send_notification']);

    // videos
    Route::post('/upload_video', [VideosController::class, 'upload_video']);
    Route::post('/video_details', [VideosController::class, 'video_details']);
    Route::post('/delete_single_video', [VideosController::class, 'delete_single_video']);
    Route::post('/get_video_likes', [VideosController::class, 'get_video_likes']);
    Route::post('/add_video_comments', [VideosController::class, 'add_video_comments']);
    Route::post('/remove_video_comments', [VideosController::class, 'remove_video_comments']);
    Route::post('/get_video_comments', [VideosController::class, 'get_video_comments']);
    Route::post('/get_parent_video_comments', [VideosController::class, 'get_parent_video_comments']);
    Route::post('/position_video_list', [VideosController::class, 'position_video_list']);
    Route::post('/update_video_status', [VideosController::class, 'update_video_status']);
    Route::post('/private_position_video_list', [VideosController::class, 'private_position_video_list']);

    // commet like & remove like 
    Route::post('/add_comment_like', [VideoCommentLikesController::class, 'add_comment_like']);
    Route::post('/remove_comment_like', [VideoCommentLikesController::class, 'remove_comment_like']);

    // follow
    Route::post('/to_follow', [UserController::class, 'to_follow']);
    Route::post('/to_unfollow', [UserController::class, 'to_unfollow']);
    
    // report
    Route::post('/add_video_report', [VideoReportController::class, 'add_video_report']);
    Route::get('/get_video_report_types', [VideoReportController::class, 'get_video_report_types']);

    // video bookmarks
    Route::post('/get_video_bookmarks', [VideoBookmarkController::class, 'get_video_bookmarks']);
    Route::post('/add_video_bookmark', [VideoBookmarkController::class, 'add_video_bookmark']);
    Route::post('/remove_video_bookmark', [VideoBookmarkController::class, 'remove_video_bookmark']);

    //song bookmarks
    Route::post('/get_song_bookmarks', [SoundBookmarksController::class, 'get_song_bookmarks']);
    Route::post('/add_sound_bookmark', [SoundBookmarksController::class, 'add_sound_bookmark']);
    Route::post('/remove_song_bookmark', [SoundBookmarksController::class, 'remove_song_bookmark']);

    //Hashtag bookmarks
    Route::post('/get_hashtag_bookmarks', [HashtagBookmarksController::class, 'get_hashtag_bookmarks']);
    Route::post('/add_hashtag_bookmark', [HashtagBookmarksController::class, 'add_hashtag_bookmark']);
    Route::post('/remove_hashtag_bookmark', [HashtagBookmarksController::class, 'remove_hashtag_bookmark']);

    //Effect bookmarks
    Route::post('/get_effect_bookmarks', [EffectBookmarksController::class, 'get_effect_bookmarks']);
    Route::post('/remove_effect_bookmark', [EffectBookmarksController::class, 'remove_effect_bookmark']);
   
    //favorite
    Route::post('/get_video_favorites', [VideoFavoriteController::class, 'get_video_favorites']);
    Route::post('/add_video_favorite', [VideoFavoriteController::class, 'add_video_favorite']);
    Route::post('/remove_video_favorite', [VideoFavoriteController::class, 'remove_video_favorite']);
    
    //not interested
    Route::post('/get_video_not_interested', [VideoNotInterestedController::class, 'get_video_not_interested']);
    Route::post('/add_video_not_interested', [VideoNotInterestedController::class, 'add_video_not_interested']);
    Route::post('/remove_video_not_interested', [VideoNotInterestedController::class, 'remove_video_not_interested']);
    
    //not interested
    Route::post('/get_video_duets', [VideoDuetsController::class, 'get_video_duets']);
    Route::post('/add_video_duet', [VideoDuetsController::class, 'add_video_duet']);
    Route::post('/remove_video_duet', [VideoDuetsController::class, 'remove_video_duet']);

    // search
    Route::post('/add_search_history', [SearchHistoryController::class, 'add_search_history']);
    Route::post('/get_search_history', [SearchHistoryController::class, 'get_search_history']);
    Route::post('/search_top_list', [SearchHistoryController::class, 'search_top_list']);
    Route::post('/search_username_list', [SearchHistoryController::class, 'search_username_list']);
    Route::post('/search_hashtags_list', [SearchHistoryController::class, 'search_hashtags_list']);
    Route::post('/hashtags_to_videos', [SearchHistoryController::class, 'hashtags_to_videos']);

    //Video Watch History
    Route::post('/get_watch_video_history', [VideoWatchHistoryController::class, 'get_watch_video_history']);
    Route::post('/add_watch_video_history', [VideoWatchHistoryController::class, 'add_watch_video_history']);

    //Video Comment Pinned
    Route::post('/add_comment_pinned', [VideoCommentPinnedController::class, 'add_comment_pinned']);
    Route::post('/remove_comment_pinned', [VideoCommentPinnedController::class, 'remove_comment_pinned']);

    //Banner Image
    Route::get('/get_banner_image', [SongsController::class, 'get_banner_image']);
    Route::post('/add_banner_image', [SongsController::class, 'add_banner_image']);

    //Get Song list
    Route::post('/get_song', [SongsController::class, 'get_song']);
    Route::post('/get_song_new', [SongsController::class, 'get_song_new']);
    Route::post('/add_song', [SongsController::class, 'add_song']);
    Route::post('/get_song_to_video', [SongsController::class, 'get_song_to_video']);

    // Favortie Song
    Route::post('/add_favortie_song', [SongsController::class, 'add_favortie_song']);
    Route::post('/removed_favortie_song', [SongsController::class, 'removed_favortie_song']);
    Route::post('/get_favorties_song', [SongsController::class, 'get_favorties_song']);

    // Singer
    Route::get('/get_singers', [SongsController::class, 'get_singers']);

    // Effects Song
    Route::get('/get_effect', [VideoEffectsController::class, 'get_effect']);
    Route::post('/add_effect', [VideoEffectsController::class, 'add_effect']);

    //Block user
    Route::post('/get_block_user_list', [BlockUsersController::class, 'get_block_user_list']);
    Route::post('/add_block_user', [BlockUsersController::class, 'add_block_user']);
    Route::post('/remove_block_user', [BlockUsersController::class, 'remove_block_user']);


    // Analytics
    Route::post('/analytics_overview', [AnalyticsController::class, 'analytics_overview']);
    Route::post('/analytics_content', [AnalyticsController::class, 'analytics_content']);

    // recent emoji
    Route::post('/add_recent_emoji', [RecentEmojisController::class, 'add_recent_emoji']);
    Route::post('/get_recent_emoji', [RecentEmojisController::class, 'get_recent_emoji']);

    // recent emoji
    Route::get('/interests_list', [InterestsController::class, 'interests_list']);

    // Restrict Accounts
    Route::post('/add_restrict_accounts', [RestrictAccountsController::class, 'add_restrict_accounts']);
    
    // add account verification reqest send
    Route::post('/add_account_verification', [AccountVerificationController::class, 'add_account_verification']);
});

// video list foryou
Route::post('/video_list', [VideosController::class, 'video_list']);

// list country
Route::get('/country_list', [GeneralController::class, 'getcountries']);
// list account category list
Route::get('/distance', [GeneralController::class, 'distance']);