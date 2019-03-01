<?php

namespace App\Http\Controllers\Mobile;
use App\Models\Voter;
use App\Models\CivilStatus;
use App\Models\EmploymentStatus;
use App\Models\OccupancyStatus;
use App\Models\VoterStatus;
use App\Models\Gender;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Session;
use Excel;
use File;
use Image;

class VoterController extends Controller
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
						'precinct_id' => $value->email,
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
	public function getVoterInfoByName(Request $request){
		$lname = $request->lastname;
		$fname = $request->firstname;
		$mname = $request->middlename;
		$voters = Voter::where('first_name','like', "%{$fname}%")
					->where('last_name','like', "%{$lname}%")
					->where('middle_name','like', "%{$mname}%")
					->with(['status','precinct','employmentstatus','civilstatus','occupancystatus','statuses'=>function($q){$q->select(['voter_id','status_id']);}])
					->first();
		
		return response()->json(['voter'=>$voters]);
	}
	public function getVoterStatuses(Request $request){
		$voterstatus = VoterStatus::select(['status','name','description'])->get();
		$empstatus = EmploymentStatus::select(['name','description'])->get();
		$civilstatus = CivilStatus::select(['name','description'])->get();
		$occstatus = OccupancyStatus::select(['name','description'])->get();
		$genderstatus = Gender::select(['id','name','description'])->get();
		return response()->json(['voterstatus'=>$voterstatus,'empstatus'=>$empstatus,
									'civilstatus'=>$civilstatus,'occstatus'=>$occstatus,
									'gender'=>$genderstatus]);
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
    public function update(Request $request)//, $id)
    {
        /*$validator = Validator::make($request->all(), [
			'precinct_id'=>'required',
			'first_name'=>'required',
			'last_name'=>'required',
			'precinct_id'=>'required',
			'gender'=>'required',
			'status_id'=>'required',
			'precinct_id'=>'required',
			'employment_status_id'=>'required',
			'civil_status_id'=>'required',
			'occupancy_status_id'=>'required',
			'occupancy_length'=>'required',
			'monthly_household'=>'required',			
			'work'=>'required'
		]);
		if ($validator->fails()) {
			return Response::json(array(
				'reason' => $validator->getMessageBag()->toArray(),
				'msg'=>'Unauthorized, check your credentials.',
				'success'=>false
			), 400);
		}*/
		try{
			$voter = Voter::find($id);
			$voter->fill($request->all());
			if($request->hasFile('profilepic')){
				
				$path = config('app.root') . '/public/profilepic/';
				$photo=$path.basename($voter->profilepic);
				
				File::delete($photo);
				
				$md5profName = md5_file($request->file('profilepic')->getRealPath());
				$guessExtensionprof = $request->file('profilepic')->guessExtension();
	
				$srvroot = $_SERVER['DOCUMENT_ROOT'];
				$pathimage =  $srvroot . '/profilepic/';
				$path = url('/profilepic/');
				if (!File::exists($path)) {
					File::makeDirectory($path,0777);
				}
	
				$width = 160;
				$height = 160;
				$image = Image::make($request->file('profilepic')->getRealPath());
				$image->width() > $image->height() ? $width=null : $height=null;
				$image->resize($width, $height, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
	
				$image->save($pathimage.$md5profName.'.'.$guessExtensionprof);
	
				$filename = $md5profName.'.'.$guessExtensionprof;
				$voter->profilepic =  config('app.url') . '/profilepic/' . $filename;
	
			}
			$voter->save();
			return response()->json(['success'=>true,'msg'=>'Updating Voter Successful!'],200);
		}catch(\Exception $e){
			return response()->json(['success'=>true,'msg'=>$e]);
		}
    }
	public function sendInfo(Request $request){
		info($request);	
		return response()->json(['success'=>true,'msg'=>'Saving Survey Info on the Server Successful!'],200);
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
