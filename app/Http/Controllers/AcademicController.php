<?php

namespace App\Http\Controllers;

use App\Academic;
use Illuminate\Http\Request;
use \App\Laravue\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AcademicController extends Controller
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
    public function store(Request $request, $admission_id,$student_id, $academic_id=0)
    {
        //
        $data = json_decode($request->getContent(), true);
        $academicInfo=[];
        
        $academic = new Academic();
        foreach($data as $key=>$value){
            if(is_array($value)){
                if(in_array($key,$academic->fillable))
                    $academicInfo[$key]=json_encode($value);
            } else {
                if(in_array($key,$academic->fillable))
                    $academicInfo[$key]=$value;
            }
        }

        //if($data['ac_id'])
        if($academic_id)
            $academic=Academic::where('ac_id','=', $academic_id)->first();

        if(!$academic->count())
            $academic=Academic::where('student_id','=', $student_id)->first();

        $academicInfo['admission_id']=$admission_id;
        $academicInfo['student_id']=$student_id;
        $academicInfo['user_id']=Auth::user()->id;
        //Log::info("Ac id: ".$academic_id);
        $acds_id='';
        if(!$data['ac_id']){
            $academic=new Academic($academicInfo);
            $academic->save();
            $acds_id=$academic->id;
        } else {
            $academic->where('ac_id','=',$data['ac_id'])->update($academicInfo);
            $acds_id=$data['ac_id'];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Academic  $academic
     * @return \Illuminate\Http\Response
     */
    public function show(Academic $academic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Academic  $academic
     * @return \Illuminate\Http\Response
     */
    public function edit(Academic $academic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Academic  $academic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Academic $academic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Academic  $academic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Academic $academic)
    {
        //
    }
}
