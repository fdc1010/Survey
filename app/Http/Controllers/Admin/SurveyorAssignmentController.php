<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SurveyorAssignment;
use App\Models\SurveyAnswer;
use App\Models\TallyVote;
class SurveyorAssignmentController extends Controller
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
    // User-defined FUNCTIONS
    public function getSurveyorProgressDetails(Request $request){
        if($request->has('survey_detail_id')){
          if($request->survey_detail_id>0){
              $surveyorsquota = SurveyorAssignment::where('survey_detail_id',$request->survey_detail_id)->sum('quota');
              $countsurvey = SurveyAnswer::where('survey_detail_id',$request->survey_detail_id)
                              ->select(['voter_id'])
                              ->groupBy('voter_id')
          										->get();
          		if($countsurvey)
          			$surveyorscount = count($countsurvey);
          		else
          			$surveyorscount = 0;

              $surveyorsprogress = round(($surveyorscount / $surveyorsquota) * 100,2);
              return response()->json(['totalquota'=>$surveyorsquota,'totalcount'=>$surveyorscount,'totalprogress'=>$surveyorsprogress],200);
          }else{
            $surveyorsassignment = new SurveyorAssignment;
            $surveyorscount = $surveyorsassignment->getAllSurveyCount();
            $surveyorsquota = $surveyorsassignment->getAllSurveyQuota();
            $surveyorsprogress = round(($surveyorscount / $surveyorsquota) * 100,2);
            return response()->json(['totalquota'=>$surveyorsquota,'totalcount'=>$surveyorscount,'totalprogress'=>$surveyorsprogress],200);
          }
        }
    }
}
