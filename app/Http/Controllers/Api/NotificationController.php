<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Videos;
use App\Models\VideoLikes;
use App\Models\VideoComments;
use App\Models\VideoData;
use App\Models\User;
use App\Models\Followers;
use App\Models\VideoCommentLikes;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use Storage;
use File;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notification(Request $request)
    {
        $video_all_data = array();
        $yesterday_more_result = array();

        $user_id = $request->user_id;

        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }   

        // all
        $all_notidication_data = Notification::where(['receiver_id' => $user_id])->orderBy('created_at','DESC')->get();
        // echo "<pre>"; print_r($all_notidication_data); die();
        if ($all_notidication_data != '') 
        {  
            foreach ($all_notidication_data as $row) {
                // if (isset($row->follower_id)) {
                //     $user_data = User::where('id','=',$row->follower_id)->first();
                // }
                // else if (isset($row->mention_id)) {
                //     $user_data = User::where('id','=',$row->mention_id)->first();
                // }
                // else{
                //     $user_data = User::where('id','=',$row->user_id)->first();
                // }
                $user_data = User::where('id','=',$row->user_id)->first();
                if ($user_data != ''){
                    $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
                    if(!empty($user_data->profile_image)){
                        if(File::exists($destinationPath.'/'.$user_data->profile_image)) {
                            $profile_image = url('storage/app/public/uploads/user/profile/'.$user_data->profile_image);
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

                    $video_data = Videos::where('id','=',$row->video_id)->first();
                    if ($video_data != '') 
                    {
                        if ($video_data->cover_image != '') 
                        {
                            $deldestinationPath =  Storage::disk('public')->path('uploads/videos/cover_images');
                            if(File::exists($deldestinationPath.'/'.$video_data->cover_image)) {
                                $cover_image = url('storage/app/public/uploads/videos/cover_images/'.$video_data->cover_image);
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
                        if ($video_data->file_name != '') 
                        {
                           $delddestinationPath =  Storage::disk('public')->path('uploads/videos/videos');
                            if(File::exists($delddestinationPath.'/'.$video_data->file_name)) {
                                $video_url = url('storage/app/public/uploads/videos/videos/'.$video_data->file_name);
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
                    }
                    else
                    {
                        $video_url = "";
                        $cover_image = "";
                    }

                    $follower_data = Followers::where(['user_id' => $user_id,'follower_id' => $row->user_id])->first();
                    if ($follower_data != '') 
                    {
                        $is_follow = 1;
                    }
                    else
                    {
                        $is_follow = 0;
                    }

                    // more likes 
                    $noti_more_data = Notification::leftJoin("users","users.id","=","notifications.receiver_id")
                                    ->select("notifications.*","users.*","notifications.id as id","users.username as username","users.profile_image as profile_image")
                                    ->where(['notifications.receiver_id' => $request->user_id,'notifications.video_id' => $row->video_id])
                                    ->whereDate('notifications.created_at', Carbon::yesterday())
                                    ->get();
                    $noti_more_count = Notification::leftJoin("users","users.id","=","notifications.receiver_id")
                                        ->select("notifications.*","users.*","notifications.id as id","users.username as username","users.profile_image as profile_image")
                                        ->where(['notifications.receiver_id' => $request->user_id,'notifications.video_id' => $row->video_id])
                                        ->whereDate('notifications.created_at', Carbon::yesterday())
                                        ->count();
                    // echo "<pre>"; print_r($noti_more_data); die();
                    if (count($noti_more_data)) {
                        foreach ($noti_more_data as $val) {
                            $destinationPath2 =  Storage::disk('public')->path('uploads/user/profile');
                            if(!empty($val->profile_image)){
                                if(File::exists($destinationPath2.'/'.$val->profile_image)) {
                                    $profile_image = url('storage/app/public/uploads/user/profile/'.$val->profile_image);
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

                            if ($row->type == 1) {
                                $more_result[] = array(
                                    "id"             => $val->id,
                                    "user_id"        => (int)$val->receiver_id,
                                    "name"           => isset($val->name) ? $val->name : '',
                                    "username"       => isset($val->username) ? $val->username : '',
                                    "profile_image"  => $profile_image,
                                    "total_another_like"  => count($noti_more_data),
                                    "created_at"     => date("Y-m-d H:i:s",strtotime($val->created_at)),
                                );
                                $yesterday_more_result = $more_result;
                            }else{
                                $yesterday_more_result = array();
                            }
                        }
                    }

                    $is_like_comment = 0;
                    if ($row->type == 2) {
                        $comment_like_data = VideoCommentLikes::where(['user_id' => $request->user_id,'comment_id' => $row->comment_id])->count();
                        if ($comment_like_data != '' && $comment_like_data > 0) 
                        {
                            $is_like_comment = 1;
                        }
                        else
                        {
                            $is_like_comment = 0;
                        }
                    }

                    $all_video_data[] = array(
                        "id"             => $row->id,
                        "user_id"        => $user_data->id,
                        "video_id"       => isset($row->video_id) ? (int)$row->video_id : 0,
                        "profile_image"  => $profile_image,
                        "type"           => (int)$row->type,
                        "total_another_like"  => $noti_more_count,
                        "more_result"    => $yesterday_more_result,
                        "created_at"     => date("Y-m-d H:i:s",strtotime($row->created_at)),
                    );
                    $video_all_data = $all_video_data;
                    unset($more_result);
                }
            }
                          
        }
      
        if (!empty($video_all_data)) 
        {
            return response()->json(['data' => $video_all_data,'msg'=>'Notification Retrive Successfully.', 'status' =>'1']);
        }
        else
        {
            return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
        }
    }

    public function follower_notification_list(Request $request)
    {
        $today_list = array();
        $yesterday_list = array();

        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }   

        // today
        $today_followers_notification_data = Notification::leftJoin("users","users.id","=","notifications.user_id")
                                            ->select("notifications.*","users.*","notifications.id as id","notifications.user_id as user_id","users.username as username","users.profile_image as profile_image")
                                            ->where(['notifications.receiver_id' => $request->user_id,'notifications.type' => 3])
                                            ->orderBy('notifications.created_at','DESC')
                                            ->get();
        // echo "<pre>"; print_r($today_followers_notification_data->toArray()); die();
        if ($today_followers_notification_data != '') 
        {  
            foreach ($today_followers_notification_data as $row) {
                $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
                if(!empty($row->profile_image)){
                    if(File::exists($destinationPath.'/'.$row->profile_image)) {
                        $profile_image = url('storage/app/public/uploads/user/profile/'.$row->profile_image);
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

                $follower_data = Followers::where(['user_id' => $request->user_id,'follower_id' => $row->user_id])->first();
                if ($follower_data != '') 
                {
                    $is_follow = 1;
                }
                else
                {
                    $is_follow = 0;
                }
                $results[] = array(
                    "id"             => $row->id,
                    "user_id"        => (int)$row->user_id,
                    "name"           => isset($row->name) ? $row->name : '',
                    "username"       => isset($row->username) ? $row->username : '',
                    "profile_image"  => $profile_image,
                    "is_follow"      => $is_follow,
                    "created_at"     => date("Y-m-d H:i:s",strtotime($row->created_at)),
                );
                $today_list = $results;
            }
                          
        }

        // yesterday
        $yesterday_followers_notification_data = Notification::leftJoin("users","users.id","=","notifications.user_id")
                                            ->select("notifications.*","users.*","notifications.id as id","notifications.user_id as user_id","users.username as username","users.profile_image as profile_image")
                                            ->where(['notifications.receiver_id' => $request->user_id,'notifications.type' => 3])
                                            ->orderBy('notifications.created_at','DESC')
                                            ->get();
        // echo "<pre>"; print_r($yesterday_followers_notification_data->toArray()); die();
        if ($yesterday_followers_notification_data != '') 
        {  
            foreach ($yesterday_followers_notification_data as $row) {
                $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
                if(!empty($row->profile_image)){
                    if(File::exists($destinationPath.'/'.$row->profile_image)) {
                        $profile_image = url('storage/app/public/uploads/user/profile/'.$row->profile_image);
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

                $follower_data = Followers::where(['user_id' => $request->user_id,'follower_id' => $row->user_id])->first();
                if ($follower_data != '') 
                {
                    $is_follow = 1;
                }
                else
                {
                    $is_follow = 0;
                }
                $yesterday_results[] = array(
                    "id"             => $row->id,
                    "user_id"        => (int)$row->user_id,
                    "name"           => isset($row->name) ? $row->name : '',
                    "username"       => isset($row->username) ? $row->username : '',
                    "profile_image"  => $profile_image,
                    "is_follow"      => $is_follow,
                    "created_at"     => date("Y-m-d H:i:s",strtotime($row->created_at)),
                );
                $yesterday_list = $yesterday_results;
            }
                          
        }

        $notification_data[] = array(
            "today_list"   => $today_list,
            "yesterday_list" => $yesterday_list,
        );
        return response()->json(['data' => $notification_data,'msg'=>'Notification Retrive Successfully.', 'status' =>'1']);
    }

    public function mentions_notification_list(Request $request)
    {
        $results = array();

        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['msg'=>$message, 'status' =>'0']);            
        }   

        // today
        $today_followers_notification_data = Notification::leftJoin("users","users.id","=","notifications.user_id")
                                            ->leftJoin("videos","videos.id","=","notifications.video_id")
                                            ->select("notifications.*","users.*","videos.*","notifications.id as id","users.username as username","users.profile_image as profile_image")
                                            ->orderBy('notifications.created_at','DESC')
                                            ->get();
        // echo "<pre>"; print_r($today_followers_notification_data->toArray()); die();
        if ($today_followers_notification_data != '') 
        {  
            foreach ($today_followers_notification_data as $row) {
                $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
                if(!empty($row->profile_image)){
                    if(File::exists($destinationPath.'/'.$row->profile_image)) {
                        $profile_image = url('storage/app/public/uploads/user/profile/'.$row->profile_image);
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

                $results[] = array(
                    "id"             => $row->id,
                    "user_id"        => (int)$row->receiver_id,
                    "name"           => isset($row->name) ? $row->name : '',
                    "username"       => isset($row->username) ? $row->username : '',
                    "profile_image"  => $profile_image,
                    "cover_image"    => $cover_image,
                    "video_url"      => $video_url,
                    "created_at"     => date("Y-m-d H:i:s",strtotime($row->created_at)),
                );
            }
            if (count($results) > 0) {
                return response()->json(['data' => $results,'msg'=>'Notification Retrive Successfully.', 'status' =>'1']);
            }
            return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
        }else{ 
            return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
        }
    }

    public function comment_notification_list(Request $request)
    {
        $results = array();
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }   

        // today
        $today_followers_notification_data = Notification::leftJoin("users","users.id","=","notifications.user_id")
                                            ->leftJoin("videos","videos.id","=","notifications.video_id")
                                            ->select("notifications.*","users.*","videos.*","notifications.id as id","users.username as username","users.profile_image as profile_image")
                                            ->orderBy('notifications.created_at','DESC')
                                            ->get();
        // echo "<pre>"; print_r($today_followers_notification_data->toArray()); die();
        if ($today_followers_notification_data != '') 
        {  
            foreach ($today_followers_notification_data as $row) {
                $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
                if(!empty($row->profile_image)){
                    if(File::exists($destinationPath.'/'.$row->profile_image)) {
                        $profile_image = url('storage/app/public/uploads/user/profile/'.$row->profile_image);
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

                $comment_like_data = VideoCommentLikes::where(['user_id' => $request->user_id,'comment_id' => $row->id])->count();
                if ($comment_like_data != '' && $comment_like_data > 0) 
                {
                    $is_like_comment = 1;
                }
                else
                {
                    $is_like_comment = 0;
                }
                    
                $results[] = array(
                    "id"             => $row->id,
                    "comment_id"     => isset($row->comment_id) ? (int)$row->comment_id : '',
                    "video_id"       => isset($row->video_id) ? (int)$row->video_id : 0,
                    "user_id"        => (int)$row->receiver_id,
                    "name"           => isset($row->name) ? $row->name : '',
                    "username"       => isset($row->username) ? $row->username : '',
                    "cover_image"    => $cover_image,
                    "video_url"      => $video_url,
                    "is_like_comment"=> $is_like_comment,
                    "created_at"     => date("Y-m-d H:i:s",strtotime($row->created_at)),
                );
            }
            return response()->json(['data' => $results,'msg'=>'Notification Retrive Successfully.', 'status' =>'1']);
        }else{ 
            return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
        }
    }

    public function like_notification_list(Request $request)
    {

        $today_list = array();
        $yesterday_list = array();
        $yesterday_more_result = array();

        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }   

        // today
        $today_notification_data = Notification::leftJoin("users","users.id","=","notifications.user_id")
                                            ->leftJoin("videos","videos.id","=","notifications.video_id")
                                            ->select("notifications.*","users.*","notifications.id as id","users.username as username","users.profile_image as profile_image","videos.file_name as file_name","videos.cover_image as cover_image")
                                            ->whereDate('notifications.created_at', Carbon::today())
                                            ->orderBy('notifications.created_at','DESC')
                                            ->get();
        // echo "<pre>"; print_r($today_notification_data->toArray()); die();
        if ($today_notification_data != '') 
        {  
            foreach ($today_notification_data as $row) {
                $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
                if(!empty($row->profile_image)){
                    if(File::exists($destinationPath.'/'.$row->profile_image)) {
                        $profile_image = url('storage/app/public/uploads/user/profile/'.$row->profile_image);
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

                $results[] = array(
                    "id"             => $row->id,
                    "video_id"       => (int)$row->video_id,
                    "user_id"        => (int)$row->user_id,
                    "name"           => isset($row->name) ? $row->name : '',
                    "username"       => isset($row->username) ? $row->username : '',
                    "profile_image"  => $profile_image,
                    "cover_image"    => $cover_image,
                    "video_url"      => $video_url,
                    "created_at"     => date("Y-m-d H:i:s",strtotime($row->created_at)),
                );
                $today_list = $results;
            }
        }

        // yesterday
        $yesterday_notification_data = Notification::leftJoin("users","users.id","=","notifications.user_id")
                                             ->leftJoin("videos","videos.id","=","notifications.video_id")
                                            ->select("notifications.*","users.*","notifications.id as id","users.username as username","users.profile_image as profile_image","videos.file_name as file_name","videos.cover_image as cover_image")
                                            ->where(['notifications.receiver_id' => $request->user_id,'notifications.type' => 1])
                                            ->groupBy('notifications.video_id')
                                            ->get();
        // echo "<pre>"; print_r($yesterday_notification_data->toArray()); die();
        if ($yesterday_notification_data != '') 
        {  
            foreach ($yesterday_notification_data as $row) {
                $noti_more_data = Notification::leftJoin("users","users.id","=","notifications.user_id")
                                     ->leftJoin("videos","videos.id","=","notifications.video_id")
                                    ->select("notifications.*","users.*","notifications.id as id","users.username as username","users.profile_image as profile_image","videos.file_name as file_name","videos.cover_image as cover_image")
                                    ->where(['notifications.receiver_id' => $request->user_id,'notifications.type' => 1,'notifications.video_id' => $row->video_id])
                                    ->where('notifications.id','!=',$row->id)
                                    ->whereDate('notifications.created_at', Carbon::yesterday())
                                    ->get();
                $noti_more_count = Notification::leftJoin("users","users.id","=","notifications.user_id")
                                     ->leftJoin("videos","videos.id","=","notifications.video_id")
                                    ->select("notifications.*","users.*","notifications.id as id","users.username as username","users.profile_image as profile_image","videos.file_name as file_name","videos.cover_image as cover_image")
                                    ->where(['notifications.receiver_id' => $request->user_id,'notifications.type' => 1,'notifications.video_id' => $row->video_id])
                                    ->where('notifications.id','!=',$row->id)
                                    ->whereDate('notifications.created_at', Carbon::yesterday())
                                    ->count();
                // echo "<pre>"; print_r($noti_more_data); die();
                if (count($noti_more_data)) {
                    foreach ($noti_more_data as $val) {
                        $destinationPath2 =  Storage::disk('public')->path('uploads/user/profile');
                        if(!empty($val->profile_image)){
                            if(File::exists($destinationPath2.'/'.$val->profile_image)) {
                                $profile_image = url('storage/app/public/uploads/user/profile/'.$val->profile_image);
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

                        $more_result[] = array(
                            "id"             => $val->id,
                            "user_id"        => (int)$val->user_id,
                            "name"           => isset($val->name) ? $val->name : '',
                            "username"       => isset($val->username) ? $val->username : '',
                            "profile_image"  => $profile_image,
                            "total_another_like"  => count($noti_more_data),
                            "created_at"     => date("Y-m-d H:i:s",strtotime($val->created_at)),
                        );
                        $yesterday_more_result = $more_result;
                    }
                }

                $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
                if(!empty($row->profile_image)){
                    if(File::exists($destinationPath.'/'.$row->profile_image)) {
                        $profile_image = url('storage/app/public/uploads/user/profile/'.$row->profile_image);
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

                $yesterday_results[] = array(
                    "id"             => $row->id,
                    "video_id"       => (int)$row->video_id,
                    "user_id"        => (int)$row->user_id,
                    "name"           => isset($row->name) ? $row->name : '',
                    "username"       => isset($row->username) ? $row->username : '',
                    "profile_image"  => $profile_image,
                    "cover_image"    => $cover_image,
                    "video_url"      => $video_url,
                    "created_at"     => date("Y-m-d H:i:s",strtotime($row->created_at)),
                );
                $yesterday_list = $yesterday_results;
            }
                          
        }

        $notification_data[] = array(
            "today_list"   => $today_list,
            "yesterday_list" => $yesterday_list,
        );
        return response()->json(['data' => $notification_data,'msg'=>'Notification Retrive Successfully.', 'status' =>'1']);
    }


    public function dateDiff($date)
    {
        $mydate= date("Y-m-d H:i:s");
        $theDiff="";
        //echo $mydate;//2014-06-06 21:35:55
        $datetime1 = date_create($date);
        $datetime2 = date_create($mydate);
        $interval = date_diff($datetime1, $datetime2);
        //echo $interval->format('%s Seconds %i Minutes %h Hours %d days %m Months %y Year    Ago')."<br>";
        $min=$interval->format('%i');
        $sec=$interval->format('%s');
        $hour=$interval->format('%h');
         if($interval->format('%h%d%m%y')=="0000"){
            return $min." Minutes";
        } else if($interval->format('%d%m%y')=="000"){
            return $hour." Hours";
        } else if($interval->format('%m%y')=="00"){
            return $day." Days";
        } else if($interval->format('%y')=="0"){
            return $mon." Months";
        } else{
            return $year." Years";
        }    
    }


    // api for send notification
    public function send_notification(Request $request)
    {
        $user_id        = $request->user_id;
        $title          = $request->title;
        $message        = $request->message;
        
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'title'     => 'required',
            'message'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $user_data= User::where('id', $user_id)->first();
        if (!empty($user_data)) 
        {   

            $ddd = "U5_CoQzToemdW-LLRgywn:APA91bHmWTcnQKceuC1BTikU2s_KjcR5DH9ElucOKHV85GwGq4ctcyrqTpjGuMIbIdkJknXET2kW8XTjvd0JcCsIO3fRjNkhs5vPMnEmX46xiqMhrNCENbqZZVjw-wMGlgfHWZN4gtTr";
            if($user_data->device_id<>"")
            { 
                $notification_id    = rand(0000,9999);
                $find_reciever_id   = $user_data->device_id;
        
                $FCMS=array();
                array_push($FCMS,$find_reciever_id);
               
                if($find_reciever_id<>"")
                {  
                    $img = "http://binarygeckos.com/pvpl/vadmin/assets/images/place_holder_new.png";
                    $field = array('registration_ids'  =>array($find_reciever_id),'data'=> array( "message" => $title,"title" => $title,"body" => $message,"content"=>$message,"notification_id"=>$notification_id,"type"=>1,"id"=>$user_id,"image"=>$img,"sound"=>1,"vibrate"=>1));
                    
                    $fields = json_encode ($field);
                    $headers = array (
                            'Authorization: key=AAAAzoC3TFA:APA91bHSq2d1ECf3rUcKN1pGCSj6NKOV04kgNCMac_iH04FMQ6n3iWCYbrWuKdRCL9dx7kkCpN8tDpSzoA49jSk1TuwdIEtB07ObVvHkKeQuxuAlhH3TnQfjH5-_vPqmbHmCHy5AZlvl',
                            'Content-Type: application/json'
                    );
                    $url = 'https://fcm.googleapis.com/fcm/send';
                    $ch = curl_init ();
                    curl_setopt ( $ch, CURLOPT_URL, $url );
                    curl_setopt ( $ch, CURLOPT_POST, true );
                    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
                    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
                    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
                    $resultss = curl_exec ( $ch );
                    // echo "<pre>";
                    //  print_r($resultss); die();
                    curl_close ( $ch );
                }
                return response()->json(['results' => $resultss,'msg'=>'Notification data.!', 'status' =>'0']);

            }
            else
            {
                return response()->json(['msg'=>'This user device id not exist our database.!', 'status' =>'0']);
            }
        }
        else
        { 
            return response()->json(['msg'=>'This user not exist our database.!', 'status' =>'0']);
        }
    }

}
