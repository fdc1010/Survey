<?php

namespace App\Http\Controllers\Mobile;
use App\Models\Question;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\AnsweredOption;
use App\Models\OptionCandidate;
use App\Models\OptionPosition;
use App\Models\OptionProblem;
use App\Models\RelatedQuestion;
use App\Models\TallyVote;
use App\Models\TallyOtherVote;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
		//$sid = $request->survey_detail_id;
		//$survey = Survey::find($sid);
		info($request);
		/*$surveyans = new SurveyAnswer;		
		$surveyans->survey_detail_id = $request->survey_detail_id;
		$surveyans->question_id = $request->question_id;
		$surveyans->answered_option = $request->option_id;
		$surveyans->user_id = $request->user_id;
		$surveyans->voter_id = $request->voter_id;
		$surveyans->latitude = $request->latitude;		
		$surveyans->longitude = $request->longitude;
		if($request->has('other_answer')){
			$surveyans->other_answer = $request->other_answer;
		}
		if($request->has('option_other_answer')){
			$surveyans->option_other_answer = $request->option_other_answer;
		}
		$surveyansid=$surveyans->save();
		
		foreach($request->option_id as $optid){
			$answeredoptions = new AnsweredOption;
			$answeredoptions->survey_answer_id = $surveyansid;
			$answeredoptions->option_id = $optid;
			$answeredoptions->save();
			
			$optioncandidate = OptionCandidate::where('option_id',$optid)->first();
			if($optioncandidate){
				$tallycandidate = new TallyVote;
				$tallycandidate->candidate_id = $optioncandidate->candidate_id;
				$tallycandidate->voter_id = $request->voter_id;
				$tallycandidate->survey_detail_id = $request->survey_detail_id;
				$tallycandidate->save();
			}
			$relquestion = RelatedQuestion::where('question_id',$request->question_id)->first();
			if($relquestion){
				$surans = SurveyAnswer::where('survey_detail_id',$request->survey_detail_id)
										->where('question_id',$relquestion->question_id)
										->where('voter_id',$request->voter_id)
										->first();
				if($surans){
					$question = Question::find($relquestion->question_id);
					if(!empty($question->for_position) && is_numeric($question->for_position)){
						$ansoption = AnsweredOption::where('survey_answer_id',$surans->id)->first();
						foreach($ansoption->option_id as $ansoptid){
							$optioncandidate = OptionCandidate::where('option_id',$ansoptid)->first();
							if($optioncandidate){
								$tallycandidate = new TallyOtherVote;
								$tallycandidate->option_id = $optid;
								$tallycandidate->voter_id = $request->voter_id;
								$tallycandidate->candidate_id = $optioncandidate->candidate_id;
								$tallycandidate->survey_detail_id = $request->survey_detail_id;
								$tallycandidate->save();
							}
					}
				}
			}
			
			$optionproblem = OptionProblem::where('option_id',$optid)->first();
			if($optionproblem){
				$voterbrgy = Voter::with('precinct')->find($request->voter_id);
				$tallyproblem = new TallyOtherVote;
				$tallyproblem->option_id = $optid;
				$tallyproblem->voter_id = $request->voter_id;
				$tallyproblem->survey_detail_id = $request->survey_detail_id;
				$tallyproblem->barangay_id = $voterbrgy->precinct->barangay_id;
				$tallyproblem->save();
			}
		}*/		
		return response()->json(['success'=>true,'msg'=>'Answers are saved!']);
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
