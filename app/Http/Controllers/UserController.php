<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Followers;
use App\Models\Videos;
use App\Models\VideoData;
use App\Models\VideoLikes;
use App\Models\Safety;
use App\Models\NotificationSettings;
use view;
class UserController extends Controller
{
    // public function __Construct() {
    //     return $this->middleware('auth');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::query()->whereNull('deleted_at')->paginate(10);
        // echo "<pre>"; print_r($users); die();
        return View('users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'language_name' => 'required|unique:languages',
        ]);

        $users = new User();
        $users->language_name = $request->language_name;
        $users->save();
        
        return redirect()->route('users.index')->with('success', 'New user added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // echo $id; die();
        $user = User::find($id); 
        if(!empty($user)) 
        {   
            $total_following = Followers::where('follower_id',$id)->count();
            $total_follow   = Followers::where('user_id',$id)->count();

            // total likes
            $total_likes = 0;
            $all_video_data = Videos::where('user_id',$id)->get();
            if (count($all_video_data) > 0) 
            {  
                foreach ($all_video_data as $row) {

                    $videos_data = VideoData::where(['video_id' => $row->id])->get();
                    if (count($videos_data) > 0) 
                    {  
                        foreach ($videos_data as $val) {
                            $total_likes += VideoLikes::where('video_id',"=",$val->id)->count();
                        }
                    }
                    else
                    {
                        $total_likes = 0;
                    }
                }
            }
            else
            {
                $total_likes = 0;
            }
            return View('users.account',compact('user','total_following','total_follow','total_likes'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id); 
        if(!empty($user)) 
        {
            $safety = Safety::where('user_id',$id)->first();
            $notificationsettings = NotificationSettings::where('user_id',$id)->first();
            return View('users.edit',compact('user','safety','notificationsettings'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // echo "<pre>"; print_r($request->all()); die();
        $this->validate($request, [
            'name'      => 'required',
            'username'  => 'required|unique:users,username,'.$id,
            'email'     => 'required|unique:users,email,'.$id,
            'mobile_no' => 'required|unique:users,mobile_no,'.$id,
            'country_code' => 'required',
            'page_name' => 'required',
            'dob'       => 'required',
            'website'   => 'required',
            'bio'       => 'required',
        ]);

        $users = User::find($id); 
        $users->name = $request->name;
        $users->username = $request->username;
        $users->email = $request->email;
        $users->mobile_no = $request->mobile_no;
        $users->country_code = $request->country_code;
        $users->page_name = $request->page_name;
        $users->dob = isset($request->dob) ? date("Y-m-d",strtotime($request->dob)): NULL;
        $users->website = $request->website;
        $users->bio = $request->bio;
        $users->allow_find_me = '0';
        if ($request->allow_find_me == 'on') 
        {
            $users->allow_find_me = '1';
        }
        $users->private_account = '0';
        if ($request->private_account == 'on') 
        {
            $users->private_account = '1';
        }
        $users->is_vip = '0';
        if ($request->is_vip == 'on') 
        {
            $users->is_vip = '1';
        }
        
        // if($users->profile_image){
        //     $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
        //     if(!File::exists($destinationPath)) {
        //         File::makeDirectory($destinationPath,0777, true, true);
        //     }
        //     if ($users->profile_image != '') 
        //     {
        //         if(!empty($users) && !empty($users->profile_image)){
        //             if(File::exists($destinationPath.'/'.$users->profile_image)) {
        //                 $delete=File::delete($destinationPath.'/'.$users->profile_image);
        //             }
        //         }
        //     }
        //     $this->upload_dir1          = public_path('uploads/users/profile_image/');
        //     $dataURL                    = $request->input('profile_image');
        //     $dataURL                    = str_replace('data:image/png;base64,', '', $dataURL);
        //     $dataURL                    = str_replace(' ', '+', $dataURL);
        //     $image                      = base64_decode($dataURL);
        //     $filename                   = time()."_".rand().".png";
        //     $file_path =  Storage::disk('public')->path('uploads/user/profile/'.$filename);
        //     file_put_contents($file_path, $image);               
        //     $users->profile_image  = $filename;
        // }
        $users->save();

        // safety setting update
        $safety = Safety::find($request->safety_id);
        if ($request->is_allow_comments == 'on') 
        {
            $safety->is_allow_comments = '1';
        }
        else
        {
            $safety->is_allow_comments = $safety->is_allow_comments;
        }
        if ($request->is_allow_duets == 'on') 
        {
            $safety->is_allow_duets = '1';
        }
        else
        {
            $safety->is_allow_duets = $safety->is_allow_duets;
        }
        if ($request->is_allow_messages == 'on') 
        {
            $safety->is_allow_messages = '1';
        }
        else
        {
            $safety->is_allow_messages = $safety->is_allow_messages;
        }
        if ($request->is_allow_downloads == 'on') 
        {
            $safety->is_allow_downloads = '1';
        }
        else
        {
            $safety->is_allow_downloads = $safety->is_allow_downloads;
        }
        $safety->save();

        // notification setting update
        $notificationsettings = NotificationSettings::find($request->notificationsettings_id);
        if ($request->is_likes == 'on') 
        {
            $notificationsettings->is_likes = '1';
        }
        else
        {
            $notificationsettings->is_likes = $notificationsettings->is_likes;
        }
        if ($request->is_comments == 'on') 
        {
            $notificationsettings->is_comments = '1';
        }
        else
        {
            $notificationsettings->is_comments = $notificationsettings->is_comments;
        }
        if ($request->is_new_followers == 'on') 
        {
            $notificationsettings->is_new_followers = '1';
        }
        else
        {
            $notificationsettings->is_new_followers = $notificationsettings->is_new_followers;
        }
        if ($request->is_mentions == 'on') 
        {
            $notificationsettings->is_mentions = '1';
        }
        else
        {
            $notificationsettings->is_mentions = $notificationsettings->is_mentions;
        }
        if ($request->is_direct_messages == 'on') 
        {
            $notificationsettings->is_direct_messages = '1';
        }
        else
        {
            $notificationsettings->is_direct_messages = $notificationsettings->is_direct_messages;
        }
        if ($request->is_videos_from_follow == 'on') 
        {
            $notificationsettings->is_videos_from_follow = '1';
        }
        else
        {
            $notificationsettings->is_videos_from_follow = $notificationsettings->is_videos_from_follow;
        }
        if ($request->is_video_suggestions == 'on') 
        {
            $notificationsettings->is_video_suggestions = '1';
        }
        else
        {
            $notificationsettings->is_video_suggestions = $notificationsettings->is_video_suggestions;
        }
        if ($request->is_livestreams_from_follow == 'on') 
        {
            $notificationsettings->is_livestreams_from_follow = '1';
        }
        else
        {
            $notificationsettings->is_livestreams_from_follow = $notificationsettings->is_livestreams_from_follow;
        }
        if ($request->is_recommended_broadcasts == 'on') 
        {
            $notificationsettings->is_recommended_broadcasts = '1';
        }
        else
        {
            $notificationsettings->is_recommended_broadcasts = $notificationsettings->is_recommended_broadcasts;
        }
        if ($request->is_customized_updates == 'on') 
        {
            $notificationsettings->is_customized_updates = '1';
        }
        else
        {
            $notificationsettings->is_customized_updates = $notificationsettings->is_customized_updates;
        }
        $notificationsettings->save();
        return redirect()->route('users.index')->with('success', 'User details update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function status(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        $users = User::find($request->id);
        if ($users->status == 1) 
        {
            $users->status = '0';
        }
        else
        {
            $users->status = '1';
        }
        $users->save();

        return redirect()->route('users.index')->with('success', 'Status update successfully.');
    }

    
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $users = User::find($request->id)->delete();
        return redirect()->route('users.index')->with('success', 'User delete successfully.');
    }

    public function notificationsetting($id)
    {
        $user = User::find($id); 
        if(!empty($user)) 
        {
            $total_following = Followers::where('follower_id',$id)->count();
            $total_follow   = Followers::where('user_id',$id)->count();

            // total likes
            $total_likes = 0;
            $all_video_data = Videos::where('user_id',$id)->get();
            if (count($all_video_data) > 0) 
            {  
                foreach ($all_video_data as $row) {

                    $videos_data = VideoData::where(['video_id' => $row->id])->get();
                    if (count($videos_data) > 0) 
                    {  
                        foreach ($videos_data as $val) {
                            $total_likes += VideoLikes::where('video_id',"=",$val->id)->count();
                        }
                    }
                    else
                    {
                        $total_likes = 0;
                    }
                }
            }
            else
            {
                $total_likes = 0;
            }
            return View('users.notificationsetting',compact('user','total_following','total_follow','total_likes'));
        }
    }

    public function privacy($id)
    {
        $user = User::find($id); 
        if(!empty($user)) 
        {
            $total_following = Followers::where('follower_id',$id)->count();
            $total_follow   = Followers::where('user_id',$id)->count();

            // total likes
            $total_likes = 0;
            $all_video_data = Videos::where('user_id',$id)->get();
            if (count($all_video_data) > 0) 
            {  
                foreach ($all_video_data as $row) {

                    $videos_data = VideoData::where(['video_id' => $row->id])->get();
                    if (count($videos_data) > 0) 
                    {  
                        foreach ($videos_data as $val) {
                            $total_likes += VideoLikes::where('video_id',"=",$val->id)->count();
                        }
                    }
                    else
                    {
                        $total_likes = 0;
                    }
                }
            }
            else
            {
                $total_likes = 0;
            }
            return View('users.privacy',compact('user','total_following','total_follow','total_likes'));
        }
    }

    public function safety($id)
    {
        $user = User::find($id); 
        if(!empty($user)) 
        {
            $total_following = Followers::where('follower_id',$id)->count();
            $total_follow   = Followers::where('user_id',$id)->count();

            // total likes
            $total_likes = 0;
            $all_video_data = Videos::where('user_id',$id)->get();
            if (count($all_video_data) > 0) 
            {  
                foreach ($all_video_data as $row) {

                    $videos_data = VideoData::where(['video_id' => $row->id])->get();
                    if (count($videos_data) > 0) 
                    {  
                        foreach ($videos_data as $val) {
                            $total_likes += VideoLikes::where('video_id',"=",$val->id)->count();
                        }
                    }
                    else
                    {
                        $total_likes = 0;
                    }
                }
            }
            else
            {
                $total_likes = 0;
            }
            $Safety = Safety::where('user_id',$id)->count();
            return View('users.safety',compact('user','total_following','total_follow','total_likes','Safety'));
        }
    }
}
