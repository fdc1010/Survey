<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\Voter;
use App\Models\VoterStatus;
use App\Models\StatusDetails;
use App\Models\EmploymentStatus;
use App\Models\CivilStatus;
use App\Models\OccupancyStatus;
use App\Models\Gender;
use App\Models\SurveyorAssignment;
use App\Models\AssignmentDetail;

class MobileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }
    public function getSurveyorInfo(Request $request)
  	{


  		    $userid = $request->id;
          $user = User::where('id',$userid)->select(['name','email','api_token','imei'])->first();
  			  $voterstatus = VoterStatus::select(['id','status','name','description'])->get();
  			  $empstatus = EmploymentStatus::select(['id','name','description'])->get();
  			  $civilstatus = CivilStatus::select(['id','name','description'])->get();
  			  $occstatus = OccupancyStatus::select(['id','name','description'])->get();
  			  $genderstatus = Gender::select(['id','name','description'])->get();
          $data = [];
  			  $surveyordetails = SurveyorAssignment::where('user_id',$userid)
  			  										->where('completed',0)
                              ->select(['id','user_id','survey_detail_id','quota','count','progress','task','description'])
                              ->first();
          if($surveyordetails){
                $assignmentarea = AssignmentDetail::where('assignment_id',$surveyordetails->id)
                                                      ->with(['barangay'=>function($q){
                                                                    $q->select('name');
                                                              }
                                                            ])
                                                      ->select(['assignment_id','barangay_id','quota','count','progress','task','description'])
                                                      ->get();
                $assignmentbrgy = AssignmentDetail::where('assignment_id',$surveyordetails->id)->get()->pluck('barangay_id')->toArray();
                $voters = Voter::whereIn('barangay_id',$assignmentbrgy)
                              ->where('is_done_survey',0)
  													  ->with(['precinct'=>function($q){
                                            $q->select('precinct_number');
                                      },
                                      'statuses'=>function($q){
                                            $q->select(['voter_id','status_id']);
                                      },
                                      'barangay'=>function($q){
                                            $q->select('name');
                                      },
                                      'employmentstatus'=>function($q){
                                            $q->select('name');
                                      },
                                      'civilstatus'=>function($q){
                                            $q->select('name');
                                      },
                                      'occupancystatus'=>function($q){
                                            $q->select('name');
                                      },
                                      'gender'=>function($q){
                                            $q->select('name');
                                      }
                                    ])
                              ->select(['id','precinct_id','barangay_id','gender_id','status_id','employment_status_id','civil_status_id','occupancy_status_id',
                                        'first_name','last_name','middle_name', 'birth_date','contact',
                          							'address', 'birth_place','age','profilepic',
                          							'occupancy_length','monthly_household',
                          							'yearly_household','work'])
  			  										->chunk(400, function ($results) use (&$data){
                                  foreach ($results as $voter) {
                                      array_push($data,$voter);
                                  }
                              });
          }
  			  return response()->json(['user'=>$user,
  			  						'voterstatus'=>$voterstatus,'empstatus'=>$empstatus,
  										'civilstatus'=>$civilstatus,'occstatus'=>$occstatus,
  										'gender'=>$genderstatus,'surveyordetails'=>$surveyordetails,
                      'assignmentarea'=>$assignmentarea,'voters'=>$data]);

  	}
	public function login(Request $request)
	{

		/*$this->validate($request, [
		   'email' => 'required|email',
		   'password' => 'required|string',
		]);*/
		$validator = Validator::make($request->all(), [
			'imei'=>'required',
			'password'=>'required',
			'email'=>'required',
		]);
		if ($validator->fails()) {
			return Response::json(array(
				'reason' => $validator->getMessageBag()->toArray(),
				'msg'=>'Unauthorized, check your credentials.',
				'success'=>false
			), 400);
		}
		$user = User::where('email', $request->get('email'))->first();
		if($user){
		   $auth = Hash::check($request->get('password'), $user->password);
		   if($auth){

			  $user->rollApiKey(); //Model Function
			  $user->is_online=1;
			  $user->imei=$request->imei;
			  $user->save();
			  $voterstatus = VoterStatus::select(['id','status','name','description'])->get();
			  $empstatus = EmploymentStatus::select(['id','name','description'])->get();
			  $civilstatus = CivilStatus::select(['id','name','description'])->get();
			  $occstatus = OccupancyStatus::select(['id','name','description'])->get();
			  $genderstatus = Gender::select(['id','name','description'])->get();
			  $surveyordetails = SurveyorAssignment::where('user_id',$user->id)
			  										->where('completed',0)
                            ->with('assignments')
													  // ->with(['assignments'=>function($q){
														// 				$q->with(['barangay'=>function($qu){
    												// 								$qu->with(['voters'=>function($qs){
    												// 													$qs->where('is_done_survey',0)
                            //                              ->with(['statuses'=>function($qvs){
    												// 																	$qvs->select(['voter_id','status_id']);
    												// 																},'precinct']);
    												// 										}]);
                            //
														// 					}]);
														// 	}])
			  										->first();
			  return response()->json(['success'=>true,'msg'=>'Authorization Successful','user'=>$user,
			  						'voterstatus'=>$voterstatus,'empstatus'=>$empstatus,
										'civilstatus'=>$civilstatus,'occstatus'=>$occstatus,
										'gender'=>$genderstatus,'surveyordetails'=>$surveyordetails]);
		   }
		}
		return response()->json(['success'=>false,'msg'=>'Unauthorized, Check your credentials.']);


	}
	public function logout(Request $request){
		$validator = Validator::make($request->all(), [
			'imei'=>'required',
			'password'=>'required',
			'email'=>'required',
		]);
		if ($validator->fails()) {
			return Response::json(array(
				'reason' => $validator->getMessageBag()->toArray(),
				'success'=>false
			), 400);
		}
		$user = User::where('email', $request->get('email'))->first();
		if($user){

			  $user->api_token = null;
			  $user->is_online = 0;
			  return response()->json(['success'=>true,'msg'=>'Authorization Successful']);

		}
		return response()->json(['success'=>false,'msg'=>'Opps! an error occured, Check your credentials/device.']);
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
    public function store(Request $request)
    {
        //
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
        //
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
    public function destroy($id)
    {
        //
    }
}
