<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\User;
use App\Models\AccountCategory;
use Validator;
use DB; 

class GeneralController extends Controller
{
    public function getcountries()
    {
        $countrie = Country::all();
        if(!empty($countrie)) {
            foreach ($countrie as $row) {
                $record[] = array(
                    "id"                    => $row->id,
                    "name"                  => $row->name,
                );
                $result = $record;
            }
            return response()->json(['data' => $result,'msg'=>'Country Get Successfully.', 'status' =>'1']);
        }
        return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
    }

    public function getaccountcategory()
    {
        $account_category = AccountCategory::all();
        if(!empty($account_category)) {
            foreach ($account_category as $row) {
                $record[] = array(
                    "id"                    => $row->id,
                    "name"                  => $row->name,
                );
                $result = $record;
            }
            return response()->json(['data' => $result,'msg'=>'Account category Get Successfully.', 'status' =>'1']);
        }
        return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
    } 

    public function distance()
    {
        $latitude = "23.1309312";
        $longitude = "72.531968";
        $radius = 2500;
        $users = User::select(
                'id',
                'name',
                'username',
                'email',
                'gender',
                DB::raw("3959 * 1.609344 * acos(cos(radians(" . $latitude . ")) 
                * cos(radians(users.lat)) 
                * cos(radians(users.long) - radians(" . $longitude . ")) 
                + sin(radians(" . $latitude . ")) 
                * sin(radians(users.lat))) AS distance")
            );
         $users =  $users->whereNotNull("lat");
         $users =  $users->whereNotNull("long");
         $users =  $users->having("distance", "<=", $radius);
         $users =  $users->get();
         echo "<pre>"; print_r($users->toArray()); die();
    }
}
