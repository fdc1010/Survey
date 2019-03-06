<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\TallyOtherVote;

use Illuminate\Http\Request;

class SurveyAnswerController extends Controller
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
	public function storeAnswers(Request $request){
		$sid = $request->survey_id;
		$survey = Survey::find($sid);
		$surveyans = new SurveyAnswer;
		$surveyans->survey_id = $survey->id;
		$surveyans->question_id = $request->question_id;
		$surveyans->answered_option = $request->answered_option;
		if($request->has('other_answer')){
			$surveyans->other_answer = $request->other_answer;
		}
		if($request->has('option_other_answer')){
			$surveyans->option_other_answer = $request->option_other_answer;
		}
		$surveyans->save();
		return response()->json(['success'=>true,'msg'=>'Answers are saved!']);
	}
  public function updateothertallyvotesquality(Request $request){
      $surveyans = SurveyAnswer::whereIn('question_id',[10,11,12])
                                  ->where('survey_detail_id',1)
                                  ->orderBy('id')
                                  ->get();
      if(!empty($surveyans) && count($surveyans)>0){
        echo "Survey Answer:<br>";
        foreach($surveyans as $survey){
                 $surveyansocs = SurveyAnswer::with('option')
                                            ->whereIn('option_id',[3,4,5,6,7,8,9,10,11,23,24,25,26,27,28,36,37])
                                            ->where('question_id',3)
                                            ->where('survey_detail_id',1)
                                            ->where('voter_id',$survey->voter_id)
                                            ->orderBy('id')
                                            ->take(3)
                                            ->get()->toArray();
                  echo $surveyansocs[0];
                  $tallyothervotes = TallyOtherVote::where('voter_id',$survey->voter_id)
                                                    ->where('survey_detail_id',1)
                                                    ->whereNull('barangay_id')
                                                    ->whereIn('option_id',[10,11,12,13,14,15,16,17])
                                                    ->whereIn('candidate_id',[3,4,5,6,7,8,9,10,11,23,24,25,26,27,28,36,37])
                                                    ->orderBy('id')
                                                    ->take(3)
                                                    ->get();

                  // TallyOtherVote::where('id',$tallyothervotes->id)
                  //                 ->update(['candidate_id'=>$surveyansocs->option->candidate_id,
                  //                           'question_id'=>$surveyansocs->question_id,
                  //                           'user_id'=>$survey->user_id]);

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
     * @param  \App\SurveyAnswer  $surveyAnswer
     * @return \Illuminate\Http\Response
     */
    public function show(SurveyAnswer $surveyAnswer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SurveyAnswer  $surveyAnswer
     * @return \Illuminate\Http\Response
     */
    public function edit(SurveyAnswer $surveyAnswer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SurveyAnswer  $surveyAnswer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SurveyAnswer $surveyAnswer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SurveyAnswer  $surveyAnswer
     * @return \Illuminate\Http\Response
     */
    public function destroy(SurveyAnswer $surveyAnswer)
    {
        //
    }
}
