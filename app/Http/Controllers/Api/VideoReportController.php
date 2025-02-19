<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Videos;
use App\Models\VideoReport;
use App\Models\VideoReportData;
use App\Models\VideoReportType;
use Illuminate\Http\Request;
use Validator;
use Storage;
use File;

class VideoReportController extends Controller
{
    // api for add video report
    public function add_video_report(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); die();
        $user_id        = $request->user_id;
        $video_id       = $request->video_id;
        $type           = $request->type;
        $description    = $request->description;

        $validator = Validator::make($request->all(), [ 
            'user_id'   => 'required',
            'type'    => 'required',
        ]);

        if ($validator->fails())
        { 
            $message = $validator->errors()->first();
            return response()->json(['data' => [],'msg'=>$message, 'status' =>'0']);            
        }
        
        $user_data= User::where('id', $user_id)->first();
        if (!empty($user_data)) 
        {   
           
            $videoreport               = new VideoReport();
            $videoreport->user_id      = $user_id;
            $videoreport->video_id     = isset($request->video_id) ? $request->video_id : '';
            $videoreport->type         = $type;
            $videoreport->description  = $description?$description:'';
            $videoreport->save(); 

            $report_id = $videoreport->id;

            if ($report_id != '') {
                // video file
                $report_files = $request->file('report_files');
                $destinationPath =  Storage::disk('public')->path('uploads/videos/reports');
                if(!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath,0777, true, true);
                }
                if ($report_files != '') {
                    foreach($report_files as $k => $image) {

                        $fileName   = time()."_".rand(11111,99999).".".$report_files[$k]->getClientOriginalExtension();
                        $upload_success = $image->move($destinationPath, $fileName);
                        
                        $videoreportdata                  = new VideoReportData();
                        $videoreportdata->report_id       = $report_id;
                        $videoreportdata->file_name       = isset($fileName) ? $fileName :'';
                        $videoreportdata->save();
                    }
                }
                return response()->json(['msg'=>'Video report add successfully!', 'status' =>'1']);
            }
            return response()->json(['msg'=>'Video report add error', 'status' =>'0']);
        }
        else
        { 
            return response()->json(['msg'=>'This user not exist our database.!', 'status' =>'0']);
        }
    }

     // api for video list
    public function get_video_report_types(Request $request) {

        $VideoReportType = VideoReportType::get();
        if (count($VideoReportType) > 0) 
        {  
            foreach ($VideoReportType as $row) {
                $record[] = array(
                    "id"                    => $row->id,
                    "title"                 => $row->name,
                    "description"           => $row->description,
                );
                $result = $record;
            }
            return response()->json(['data' => $result,'msg'=>'Video report types get successfully.', 'status' =>'1']);
        }
        else
        {
            return response()->json(['msg'=>'No data found.!', 'status' =>'0']);
        } 
    }
}
