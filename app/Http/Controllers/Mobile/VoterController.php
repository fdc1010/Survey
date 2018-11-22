<?php

namespace App\Http\Controllers\Mobile;
use App\Models\Voter;
use App\Models\CivilStatus;
use App\Models\EmploymentStatus;
use App\Models\OccupancyStatus;
use App\Models\VoterStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
	public function getVoterInfoByName(Request $request){
		$lname = $request->lastname;
		$fname = $request->firstname;
		$mname = $request->middlename;
		$voters = Voter::where('first_name','like', "%{$fname}%")
					->where('last_name','like', "%{$lname}%")
					->where('middle_name','like', "%{$mname}%")
					->with('status','precinct','employmentstatus','civilstatus','occupancystatus')
					->first();
		
		return response()->json(['voter'=>$voters]);
	}
	public function getVoterStatuses(Request $request){
		$voterstatus = VoterStatus::select(['status','name','description'])->get();
		$empstatus = EmploymentStatus::select(['name','description'])->get();
		$civilstatus = CivilStatus::select(['name','description'])->get();
		$occstatus = OccupancyStatus::select(['name','description'])->get();
		return response()->json(['voterstatus'=>$voterstatus,'empstatus'=>$empstatus,
									'civilstatus'=>$civilstatus,'occstatus'=>$occstatus]);		
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
