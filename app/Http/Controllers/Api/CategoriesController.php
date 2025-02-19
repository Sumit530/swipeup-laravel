<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Validator;

class CategoriesController extends Controller
{
    public function getcategories()
    {
        $categories = Categories::all();
        if(!empty($categories)) {
            foreach ($categories as $row) {
                $record[] = array(
                    "id"                    => $row->id,
                    "name"                  => $row->name,
                );
                $result = $record;
            }
            return response()->json(['data' => $result,'msg'=>'Categories Get Successfully.', 'status' =>'1']);
        }
        return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
    }
}
