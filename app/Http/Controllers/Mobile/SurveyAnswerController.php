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
use App\Models\Voter;
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
		//info($request);
		$userid = $request->user_id;
		$voterid = $request->voter_id;
		$surveydetailid = $request->survey_detail_id;
		$receivedans = json_decode($request->q_and_a, true);
		
		foreach($receivedans as $voteranswers){
			foreach($voteranswers['answers'] as $ansid){								
				$optid = $ansid['id'];

				$surveyans = new SurveyAnswer;		
				$surveyans->survey_detail_id = $surveydetailid;
				$surveyans->question_id = $voteranswers['questionId'];
				$surveyans->answered_option = $voteranswers['answers'];
				$surveyans->option_id = $optid;
				$surveyans->user_id = $userid;
				$surveyans->voter_id = $voterid;
				$surveyans->other_answer = $ansid['otherAnswer'];
				//$surveyans->latitude = $request->latitude;		
				//$surveyans->longitude = $request->longitude;
				$surveyansid=$surveyans->save();				
				
				/*$answeredoptions = AnsweredOption::create([
					'other_answer' => $ansid['otherAnswer'],
					'survey_answer_id' => $surveyansid,
					'option_id' => $optid
				]);*/
				//if($request->has('option_other_answer')){
				//	$surveyans->option_other_answer = $request->option_other_answer;
				//}
				$optioncandidate = OptionCandidate::where('option_id',$optid)->first();
				if($optioncandidate){
					$tallycandidate = new TallyVote;
					$tallycandidate->candidate_id = $optioncandidate->candidate_id;
					$tallycandidate->voter_id = $voterid;
					$tallycandidate->survey_detail_id = $surveydetailid;
					$tallycandidate->save();
				}
				$relquestion = RelatedQuestion::where('question_id',$voteranswers['questionId'])->first();
				if($relquestion){
					$surans = SurveyAnswer::where('survey_detail_id',$surveydetailid)
											->where('question_id',$relquestion->question_id)
											//->where('voter_id',$voterid)
											->first();
					if($surans){
						$question = Question::find($relquestion->question_id);
						if(!empty($question->for_position) && is_numeric($question->for_position)){							
							//$ansoption = AnsweredOption::where('survey_answer_id',$surans->id)->get();
							//foreach($ansoption as $ansoptid){
								$optioncandidate = OptionCandidate::where('option_id',$surans->option_id)->first();
								info($optioncandidate);
								if($optioncandidate){
									$tallycandidate = new TallyOtherVote;
									$tallycandidate->option_id = $optid;
									$tallycandidate->voter_id = $voterid;
									$tallycandidate->candidate_id = $optioncandidate->candidate_id;
									$tallycandidate->survey_detail_id = $surveydetailid;
									$tallycandidate->save();
								}
							//}
						
						}
					}
				}
				
				$optionproblem = OptionProblem::where('option_id',$optid)->first();
				if($optionproblem){
					$voterbrgy = Voter::with('precinct')->find($voterid);
					$tallyproblem = new TallyOtherVote;
					$tallyproblem->option_id = $optid;
					$tallyproblem->voter_id = $voterid;
					$tallyproblem->survey_detail_id = $surveydetailid;
					$tallyproblem->barangay_id = $voterbrgy->precinct->barangay_id;
					$tallyproblem->save();
				}				
			}		
		}
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
