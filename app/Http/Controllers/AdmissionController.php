<?php

namespace App\Http\Controllers;

use App\Admission; 
use Illuminate\Http\Request;
use \App\Laravue\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $student_id)
    {
        //
        $data = json_decode($request->getContent(), true);
        $admissionInfo=[];

        $admission=Admission::where('student_id','=', $student_id)
        ->where('session','=',$data['session'])->first();

        $admissionInfo['student_id']=$student_id;
        $admissionInfo['user_id']=$data['userId'];//Auth::user()->id;
        $admsn_id=[];
        if(!$admission){
            $admission=new Admission();
            foreach($data as $key=>$value){
                if(in_array($key,$admission->fillable)){
                    if(is_array($value)){ 
                        $admissionInfo[$key]=json_encode($value);
                    } else {
                        $admissionInfo[$key]=$value;
                    }
                }
            }
            $admissionInfo['admsStatus']='active';
            
            $admission = new Admission($admissionInfo);
            $admission->save();
            $admsn_id['id']=$admission->id; 

            $prevSess=explode('-',$data['session']);
            $prevSess=($prevSess[0]-1).'-'.($prevSess[1]-1);
            $admission->where('student_id','=',$admissionInfo['student_id'])
                ->where('session',$prevSess)
                ->update(['admsStatus'=>'inactive']);
            $admsn_id['msg']='Record inserted/promoted to a new session.';
        } else {
            foreach($data as $key=>$value){
                if(in_array($key,$admission->fillable)){
                    if(is_array($value)){ 
                        $admissionInfo[$key]=json_encode($value);
                    } else {
                        $admissionInfo[$key]=$value;
                    }
                }
            }
            $remarks=[
                "Was Allowed/Promised Promotion to Upper Class OR",
                "Passed the Examination in Highest Class Available in School OR",
                "Left the school mid-session to join a different school",
                "Failed in Subject/Examination"
            ];
            //if(in_array($data['slcRemarks'],$remarks)){
            if(strlen($data['slcRemarks'])>10){
                $admissionInfo['admsStatus']='Inactive';
                $admission->where('ad_id','=',$admission->ad_id)->update($admissionInfo);
                $admsn_id['id']=$admission->ad_id;
                $admsn_id['msg']='Record is updated successfully';
            } else {
                $admission->where('ad_id','=',$admission->ad_id)->update($admissionInfo);
                $admsn_id['id']=$admission->ad_id;
                $admsn_id['msg']='Record is updated successfully';
            }
        }
        return $admsn_id;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admission  $admission
     * @return \Illuminate\Http\Response
     */
    public function show(Admission $admission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admission  $admission
     * @return \Illuminate\Http\Response
     */
    public function edit(Admission $admission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admission  $admission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admission $admission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admission  $admission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admission $admission)
    {
        //
    }
}
