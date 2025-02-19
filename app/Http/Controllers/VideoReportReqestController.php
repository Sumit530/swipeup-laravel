<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoReport;
use App\Models\VideoReportData;
use App\Models\Videos;
use App\Models\User;
use view;
use Storage;
use File;

class VideoReportReqestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videoreports = VideoReport::leftJoin("videos","video_reports.video_id","=","videos.id")
                    ->leftJoin("users","video_reports.user_id","=","users.id")
                    ->select("videos.description as video_description","users.name as user_name")
                    ->get();

        $data = VideoReport::get();
        // echo "<pre>"; print_r($data); exit;
        if(isset($data))
        {
            foreach ($data as $key => $val) 
            {
                $report_data = VideoReportData::where("report_id",$val->id)->get();
                if(isset($report_data))
                {
                    foreach ($report_data as $key => $row) 
                    {   
                        // song banner image
                        if ($row->file_name != '') 
                        {
                            $destinationPath =  Storage::disk('public')->path('uploads/videos/reports');
                            if(!empty($row->file_name)){
                                if(File::exists($destinationPath.'/'.$row->file_name)) {
                                    $file_name = url('storage/app/public/uploads/videos/reports/'.$row->file_name);
                                }
                                else
                                {
                                    $file_name = "";
                                }
                            }
                            else
                            {
                                $file_name = "";
                            }
                        }
                        else
                        {
                            $file_name = "";
                        }
                        $report_resutlt[] = [
                            'id' => $row->id,
                            'file_name' => $row->file_name,
                            'file_url' => $file_name,
                        ];
                        $attchments = $report_resutlt;
                    }
                }
                else
                {
                    $attchments = array();
                }
                $videos_data = videos::where('id',$val->video_id)->first();
                $users_data = User::where('id',$val->user_id)->first();
                $resutlt[] = [
                    'id' => isset($val->id) ? $val->id : "",
                    'video_description' => isset($videos_data->description) ? $videos_data->description :"",
                    'user_name' => isset($users_data->name) ? $users_data->name :"",
                    'type' => $val->type,
                    'status' => $val->status,
                    'description' => isset($val->description) ? $val->description :"",
                    'attchments' => $attchments,
                ];
                $videoreports = $resutlt;
            }
        }
        else
        {
            $videoreports = [];
        }
        // echo "<pre>"; print_r($videoreports); die();
        return View('video.reports.index',compact('videoreports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
        $report = VideoReport::find($request->id);
        if ($report->status == 1) 
        {
            $report->status = '0';
        }
        else
        {
            $report->status = '1';
        }
        $report->save();

        return redirect()->route('videoreportreqest.index')->with('success', 'Status update successfully.');
    }

    
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $report_data = VideoReportData::where("report_id",$request->id)->get();
        if(isset($report_data))
        {
            foreach ($report_data as $key => $row) 
            {   
                $deldestinationPath =  Storage::disk('public')->path('uploads/videos/reports');
                if(!empty($row) && !empty($row->file_name)){
                    if(File::exists($deldestinationPath.'/'.$row->file_name)) {
                        $delete=File::delete($deldestinationPath.'/'.$row->file_name);
                    }
                }
            VideoReportData::where("report_id",$request->id)->delete();
            }
        }
        VideoReport::find($request->id)->delete();
        return redirect()->route('videoreportreqest.index')->with('success', 'Report delete successfully.');
    }
}
