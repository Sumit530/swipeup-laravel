<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\BannerImages;
use App\Models\User;
use App\Models\AccountVerification;
use Validator;
use Storage;
use File;

class AccountVerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $account_verifications = AccountVerification::get();
        return View('account_verifications.index',compact('account_verifications'));
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
        $AccountVerification = AccountVerification::find($request->id);
        if ($AccountVerification->status == 1) 
        {
            $AccountVerification->status = '0';
        }
        else
        {
            $AccountVerification->status = '1';
        }
        $AccountVerification->save();

        return redirect()->route('account_verifications.index')->with('success', 'Status update successfully.');
    }

    
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        // $AccountVerification                  = AccountVerification::find($request->id); 
        // delete old file
        // $deldestinationPath =  Storage::disk('public')->path('uploads/account/verification');
        // if(!empty($AccountVerification) && !empty($AccountVerification->document)){
        //     if(File::exists($deldestinationPath.'/'.$AccountVerification->document)) {
        //         $delete=File::delete($deldestinationPath.'/'.$AccountVerification->document);
        //     }
        // }
        AccountVerification::find($request->id)->delete();
        return redirect()->route('account_verifications.index')->with('success', 'Account verifications delete successfully.');
    }
}
