<?php

namespace App\Http\Controllers\Admin;

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
      $surveyorsassignment = SurveyorAssignment::where('survey_detail_id',$request->survey_detail_id)->first();
      $surveyorscount = $surveyorsassignment->getAllSurveyCount();
      $surveyorsquota = $surveyorsassignment->getAllSurveyQuota();
      $surveyorsprogress = round(($surveyorscount / $surveyorsquota) * 100,2);
      return response()->json(['totalquota'=>$surveyorsquota,'totalcount'=>$surveyorscount,'totalprogress'=>$surveyorsprogress],200);
    }
}
