<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\VideoReportType;

class VideoReportTypeController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videoreporttype = VideoReportType::query()->whereNull('deleted_at')->get();
        return View('videoreporttype.index',compact('videoreporttype'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('videoreporttype.create');
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
            'name' => 'required|unique:video_report_types',
        ]);

        $videoreporttype = new VideoReportType();
        $videoreporttype->name = $request->name;
        $videoreporttype->description = isset($request->description) ? $request->description : '';
        $videoreporttype->save();
        
        return redirect()->route('videoreporttype.index')->with('success', 'New video report type added successfully.');
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
        $videoreporttype = VideoReportType::find($id); 
        if(!empty($videoreporttype)) 
        {
            return View('videoreporttype.edit',compact('videoreporttype'));
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
        $this->validate($request, [
            'name' => 'required|unique:video_report_types,name,'.$id,
        ]);

        $videoreporttype = VideoReportType::find($id); 
        $videoreporttype->name = $request->name;
        $videoreporttype->description = isset($request->description) ? $request->description : '';
        $videoreporttype->save();
        
        return redirect()->route('videoreporttype.index')->with('success', 'Video report type details update successfully.');
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
        $videoreporttype = VideoReportType::find($request->id);
        if ($videoreporttype->status == 1) 
        {
            $videoreporttype->status = '0';
        }
        else
        {
            $videoreporttype->status = '1';
        }
        $videoreporttype->save();

        return redirect()->route('videoreporttype.index')->with('success', 'Status update successfully.');
    }

    
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        VideoReportType::find($request->id)->delete();
        return redirect()->route('videoreporttype.index')->with('success', 'Video report type delete successfully.');
    }
}
