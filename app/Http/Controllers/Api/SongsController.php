<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Songs;
use App\Models\User;
use App\Models\BannerImages;
use App\Models\Categories;
use App\Models\SoundBookmarks;
use App\Models\Singers;
use App\Models\VideoWatchHistory;
use App\Models\Videos;
use App\Models\VideoLikes;
use App\Models\VideoComments;
use App\Models\VideoBookmark;
use App\Models\VideoFavorite;
use Illuminate\Http\Request;
use Validator;
use Storage;
use File;
use DB;

class SongsController extends Controller
{
    
    // api for add banner image
    public function add_banner_image(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'banner_image'   => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        if ($request->file('banner_image') != '') 
        {
            // banner image file
            $banner_image = $request->file('banner_image');
            $destinationPath =  Storage::disk('public')->path('uploads/banner_image');
            if(!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath,0777, true, true);
            }

            $fileName   = time()."_".rand(11111,99999).".".$banner_image->getClientOriginalExtension();
            $upload_success = $banner_image->move($destinationPath, $fileName);

            $bannerimages                  = new BannerImages();
            $bannerimages->image_name      = isset($fileName) ? $fileName :'';
            $bannerimages->save();

            return response()->json(['msg'=>'Banner image upload successfully!', 'status' =>'1']);

        }
        else
        { 
            return response()->json(['msg'=>'Plase upload banner image.!', 'status' =>'0']);
        }
    }

    // api for categorie
    public function get_categories(Request $request) {

        $categories_data = Categories::whereNull('deleted_at')->get();
        if (count($categories_data) > 0) 
        {  
            foreach ($categories_data as $row) {
                $record[] = array(
                    "id"                    => $row->id,
                    "name"                  => $row->name,
                );
                $result = $record;
            }
            return response()->json(['data' => $result,'msg'=>'Categorie like get successfully.', 'status' =>'1']);
        }
        else
        {
            return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
        } 
    }


    // api for add song
    public function add_song(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'cat_id'   => 'required',
            'name'      => 'required',
            'attachment'   => 'required',
            'banner_image'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        if ($request->file('banner_image') != '') 
        {
            // banner image file
            $banner_image = $request->file('banner_image');
            $destinationPath =  Storage::disk('public')->path('uploads/song_banner_images');
            if(!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath,0777, true, true);
            }

            $banner_image_fileName   = time()."_".rand(11111,99999).".".$banner_image->getClientOriginalExtension();
            $upload_success = $banner_image->move($destinationPath, $banner_image_fileName);
        }
        else
        {
            $banner_image_fileName = "";
        }

        if ($request->file('attachment') != '') 
        {
            // song file
            $attachment = $request->file('attachment');
            $destinationPath =  Storage::disk('public')->path('uploads/songs');
            if(!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath,0777, true, true);
            }

            $attachment_fileName   = time()."_".rand(11111,99999).".".$attachment->getClientOriginalExtension();
            $upload_success = $attachment->move($destinationPath, $attachment_fileName);
        }
        else
        {
            $attachment_fileName = "";
        }

        $songs                  = new Songs();
        $songs->cat_id          = $request->cat_id;
        $songs->name            = $request->name;
        $songs->banner_image    = isset($banner_image_fileName) ? $banner_image_fileName :'';
        $songs->attachment      = isset($attachment_fileName) ? $attachment_fileName :'';
        $songs->save();
        return response()->json(['msg'=>'Song upload successfully!', 'status' =>'1']);
    }

    // api for list singers
    public function get_singers(Request $request) {

        $singer_data  = Singers::whereNull('deleted_at')->get();
        // echo "<pre>"; print_r($singer_data); die();
        if (count($singer_data) > 0) 
        {  
            foreach ($singer_data as $row) {
                // song file
                if ($row->image != '') 
                {
                    $destinationPath =  Storage::disk('public')->path('uploads/singers');
                    if(!empty($row->image)){
                        if(File::exists($destinationPath.'/'.$row->image)) {
                            $attachment = url('storage/app/public/uploads/singers/'.$row->image);
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

                $record[] = array(
                    "id"                    => $row->id,
                    "name"                  => $row->name,
                    "description"           => $row->description,
                    "image"                 => $attachment,
                );
                $result = $record;
            }
        }
        else
        {
            $result = array();
        }

        return response()->json(['data' => $result,'msg'=>'Singer list get successfully.', 'status' =>'1']);
    }

    public function get_song(Request $request) {

        $cat_song_data = array();
       $category_data  = Categories::whereNull("deleted_at")->get();
        if (count($category_data) > 0) 
        {  
            foreach ($category_data as $row) {
                $song_data  = Songs::where("cat_id",$row->id)->get();
                if (count($song_data) > 0) {
                    foreach ($song_data as $key => $val) {
                        // song file
                        if ($val->attachment != '') 
                        {
                            $destinationPath =  Storage::disk('public')->path('uploads/songs');
                            if(!empty($val->attachment)){
                                if(File::exists($destinationPath.'/'.$val->attachment)) {
                                    $attachment = url('storage/app/public/uploads/songs/'.$val->attachment);
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
                        if ($val->banner_image != '') 
                        {
                            $destinationPath =  Storage::disk('public')->path('uploads/song_banner_images');
                            if(!empty($val->banner_image)){
                                if(File::exists($destinationPath.'/'.$val->banner_image)) {
                                    $banner_image = url('storage/app/public/uploads/song_banner_images/'.$val->banner_image);
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


                        $song_data[] = array(
                            "id"                    => $val->id,
                            "name"                  => $val->name,
                            "description"           => $val->description,
                            "duration"              => $val->duration,
                            "attachment"            => $attachment,
                        );
                        $cat_song_data = $song_data;
                    }
                }
                $record_song[] = array(
                    "id"                    => $row->id,
                    "name"                  => $row->name,
                    "song_data"             => $cat_song_data,
                );
                $result = $record_song;
                unset($song_data);
            }
        }
        else
        {
            $result = array();
        }

        // banner images
        $banner_images_data  = BannerImages::get();
        // echo "<pre>"; print_r($banner_images_data->toArray()); die();
        if (count($banner_images_data) > 0) 
        {  
            foreach ($banner_images_data as $row) {
                // song banner image
                if ($row->image_name != '') 
                {
                    $destinationPath =  Storage::disk('public')->path('uploads/banner_image');
                    if(!empty($row->image_name)){
                        if(File::exists($destinationPath.'/'.$row->image_name)) {
                            $banner_image = url('storage/app/public/uploads/banner_image/'.$row->image_name);
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
                $record_banner[] = array(
                    "id"                    => $row->id,
                    "banner_image"          => $banner_image,
                );
                $banner_result = $record_banner;
            }
        }
        else
        {
            $banner_result = array();
        }

        return response()->json(['banner_result' => $banner_result,'data' => $result,'msg'=>'Song list get successfully.', 'status' =>'1']);
    }

    // api for add song Favorties
    public function add_favortie_song(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'sound_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $fev_song_data = SoundBookmarks::where(['user_id' => $request->user_id,'sound_id' => $request->sound_id])->first();
        if (empty($fev_song_data)) {
            $soundbookmarks                  = new SoundBookmarks();
            $soundbookmarks->sound_id        = $request->sound_id;
            $soundbookmarks->save();
            return response()->json(['msg'=>'Song favortie add successfully!', 'status' =>'1']);
        }
        else
        {
            return response()->json(['msg'=>'This song already added favortie!', 'status' =>'1']);
        }
    }

    // api for remove song Favorties
    public function removed_favortie_song(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'sound_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        $fev_song_data = SoundBookmarks::where(['user_id' => $request->user_id,'sound_id' => $request->sound_id])->first();
        if (!empty($fev_song_data)) {
            SoundBookmarks::where(['user_id' => $request->user_id,'sound_id' => $request->sound_id])->delete();
            return response()->json(['msg'=>'Song favortie to remove successfully!', 'status' =>'1']);
        }
        else
        {
            return response()->json(['msg'=>'No data found!', 'status' =>'1']);
        }
    }

    // api for list favorties song
    public function get_favorties_song(Request $request) {

        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        if(isset($request->keyword) && !empty($request->keyword)) 
        {
            $song_data  = SoundBookmarks::leftJoin("songs","sound_bookmarks.sound_id","=","songs.id")
                        ->leftJoin("categories","songs.cat_id","=","categories.id")
                        ->select("songs.*","categories.name as categories_name")
                        ->where('sound_bookmarks.user_id',$request->user_id)
                        ->whereNull('songs.deleted_at')
                        ->get();
        } 
        else 
        {
            $song_data  = SoundBookmarks::leftJoin("songs","sound_bookmarks.sound_id","=","songs.id")
                        ->leftJoin("categories","songs.cat_id","=","categories.id")
                        ->whereNull('songs.deleted_at')
                        ->get();
        }
        // echo "<pre>"; print_r($song_data->toArray()); die();
        if (count($song_data) > 0) 
        {  
            foreach ($song_data as $row) {
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


                $record[] = array(
                    "id"                    => $row->id,
                    "cat_id"                => $row->cat_id,
                    "categories_name"       => $row->categories_name,
                    "name"                  => $row->name,
                    "description"           => $row->description,
                    "duration"              => $row->duration,
                    "banner_image"          => $banner_image,
                    "attachment"            => $attachment,
                );
                $result = $record;
            }
        }
        else
        {
            $result = array();
        }

        // banner images
        $banner_images_data  = BannerImages::get();
        // echo "<pre>"; print_r($banner_images_data->toArray()); die();
        if (count($banner_images_data) > 0) 
        {  
            foreach ($banner_images_data as $row) {
                // song banner image
                if ($row->image_name != '') 
                {
                    $destinationPath =  Storage::disk('public')->path('uploads/banner_image');
                    if(!empty($row->image_name)){
                        if(File::exists($destinationPath.'/'.$row->image_name)) {
                            $banner_image = url('storage/app/public/uploads/banner_image/'.$row->image_name);
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
                $record_banner[] = array(
                    "id"                    => $row->id,
                    "banner_image"          => $banner_image,
                );
                $banner_result = $record_banner;
            }
        }
        else
        {
            $banner_result = array();
        }

        return response()->json(['banner_result' => $banner_result,'data' => $result,'msg'=>'Song list get successfully.', 'status' =>'1']);
    }

    // api for list song to video
    public function get_song_to_video(Request $request) {

        $result = array();

        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'song_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        // user is song bookmark or not check 
        $sound_bookmark_data = SoundBookmarks::where(['user_id' => $request->user_id,'sound_id' => $request->song_id])->count();
        if ($sound_bookmark_data > 0) 
        {
            $is_song_bookmark = 1;
        }
        else
        {
            $is_song_bookmark = 0;
        }

        $single_song_data  = Songs::leftJoin("singers","singers.id","=","songs.singer_id")
                                ->select("songs.*","songs.id as songs_id","singers.name as singer_name","singers.description as singer_description","singers.image as singer_image")
                                ->where("songs.id","=",$request->song_id)
                                ->first();
        if ($single_song_data !='') {

            // song file
            if ($single_song_data->attachment != '') 
            {
                $destinationPath =  Storage::disk('public')->path('uploads/songs');
                if(!empty($single_song_data->attachment)){
                    if(File::exists($destinationPath.'/'.$single_song_data->attachment)) {
                        $attachment = url('storage/app/public/uploads/songs/'.$single_song_data->attachment);
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
            if ($single_song_data->banner_image != '') 
            {
                $destinationPath =  Storage::disk('public')->path('uploads/song_banner_images');
                if(!empty($single_song_data->banner_image)){
                    if(File::exists($destinationPath.'/'.$single_song_data->banner_image)) {
                        $banner_image = url('storage/app/public/uploads/song_banner_images/'.$single_song_data->banner_image);
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

            // song banner image
            if ($single_song_data->singer_image != '') 
            {
                $destinationPath =  Storage::disk('public')->path('uploads/singers');
                if(!empty($single_song_data->singer_image)){
                    if(File::exists($destinationPath.'/'.$single_song_data->singer_image)) {
                        $singer_image = url('storage/app/public/uploads/singers/'.$single_song_data->singer_image);
                    }
                    else
                    {
                        $singer_image = "";
                    }
                }
                else
                {
                    $singer_image = "";
                }
            }
            else
            {
                $singer_image = "";
            }
            $total_videos = Videos::where('song_id',$single_song_data->id)->count();

            if ($request->video_id != '' && $request->video_id != 0) {
                $song_data  = Songs::select('songs.*','videos.*','songs.id as songs_id','videos.id as id','songs.name as name')
                            ->leftJoin('videos','songs.id','=','videos.song_id')
                            ->orderBy("songs.id","DESC")
                            ->get();
            }
            else{
                $song_data  = Songs::select('songs.*','videos.*','songs.id as songs_id','videos.id as id','songs.name as name')
                            ->leftJoin('videos','songs.id','=','videos.song_id')
                            ->where("videos.song_id","=",$request->song_id)            
                            ->orderBy("songs.id","DESC")
                            ->get();
            }
            // echo "<pre>"; print_r($song_data->toArray()); die();
            if (count($song_data) > 0) 
            {  
                foreach ($song_data as $row) {

                    $total_views = VideoWatchHistory::where('video_id',$row->id)->count();
                    // cover image
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
                    // video file
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
                    $record_song[] = array(
                        "video_id"              => $row->id,
                        "user_id"               => $row->user_id,
                        "name"                  => $username,
                        "username"              => $user_username,
                        "cover_image"           => $cover_image,
                        "video_url"             => $video_url,
                        "total_views"           => (int)$total_views,
                        "is_bookmark"           => $is_video_bookmark,
                        "is_favorite"           => $is_favorite,
                    );
                    $result = $record_song;
                }
            }

            if ($request->video_id != '' && $request->video_id != 0) {
                $single_song_data  = Songs::select('songs.*','videos.*','videos.id as id','songs.id as songs_id','songs.name as name')
                                ->leftJoin('videos','songs.id','=','videos.song_id')
                                ->where("videos.song_id","=",$request->song_id)            
                                ->where("videos.id","=",$request->video_id)            
                                ->first();
                $total_views = VideoWatchHistory::where('video_id',$single_song_data->id)->count();
                // cover image
                if ($single_song_data->cover_image != '') 
                {
                    $deldestinationPath =  Storage::disk('public')->path('uploads/videos/cover_images');
                    if(File::exists($deldestinationPath.'/'.$single_song_data->cover_image)) {
                        $cover_image = url('storage/app/public/uploads/videos/cover_images/'.$single_song_data->cover_image);
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
                // video file
                if ($single_song_data->file_name != '') 
                {
                   $delddestinationPath =  Storage::disk('public')->path('uploads/videos/videos');
                    if(File::exists($delddestinationPath.'/'.$single_song_data->file_name)) {
                        $video_url = url('storage/app/public/uploads/videos/videos/'.$single_song_data->file_name);
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

                $user_details    = User::where('id',$single_song_data->user_id)->first();
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
                $total_likes = VideoLikes::where('video_id',$single_song_data->video_id)->count();
                // total comments
                $total_comments = VideoComments::where('video_id',$single_song_data->video_id)->where('parent_id',0)->count();

                // video like or not
                $user_like_data= VideoLikes::where(['user_id' => $request->user_id,'video_id' => $single_song_data->video_id])->first();
                if (!empty($user_like_data)) 
                {
                    $is_video_like = 1;
                }
                else
                {
                    $is_video_like = 0;
                }
                
                $user_bookmark_data = VideoBookmark::where(['user_id' => $request->user_id,'video_id' => $single_song_data->video_id])->first();
                if (!empty($user_bookmark_data)) 
                {
                    $is_video_bookmark = 1;
                }
                else
                {
                    $is_video_bookmark = 0;
                }
                // user is favorites or not check 
                $video_favorites_data = VideoFavorite::where(['user_id' => $request->user_id,'video_id' => $single_song_data->video_id])->count();
                if ($video_favorites_data > 0) 
                {
                    $is_favorite = 1;
                }
                else
                {
                    $is_favorite = 0;
                }

                $single_record_song[] = array(
                    "video_id"              => $single_song_data->id,
                    "user_id"               => $single_song_data->user_id,
                    "name"                  => $username,
                    "username"              => $user_username,
                    "total_likes"           => $total_likes,
                    "total_comments"        => $total_comments,
                    "cover_image"           => $cover_image,
                    "is_favorite"           => $is_favorite,
                );
                $single_result = $single_record_song;
            }
            else
            {
                $single_result = array();
            }

            $main_array = array_merge($single_result,$result);
            return response()->json(['song_id' =>$single_song_data->songs_id,'song_name' =>$single_song_data->name,'song_banner_image' =>$banner_image,'song_url' =>$attachment,'total_videos' =>$total_videos,'singer_id' =>$single_song_data->singer_id,'singer_name' =>$single_song_data->singer_name,'singer_description' =>$single_song_data->singer_description,'singer_image' =>$singer_image,'is_song_bookmark' =>$is_song_bookmark,'data' => $main_array,'msg'=>'Song to video list get successfully.', 'status' =>'1']);
        }
        return response()->json(['msg'=>'This song not found.', 'status' =>'0']);
    }

}
