<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Followers;
use App\Models\Videos;
use App\Models\VideoLikes;
use App\Models\VideoData;
use App\Models\Country;
use App\Models\VideoWatchHistory;
use Validator;
use Hash;
use Storage;
use File;
use DB;
class AnalyticsController extends Controller
{

    // api for Overview details
    function analytics_overview(Request $request)
    {
        $result = array();
        $female_followers = 0;
        $male_followers = 0;
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
        ]);
        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $start_date = date('Y-m-d');
        $start_time = strtotime($start_date);
        $seven_time = strtotime("+7 day", $start_time);
        $month_time = strtotime("+28 day", $start_time);

        for($i=$start_time; $i<$seven_time; $i+=86400)
        {
            $past_seven_views[] = array(
                "date"          => date('d', $i),
                "video_views"   => 123,
                "profile_views" => 123,
                "followers"     => 123,
            );
        }
        for($i=$start_time; $i<$month_time; $i+=86400)
        {
            $past_28_views[] = array(
                "date"          => date('d', $i),
                "video_views"   => 123,
                "profile_views" => 123,
                "followers"     => 123,
            );
        }

        $record[] = array(
            "past_seven_views"  => $past_seven_views,
            "past_28_views"     => $past_28_views,
        );
         
        return response()->json(['data' => $record,'msg'=>'Overview analytics data list get successfully.', 'status' =>'1']);
    }

    // api for user details
    function analytics_followers(Request $request)
    {
        $result = array();
        $female_followers = 0;
        $male_followers = 0;
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
        ]);
        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $today_date = date('Y-m-d');
        $past_week_date = date('Y-m-d', strtotime('-7 days'));
        
        $total_followers = Followers::where('user_id','=',$request->user_id)->count();
        $country_list = Country::join("users","countries.id","=","users.country_id")
                                ->join("followers","followers.follower_id","=","users.id")
                                ->select("countries.*",DB::raw('count(users.country_id) as total_users'))
                                ->whereNull('countries.deleted_at')
                                ->groupBy('countries.name')
                                ->get();
        $pastweek_followers = Followers::where('user_id','=',$request->user_id)->where('created_at','>=',$past_week_date)->count();

        $male_female_follower = Followers::leftJoin("users","followers.follower_id","=","users.id")
                                        ->select("followers.*","users.gender as gender")
                                        ->where('followers.user_id','=',$request->user_id)
                                        ->get();
        // echo "<pre>"; print_r($male_female_follower->toArray()); die(); 
        if (count($male_female_follower) > 0) {
            foreach ($male_female_follower as $key => $val) {
                if ($val->gender == "female") {
                    $female_followers += 1;
                }
                if ($val->gender == "male") {
                    $male_followers += 1;
                }
            }
        }              
        if (count($country_list) > 0) {
            foreach ($country_list as $key => $val) {
                $response[] = array(
                    "name" => $val->name,
                    "total_users" => $val->total_users,
                );
                $result = $response;
            }
        } 

        $male_pr = $male_followers/$total_followers * 100;
        $female_pr = $female_followers/$total_followers * 100;
        // $result_data['total_followers']          = $total_followers;
        // $result_data['last_week_followers']      = $pastweek_followers;
        // $result_data['week_date']                = date("M d", strtotime('-7 days')).' - '.date('M d');   
        // $result_data['female_followers']         = number_format($female_pr,2);   
        // $result_data['male_followers']           = number_format($male_pr,2);   
        // $result_data['country_list']             = $result;   

        $record[] = array(
            "total_followers"       => $total_followers,
            "last_week_followers"   => $pastweek_followers,
            "week_date"             => date("M d", strtotime('-7 days')).' - '.date('M d'),
            "male_followers"        => number_format($male_pr,2),
            "country_list"          => $result,
        );
        $result = $record;
        return response()->json(['data' => $result,'msg'=>'Followers analytics data list get successfully.', 'status' =>'1']);
    }

    // api for Content details
    function analytics_content(Request $request)
    {
        $result = array();
        $last_week_videos = array();
        $trending_videos = array();
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
        ]);
        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $today_date = date('Y-m-d');
        $past_week_date = date('Y-m-d', strtotime('-7 days'));
        
        $total_post = Videos::where('user_id','=',$request->user_id)->count();
        $last_week_followers = Videos::where('user_id','=',$request->user_id)->where('created_at','>=',$past_week_date)->get();
        // echo "<pre>"; print_r($last_week_followers->toArray()); die(); 
        if (count($last_week_followers) > 0) {
            foreach ($last_week_followers as $key => $row) {
                // total watch
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

                $last_week_record[] = array(
                    "video_id"              => $row->id,
                    "total_views"           => (int)$total_views,
                    "cover_image"           => $cover_image,
                    "video_url"             => $video_url,
                );
                $last_week_videos = $last_week_record;
            }
        }   

        $trending_video_list = Videos::leftJoin("video_watch_histories","video_watch_histories.video_id","=","videos.id")
                    ->select("videos.*",DB::raw('count(video_watch_histories.id) as total_views'))
                    ->where('videos.user_id','=',$request->user_id)
                    ->groupBy('videos.id')
                    ->orderBy("total_views","DESC")
                    ->get();
        // $trending_video_list = Videos::where('videos.user_id','=',$request->user_id)->get();           
        // echo "<pre>"; print_r($trending_video_list->toArray()); die(); 
        if (count($trending_video_list) > 0) {
            foreach ($trending_video_list as $key => $row) {
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

                $trending_video_record[] = array(
                    "video_id"              => $row->id,
                    "total_views"           => (int)$row->total_views,
                    "cover_image"           => $cover_image,
                    "video_url"             => $video_url,
                );
                $trending_videos = $trending_video_record;
            }
        }    
        // $result_data['total_post']               = $total_post;
        // $result_data['last_week_followers']      = count($last_week_followers);
        // $result_data['week_date']                = date("M d", strtotime('-7 days')).' - '.date('M d');   
        // $result_data['last_week_videos']         = $last_week_videos;   
        // $result_data['trending_videos']          = $trending_videos;  

        $record[] = array(
            "total_post"            => $total_post,
            "last_week_posts"       => count($last_week_followers),
            "week_date"             => date("M d", strtotime('-7 days')).' - '.date('M d'),
            "last_week_videos"      => $last_week_videos,
            "trending_videos"       => $trending_videos,
        );
        $result = $record; 
        return response()->json(['data' => $result,'msg'=>'Followers analytics data list get successfully.', 'status' =>'1']);
    }
}
