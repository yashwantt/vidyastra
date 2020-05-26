<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Laravue\JsonResponse;
use \App\Student;
use \App\Enquiries;
use Illuminate\Support\Facades\Auth;
use \App\Admission;
//use \App\Academic;
use \App\Attendance;
use DB;
use \App\Model\User;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $params=$request->all();
        $status=isset($params['status']) && strlen($params['status'])?$params['status']:'active';

        $admissions=Admission::where('session','=', $params['session'])->get();//$params['atDate']
        //Log::info("sess now ".$setSess); echo $setSess;
        $students=Student::where('status','=',$status)->get();
    
        /*if($admissions->count() < $students->count()){
            $prevSess=explode('-',$params['session']);
            $prevSess=($prevSess[0]-1).'-'.($prevSess[1]-1);
            //Log::info($prevSess);
            $admissions=Admission::where('session','like',$prevSess)->get();
            $admissionInfo=array();
            foreach($admissions as $student){
                //Log::info("here ".$admissions->count());
                $recExists=Admission::where('session','like',$params['session'])->where('student_id','=',$student->student_id)->first();
                if($recExists) continue;
                $admission=new Admission();
                $data=json_decode($student,true);
                //Log::info($data);
                foreach($data as $key=>$value){
                    if(in_array($key,$admission->fillable)){
                        $admissionInfo[$key]=$value;
                    }
                }

                $admissionInfo['doa']=date('Y-m-d');
                $admissionInfo['session']=$params['session'];
                
                $admissionInfo['user_id']=$params['userId'];
                //Log::info($admissionInfo);
                $admission=new Admission($admissionInfo);
                $admission->save();
            }     
        }*/
        $student=Student::leftJoin('admissions','admissions.student_id','=','students.st_id');
       
        $studentTotal=Student::leftJoin('admissions','admissions.student_id','=','students.st_id');
           
        $studentP=Student::leftJoin('admissions','admissions.student_id','=','students.st_id')
            ->leftJoin('attendances','attendances.student_id','=','students.st_id');
        
        $studentA=Student::leftJoin('admissions','admissions.student_id','=','students.st_id')
            ->leftJoin('attendances','attendances.student_id','=','students.st_id');

        $studentL=Student::leftJoin('admissions','admissions.student_id','=','students.st_id')
            ->leftJoin('attendances','attendances.student_id','=','students.st_id');

        if(isset($params['keyword']) && isset($params['search']) && strlen($params['keyword']) >= 1 && strlen($params['search']) >= 2){
            $keywd=explode(" | ",$params['keyword']);
            if(!isset($keywd[1])){
                $keywd=explode(" ",$params['keyword']);
            }
            $srch=explode(" | ",$params['search']);
            if(!isset($srch[1])){
                $srch=explode(" ",$params['search']);
            }

            if(isset($keywd[0]) && isset($srch[0])  && strlen(trim($keywd[0]))){ 
                $student=$student->where(strtolower($srch[0]),'like', '%'.strtolower($keywd[0]).'%');
                
                $studentP=$studentP->where('atDate','=', $params['atDate']);
                $studentA=$studentA->where('atDate','=', $params['atDate']);
                $studentL=$studentL->where('atDate','=', $params['atDate']);

                if(isset($keywd[1]) && isset($srch[1]) && strlen(trim($keywd[1]))){               
                    $student=$student->where(strtolower($srch[1]),'like', '%'.strtolower($keywd[1]).'%');
                    $studentTotal=$studentTotal->where(strtolower($srch[1]),'like', '%'.strtolower($keywd[1]).'%');

                    $studentP=$studentP->where(strtolower($srch[1]),'like', '%'.strtolower($keywd[1]).'%');
                    $studentA=$studentA->where(strtolower($srch[1]),'like', '%'.strtolower($keywd[1]).'%');
                    $studentL=$studentL->where(strtolower($srch[1]),'like', '%'.strtolower($keywd[1]).'%');
                }
                if(isset($keywd[2]) && isset($srch[2])  && strlen(trim($keywd[2]))){               
                    $student=$student->where('subjectSections','like', '%'.strtolower($keywd[1]." | ".$keywd[2]).'%');
                    $studentTotal=$studentTotal->where('subjectSections','like', '%'.strtolower($keywd[1]." | ".$keywd[2]).'%');

                    $studentP=$studentP->where('subjectSections','like', '%'.strtolower($keywd[1]." | ".$keywd[2]).'%');
                    $studentA=$studentA->where('subjectSections','like', '%'.strtolower($keywd[1]." | ".$keywd[2]).'%');
                    $studentL=$studentL->where('subjectSections','like', '%'.strtolower($keywd[1]." | ".$keywd[2]).'%');
                }
            } else if(isset($params['keyword']) && isset($params['search']) && is_numeric($params['keyword'])){              
                $srch=explode(" | ",$params['search']);
                $student=$student->where(strtolower($srch[0]),'=', strtolower($params['keyword']));

                if($params['limit']){
                    $student=$student->skip(($params['page']-1)*$params['limit'])->take($params['limit']);
                }

                $studentP=$studentP->where('atDate','=', $params['atDate']);
                $studentA=$studentA->where('atDate','=', $params['atDate']);
                $studentL=$studentL->where('atDate','=', $params['atDate']);
            } else {
                $srch=explode(" | ",$params['search']);
                $student=$student->where(strtolower($srch[0]),'like', '%'.strtolower($params['keyword']).'%');
                
                if($params['limit']){
                    $student=$student->skip(($params['page']-1)*$params['limit'])->take($params['limit']);
                }

                $studentP=$studentP->where('atDate','=', $params['atDate']);
                $studentA=$studentA->where('atDate','=', $params['atDate']);
                $studentL=$studentL->where('atDate','=', $params['atDate']);
            }
        } else {
            
            if($params['limit']){
                $student=$student->skip(($params['page']-1)*$params['limit'])->take($params['limit']);
            }

            $studentP=$studentP->where('atDate','=', $params['atDate']);
            $studentA=$studentA->where('atDate','=', $params['atDate']);
            $studentL=$studentL->where('atDate','=', $params['atDate']);
        }

        $student=$student->where('status','=',$status)
            ->where('session','like', $params['session'])->get();
        $studentTotal=$studentTotal->where('status','=',$status)
            ->where('session','like', $params['session'])->get(); 
        
        $studentP=$studentP->where('status','=',$status)
            ->where('session','like', $params['session'])->where('attendance','like','%"'.$params['atSession'].'":"P"%')->get();
        $studentA=$studentA->where('session','like', $params['session'])
            ->where('status','=',$status)->where('attendance','like','%"'.$params['atSession'].'":"A"%')->get();
        $studentL=$studentL->where('session','like', $params['session'])
            ->where('status','=',$status)->where('attendance','like','%"'.$params['atSession'].'":"L"%')->get();
        
        $sessions=Admission::distinct()->get(['session']);
        return response()->json(new JsonResponse(['records' => $student, 'total' =>$studentTotal->count(),'present'=>$studentP->count(),'absent'=>$studentA->count(),'leave'=>$studentL->count(),'sessions'=>$sessions]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return response()->json(['success'=>'Welcome'],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $studentInfo=[];
        $status=isset($data['status']) && strlen($data['status'])?$data['status']:'active';
        $count=1;
        
        if($request->get('image') && stristr($request->get('image'),"data:image/jpeg")){
            //Log::info("i am here");
            $image = $request->get('image');
            $name = $data['fullName'].'-'.$data['uid'].'-'.date('Ymd-His-').$count++.'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($request->get('image'))->save(public_path('images/students/').$name);
            $studentInfo['image']='/images/students/'.$name;
        }
        if($request->get('images')){
            $imgs_arr = $request->get('images');
            
            foreach($imgs_arr as $value){
                if(stristr($value,"data:image/jpeg")){
                    $name = $data['fullName'].'-'.$data['uid'].'-'.date('Ymd-His-').$count++.'.' . explode('/', explode(':', substr($value, 0, strpos($value, ';')))[1])[1];
                    \Image::make($value)->save(public_path('images/attachments/').$name);
                    $studentInfo['attachments'][]='/images/attachments/'.$name;
                } else {
                    $studentInfo['attachments'][]=$value;
                }
            }
        }
        
        if($request->get('unlinkImgs')){
            $imgs_arr = $request->get('unlinkImgs');
            $count=1;
            foreach($imgs_arr as $value){
                if(file_exists(public_path($value)))
                    unlink(public_path($value));
            }
        }
        $student=Student::where('status','=',$status)
            ->where('uid','=', $data['uid'])->first();
        //Log::info($student);
        $studentInfo['user_id']=$data['userId'];//Auth::user()->id?Auth::user()->id:$data['userId'];
        
        $admission=new AdmissionController();
        //$academic=new AcademicController();
        
        if(!$student){
            $student=new Student();
            foreach($data as $key=>$value){
                if($key=="image") continue;
                if(in_array($key,$student->fillable)){
                    if(is_array($value)){
                        $studentInfo[$key]=json_encode($value);
                    } else {
                        $studentInfo[$key]=$value;
                    }
                }
            } 
            $student=new Student($studentInfo);
            //Log::info($studentInfo);
            $student->save();
            $admission_id=$admission->store($request, $student->id);
            //Log::info("From save");
            //$academic->store($request, $admission_id, $student->id);
            $studentInfo['st_id']=$student->id;
            $studentInfo['ad_id']=$admission_id['id'];
            $studentInfo['msg']="Student record inserted successfully. ".$admission_id['msg'];
        } else { 
            foreach($data as $key=>$value){
                if($key=="image") continue;
                if(in_array($key,$student->fillable)){
                    if(is_array($value)){
                        $studentInfo[$key]=json_encode($value);
                    } else {
                        $studentInfo[$key]=$value;
                    }
                }
            }  
            //Log::info("From update");
            $admission_id=$admission->store($request, $student->st_id);
            Log::info($studentInfo);
            $student->where('st_id','=',$student->st_id)->update($studentInfo);
            
            //$academic->store($request, $data['ad_id'], $data['st_id'], $data['ac_id']);
            $studentInfo['st_id']=$student->st_id;
            $studentInfo['ad_id']=$admission_id['id'];
            $studentInfo['msg']="Student record already exists. ".$admission_id['msg'];
        }
        
        //Log::info($studentInfo);
        return response()->json(new JsonResponse($studentInfo));
    }
    public function enquiry(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $data = $data['data'];
        
        //Log::info($data);

        $enquiryInfo=[];
        
        $count=1;
        
        if($request->get('image') && stristr($request->get('image'),"data:image/jpeg")){
            //Log::info("i am here");
            $image = $request->get('image');
            $name = $data['fullName'].'-'.$data['uid'].'-'.date('Ymd-His-').$count++.'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($request->get('image'))->save(public_path('images/enquiries/').$name);
            $enquiryInfo['image']='/images/enquiries/'.$name;
        }
        if($request->get('images')){
            $imgs_arr = $request->get('images');
            
            foreach($imgs_arr as $value){
                if(stristr($value,"data:image/jpeg")){
                    $name = $data['fullName'].'-'.$data['uid'].'-'.date('Ymd-His-').$count++.'.' . explode('/', explode(':', substr($value, 0, strpos($value, ';')))[1])[1];
                    \Image::make($value)->save(public_path('images/attachments/').$name);
                    $enquiryInfo['attachments'][]='/images/attachments/'.$name;
                } else {
                    $enquiryInfo['attachments'][]=$value;
                }
            }
        }
        
        if($request->get('unlinkImgs')){
            $imgs_arr = $request->get('unlinkImgs');
            $count=1;
            foreach($imgs_arr as $value){
                if(file_exists(public_path($value)))
                    unlink(public_path($value));
            }
        }
        $enquiry=Enquiries::where('uid','=', $data['uid'])->first();
        
        if(!$enquiry){
            $enquiry=new Enquiries();
            foreach($data as $key=>$value){
                if($key=="image") continue;
                if(in_array($key,$enquiry->fillable)){
                    if(is_array($value)){
                        
                        $enquiryInfo[$key]=json_encode($value);
                    } else {
                        $enquiryInfo[$key]=$value;
                    }
                }
            } 
        
            $enquiry=new Enquiries($enquiryInfo);
            
            $enquiry->save();
            $enquiryInfo['msg']="Enquiry record inserted successfully. School will contact you soon.";
            
        } else { 
            /*foreach($data as $key=>$value){
                if($key=="image") continue;
                if(in_array($key,$enquiry->fillable)){
                    if(is_array($value)){
                        $enquiryInfo[$key]=json_encode($value);
                    } else {
                        $enquiryInfo[$key]=$value;
                    }
                }
            } 
            
            $enquiry->where('uid','=',$data['uid'])->update($enquiryInfo);*/
            
            $enquiryInfo['msg']="Enquiry record already exists. (Name: $enquiry->fullName, S/D/W/o: $enquiry->father, UID: $enquiry->uid)";
        }
        return response()->json(new JsonResponse(['msg'=>$enquiryInfo['msg']]));
    }
    public function enquiries(Request $request, $id=0){
        $enquiry=new Enquiries();
        $enquiryTotal=new Enquiries();
        $sessions=Enquiries::distinct()->get(['session']);
        if($id){
            $enquiry=Enquiries::where('st_id',$id)->first();
        } else {
                $params=$request->all();

                if(isset($params['keyword']) && isset($params['search']) && strlen($params['keyword']) >= 1 && strlen($params['search']) >= 2){
                    $keywd=explode(" | ",$params['keyword']);
                    if(!isset($keywd[1])){
                        $keywd=explode(" ",$params['keyword']);
                    }
                    $srch=explode(" | ",$params['search']);
                    if(!isset($srch[1])){
                        $srch=explode(" ",$params['search']);
                    }
        
                    if(isset($keywd[0]) && isset($srch[0])  && strlen(trim($keywd[0]))){ 
                        $enquiry=$enquiry->where(strtolower($srch[0]),'like', '%'.strtolower($keywd[0]).'%');
        
                        if(isset($keywd[1]) && isset($srch[1]) && strlen(trim($keywd[1]))){               
                            $enquiry=$enquiry->where(strtolower($srch[1]),'like', '%'.strtolower($keywd[1]).'%');
                            $studTotal=$enquiryTotal->where(strtolower($srch[1]),'like', '%'.strtolower($keywd[1]).'%');
                        }
                        if(isset($keywd[2]) && isset($srch[2])  && strlen(trim($keywd[2]))){               
                            $enquiry=$enquiry->where('subjectSections','like', '%'.strtolower($keywd[1]." | ".$keywd[2]).'%');
                            $enquiryTotal=$enquiryTotal->where('subjectSections','like', '%'.strtolower($keywd[1]." | ".$keywd[2]).'%');
                        }
                    } else if(isset($params['keyword']) && isset($params['search']) && is_numeric($params['keyword'])){              
                        $srch=explode(" | ",$params['search']);
                        $enquiry=$enquiry->where(strtolower($srch[0]),'=', strtolower($params['keyword']));
        
                        if(isset($params['limit'])){
                            $enquiry=$enquiry->skip(($params['page']-1)*$params['limit'])->take($params['limit']);
                        }
                    } else {
                        $srch=explode(" | ",$params['search']);
                        $enquiry=$enquiry->where(strtolower($srch[0]),'like', '%'.strtolower($params['keyword']).'%');
                        
                        if(isset($params['limit'])){
                            $enquiry=$enquiry->skip(($params['page']-1)*$params['limit'])->take($params['limit']);
                        }
                    }
                    if(isset($params['session'])){
                        $enquiry=$enquiry->where('session','like','%'.$params['session'].'%');
                        $enquiryTotal=$enquiryTotal->where('session','%'.$params['session'].'%'); 
                    }
                    $enquiry=$enquiry->orderBy('st_id')->get();
                    $enquiryTotal=$enquiryTotal->orderBy('st_id')->get(); 
                } else {
                    $enquiry=Enquiries::all();
                    $enquiryTotal=Enquiries::all();

                    if(isset($params['session'])){
                        $enquiry=Enquiries::where('session',$params['session'])->get();
                        $enquiryTotal=Enquiries::where('session',$params['session'])->get(); 
                    }
                }
        }
        return response()->json(new JsonResponse(['records' => $enquiry, 'total' =>$enquiryTotal->count(),'sessions'=>$sessions]));
    }
    public function marked(Request $request, $id, $smapkey, $smsnid,$schlnm){
        $data = json_decode($request->getContent(), true);
        //Log::info($data);
        $attendanceInfo=[];
        $attendance=new Attendance();
        $attArr=array('P'=>'Present','A'=>'Absent','L'=>'Leave','O'=>'Other');
        foreach($data as $key=>$value){
            if($id==0 || $id==1){
                $attendanceInfo=["attendance"=>[$value[1]]];
                //$attendanceInfo+=["notify"=>$value[5]];
            }
            if($id==2)
                $attendanceInfo=["notify"=>$value[5]];

            $attendance->where('at_id','=',$value[0])->update($attendanceInfo);

            $Mobile_No=isset($value[2])?$value[2]:"";
            $attend=$value[1][$value[3]];
            $name=$value[4];
            $session=$value[3];
            
            //Log::info($Mobile_No." ".$attend." ".$name." ".$session);
            //Log::info($id); 

            //$api_key = $smapkey;//'35CE943FB9994B';
            //Log::info("Api Key: ".$smapkey);
            
            //$from = $smsnid;//'GGSSBT';
            //Log::info("Sender Id: ".$smsnid);
            //Log::info("Sender Id: ".$schlnm);

            if($id==1 && strlen($Mobile_No) && ($attend=='A' || $attend=='L')){
                $contacts = '9459022104, 9418456807';//$Mobile_No;    
                if($attend=='A'){
                    $sms_text = urlencode("Dear Parent, $name is $attArr[$attend] today in $session session. ".$schlnm);
                } else {
                    $sms_text = urlencode("Dear Parent, $name is on $attArr[$attend] today in $session session. ".$schlnm);
                }
                
                //Submit to server
                
                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL, "http://sms.srvc.in/app/smsapi/index.php");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "key=".$smapkey."&campaign=0&routeid=7&type=text&contacts=".$contacts."&senderid=".$smsnid."&msg=".$sms_text);
                $response = curl_exec($ch);
                curl_close($ch);                
            }
        }
        
        return response()->json(new JsonResponse($attendanceInfo));
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
        $udata=DB::table('users')->select('alloted','allotedOptions')->where('id','=',$id)->get();
        
        return response()->json(new JsonResponse($udata));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id, $ad_id=0)
    { 
        $params=$request->all();
        $status=isset($params['status']) && strlen($params['status'])?$params['status']:'active';
        $student=Student::leftJoin('admissions','admissions.student_id','=','students.st_id')
            // ->leftJoin('academics','academics.student_id','=','students.st_id')
             //->leftJoin('attendances','attendances.student_id','=','students.st_id')
             
             ->where('session','like', $params['session'])
             ->where('students.st_id','=',$id)
             ->where('admissions.ad_id','=',$ad_id)
             ->get();
             $divds=explode("-",$params['session']);
             $newss=($divds[0]-1).'-'.($divds[1]-1);
             //Log::info("newss: ".$newss.$student);
        if(!$student->count()){
            
            $student=Student::leftJoin('admissions','admissions.student_id','=','students.st_id')
                
                // ->leftJoin('academics','academics.student_id','=','students.st_id')
                //->leftJoin('attendances','attendances.student_id','=','students.st_id')
                ->where('session','like', $newss)
                ->where('students.st_id','=',$id)
                ->where('admissions.ad_id','=',$ad_id)
                ->get();
        }
        if($params['fetch']=='true'){
            $attendance=Attendance::where('student_id','=',$id)
                ->where('admission_id','=',$ad_id)
                ->get();
            return response()->json(new JsonResponse([$student,$attendance]));
        } else {  
            return response()->json(new JsonResponse([$student,[]]));
        }
    }
    public function cronJob($params=[]){
        $status=isset($params['status']) && strlen($params['status'])?$params['status']:'active';
        $attendanceInfo=[]; $atDate=date('Y-m-d'); //isset($params['atDate'])?$params['atDate']:
        $setSess=date('m')>=2?date('Y').'-'.(date('Y')+1):(date('Y')-1).'-'.date('Y');
        $attendance=Attendance::where('atDate','=', $atDate)->get();//$params['atDate']
        //Log::info("sess now ".$setSess); echo $setSess;
        $students=Student::select('st_id', 'ad_id') 
                ->leftJoin('admissions','admissions.student_id','=','students.st_id')
                ->where('session','like',$setSess);

        if(isset($params['class'])){
            $students=$students->where('class','like','%'.$params['class'].'%');
            //Log::info($params['class']);
        }
        if(isset($params['subject'])){
            $students=$students->where('subject','like','%'.$params['subject'].'%');
            //Log::info($params['subject']);
        }
        else if(isset($params['section'])){
            $students=$students->where('section','like',$params['section']);
            Log::info($params['section']);
        }
        if(isset($params['subjectSections'])){
            $students=$students->where('subjectSections','like','%'.$params['subjectSections'].'%');
            //Log::info($params['subjectSections']);
        }
        $students=$students->where('status','=',$status)->get();
        //Log::info($students);
        //if(sizeof($attendance) < sizeof($students)){
        if($attendance->count() < $students->count()){
                
                foreach($students as $student){
                    $attendanceInfo['atDate']=$atDate;
                    $attendanceInfo['attendance']='['.json_encode(["Morning"=>"O","Evening"=>"O","SubjectWise"=>[]]).']';
                    $attendanceInfo['notify']='[]';
                    $attendanceInfo['admission_id']=$student->ad_id;
                    $attendanceInfo['student_id']=$student->st_id;
                    $attendanceInfo['user_id']=1;
                    
                    $attendance=new Attendance($attendanceInfo);
                    $attendance->save();
                }     
        }
    }
    public function attendance(Request $request)
    {
        //
        $params=$request->all();
        $status=isset($params['status']) && strlen($params['status'])?$params['status']:'active';
        $student=Student::leftJoin('admissions','admissions.student_id','=','students.st_id')
            ->leftJoin('attendances','attendances.student_id','=','students.st_id')
            
            ->where('session','like', $params['session']);

        if(!$student->count()){
                $divds=explode("-",$params['session']);
                $newss=($divds[0]-1).'-'.($divds[1]-1);
                $student=Student::leftJoin('admissions','admissions.student_id','=','students.st_id')
                ->leftJoin('attendances','attendances.student_id','=','students.st_id')
                
                ->where('session','like', $newss);
            }
        $studentTotal=Student::leftJoin('admissions','admissions.student_id','=','students.st_id')
           
            ->where('session','like', $params['session']);
            
        if(!$studentTotal->count()){
            $divds=explode("-",$params['session']);
            $newss=($divds[0]-1).'-'.($divds[1]-1);

            $studentTotal=Student::leftJoin('admissions','admissions.student_id','=','students.st_id')
                
                ->where('session','like', $newss);
        }
        $studentP=Student::leftJoin('admissions','admissions.student_id','=','students.st_id')
            //->leftJoin('academics','academics.student_id','=','students.st_id')
            ->leftJoin('attendances','attendances.student_id','=','students.st_id')
           
            ->where('session','like', $params['session']);
            //->where('atDate','=', $params['atDate']);
        
        $studentA=Student::leftJoin('admissions','admissions.student_id','=','students.st_id')
            //->leftJoin('academics','academics.student_id','=','students.st_id')
            ->leftJoin('attendances','attendances.student_id','=','students.st_id')
            
            ->where('session','like', $params['session']);
            //->where('atDate','=', $params['atDate']);

        $studentL=Student::leftJoin('admissions','admissions.student_id','=','students.st_id')
            //->leftJoin('academics','academics.student_id','=','students.st_id')
            ->leftJoin('attendances','attendances.student_id','=','students.st_id')
            
            ->where('session','like', $params['session']);
           // ->where('atDate','=', $params['atDate']);

        if(isset($params['keyword']) && isset($params['search']) && strlen($params['keyword']) >= 1 && strlen($params['search']) >= 2){
            $keywd=explode(" | ",$params['keyword']);
            if(!isset($keywd[1])){
                $keywd=explode(" ",$params['keyword']);
            }
            $srch=explode(" | ",$params['search']);
            if(!isset($srch[1])){
                $srch=explode(" ",$params['search']);
            }

            if(isset($keywd[0]) && isset($srch[0])  && strlen(trim($keywd[0]))){ 
                $student=$student->where('atDate','=', $params['atDate'])
                    ->where(strtolower($srch[0]),'like', '%'.strtolower($keywd[0]).'%');
            
                //$studentTotal=$studentTotal->where(strtolower($srch[0]),'like', '%'.strtolower($keywd[0]).'%')
                  //  ->where('atDate','=', $params['atDate']);
                
                $studentP=$studentP->where('atDate','=', $params['atDate']);
                $studentA=$studentA->where('atDate','=', $params['atDate']);
                $studentL=$studentL->where('atDate','=', $params['atDate']);

                if(isset($keywd[1]) && isset($srch[1]) && strlen(trim($keywd[1]))){               
                    $student=$student->where(strtolower($srch[1]),'like', '%'.strtolower($keywd[1]).'%');
                    $studentTotal=$studentTotal->where(strtolower($srch[1]),'like', '%'.strtolower($keywd[1]).'%');

                    $studentP=$studentP->where(strtolower($srch[1]),'like', '%'.strtolower($keywd[1]).'%');
                    $studentA=$studentA->where(strtolower($srch[1]),'like', '%'.strtolower($keywd[1]).'%');
                    $studentL=$studentL->where(strtolower($srch[1]),'like', '%'.strtolower($keywd[1]).'%');
                }
                if(isset($keywd[2]) && isset($srch[2])  && strlen(trim($keywd[2]))){               
                    $student=$student->where('subjectSections','like', '%'.strtolower($keywd[1]." | ".$keywd[2]).'%');
                    $studentTotal=$studentTotal->where('subjectSections','like', '%'.strtolower($keywd[1]." | ".$keywd[2]).'%');

                    $studentP=$studentP->where('subjectSections','like', '%'.strtolower($keywd[1]." | ".$keywd[2]).'%');
                    $studentA=$studentA->where('subjectSections','like', '%'.strtolower($keywd[1]." | ".$keywd[2]).'%');
                    $studentL=$studentL->where('subjectSections','like', '%'.strtolower($keywd[1]." | ".$keywd[2]).'%');
                }
            } else if(isset($params['keyword']) && isset($params['search']) && is_numeric($params['keyword'])){              
                $srch=explode(" | ",$params['search']);
                $student=$student->where('atDate','=', $params['atDate'])
                    ->where(strtolower($srch[0]),'=', strtolower($params['keyword']));

                
                //$studentTotal=$studentTotal->where(strtolower($srch[0]),'=', strtolower($params['keyword']))
                  //  ->where('atDate','=', $params['atDate']);

                $studentP=$studentP->where('atDate','=', $params['atDate']);
                $studentA=$studentA->where('atDate','=', $params['atDate']);
                $studentL=$studentL->where('atDate','=', $params['atDate']);
            } else {
                $srch=explode(" | ",$params['search']);
                $student=$student->where('atDate','=', $params['atDate'])
                    ->where(strtolower($srch[0]),'like', '%'.strtolower($params['keyword']).'%');
                
                
                //$studentTotal=$studentTotal->where(strtolower($srch[0]),'like', '%'.strtolower($params['keyword']).'%')
                  //  ->where('atDate','=', $params['atDate']);
                
                $studentP=$studentP->where('atDate','=', $params['atDate']);
                $studentA=$studentA->where('atDate','=', $params['atDate']);
                $studentL=$studentL->where('atDate','=', $params['atDate']);
            }
        } else {
            $student=$student->where('session','like', $params['session'])
                ->where('atDate','=', $params['atDate']);
            
            
            //$studentTotal=$studentTotal->where('atDate','=', $params['atDate']);
            $studentP=$studentP->where('atDate','=', $params['atDate']);
            $studentA=$studentA->where('atDate','=', $params['atDate']);
            $studentL=$studentL->where('atDate','=', $params['atDate']);
        }
        if(isset($params['allot']) && strlen($params['allot'])){
            
            $ex_val=explode(" | ",$params['allot']);
            $cronParams['class']=$ex_val[0];
            if(isset($ex_val[1])){
                $cronParams[strlen($ex_val[1])>1?'subject':'section']=$ex_val[1];
            }
            if(isset($ex_val[2])){
                $cronParams['subjectSections']=strtolower($ex_val[1]." | ".$ex_val[2]);
            }
            //$this->cronJob($cronParams);
            //Log::info($ex_val[0]);
            $student=$student->where('class','like','%'.$ex_val[0].'%');
            $studentTotal=$studentTotal->where('class','like','%'.$ex_val[0].'%');
            
            $studentP=$studentP->where('class','like','%'.$ex_val[0].'%');
            $studentA=$studentA->where('class','like','%'.$ex_val[0].'%');
            $studentL=$studentL->where('class','like','%'.$ex_val[0].'%');

            if(isset($ex_val[1]) && strlen(trim($ex_val[1]))){
                //Log::info($ex_val[1]);
                $student=$student->where(strlen($ex_val[1])>1?'subject':'section','like','%'.$ex_val[1].'%');
                $studentTotal=$studentTotal->where(strlen($ex_val[1])>1?'subject':'section','like','%'.$ex_val[1].'%');

                $studentP=$studentP->where(strlen($ex_val[1])>1?'subject':'section','like','%'.$ex_val[1].'%');
                $studentA=$studentA->where(strlen($ex_val[1])>1?'subject':'section','like','%'.$ex_val[1].'%');
                $studentL=$studentL->where(strlen($ex_val[1])>1?'subject':'section','like','%'.$ex_val[1].'%');
            }
            if(isset($ex_val[2])  && strlen(trim($ex_val[2]))){
                //Log::info($ex_val[2]);
                $student=$student->where('subjectSections','like', '%'.strtolower($ex_val[1]." | ".$ex_val[2]).'%');
                $studentTotal=$studentTotal->where('subjectSections','like', '%'.strtolower($ex_val[1]." | ".$ex_val[2]).'%');

                $studentP=$studentP->where('subjectSections','like', '%'.strtolower($ex_val[1]." | ".$ex_val[2]).'%');
                $studentA=$studentA->where('subjectSections','like', '%'.strtolower($ex_val[1]." | ".$ex_val[2]).'%');
                $studentL=$studentL->where('subjectSections','like', '%'.strtolower($ex_val[1]." | ".$ex_val[2]).'%');
            }
        }
        $student=$student->where('status','=',$status)->get();
        $studentTotal=$studentTotal->where('status','=',$status)->get(); 
        //Log::info("At session: ".$params['atSession']);
        $studentP=$studentP->where('status','=',$status)->where('attendance','like','%"'.$params['atSession'].'":"P"%')->get();
        $studentA=$studentA->where('status','=',$status)->where('attendance','like','%"'.$params['atSession'].'":"A"%')->get();
        $studentL=$studentL->where('status','=',$status)->where('attendance','like','%"'.$params['atSession'].'":"L"%')->get();
        
        return response()->json(new JsonResponse(['records' => $student, 'total' =>$studentTotal->count(),'present'=>$studentP->count(),'absent'=>$studentA->count(),'leave'=>$studentL->count()]));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $n=0)
    {
        $enquiry=Enquiries::where('st_id',$id)->delete();

        $enquiry=new Enquiries();
        $enquiryTotal=new Enquiries();
        
        return response()->json(new JsonResponse(['records' => $enquiry, 'total' =>$enquiryTotal->count()]));
    }
    
}
