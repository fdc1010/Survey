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
use App\Models\VoterStatus;
use App\Models\EmploymentStatus;
use App\Models\CivilStatus;
use App\Models\OccupancyStatus;
use App\Models\Gender;
use App\Models\SurveyorAssignment;

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
			  $user->save();
			  $voterstatus = VoterStatus::select(['id','status','name','description'])->get();
			  $empstatus = EmploymentStatus::select(['id','name','description'])->get();
			  $civilstatus = CivilStatus::select(['id','name','description'])->get();
			  $occstatus = OccupancyStatus::select(['id','name','description'])->get();
			  $genderstatus = Gender::select(['id','name','description'])->get();
			  $surveyordetails = SurveyorAssignment::where('user_id',$user->id)
			  										->where('completed',0)
													->with(['assignments'=>function($q){
																		$q->with(['sitio'=>function($qu){
																				$qu->with('voters');
																			}]);
															}])
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
	public function syncInData(Request $request){
		//validate the xls file
        $this->validate($request, array(
            'file'      => 'required'
        ));
 
        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
 
                $path = $request->file->getRealPath();
                $data = Excel::load($path, function($reader) {
                })->get();
                if(!empty($data) && $data->count()){
 
                    foreach ($data as $key => $value) {
                        $insert[] = [						
						'precinct_id' => $value->precinct,
                        'seq_num' => $value->seqnum,
                        'status_id' => $value->status,
						'sitio_id' => $value->sitio,
                        'last_name' => $value->lastname,
						'first_name' => $value->firstname,
						'middle_name' => $value->middlename,
						'address' => $value->address
                        ];
                    }
 
                    if(!empty($insert)){
 
                        $insertData = DB::table('survey_answers')->insert($insert);
                        if ($insertData) {
                            return response()->json(['success'=>true,'message'=>'Your Data has successfully imported']);
                        }else {                        
                            return response()->json(['success'=>false,'message'=>'Error inserting the data..']);
                
                        }
                    }
                }
 
                return response()->json(['success'=>false,'message'=>'Empty File Content']);
 
            }else {
                return response()->json(['success'=>false,'message'=>'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!']);
                
            }
        }
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
