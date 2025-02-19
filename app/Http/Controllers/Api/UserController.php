<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Followers;
use App\Models\NotificationSettings;
use App\Models\Safety;
use App\Models\Videos;
use App\Models\VideoLikes;
use App\Models\VideoData;
use App\Models\VideoComments;
use App\Models\FavortiesSongs;
use App\Models\VideoFavorite;
use App\Models\VideoWatchHistory;
use App\Models\Notification;
use App\Models\RestrictAccounts;
use Validator;
use Hash;
use Storage;
use File;
use DB;

class UserController extends Controller
{
    public $successStatus = 200;
    public $errorStatus = 300;

    /**
    * Registrion Process
    */

    // send otp
    public function send_sms($mobile_no,$otp)
    {
        $api_key = "f0e17ead-4a52-11ed-9c12-0200cd936042";
        $url = "https://2factor.in/API/V1/".$api_key."/SMS/+91".$mobile_no."/".$otp;
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url
        ]);
        $resp = curl_exec($curl);
        curl_close($curl);
        $json_output = json_decode($resp);  
        $status = $json_output->Status; //Error,Success 
        $status = "Success"; //Error,Success 
        return $status;
    }

    //api for registration user 
    function registration(Request $request)
    {
        // echo Hash::make(12345678); die();
        $country_code 		= $request->country_code;
        $mobile_no          = $request->mobile_no;
    	$email 				= $request->email;
         
        if(!empty($mobile_no)) 
        {
            $validator = Validator::make($request->all(), [ 
                'mobile_no'   => 'required|numeric|digits:10|unique:users,mobile_no',
                'country_code'   => 'required|numeric',
            ]);

            if ($validator->fails())
            { 
                $message = $validator->errors()->first();
                return response()->json(['msg'=>$message, 'status' =>'0']);            
            }

		    $chk_user = User::where('mobile_no','=',$mobile_no)->first();
			if(empty($chk_user)) 
			{    
                $otp = mt_rand(1000, 9999);
                $otp_status = $this->send_sms($mobile_no,$otp);

				$users                  = new User();
                $users->country_code    = $country_code;
                $users->mobile_no       = $mobile_no;
                $users->otp     		= $otp;
                $users->otp_expired    	= strtotime("+30 min");
                $users->save(); 

                $user_id = $users->id;

                // notification
                $Notification_settings  = new NotificationSettings();
                $Notification_settings->user_id  = $user_id;
                $Notification_settings->save(); 
                
                // safety
                $safety                 = new Safety();
                $safety->user_id        = $user_id;
                $safety->save(); 

                // get single user data
                $user_data= User::where('id', $user_id)->first();

                $result_data['user_id']          = $user_data->id;
                $result_data['name']             = $user_data->name?$user_data->name:'';   
                $result_data['email']            = $user_data->email?$user_data->email:'';
				return response()->json(['data' => $result_data,'msg'=>'Registration successfully.', 'status' =>'1']);
			} 
			else 
			{
				return response()->json(['msg'=>'This mobile no is already exist our database', 'status' =>'0']);
			}
        }
        elseif(!empty($email)) 
        {   
            $validator = Validator::make($request->all(), [ 
                'email'   => 'required|email|unique:users,email',
            ]);

            if ($validator->fails())
            { 
                $message = $validator->errors()->first();
                return response()->json(['msg'=>$message, 'status' =>'0']);            
            }

		    $chk_user = User::where('email','=',$email)->first();
			if(empty($chk_user)) 
			{
				$otp = rand(1111,9999);

                $details = [
                    'email' => $email,
                    'otp' => $otp
                ];
               
                \Mail::to($email)->send(new \App\Mail\SendOtp($details));

				$users                  = new User();
                $users->email       	= $email;
                $users->otp     		= $otp;
                $users->otp_expired    	= strtotime("+30 min");
                $users->save(); 

				$user_id = $users->id;

                // notification
                $Notification_settings  = new NotificationSettings();
                $Notification_settings->user_id  = $user_id;
                $Notification_settings->save(); 
                
                // safety
                $safety                 = new Safety();
                $safety->user_id        = $user_id;
                $safety->save(); 

                // get single user data
                $user_data= User::where('id', $user_id)->first();

                $result_data['user_id']          = $user_data->id;
                $result_data['name']             = $user_data->name?$user_data->name:'';   
                $result_data['country_code']     = $user_data->country_code?$user_data->country_code:'';   
                $result_data['mobile_no']        = $user_data->mobile_no?$user_data->mobile_no:'';   
                $result_data['email']            = $user_data->email?$user_data->email:'';
                return response()->json(['data' => $result_data,'msg'=>'Registration successfully.', 'status' =>'1']);
			} 
			else 
			{
				return response()->json(['msg'=>'This email is already exist our database', 'status' =>'0']);
			}
        }
        else
        {
            return response()->json(['msg'=>'Please enter email or mobile no.!', 'status' =>'0']);
        }
    }

    //api for registration user 
    function social_signup(Request $request)
    {
        $name               = $request->name;
        $email              = $request->email;
        $social_id          = $request->social_id;
        $type               = $request->type;
         
        $validator = Validator::make($request->all(), [ 
            // 'social_id'     => 'required',
            // 'name'          => 'required',
            'email'         => 'required|email',
            'type'          => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['msg'=>$message, 'status' =>'0']);            
        }

        $chk_user = User::where('email','=',$email)->first();
        if(empty($chk_user)) 
        {    
            $users                  = new User();
            $users->social_id       = $social_id ? $social_id : '';
            $users->name            = $name ? $name : '';
            $users->email           = $email;
            $users->type            = $type;
            $users->save(); 

            $user_id = $users->id;

            // notification
            $Notification_settings  = new NotificationSettings();
            $Notification_settings->user_id  = $user_id;
            $Notification_settings->save(); 
            
            // safety
            $safety                 = new Safety();
            $safety->user_id        = $user_id;
            $safety->save(); 

            // get single user data
            $user_data= User::where('id', $user_id)->first();

            $result_data['user_id']          = $user_data->id;
            $result_data['name']             = $user_data->name?$user_data->name:'';   
            $result_data['email']            = $user_data->email?$user_data->email:'';
            $result_data['token']            = $user_data->createToken('swape')->accessToken;
            return response()->json(['data' => $result_data,'msg'=>'Registration successfully.', 'status' =>'1']);
        } 
        else 
        {
            $result_data['user_id']          = $chk_user->id;
            $result_data['name']             = $chk_user->name?$chk_user->name:'';   
            $result_data['email']            = $chk_user->email?$chk_user->email:'';
            $result_data['token']            = $chk_user->createToken('swape')->accessToken;
            return response()->json(['data' => $result_data,'msg'=>'Already registered user', 'status' =>'1']);
        }
    }

    // api for login
    function login(Request $request)
    {
        
        // echo Hash::make(123); die();
        $email 				= $request->email;
    	$password 			= $request->password;
        $fcm_id             = $request->fcm_id;
        $device_id          = $request->device_id;
         
        if(!empty($email) && !empty($password) && !empty($device_id) && !empty($fcm_id)) 
        {
            if(is_numeric($request->get('email'))){
    	       $user_data= User::where('mobile_no', $email)->first();
            }
            elseif (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
                $user_data= User::where('email', $email)->first();
            }
            else
            {
                $user_data= User::where('username', $email)->first();
            }
            // print_r($data);
            if(!empty($user_data))
            {
                if (!Hash::check($password, $user_data->password)) {
                    return response()->json(['msg'=>'Please enter valid login details.!', 'status' =>'0']);
                }
                else
                { 
                    $data['device_id'] = $device_id;
                    $data['fcm_id']    = $fcm_id;
                    User::where("email",$email)->update($data);

                    $result_data['user_id']          = $user_data->id;
                    $result_data['name']             = $user_data->name?$user_data->name:'';   
                    $result_data['country_code']     = $user_data->country_code?$user_data->country_code:'';   
                    $result_data['mobile_no']        = $user_data->mobile_no?$user_data->mobile_no:'';   
                    $result_data['email']            = $user_data->email?$user_data->email:'';   
                    $result_data['language_id']      = $user_data->language_id?$user_data->language_id:'';   
                    $result_data['token']            = $user_data->createToken('swape')->accessToken;
					return response()->json(['data' => $result_data,'msg'=>'Login successfully.', 'status' =>'1']);
    			}
            }
            else 
			{
				return response()->json(['msg'=>'This user is not exist our database', 'status' =>'0']);
			}
        }
        else
        {
        	return response()->json(['msg'=>'Plase provide login details.', 'status' =>'0']);
        }
        
    }

    //api for registration user 
    function send_otp(Request $request)
    {
        $mobile_no          = $request->mobile_no;
         
        $validator = Validator::make($request->all(), [ 
            'mobile_no'   => 'required|numeric|digits:10',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        
        $otp = mt_rand(1000, 9999);
        $otp_status = $this->send_sms($mobile_no,$otp);
        $result_data['mobile_no']        = $mobile_no;
        $result_data['otp']              = $otp;
        return response()->json(['data' => $result_data,'msg'=>'Otp send successfully.', 'status' =>'1']);
    }

    //api for registration user 
    function resend_otp(Request $request)
    {
        $mobile_no          = $request->mobile_no;
         
        $validator = Validator::make($request->all(), [ 
            'mobile_no'   => 'required|numeric|digits:10',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $user_data = User::where('mobile_no','=',$mobile_no)->first();
        if(!empty($user_data)) 
        {    
            $otp = mt_rand(1000, 9999);
            $otp_status = $this->send_sms($mobile_no,$otp);

            $data_update['otp'] = $otp;
            $data_update['otp_expired'] = strtotime("+30 min");
            User::where('id',$user_data->id)->update($data_update);

            $result_data['user_id']          = $user_data->id;
            $result_data['name']             = $user_data->name?$user_data->name:'';   
            $result_data['country_code']     = $user_data->country_code?$user_data->country_code:'';   
            $result_data['mobile_no']        = $user_data->mobile_no?$user_data->mobile_no:'';   
            $result_data['email']            = $user_data->email?$user_data->email:'';
            $result_data['language_id']      = $user_data->language_id?$user_data->language_id:'';
            $result_data['otp']              = $otp;
            return response()->json(['data' => $result_data,'msg'=>'Otp send successfully.', 'status' =>'1']);
        } 
        else 
        {
            return response()->json(['msg'=>'This mobile no is not exist our database', 'status' =>'0']);
        }
    }

    // api for user details
    function user_details(Request $request)
    {
        
        $keyword              = $request->keyword;
         

        $validator = Validator::make($request->all(), [ 
            'keyword'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        if(is_numeric($request->get('keyword'))){
           $user_data= User::where('mobile_no','LIKE','%'.$keyword.'%')->first();
        }
        elseif (filter_var($request->get('keyword'), FILTER_VALIDATE_EMAIL)) {
            $user_data= User::where('email','LIKE','%'.$keyword.'%')->first();
        }
        // else
        // {
        //     $user_data= User::where('username', $keyword)->first();
        // }
        // print_r($data);
        if(!empty($user_data))
        {
            return response()->json(['data' => $user_data,'msg'=>'User detail get successfully.', 'status' =>'1']);
        }
        else 
        {
            return response()->json(['msg'=>'This user is not exist our database', 'status' =>'0']);
        }
    }

    // api for my all accounts 
    function get_my_accounts(Request $request)
    {   
        $result = array();

        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $user_data= User::where('id', '=',$request->user_id)->get();
        // echo "<pre>"; print_r($user_data->toArray()); die();
        if(!empty($user_data)) {
            foreach ($user_data as $row) {
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
                $record[] = array(
                    "id"                    => $row->id,
                    "name"                  => $row->name?$row->name:'',
                    "username"              => $row->username?$row->username:'',
                    "email"                 => $row->email?$row->email:'',
                    "country_code"          => $row->country_code?$row->country_code:'',
                    "dob"                   => $row->dob?$row->dob:'',
                    "private_account"       => $row->private_account,
                    "language_id"           => $row->language_id?$row->language_id:'',
                    "is_vip"                => $row->is_vip,
                    "wallet"                => $row->wallet,
                    "profile_image"         => $file_path,
                );
                $result = $record;
            }
            return response()->json(['data' => $result,'msg'=>'User list get successfully.', 'status' =>'1']);
        }
        return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
    }

    // api for user details
    function get_all_users(Request $request)
    {   
        $result = array();

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
            $user_data= User::where('id', '!=',$request->user_id)
                // ->where('username','LIKE',"'%".$request->keyword."%'")
                ->where('status','=','1')
                ->orWhere('username','LIKE',"'%".$request->keyword."%'")
                ->get();
        } 
        else 
        {
            $user_data= User::where('status','=','1')->where('id', '!=',$request->user_id)->get();
        }

        // echo "<pre>"; print_r($user_data->toArray()); die();
        if(!empty($user_data)) {
            foreach ($user_data as $row) {
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
                $record[] = array(
                    "id"                    => $row->id,
                    "name"                  => $row->name?$row->name:'',
                    "username"              => $row->username?$row->username:'',
                    "email"                 => $row->email?$row->email:'',
                    "country_code"          => $row->country_code?$row->country_code:'',
                    "mobile_no"             => $row->mobile_no?$row->mobile_no:'',
                    "page_name"             => $row->page_name?$row->page_name:'',
                    "dob"                   => $row->dob?$row->dob:'',
                    "language_id"           => $row->language_id?$row->language_id:'',
                    "private_account"       => $row->private_account,
                    "is_vip"                => $row->is_vip,
                    "wallet"                => $row->wallet,
                    "profile_image"         => $file_path,
                );
                $result = $record;
            }
            return response()->json(['data' => $result,'msg'=>'User list get successfully.', 'status' =>'1']);
        }
        return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
    }

    // api for check otp
    function check_otp(Request $request)
    {
        $user_id 		= $request->user_id;
    	$otp 			= $request->otp;
         
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $user_data= User::where('id', $user_id)->first();
        if (!empty($user_data)) 
        {	
        	if ($user_data->otp == $otp)
        	{
        		$data_update['otp'] = "";
                $data_update['otp_expired'] = "";
                User::where('id',$user_data->id)->update($data_update);

				return response()->json(['msg'=>'Otp match successfully.', 'status' =>'1']);
        	}
        	else
        	{
            	return response()->json(['msg'=>'Otp not match.!', 'status' =>'0']);
        	}
        }
        else
        { 
            return response()->json(['msg'=>'This user details not found our database.!', 'status' =>'0']);
		}
    }

    // api for check username
    function check_username(Request $request)
    {
        $username 		= $request->username;
         
        $validator = Validator::make($request->all(), [ 
            'username'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

    	$user_data= User::where('username', $username)->first();
        if (!empty($user_data)) 
        {	
			return response()->json(['msg'=>'This user name is already our database.', 'status' =>'1']);
        }
        else
        { 
            return response()->json(['msg'=>'This user not found our database.!', 'status' =>'0']);
		}
    }

    // api for update username
    function update_username(Request $request)
    {
        $user_id        = $request->user_id;
        $username       = $request->username;

        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'username'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $user_data = User::where('username', $username)->first();
        if (empty($user_data)) 
        {   
            $data_update['username'] = $username;
            User::where('id',$user_id)->update($data_update);

            return response()->json(['msg'=>'Username update successfully.', 'status' =>'1']);
        }
        else
        { 
            return response()->json(['msg'=>'This username already exist our database.!', 'status' =>'0']);
        }
    }

    // api for update mobile no
    function update_mobile_no(Request $request)
    {
        $user_id        = $request->user_id;
        $mobile_no      = $request->mobile_no;

        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'mobile_no' => 'required|unique:users,mobile_no,'.$request->user_id,
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['msg'=>$message, 'status' =>'0']);            
        }

        $data_update['mobile_no'] = $mobile_no;
        User::where('id',$user_id)->update($data_update);

        return response()->json(['msg'=>'User mobile no update successfully.', 'status' =>'1']);
    }

    // api for update page name
    function update_page_name(Request $request)
    {
        $user_id 		= $request->user_id;
        $page_name 		= $request->page_name;
        
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'page_name'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $data_update['page_name'] = $page_name;
        User::where('id',$user_id)->update($data_update);

		return response()->json(['msg'=>'Page name update successfully.', 'status' =>'1']);
        
    }

   
    // api for update privacy
    function update_privacy(Request $request)
    {
        $user_id        = $request->user_id;
        $allow_find_me  = $request->allow_find_me;
        $private_account = $request->private_account;
         
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'allow_find_me'   => 'required',
            'private_account'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $data_update['allow_find_me'] = $allow_find_me;
        $data_update['private_account'] = $private_account;
        User::where('id',$user_id)->update($data_update);
        return response()->json(['msg'=>'Privacy details update successfully.', 'status' =>'1']);
    }

    // api for get safeties
    function get_user_safeties(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        $user_safeties = Safety::where('user_id',$request->user_id)->first();
        if ($user_safeties != '') {
            return response()->json(['data' => $user_safeties, 'msg'=>'Safeties details update successfully.', 'status' =>'1']);
        }
        return response()->json(['msg'=>'Safeties details not found.', 'status' =>'0']);
    }

    // api for update safeties
    function update_safeties(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'is_allow_comments'     => 'required',
            'is_allow_downloads'    => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        $user_safeties = Safety::where('user_id',$request->user_id)->first();
        if ($user_safeties != '') {
            $data_update['is_allow_comments'] = $request->is_allow_comments;
            $data_update['is_allow_downloads'] = $request->is_allow_downloads;
            Safety::where('user_id',$request->user_id)->update($data_update);
            return response()->json(['msg'=>'Safeties details update successfully.', 'status' =>'1']);
        }
        return response()->json(['msg'=>'Safeties details not found.', 'status' =>'0']);
    }

    // api for update Notification setting
    function update_notification_settings(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'is_likes'  => 'required',
            'is_mentions' => 'required',
            'is_direct_messages' => 'required',
            'is_recommended_broadcasts' => 'required',
            'is_customized_updates' => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        $user_safeties = NotificationSettings::where('user_id',$request->user_id)->first();
        if ($user_safeties != '') {
            $data_update['is_likes'] = $request->is_likes;
            $data_update['is_mentions'] = $request->is_mentions;
            $data_update['is_direct_messages'] = $request->is_direct_messages;
            $data_update['is_recommended_broadcasts'] = $request->is_recommended_broadcasts;
            $data_update['is_customized_updates'] = $request->is_customized_updates;
            NotificationSettings::where('user_id',$request->user_id)->update($data_update);
            return response()->json(['msg'=>'Notification setting details update successfully.', 'status' =>'1']);
        }
        return response()->json(['msg'=>'Notification setting details not found.', 'status' =>'0']);
    }

    // api for get Notification setting
    function get_notification_settings(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        $user_safeties = NotificationSettings::where('user_id',$request->user_id)->first();
        if ($user_safeties != '') {
            return response()->json(['data' => $user_safeties,'msg'=>'Notification setting details get successfully.', 'status' =>'1']);
        }
        return response()->json(['msg'=>'Notification setting details not found.', 'status' =>'0']);
    }

   

    public function getProfile(Request $request) {

        $validator = Validator::make($request->all(), [ 
            'login_id'            => 'required',
            'follower_id'         => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $profile= User::with('getCountryById')->find($request->follower_id);
        // echo "<pre>"; print_r($profile); die();
        if (empty($profile)) {
            return response()->json(['msg'=>'No Data Found!', 'status' =>'0']);
        }

        $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
        if(!empty($profile->profile_image)){
            if(File::exists($destinationPath.'/'.$profile->profile_image)) {
                $file_path = url('storage/app/public/uploads/user/profile/'.$profile->profile_image);
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

        // total likes
        $total_following = Followers::where('follower_id',$profile->id)->count();
        $total_follow   = Followers::where('user_id',$profile->id)->count();

        $total_likess = 0;
        $all_video_data = Videos::where('user_id',$profile->id)->get();
        // echo "<pre>"; print_r($all_video_data); die();
        if (count($all_video_data) > 0) 
        {  
            foreach ($all_video_data as $row) {
                $total_likess += VideoLikes::where('video_id',"=",$row->id)->count();
            }
        }
        else
        {
            $total_likess = 0;
        }

        $total_likes = 0;
        $total_like_this_video = 0;
        $total_comments = 0;
        $all_video_data = Videos::where(['user_id' => $profile->id,'is_view' => 1,'is_save_to_device' => 0])->get();
        // echo "<pre>"; print_r($all_video_data); die();
        if (count($all_video_data) > 0) 
        {  
            foreach ($all_video_data as $row) {

                $total_views = VideoWatchHistory::where('video_id',$row->id)->count();
        		$total_likes += VideoLikes::where('video_id',"=",$row->id)->count();
        		$total_like_this_video = VideoLikes::where('video_id',"=",$row->id)->count();
                $total_comments = VideoComments::where('video_id',"=",$row->id)->where('parent_id',"=","'0'")->count();
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
                // video like or not
                $user_like_data= VideoLikes::where(['user_id' => $request->login_id,'video_id' => $row->id])->first();
                if (!empty($user_like_data)) 
                {
                    $is_video_like = 1;
                }
                else
                {
                    $is_video_like = 0;
                }
                $record_video_file[] = array(
                    "id"                    => $row->id,
                    "cover_image"           => $cover_image,
                    "video_url"             => $video_url,
                    "description"           => $row->description,
                    "is_video_like"         => $is_video_like,
                    "total_comments"        => $total_comments,
                    "total_views"           => $total_views,
                );
                $video_file_data = $record_video_file;
                unset($record_video_comments);
            }
        }
        else
        {
            $video_file_data = [];
        }

        $favorite_data = array();
        // Video Favorite
        $all_favorite_video_data = VideoFavorite::where('user_id',$request->login_id)->get();
        // echo "<pre>"; print_r($all_video_data); die();
        if (count($all_favorite_video_data) > 0) 
        {  
            foreach ($all_favorite_video_data as $row) {

                $total_views = VideoWatchHistory::where('video_id',$row->video_id)->count();
                $videos_data = Videos::where('id',"=",$row->video_id)->first();
                if ($videos_data != '' && $videos_data->is_view == 1) {
                    $total_likes += VideoLikes::where('video_id',"=",$row->id)->count();
                    $total_like_this_video = VideoLikes::where('video_id',"=",$row->id)->count();
                    $total_comments = VideoComments::where('video_id',"=",$row->id)->where('parent_id',"=","'0'")->count();
                    if ($videos_data->cover_image != '') 
                    {
                        $deldestinationPath =  Storage::disk('public')->path('uploads/videos/cover_images');
                        if(File::exists($deldestinationPath.'/'.$videos_data->cover_image)) {
                            $cover_image = url('storage/app/public/uploads/videos/cover_images/'.$videos_data->cover_image);
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
                    if ($videos_data->file_name != '') 
                    {
                       $delddestinationPath =  Storage::disk('public')->path('uploads/videos/videos');
                        if(File::exists($delddestinationPath.'/'.$videos_data->file_name)) {
                            $video_url = url('storage/app/public/uploads/videos/videos/'.$videos_data->file_name);
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
                    $record_video_files[] = array(
                        "id"                    => $row->id,
                        "video_id"              => $row->video_id,
                        "cover_image"           => $cover_image,
                        "video_url"             => $video_url,
                        "total_likes"           => $total_like_this_video,
                        "is_video_like"         => $is_video_like,
                        "total_comments"        => $total_comments,
                        "total_views"           => $total_views,
                    );
                    $favorite_data = $record_video_files;
                }
            }
              
        }

        //private videos
        $private_videos = Videos::where('is_view',3)->where(['user_id' => $profile->id,'is_save_to_device' => 0])->get();
        // echo "<pre>"; print_r($private_videos->toArray()); die();
        if (count($private_videos) > 0) 
        {  
            foreach ($private_videos as $row) {
               
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

                $total_views = VideoWatchHistory::where('video_id',$row->id)->count();
                $total_like_this_video = VideoLikes::where('video_id',"=",$row->id)->count();
                $total_comments = VideoComments::where('video_id',"=",$row->id)->where('parent_id',"=","'0'")->count();
                // video like or not
                $user_like_data= VideoLikes::where(['user_id' => $request->login_id,'video_id' => $row->id])->first();
                if (!empty($user_like_data)) 
                {
                    $is_video_like = 1;
                }
                else
                {
                    $is_video_like = 0;
                }
                $recordp_video_file[] = array(
                    "id"                    => $row->id,
                    "cover_image"           => $cover_image,
                    "video_url"             => $video_url,
                    "total_likes"           => $total_like_this_video,
                    "is_video_like"         => $is_video_like,
                    "total_comments"        => $total_comments,
                    "total_views"           => $total_views,
                );
                $private_videoss = $recordp_video_file;
            }
               
        }
        else
        {
            $private_videoss = [];
        }


        $followers_result = array();
        $followers_data = Followers::where('user_id','=',$request->follower_id)->orderBy(DB::raw('RAND()'))->limit(25)->get();
        // echo "<pre>"; print_r($followers_data->toArray()); die();
        if ($followers_data != '') 
        {   
            foreach ($followers_data as $row) {
                $user_data = User::where('id',$row->follower_id)->first();
                if ($user_data != '') 
                {
                    $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
                    if(!empty($user_data->profile_image)){
                        if(File::exists($destinationPath.'/'.$user_data->profile_image)) {
                            $file_path = url('storage/app/public/uploads/user/profile/'.$user_data->profile_image);
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
                    $followers_record[] = array(
                        "id"                    => $row->id,
                        "user_id"               => $user_data->id,
                        "name"                  => isset($user_data->name) ? $user_data->name : '',
                        "username"              => isset($user_data->username) ? $user_data->username : '',
                        "private_account"       => $user_data->private_account,
                        "profile_image"         => $file_path,
                    );
                    $followers_result = $followers_record;
                }
            }
        }
        else
        {
            $followers_result = array();
        }
        $email = "";
        if ($profile->email != "") 
        {
            $email = $this->hideEmailAddress($profile->email);
        }
        $mobile_no = "";
        if ($profile->mobile_no != "") 
        {
            $mobile_no = substr($profile->mobile_no, 0, 2) . '******' . substr($profile->mobile_no, -2);
        }

        $is_follow   = Followers::where(['user_id' => $request->login_id,'follower_id' => $request->follower_id])->count();
        
        $is_restrictAccount = RestrictAccounts::where('login_id',$request->login_id)->where('user_id',$request->follower_id)->count();

       
        $result_data['user_id']             = $profile->id;
        $result_data['name']                = $profile->name?$profile->name:'';
        $result_data['username']            = $profile->username?$profile->username:'';
        $result_data['email']               = $email;
        $result_data['total_following']     = $total_following;
        $result_data['is_restrict_account'] = $is_restrictAccount;
        $result_data['total_follow']        = $total_follow;
        $result_data['my_video_data']       = $video_file_data;
        $result_data['favorite_video_data'] = $favorite_data;
        $result_data['private_video_data']  = $private_videoss;
        $result_data['followers_result']    = $followers_result;

        return response()->json(['data' => $result_data,'msg'=>'Profile details get successfully!', 'status' =>'1']);
    }

   
    // api for update location
    function update_location(Request $request)
    {
        
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'lat'       => 'required',
            'long'      => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $user_data= User::where('id', $request->user_id)->first();
        if (!empty($user_data)) 
        {   
            $data_update['lat'] = $request->lat;
            $data_update['long'] = $request->long;
            User::where('id',$request->user_id)->update($data_update);

            return response()->json(['msg'=>'Location update successfully.', 'status' =>'1']);
        }
        else
        { 
            return response()->json(['msg'=>'This user not exist our database.!', 'status' =>'0']);
        }
    }

    /**
    * Get User list with search
    */

    public function following_list(Request $request) {

        $result = array();

        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required'
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $followers_data = Followers::where('follower_id',$request->user_id)->get();
        // echo "<pre>"; print_r($followers_data); die();
        if (count($followers_data) > 0) 
        {   
            foreach ($followers_data as $row) {
                $user_data = User::where('id',$row->user_id)->first();
                if ($user_data != '') 
                {
                    $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
                    if(!empty($row->profile_image)){
                        if(File::exists($destinationPath.'/'.$user_data->profile_image)) {
                            $file_path = url('storage/app/public/uploads/user/profile/'.$user_data->profile_image);
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
                    $record[] = array(
                        "id"                    => $row->id,
                        "user_id"               => $user_data->id,
                        "name"                  => $user_data->name,
                        "username"              => $user_data->username,
                        "private_account"       => $user_data->private_account,
                        "profile_image"         => $file_path,
                    );
                    $result = $record;
                }
            }
            return response()->json(['data' => $result,'msg'=>'User Following List Retrive Successfully.', 'status' =>'1']);
        }
        else
        { 
            return response()->json(['msg'=>'No Following List Found.!', 'status' =>'0']);
        }
    }

    /**
    * User Follow List Using Id
    */
    public function follow_list(Request $request) {
        $result = array();

        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required'
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $followers_data = Followers::where(['user_id' => $request->user_id])->get();
        if (count($followers_data) > 0) 
        {   
            foreach ($followers_data as $row) {
                $user_data = User::where('id',$row->follower_id)->first();
                if ($user_data != '') 
                {
                    $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
                    if(!empty($user_data->profile_image)){
                        if(File::exists($destinationPath.'/'.$user_data->profile_image)) {
                            $file_path = url('storage/app/public/uploads/user/profile/'.$user_data->profile_image);
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
                    $record[] = array(
                        "id"                    => $row->id,
                        "user_id"               => $user_data->id,
                        "private_account"       => $user_data->private_account,
                        "profile_image"         => $file_path,
                    );
                    $result = $record;
                }
            }
            return response()->json(['data' => $result,'msg'=>'User Follow List Retrive Successfully.', 'status' =>'1']);
        }
        else
        { 
            return response()->json(['msg'=>'No Follow List Found.!', 'status' =>'0']);
        }
    }

    public function pending_follow_request(Request $request) {
        $result = array();

        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required'
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }

        $followers_data = Followers::where(['user_id' => $request->user_id,'status' => 0])->get();
        if (!empty($followers_data)) 
        {   
            foreach ($followers_data as $row) {
                $user_data = User::where('id',$row->follower_id)->first();
                if ($user_data != '') 
                {
                    $destinationPath =  Storage::disk('public')->path('uploads/user/profile');
                    if(!empty($user_data->profile_image)){
                        if(File::exists($destinationPath.'/'.$user_data->profile_image)) {
                            $file_path = url('storage/app/public/uploads/user/profile/'.$user_data->profile_image);
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
                    $record[] = array(
                        "id"                    => $row->id,
                        "user_id"               => $user_data->id,
                        "name"                  => $user_data->name,
                        "username"              => $user_data->username,
                        "private_account"       => $user_data->private_account,
                        "profile_image"         => $file_path,
                    );
                    $result = $record;
                }
            }
            return response()->json(['data' => $result,'msg'=>'User Follow List Retrive Successfully.', 'status' =>'1']);
        }
        else
        { 
            return response()->json(['msg'=>'No Follow List Found.!', 'status' =>'0']);
        }
    }

    // api for add follow
    function to_follow(Request $request)
    {
        $user_id        = $request->user_id;
        $follower_id       = $request->follower_id;
        
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'follower_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        if ($user_id == $follower_id) {
             return response()->json(['msg'=>'Plase follow another user..', 'status' =>'0']);
        }
        $user_data= User::where('id', $user_id)->first();
        if (!empty($user_data)) 
        {   
            $follower_data= Followers::where(['user_id' => $user_id,'follower_id' => $follower_id])->first();
            if (empty($follower_data)) 
            {
                // notification send video honor
                $follower_user_data = User::where('id', $follower_id)->first();
                if($follower_user_data->device_id<>"")
                { 
                    $notification_id    = rand(0000,9999);
                    $find_reciever_id   = $follower_user_data->device_id;
            
                    $FCMS=array();
                    array_push($FCMS,$find_reciever_id);
                    
                    $title = $user_data->name." send follow request";
                    $message = $user_data->name." send follow request at ".date('d-m-Y h:i A');
                    if($find_reciever_id<>"")
                    {  
                        $img = "";
                        $field = array('registration_ids'  =>array($find_reciever_id),'data'=> array( "message" => $title,"title" => $title,"body" => $message,"content"=>$message,"notification_id"=>$notification_id,"type"=>1,"id"=>$follower_id,"image"=>$img,"sound"=>1,"vibrate"=>1));
                        
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

                        // notificaion add
                        $notification              = new Notification();
                        $notification->user_id     = $user_id;
                        $notification->receiver_id = $follower_id;
                        $notification->type        = 3;
                        $notification->save(); 
                    }
                }

                $followers               = new Followers();
                $followers->user_id      = $user_id;
                $followers->follower_id  = $follower_id;
                $followers->save(); 

                return response()->json(['msg'=>'Follow successfully!.', 'status' =>'1']);
            }
            else
            {
                return response()->json(['msg'=>'This user you have a already follow..!', 'status' =>'0']);
            }
        }
        else
        { 
            return response()->json(['msg'=>'This user not exist our database.!', 'status' =>'0']);
        }
    }

    // api for remove follow
    function to_unfollow(Request $request)
    {
        $user_id        = $request->user_id;
        $follower_id       = $request->follower_id;
        
        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'follower_id'   => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $user_data= User::where('id', $user_id)->first();
        if (!empty($user_data)) 
        {   
            $follower_data= Followers::where(['user_id' => $user_id,'follower_id' => $follower_id])->first();
            if (!empty($follower_data)) 
            {
                Followers::where(['user_id' => $user_id,'follower_id' => $follower_id])->delete();
                return response()->json(['msg'=>'Unfollow successfully!.', 'status' =>'1']);
            }
            else
            {
                return response()->json(['msg'=>'This user you can not follow..!.', 'status' =>'0']);
            }
        }
        else
        { 
            return response()->json(['msg'=>'This user not exist our database.!', 'status' =>'0']);
        }
    }

}
