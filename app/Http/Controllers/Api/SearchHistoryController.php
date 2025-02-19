<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\SearchHistory;
use App\Models\User;
use App\Models\Followers;
use App\Models\Videos;
use App\Models\VideoData;
use App\Models\VideoLikes;
use App\Models\VideoComments;
use App\Models\VideoWatchHistory;
use App\Models\VideoBookmark;
use App\Models\HashtagBookmarks;
use App\Models\Hashtag;
use App\Models\HashtagData;
use App\Models\VideoFavorite;
use App\Models\Songs;
use Illuminate\Http\Request;
use Validator;
use Storage;
use File;
use DB;

class SearchHistoryController extends Controller
{
    //api for add search history
    function add_search_history(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'keyword'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $search_data = SearchHistory::where(['user_id' => $request->user_id,'keyword' => $request->keyword])->first();
        if ($search_data != '') 
        {
            $data_update['user_id'] = $request->user_id;
            $data_update['keyword'] = $request->keyword;
            SearchHistory::where('id',$search_data->id)->update($data_update);
            return response()->json(['data' => [],'msg'=>'Search history update successfully.', 'status' =>'1']);
        }
        else
        {
            $searchhistory                  = new SearchHistory();
            $searchhistory->user_id         = $request->user_id;
            $searchhistory->keyword         = $request->keyword;
            $searchhistory->save(); 
            return response()->json(['data' => [],'msg'=>'New search history add successfully.', 'status' =>'1']);
        }
    }

    // api for user search history
    function get_search_history(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $search_data = SearchHistory::where('user_id',$request->user_id)->get();
        if(count($search_data) > 0) {
            foreach ($search_data as $row) {
                $record[] = array(
                    "id"                    => $row->id,
                    "keyword"               => $row->keyword?$row->keyword:'',
                );
                $result = $record;
            }
            return response()->json(['data' => $result,'msg'=>'Search history list get successfully.', 'status' =>'1']);
        }
        else
        {
            return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
        }
    }

    //api for delete search history
    function delete_search_history(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id'     => 'required',
            'search_id'   => 'required',
            'type'        => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        if ($request->type == 1) { // 1 = Delete all search history
            SearchHistory::where('user_id',$request->user_id)->delete();
            return response()->json(['data' => [],'msg'=>'Search history clear successfully.', 'status' =>'1']);
        }
        else // remove only one search history
        {
            SearchHistory::where('id',$request->search_id)->delete();
            return response()->json(['data' => [],'msg'=>'Search history remove successfully.', 'status' =>'1']);
        }
    }


    // api for search top match
    function search_top_list(Request $request)
    {
        $hashtags_result = array();
        $video_result = array();
        $user_result = array();
        $song_result = array();

        $validator = Validator::make($request->all(), [ 
            'keyword'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        //hashtags
        // echo $request->keyword;exit;
        $search_hashtags_data = Hashtag::where('name','LIKE',"%{$request->keyword}%")->get();
        // echo "<pre>"; print_r($search_hashtags_data->toArray()); die();
        if(count($search_hashtags_data) > 0) {
            foreach ($search_hashtags_data as $row) {
                $total_videos = HashtagData::where("hashtag_id","=",$row->id)->count();
                $record_hashtags[] = array(
                    "id"        => $row->id,
                    "hashtag"   => $row->name,
                    "total_videos"   => $total_videos,
                );
                $hashtags_result = $record_hashtags;
            }
        }
        else
        {
            $hashtags_result = array();
        }

        // video
        $search_by_video_data = Videos::where('description','LIKE',"%{$request->keyword}%")->where('is_view',"=",1)->get();
        if(!empty($search_by_video_data)) {
            foreach ($search_by_video_data as $row) {

                $total_views = VideoWatchHistory::where('video_id',$row->id)->count();
                if ($row->cover_image != '') 
                {
                    $deldestinationPath =  Storage::disk('public')->path('uploads/videos/cover_images');
                    if(File::exists($deldestinationPath.'/'.$row->cover_image)) {
                        $cover_image = url('storage/app/public/uploads/videos/cover_images/'.$row->cover_image);
                    }
                    else
                    {
                        $cover_image = "";
                    }
                }
                else
                {
                    $cover_image = "";
                }
                // delete video
                if ($row->file_name != '') 
                {
                   $delddestinationPath =  Storage::disk('public')->path('uploads/videos/videos');
                    if(File::exists($delddestinationPath.'/'.$row->file_name)) {
                        $video_url = url('storage/app/public/uploads/videos/videos/'.$row->file_name);
                    }
                    else
                    {
                        $video_url = "";
                    }
                }
                else
                {
                    $video_url = "";
                }
                $record_videos[] = array(
                    "id"                    => $row->id,
                    "cover_image"           => $cover_image,
                    "video_url"             => $video_url,
                    "total_views"           => (int)$total_views,
                    "description"           => $row->description,
                );
                $video_result = $record_videos;
                
            }
        }
        else
        {
            $video_result = array();
        }

        // username
        $search_by_username_data = User::where('username','LIKE',"%{$request->keyword}%")->where('allow_find_me',"=",DB::raw("'1'"))->get();
        if(!empty($search_by_username_data)) {
            foreach ($search_by_username_data as $row) {
                $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
                if(!empty($row->profile_image)){
                    if(File::exists($destinationPath.'/'.$row->profile_image)) {
                        $file_path = url('storage/app/public/uploads/user/profile/'.$row->profile_image);
                    }
                    else
                    {
                        $file_path = "";
                    }
                }
                else
                {
                    $file_path = "";
                }
                $che_follow_data= Followers::where('follower_id',$row->id)->first();
                if ($che_follow_data != '') {
                    $is_follow = 1;
                }
                else{
                    $is_follow = 0;
                }
                $record_users[] = array(
                    "user_id"               => $row->id,
                    "name"                  => $row->name?$row->name:'',
                    "username"              => $row->username?$row->username:'',
                    "profile_image"         => $file_path,
                    "is_vip"                => $row->is_vip,
                    "is_follow"             => $is_follow,
                );
                $user_result = $record_users;
            }
        }
        else
        {
            $user_result = array();
        }

        // song
        $search_by_songs_data = Songs::where('name','LIKE',"%{$request->keyword}%")->orWhere('description','LIKE',"%{$request->keyword}%")->get();
        if(!empty($search_by_songs_data)) {
            foreach ($search_by_songs_data as $row) {
                // song file
                if ($row->attachment != '') 
                {
                    $destinationPath =  Storage::disk('public')->path('uploads/songs');
                    if(!empty($row->attachment)){
                        if(File::exists($destinationPath.'/'.$row->attachment)) {
                            $attachment = url('storage/app/public/uploads/songs/'.$row->attachment);
                        }
                        else
                        {
                            $attachment = "";
                        }
                    }
                    else
                    {
                        $attachment = "";
                    }
                }
                else
                {
                    $attachment = "";
                }

                // song banner image
                if ($row->banner_image != '') 
                {
                    $destinationPath =  Storage::disk('public')->path('uploads/song_banner_images');
                    if(!empty($row->banner_image)){
                        if(File::exists($destinationPath.'/'.$row->banner_image)) {
                            $banner_image = url('storage/app/public/uploads/song_banner_images/'.$row->banner_image);
                        }
                        else
                        {
                            $banner_image = "";
                        }
                    }
                    else
                    {
                        $banner_image = "";
                    }
                }
                else
                {
                    $banner_image = "";
                }
                $record_song[] = array(
                    "id"                    => $row->id,
                    "name"                  => $row->name,
                    "description"           => $row->description,
                    "duration"              => $row->duration,
                    "singer_id"             => $row->singer_id,
                    "banner_image"          => $banner_image,
                    "attachment"            => $attachment,
                );
                $song_result = $record_song;
            }
        }
        else
        {
            $user_result = array();
        }

        $record[] = array(
            "hashtag_data"          => $hashtags_result,
            "video_data"            => $video_result,
            "user_data"             => $user_result,
            "song_result"           => $song_result,
        );
        $result = $record;
        return response()->json(['data' => $result,'msg'=>'Top list get successfully.', 'status' =>'1']);
    }

    // api for search username
    function search_username_list(Request $request)
    {
        $result = array();

        $validator = Validator::make($request->all(), [ 
            'keyword'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $search_data = User::where('username','LIKE',"%{$request->keyword}%")->where('allow_find_me',"=",DB::raw("'1'"))->orderBy("username","DESC")->get();
        if(!empty($search_data)) {
            foreach ($search_data as $row) {
                $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
                if(!empty($row->profile_image)){
                    if(File::exists($destinationPath.'/'.$row->profile_image)) {
                        $file_path = url('storage/app/public/uploads/user/profile/'.$row->profile_image);
                    }
                    else
                    {
                        $file_path = "";
                    }
                }
                else
                {
                    $file_path = "";
                }
                $che_follow_data= Followers::where('follower_id',$row->id)->first();
                if ($che_follow_data != '') {
                    $is_follow = 1;
                }
                else{
                    $is_follow = 0;
                }
                $record[] = array(
                    "user_id"               => $row->id,
                    "name"                  => $row->name?$row->name:'',
                    "username"              => $row->username?$row->username:'',
                    "is_vip"                => $row->is_vip,
                    "is_follow"             => $is_follow,
                );
                $result = $record;
            }
            return response()->json(['data' => $result,'msg'=>'User list get successfully.', 'status' =>'1']);
        }
        else
        {
            return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
        }
    }

    // api for search video
    function search_video_list(Request $request)
    {
        $result = array();

        $validator = Validator::make($request->all(), [ 
            'keyword'   => 'required',
            'user_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        if ($request->video_id != '' && $request->video_id != 0) {
            // first video get data
            $video_details    = Videos::where('id',$request->video_id)->first();
            if ($video_details != '') 
            {
                if ($video_details->cover_image != '') 
                {
                    $deldestinationPath =  Storage::disk('public')->path('uploads/videos/cover_images');
                    if(File::exists($deldestinationPath.'/'.$video_details->cover_image)) {
                        $cover_image = url('storage/app/public/uploads/videos/cover_images/'.$video_details->cover_image);
                    }
                    else
                    {
                        $cover_image = "";
                    }
                }
                else
                {
                    $cover_image = "";
                }
                // delete video
                if ($video_details->file_name != '') 
                {
                   $delddestinationPath =  Storage::disk('public')->path('uploads/videos/videos');
                    if(File::exists($delddestinationPath.'/'.$video_details->file_name)) {
                        $video_url = url('storage/app/public/uploads/videos/videos/'.$video_details->file_name);
                    }
                    else
                    {
                        $video_url = "";
                    }
                }
                else
                {
                    $video_url = "";
                }

                $user_details    = User::where('id',$video_details->user_id)->first();
                if ($user_details != '') 
                {
                    $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
                    if(!empty($user_details->profile_image)){
                        if(File::exists($destinationPath.'/'.$user_details->profile_image)) {
                            $profile_image = url('storage/app/public/uploads/user/profile/'.$user_details->profile_image);
                        }
                        else
                        {
                            $profile_image = "";
                        }
                    }
                    else
                    {
                        $profile_image = "";
                    }

                    $username = $user_details->name;
                    $user_username = $user_details->username;
                }
                else
                {
                    $username = "";
                    $user_username = "";
                    $profile_image = "";
                }
                
                // total likes
                $total_likes = VideoLikes::where('video_id',$video_details->id)->count();
                // total comments
                $total_comments = VideoComments::where('video_id',$video_details->id)->where('parent_id',0)->count();

                // video like or not
                $user_like_data= VideoLikes::where(['user_id' => $request->user_id,'video_id' => $video_details->id])->first();
                if (!empty($user_like_data)) 
                {
                    $is_video_like = 1;
                }
                else
                {
                    $is_video_like = 0;
                }

                $total_viewsw = VideoWatchHistory::where('video_id',$request->video_id)->count();

                $user_bookmark_data = VideoBookmark::where(['user_id' => $request->user_id,'video_id' => $video_details->id])->first();
                if (!empty($user_bookmark_data)) 
                {
                    $is_bookmark = 1;
                }
                else
                {
                    $is_bookmark = 0;
                }
                // user is favorites or not check 
                $video_favorites_data = VideoFavorite::where(['user_id' => $request->user_id,'video_id' => $video_details->id])->count();
                if ($video_favorites_data > 0) 
                {
                    $is_favorite = 1;
                }
                else
                {
                    $is_favorite = 0;
                }
                $record_video_files[] = array(
                    "video_id"              => $video_details->id,
                    "user_id"               => $video_details->user_id,
                    "name"                  => $username,
                    "is_allow_comments"     => $video_details->is_allow_comments,
                    "is_allow_duet"         => $video_details->is_allow_duet,
                    "is_video_li"           => $total_likes,
                    "total_comments"        => $total_comments,
                    "cover_image"           => $cover_image,
                    "video_url"             => $video_url,
                    "is_favorite"           => $is_favorite,
                );
                $single_result = $record_video_files;
            }else{
                $single_result = array();
            }
        }
        else
        {
            $single_result = array();
        }
        if ($request->video_id != '' && $request->video_id != 0) {
            $search_data = Videos::where('description','LIKE',"%{$request->keyword}%")->where("id","!=",$request->video_id)->where('is_view',"=",1)->get();
        }else{
            $search_data = Videos::where('description','LIKE',"%{$request->keyword}%")->where('is_view',"=",1)->get();
        }
        // echo "<pre>"; print_r($search_data); die();
        if(count($search_data)) {
            foreach ($search_data as $row) {

                $total_views = VideoWatchHistory::where('video_id',$row->id)->count();
                if ($row->cover_image != '') 
                {
                    $deldestinationPath =  Storage::disk('public')->path('uploads/videos/cover_images');
                    if(File::exists($deldestinationPath.'/'.$row->cover_image)) {
                        $cover_image = url('storage/app/public/uploads/videos/cover_images/'.$row->cover_image);
                    }
                    else
                    {
                        $cover_image = "";
                    }
                }
                else
                {
                    $cover_image = "";
                }
                // delete video
                if ($row->file_name != '') 
                {
                   $delddestinationPath =  Storage::disk('public')->path('uploads/videos/videos');
                    if(File::exists($delddestinationPath.'/'.$row->file_name)) {
                        $video_url = url('storage/app/public/uploads/videos/videos/'.$row->file_name);
                    }
                    else
                    {
                        $video_url = "";
                    }
                }
                else
                {
                    $video_url = "";
                }

                $user_details    = User::where('id',$row->user_id)->first();
                if ($user_details != '') 
                {
                    $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
                    if(!empty($user_details->profile_image)){
                        if(File::exists($destinationPath.'/'.$user_details->profile_image)) {
                            $profile_image = url('storage/app/public/uploads/user/profile/'.$user_details->profile_image);
                        }
                        else
                        {
                            $profile_image = "";
                        }
                    }
                    else
                    {
                        $profile_image = "";
                    }

                    $username = $user_details->name;
                    $user_username = $user_details->username;
                }
                else
                {
                    $username = "";
                    $user_username = "";
                    $profile_image = "";
                }
                
                // total likes
                $total_likes = VideoLikes::where('video_id',$row->id)->count();
                // total comments
                $total_comments = VideoComments::where('video_id',$row->id)->where('parent_id',0)->count();

                // video like or not
                $user_like_data= VideoLikes::where(['user_id' => $request->user_id,'video_id' => $row->id])->first();
                if (!empty($user_like_data)) 
                {
                    $is_video_like = 1;
                }
                else
                {
                    $is_video_like = 0;
                }
                
                $user_bookmark_data = VideoBookmark::where(['user_id' => $request->user_id,'video_id' => $row->id])->first();
                if (!empty($user_bookmark_data)) 
                {
                    $is_bookmark = 1;
                }
                else
                {
                    $is_bookmark = 0;
                }
                // user is favorites or not check 
                $video_favorites_data = VideoFavorite::where(['user_id' => $request->user_id,'video_id' => $row->id])->count();
                if ($video_favorites_data > 0) 
                {
                    $is_favorite = 1;
                }
                else
                {
                    $is_favorite = 0;
                }
                $record[] = array(

                    "video_id"              => $row->id,
                    "user_id"               => $row->user_id,
                    "name"                  => $username,
                    "username"              => $user_username,
                    "profile_image"         => $profile_image,
                    "is_allow_comments"     => $row->is_allow_comments,
                    "cover_image"           => $cover_image,
                    "total_views"           => (int)$total_views,
                    "is_bookmark"           => $is_bookmark,
                    "is_favorite"           => $is_favorite,
                );
                $result = $record;
                
            }

            $main_array = array_merge($single_result,$result);

            if ($main_array != '') {
                return response()->json(['data' => $main_array,'msg'=>'Video list get successfully.', 'status' =>'1']);
            }
            else
            {
                return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
            }
        }
        else
        {
            return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
        }
    }

    // api for search hashtags
    function search_hashtags_list(Request $request)
    {
        $result = array();

        $validator = Validator::make($request->all(), [ 
            'keyword'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        // echo $request->keyword;exit;
        $hashtag_data = Hashtag::where('name','LIKE',"%{$request->keyword}%")->get();
        // echo "<pre>"; print_r($hashtag_data->toArray()); die();
        if(count($hashtag_data) > 0) {
            foreach ($hashtag_data as $row) {
                $total_videos = HashtagData::where("hashtag_id","=",$row->id)->count();
                $record[] = array(
                    "id"        => $row->id,
                    "hashtag"   => $row->name,
                    "total_videos"   => $total_videos,
                );
                $result = $record;
            }
            return response()->json(['data' => $result,'msg'=>'Hashtags list get successfully.', 'status' =>'1']);
        }
        else
        {
            return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
        }
    }

    // api for search video
    function hashtags_to_videos(Request $request)
    {
        $result = array();
        $single_result = array();

        $validator = Validator::make($request->all(), [ 
            'hashtag_id'   => 'required',
            'user_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $hashtag_data = Hashtag::where('id',$request->hashtag_id)->first();
        $hashtag_bookmark_data = HashtagBookmarks::where(['user_id' => $request->user_id,'hashtag_id' => $request->hashtag_id])->first();
        $is_hashtag_bookmark = 0;
        if ($hashtag_bookmark_data != '') {
            $is_hashtag_bookmark = 1;
        }
        if ($hashtag_data != '') {
            $total_videos = HashtagData::where("hashtag_id","=",$hashtag_data->id)->count();
            if ($request->video_id != '' && $request->video_id != 0) {
                $search_data = HashtagData::leftJoin("videos","videos.id","=","hashtag_data.video_id")
                            ->select("hashtag_data.*","videos.*","videos.id as video_id")
                            ->where('hashtag_data.hashtag_id',"=",$request->hashtag_id)
                            ->where('videos.id',"!=",$request->video_id)
                            ->orderBy('hashtag_data.id','DESC')
                            ->get();
            }else{
                $search_data = HashtagData::leftJoin("videos","videos.id","=","hashtag_data.video_id")
                            ->select("hashtag_data.*","videos.*","videos.id as video_id")
                            ->where('hashtag_data.hashtag_id',"=",$request->hashtag_id)
                            ->orderBy('hashtag_data.id','DESC')
                            ->get();
            }
            // echo "<pre>"; print_r($search_data->toArray()); die();
            if(count($search_data)) {
                foreach ($search_data as $row) {

                    $total_views = VideoWatchHistory::where('video_id',$row->video_id)->count();
                    if ($row->cover_image != '') 
                    {
                        $deldestinationPath =  Storage::disk('public')->path('uploads/videos/cover_images');
                        if(File::exists($deldestinationPath.'/'.$row->cover_image)) {
                            $cover_image = url('storage/app/public/uploads/videos/cover_images/'.$row->cover_image);
                        }
                        else
                        {
                            $cover_image = "";
                        }
                    }
                    else
                    {
                        $cover_image = "";
                    }
                    // delete video
                    if ($row->file_name != '') 
                    {
                       $delddestinationPath =  Storage::disk('public')->path('uploads/videos/videos');
                        if(File::exists($delddestinationPath.'/'.$row->file_name)) {
                            $video_url = url('storage/app/public/uploads/videos/videos/'.$row->file_name);
                        }
                        else
                        {
                            $video_url = "";
                        }
                    }
                    else
                    {
                        $video_url = "";
                    }

                    $user_details    = User::where('id',$row->user_id)->first();
                    if ($user_details != '') 
                    {
                        $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
                        if(!empty($user_details->profile_image)){
                            if(File::exists($destinationPath.'/'.$user_details->profile_image)) {
                                $profile_image = url('storage/app/public/uploads/user/profile/'.$user_details->profile_image);
                            }
                            else
                            {
                                $profile_image = "";
                            }
                        }
                        else
                        {
                            $profile_image = "";
                        }

                        $username = $user_details->name;
                        $user_username = $user_details->username;
                    }
                    else
                    {
                        $username = "";
                        $user_username = "";
                        $profile_image = "";
                    }
                    
                    // total likes
                    $total_likes = VideoLikes::where('video_id',$row->video_id)->count();
                    // total comments
                    $total_comments = VideoComments::where('video_id',$row->video_id)->where('parent_id',0)->count();

                    // video like or not
                    $user_like_data= VideoLikes::where(['user_id' => $request->user_id,'video_id' => $row->video_id])->first();
                    if (!empty($user_like_data)) 
                    {
                        $is_video_like = 1;
                    }
                    else
                    {
                        $is_video_like = 0;
                    }
                    
                    $user_bookmark_data = VideoBookmark::where(['user_id' => $request->user_id,'video_id' => $row->video_id])->first();
                    if (!empty($user_bookmark_data)) 
                    {
                        $is_video_bookmark = 1;
                    }
                    else
                    {
                        $is_video_bookmark = 0;
                    }
                    // user is favorites or not check 
                    $video_favorites_data = VideoFavorite::where(['user_id' => $request->user_id,'video_id' => $row->video_id])->count();
                    if ($video_favorites_data > 0) 
                    {
                        $is_favorite = 1;
                    }
                    else
                    {
                        $is_favorite = 0;
                    }
                    $record[] = array(
                        "video_id"              => $row->video_id,
                        "user_id"               => $row->user_id,
                        "name"                  => $username,
                        "username"              => $user_username,
                        "is_allow_comments"     => $row->is_allow_comments,
                        "is_allow_duet"         => $row->is_allow_duet,
                        "total_likes"           => $total_likes,
                        "cover_image"           => $cover_image,
                        "video_url"             => $video_url,
                        "is_bookmark"           => $is_video_bookmark,
                        "is_favorite"           => $is_favorite,
                    );
                    $result = $record;
                    
                }
            }

            if ($request->video_id != '' && $request->video_id != 0) {
                // first video get data
                $video_details    = Videos::where('id',$request->video_id)->first();
                $user_details    = User::where('id',$video_details->user_id)->first();
                if ($user_details != '') 
                {
                    $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
                    if(!empty($user_details->profile_image)){
                        if(File::exists($destinationPath.'/'.$user_details->profile_image)) {
                            $profile_image = url('storage/app/public/uploads/user/profile/'.$user_details->profile_image);
                        }
                        else
                        {
                            $profile_image = "";
                        }
                    }
                    else
                    {
                        $profile_image = "";
                    }

                    $username = $user_details->name;
                    $user_username = $user_details->username;
                }
                else
                {
                    $username = "";
                    $user_username = "";
                    $profile_image = "";
                }

                $total_views = VideoWatchHistory::where('video_id',$video_details->id)->count();
                // total likes
                $total_likes = VideoLikes::where('video_id',$video_details->id)->count();
                // total comments
                $total_comments = VideoComments::where('video_id',$video_details->id)->where('parent_id',0)->count();

                // video like or not
                $user_like_data= VideoLikes::where(['user_id' => $request->user_id,'video_id' => $video_details->id])->first();
                if (!empty($user_like_data)) 
                {
                    $is_video_like = 1;
                }
                else
                {
                    $is_video_like = 0;
                }

                $user_bookmark_data = VideoBookmark::where(['user_id' => $request->user_id,'video_id' => $video_details->id])->first();
                if (!empty($user_bookmark_data)) 
                {
                    $is_video_bookmark = 1;
                }
                else
                {
                    $is_video_bookmark = 0;
                }

                if ($video_details->cover_image != '') 
                {
                    $deldestinationPath =  Storage::disk('public')->path('uploads/videos/cover_images');
                    if(File::exists($deldestinationPath.'/'.$video_details->cover_image)) {
                        $cover_image = url('storage/app/public/uploads/videos/cover_images/'.$video_details->cover_image);
                    }
                    else
                    {
                        $cover_image = "";
                    }
                }
                else
                {
                    $cover_image = "";
                }
                // delete video
                if ($video_details->file_name != '') 
                {
                   $delddestinationPath =  Storage::disk('public')->path('uploads/videos/videos');
                    if(File::exists($delddestinationPath.'/'.$video_details->file_name)) {
                        $video_url = url('storage/app/public/uploads/videos/videos/'.$video_details->file_name);
                    }
                    else
                    {
                        $video_url = "";
                    }
                }
                else
                {
                    $video_url = "";
                }
                // user is favorites or not check 
                $video_favorites_data = VideoFavorite::where(['user_id' => $request->user_id,'video_id' => $video_details->id])->count();
                if ($video_favorites_data > 0) 
                {
                    $is_favorite = 1;
                }
                else
                {
                    $is_favorite = 0;
                }
                $record_video_files[] = array(
                    "video_id"              => $video_details->id,
                    "user_id"               => $video_details->user_id,
                    "name"                  => $username,
                    "username"              => $user_username,
                    "description"           => $video_details->description,
                    "is_video_like"         => $is_video_like,
                    "total_comments"        => $total_comments,
                    "cover_image"           => $cover_image,
                    "video_url"             => $video_url,
                    "total_views"           => (int)$total_views,
                    "is_bookmark"           => $is_video_bookmark,
                    "is_favorite"           => $is_favorite,
                );
                $single_result = $record_video_files;
            }
           
            $main_array = array_merge($single_result,$result);

            if ($main_array != '') {
                return response()->json(['hashtag_name' => $hashtag_data->name,'total_videos' => $total_videos,'is_hashtag_bookmark' => $is_hashtag_bookmark, 'data' => $main_array,'msg'=>'Video list get successfully.', 'status' =>'1']);
            }
            else
            {
                return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
            }
        }
        return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
    }

    
}
