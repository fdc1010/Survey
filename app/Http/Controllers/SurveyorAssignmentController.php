<?php

namespace App\Http\Controllers;

use App\Models\SurveyorAssignment;
use Illuminate\Http\Request;

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
     * @param  \App\SurveyorAssignment  $surveyorAssignment
     * @return \Illuminate\Http\Response
     */
    public function show(SurveyorAssignment $surveyorAssignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SurveyorAssignment  $surveyorAssignment
     * @return \Illuminate\Http\Response
     */
    public function edit(SurveyorAssignment $surveyorAssignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SurveyorAssignment  $surveyorAssignment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SurveyorAssignment $surveyorAssignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SurveyorAssignment  $surveyorAssignment
     * @return \Illuminate\Http\Response
     */
    public function destroy(SurveyorAssignment $surveyorAssignment)
    {
        //
    }

    // User-defined FUNCTIONS
    public function getSurveyorProgressDetails(Request $request){
      if($request->has('survey_detail_id')){
        $surveyorsassignment = new SurveyorAssignment; //::where('survey_detail_id',$request->survey_detail_id)->first();
        if($request->survey_detail_id > 0){
          $surveyorscount = $surveyorsassignment->getAllSurveyCount($request->survey_detail_id);
          $surveyorsquota = $surveyorsassignment->getAllSurveyQuota($request->survey_detail_id);
        }else{
          $surveyorscount = $surveyorsassignment->getAllSurveyCount(0);
          $surveyorsquota = $surveyorsassignment->getAllSurveyQuota(0);
        }
        $surveyorsprogress = round(($surveyorscount / $surveyorsquota) * 100,2);
        return response()->json(['totalquota'=>$surveyorsquota,'totalcount'=>$surveyorscount,'totalprogress'=>$surveyorsprogress],200);
      }
    }
}
