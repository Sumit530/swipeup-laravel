<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Videos;
use App\Models\VideoFavorite;
use App\Models\VideoLikes;
use App\Models\VideoComments;
use Illuminate\Http\Request;
use Validator;
use Storage;
use File;

class VideoFavoriteController extends Controller
{
	// api for add video favorite
    public function add_video_favorite(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); die();
        $user_id        = $request->user_id;
        $video_id       = $request->video_id;

        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'video_id'  => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $user_data= User::where('id', $user_id)->first();
        if (!empty($user_data)) 
        {   
            $video_data = Videos::where('id',$video_id)->first();
            if (!empty($video_data)) 
            {
            	$video_bookmark_data = VideoFavorite::where(['user_id' => $user_id,'video_id' => $video_id])->first();
		        if (empty($video_bookmark_data)) 
		        {
	                $videofavorite               = new VideoFavorite();
	                $videofavorite->user_id      = $user_id;
	                $videofavorite->video_id     = $video_id;
	                $videofavorite->save(); 

	                return response()->json(['msg'=>'Video favorite add successfully', 'status' =>'1']);
	            }
	            else
	            {
	                return response()->json(['msg'=>'This video already added favorite', 'status' =>'0']);
	            }
            }
            else
            {
                return response()->json(['msg'=>'This video not found..!.', 'status' =>'0']);
            }
        }
        else
        { 
            return response()->json(['msg'=>'This user not exist our database.!', 'status' =>'0']);
        }
    }

    // api for remove video favorite
    public function remove_video_favorite(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); die();
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'video_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
		$video_favorite_data = VideoFavorite::where(['user_id' => $request->user_id,'video_id' => $request->video_id])->first();
        if (!empty($video_favorite_data)) 
        {
            VideoFavorite::where(['user_id' => $request->user_id,'video_id' => $request->video_id])->delete();
       		return response()->json(['msg'=>'Video favorite remove successfully', 'status' =>'1']);
       	}
       	else
       	{
       		return response()->json(['msg'=>'Video favorite data not found..!', 'status' =>'0']);
       	}
    }

    // api for video favorite list
    public function get_video_favorites(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'user_id'  => 'required',
            'video_id'  => 'required',
            'login_id'  => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $video_favorite_data = VideoFavorite::where('user_id',$request->user_id)->where('video_id',"!=",$request->video_id)->get();
        if (count($video_favorite_data) > 0) 
        {  
            foreach ($video_favorite_data as $row) {
                $video_details    = Videos::where('id',$row->video_id)->first();
                if ($video_details != '') 
                {

                    // total likes
                    $total_likes = VideoLikes::where('video_id',$row->video_id)->count();
                    // total comments
                    $total_comments = VideoComments::where('video_id',$row->video_id)->where('parent_id',0)->count();

                    // video like or not
                    $user_like_data= VideoLikes::where(['user_id' => $request->login_id,'video_id' => $row->video_id])->first();
                    if (!empty($user_like_data)) 
                    {
                        $is_video_like = 1;
                    }
                    else
                    {
                        $is_video_like = 0;
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
                    
                    $video_id = $video_details->id;
                    $description = $video_details->description;
                    $is_allow_comments = $video_details->is_allow_comments;
                    $is_allow_duet = $video_details->is_allow_duet;
                }
                else
                {
                    $video_id = "";
                    $cover_image = "";
                    $video_url = "";
                    $description = "";
                    $is_allow_comments = "";
                    $is_allow_duet = "";
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

                $record1[] = array(
                    "favorite_id"           => $row->id,
                    "video_id"           	=> $video_id,
                    "user_id"               => $row->user_id,
                    "name"                  => $username,
                    "username"              => $user_username,
                    "profile_image"         => $profile_image,
                    "description"           => $description,
                    "is_allow_comments"     => $is_allow_comments,
                    "is_allow_duet"         => $is_allow_duet,
                    "is_video_like"         => $is_video_like,
                    "total_likes"           => $total_likes,
                    "total_comments"        => $total_comments,
                    "cover_image"           => $cover_image,
                    "video_url"             => $video_url,
                );
                $result1 = $record1;
            }
        }
        else
        {
            $result1 = array();
        } 

        $video_favorite_data2 = VideoFavorite::where('user_id',$request->user_id)->where('video_id',$request->video_id)->first();
        if ($video_favorite_data2 != '') 
        {  
            $video_details    = Videos::where('id',$video_favorite_data2->video_id)->first();
            if ($video_details != '') 
            {

                // total likes
                $total_likes = VideoLikes::where('video_id',$video_favorite_data2->video_id)->count();
                // total comments
                $total_comments = VideoComments::where('video_id',$video_favorite_data2->video_id)->where('parent_id',0)->count();

                // video like or not
                $user_like_data= VideoLikes::where(['user_id' => $request->login_id,'video_id' => $video_favorite_data2->video_id])->first();
                if (!empty($user_like_data)) 
                {
                    $is_video_like = 1;
                }
                else
                {
                    $is_video_like = 0;
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
                
                $video_id = $video_details->id;
                $description = $video_details->description;
                $is_allow_comments = $video_details->is_allow_comments;
                $is_allow_duet = $video_details->is_allow_duet;
            }
            else
            {
                $video_id = "";
                $cover_image = "";
                $video_url = "";
                $description = "";
                $is_allow_comments = "";
                $is_allow_duet = "";
            }

            $user_details    = User::where('id',$video_favorite_data2->user_id)->first();
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

            $record2[] = array(
                "favorite_id"           => $video_favorite_data2->id,
                "video_id"              => $video_id,
                "user_id"               => $video_favorite_data2->user_id,
                "name"                  => $username,
                "username"              => $user_username,
                "profile_image"         => $profile_image,
                "description"           => $description,
                "is_allow_comments"     => $is_allow_comments,
                "is_allow_duet"         => $is_allow_duet,
                "is_video_like"         => $is_video_like,
                "total_likes"           => $total_likes,
                "total_comments"        => $total_comments,
                "cover_image"           => $cover_image,
                "video_url"             => $video_url,
            );
            $result2 = $record2;
        }
        else
        {
            $result2 = array();
        }
        $main_array = array_merge($result2,$result1);
        if ($main_array != '') {
            return response()->json(['data' => $main_array,'msg'=>'Favorite Video get successfully.', 'status' =>'1']);
        }
        else
        {
            return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
        }

    }
}
