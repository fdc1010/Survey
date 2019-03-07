<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyorAssignment;
use App\Models\AssignmentDetail;
use App\Models\AnsweredOption;
use App\Models\OptionCandidate;
use App\Models\OptionPosition;
use App\Models\OptionProblem;
use App\Models\RelatedQuestion;
use App\Models\TallyVote;
use App\Models\TallyOtherVote;
use App\Models\Voter;
use App\Models\VoterStatus;
use App\Models\StatusDetail;

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
  public function updateOtherTallyVotesQuality(Request $request){
    $questionid = $request->qid;
    $surveydetailid = $request->sid;


    $relquestion = RelatedQuestion::where('question_id',$questionid)->first();
    if($relquestion){
      if(!empty($relquestion->cardinality) && $relquestion->cardinality>0){
        SurveyAnswer::where('survey_detail_id',$surveydetailid)
                    ->where('question_id',$questionid)
                    ->chunk(400, function ($results)use($surveydetailid,$relquestion){
                          foreach ($results as $lsuans) {
                            $otoptId = null;
                            $surans = SurveyAnswer::where('survey_detail_id',$surveydetailid)
                                        ->where('question_id',$relquestion->related_question_id)
                                        ->where('voter_id',$lsuans->voter_id)
                                        ->orderBy('id')
                                        ->get();
                            if(!empty($surans[$relquestion->cardinality-1])){
                                $otoptId = $surans[$relquestion->cardinality-1]->option_id;
                                if(!empty($otoptId)){
                                  $question = Question::find($relquestion->question_id);
                                  if(!empty($question->for_position) && is_numeric($question->for_position)){
                                    $optioncandidate = QuestionOption::find($otoptId);
                                    if($optioncandidate){
                                      echo "Updating tally for candidate qualities: #".$relquestion->question_id." ".$optioncandidate->candidate_id." ".$optioncandidate->option;
                                      TallyOtherVote::where('survey_detail_id',$surveydetailid)
                                                      ->where('question_id',$relquestion->question_id)
                                                      ->where('voter_id',$lsuans->voter_id)
                                                      ->update([
                                                                  'candidate_id'=>$optioncandidate->candidate_id
                                                              ]);
                                    }

                                  }
                                }
                            }
                          }
                    });

      }else{
        SurveyAnswer::where('survey_detail_id',$surveydetailid)
                    ->where('question_id',$questionid)
                    ->chunk(400, function ($results)use($surveydetailid,$relquestion){
                          foreach ($results as $lsuans) {
                            $otoptId = null;
                            $surans = SurveyAnswer::where('survey_detail_id',$surveydetailid)
                                        ->where('question_id',$relquestion->related_question_id)
                                        ->where('voter_id',$lsuans->voter_id)
                                        ->orderBy('id')
                                        ->get();
                            if(!empty($surans[$relquestion->cardinality-1])){
                                $otoptId = $surans[0]->option_id;
                                if(!empty($otoptId)){
                                  $question = Question::find($relquestion->question_id);
                                  if(!empty($question->for_position) && is_numeric($question->for_position)){
                                    $optioncandidate = QuestionOption::find($otoptId);
                                    if($optioncandidate){
                                      echo "Updating tally for candidate qualities: #".$relquestion->question_id." ".$optioncandidate->candidate_id." ".$optioncandidate->option;
                                      TallyOtherVote::where('survey_detail_id',$surveydetailid)
                                                      ->where('question_id',$relquestion->question_id)
                                                      ->where('voter_id',$lsuans->voter_id)
                                                      ->update([
                                                                  'candidate_id'=>$optioncandidate->candidate_id
                                                              ]);
                                    }

                                  }
                                }
                            }
                          }
                    });
      }

    }
  }
  public function testOtherVotersRelQ(Request $request){
    $surveydetailid = $request->sdid;
    $voterid = $request->vid;
    $questionId = $request->qid;
    $curquestion = Question::find($questionId);
    $relquestion = RelatedQuestion::where('question_id',$questionId)->first();
    if($relquestion){
      echo "Current Question: #".$questionId." ".$curquestion->question;
      $csurans = SurveyAnswer::where('survey_detail_id',$surveydetailid)
                ->where('question_id',$questionId)
                ->where('voter_id',$voterid)
                ->first();
      $optid = $csurans->option_id;
      $otoptId = null;
      $cquestionoption = QuestionOption::find($optid);
      echo "<br>Your Answer: ".$optid." ".$cquestionoption->option;
      echo "<br>Found linked Question: #".$questionId." to #".$relquestion->related_question_id." with a cardinality of ".$relquestion->cardinality."<br>";
      if(!empty($relquestion->cardinality) && $relquestion->cardinality>0){
          $surans = SurveyAnswer::where('survey_detail_id',$surveydetailid)
                      ->where('question_id',$relquestion->related_question_id)
                      ->where('voter_id',$voterid)
                      ->orderBy('id')
                      ->get();
          if(!empty($surans[$relquestion->cardinality-1])){
              $otoptId = $surans[$relquestion->cardinality-1]->option_id;
          }
      }else{
          $surans = SurveyAnswer::where('survey_detail_id',$surveydetailid)
                    ->where('question_id',$relquestion->related_question_id)
                    ->where('voter_id',$voterid)
                    ->get();
          if(!empty($surans[$relquestion->cardinality-1])){
              $otoptId = $surans[0]->option_id;
          }
      }
      if(!empty($otoptId)){
        $question = Question::find($relquestion->question_id);
        if(!empty($question->for_position) && is_numeric($question->for_position)){
          echo "<br>Getting survey info of linked Question:";
          echo "Analyzing: <br>";
          echo "<br>Linked Question Info: #" . $question->id . " " . $question->question;
          echo "<br>Voter answers:";

          $optioncandidate = QuestionOption::find($otoptId);

          echo "<br>".$otoptId." ".$optioncandidate->candidate_id." ".$optioncandidate->option;

          $questionoption = QuestionOption::find($optid);
          if($optioncandidate){
            echo "<br>Candidate Qualities: #".$questionId." option ".$optid;
            echo " ".$questionoption->option;
            echo "<br>";
          }

        }
      }
    }
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
      $i = 0;
      $qids = array(10,11,12);
      // $surveyans = SurveyAnswer::whereIn('question_id',[10,11,12])
      //                             ->where('survey_detail_id',1)
      //                             ->orderBy('id')
      //                             ->get();
      // if(!empty($surveyans) && count($surveyans)>0){
      //   echo "Survey Answer:<br>";
      //   foreach($surveyans as $survey){
      //     if($i>2)
      //       $i=0;
                 $surveyansocs = SurveyAnswer::with('option')
                                            ->whereIn('option_id',[3,4,5,6,7,8,9,10,11,23,24,25,26,27,28,36,37])
                                            ->where('question_id',3)
                                            ->where('survey_detail_id',1)
                                            ->orderBy('id')
                                            ->get();
                foreach($surveyansocs as $survey){
                      if($i>2)
                         $i=0;
                      $tallyothervotes = TallyOtherVote::where('voter_id',$survey->voter_id)
                                                        ->where('survey_detail_id',1)
                                                        ->whereNull('barangay_id')
                                                        ->whereIn('option_id',[10,11,12,13,14,15,16,17])
                                                        ->whereIn('candidate_id',[3,4,5,6,7,8,9,10,11,23,24,25,26,27,28,36,37])
                                                        ->orderBy('id')
                                                        ->skip($i)
                                                        ->take(1)
                                                        ->first();
                      if($tallyothervotes){
                          echo $qids[$i] . " " . $survey->option->candidate_id . " " . $survey->user_id . "<br>";
                          TallyOtherVote::where('id',$tallyothervotes->id)
                                          ->update(['candidate_id'=>$survey->option->candidate_id,
                                                    'question_id'=>$qids[$i],
                                                    'user_id'=>$survey->user_id]);
                          $i++;
                      }
              }
        //}
      //}

  }

  public function insertmissingothertallyvotesqualityMayor(Request $request){
          $surveyans = SurveyAnswer::where('question_id',5)
                               ->where('survey_detail_id',1)
                               //->whereIn('option_id',[10,11,12,13,14,15,16,17])
                               ->orderBy('id')
                               ->get();
          foreach($surveyans as $survey){
            $surveyansot = SurveyAnswer::where('voter_id',$survey->voter_id)
                                         ->where('question_id',4)
                                         ->where('survey_detail_id',1)
                                         ->orderBy('id')
                                         ->first();

              $tallyothervotes = TallyOtherVote::where('voter_id',$surveyansot->voter_id)
                                              ->where('survey_detail_id',1)
                                              ->where('question_id',5)
                                              ->where('option_id',$surveyansot->option_id)
                                              ->first();
              if($tallyothervotes){
                      echo "Updating Record: " . $tallyothervotes->id . " " . $surveyansot->option_id . " " . $survey->question_id . "<br>";
                      TallyOtherVote::where('id',$tallyothervotes->id)
                                      ->update(['candidate_id'=>$surveyansot->candidate_id,
                                                'user_id'=>$survey->user_id]);
              }else{
                      echo "Inserting Record: " . $surveyansot->option_id . " " . $survey->question_id . "<br>";
                      $tallyovs = new TallyOtherVote;
                      $tallyovs->survey_detail_id = $survey->survey_detail_id;
                      $tallyovs->question_id = $survey->question_id;
                      $tallyovs->candidate_id = $surveyansot->candidate_id;
                      $tallyovs->voter_id = $survey->voter_id;
                      $tallyovs->user_id = $survey->user_id;
                      $tallyovs->option_id = $survey->option_id;
                      $tallyovs->save();
              }
          }
  }
  public function insertmissingothertallyvotesqualityViceMayor(Request $request){
          $surveyans = SurveyAnswer::where('question_id',7)
                               ->where('survey_detail_id',1)
                               //->whereIn('option_id',[10,11,12,13,14,15,16,17])
                               ->orderBy('id')
                               ->get();
          foreach($surveyans as $survey){
            $surveyansot = SurveyAnswer::where('voter_id',$survey->voter_id)
                                         ->where('question_id',6)
                                         ->where('survey_detail_id',1)
                                         ->orderBy('id')
                                         ->first();

              $tallyothervotes = TallyOtherVote::where('voter_id',$surveyansot->voter_id)
                                              ->where('survey_detail_id',1)
                                              ->where('question_id',7)
                                              ->where('option_id',$surveyansot->option_id)
                                              ->first();
              if($tallyothervotes){
                      echo "Updating Record: " . $tallyothervotes->id . " " . $surveyansot->option_id . " " . $survey->question_id . "<br>";
                      TallyOtherVote::where('id',$tallyothervotes->id)
                                      ->update(['candidate_id'=>$surveyansot->candidate_id,
                                                'user_id'=>$survey->user_id]);
              }else{
                      echo "Inserting Record: " . $surveyansot->option_id . " " . $survey->question_id . "<br>";
                      $tallyovs = new TallyOtherVote;
                      $tallyovs->survey_detail_id = $survey->survey_detail_id;
                      $tallyovs->question_id = $survey->question_id;
                      $tallyovs->candidate_id = $surveyansot->candidate_id;
                      $tallyovs->voter_id = $survey->voter_id;
                      $tallyovs->user_id = $survey->user_id;
                      $tallyovs->option_id = $survey->option_id;
                      $tallyovs->save();
              }
          }
  }

  public function insertmissingothertallyvotesqualityCong(Request $request){
          $surveyans = SurveyAnswer::where('question_id',9)
                               ->where('survey_detail_id',1)
                               //->whereIn('option_id',[10,11,12,13,14,15,16,17])
                               ->orderBy('id')
                               ->get();
          foreach($surveyans as $survey){
            $surveyansot = SurveyAnswer::where('voter_id',$survey->voter_id)
                                         ->where('question_id',8)
                                         ->where('survey_detail_id',1)
                                         ->orderBy('id')
                                         ->first();

              $tallyothervotes = TallyOtherVote::where('voter_id',$surveyansot->voter_id)
                                              ->where('survey_detail_id',1)
                                              ->where('question_id',9)
                                              ->where('option_id',$surveyansot->option_id)
                                              ->first();
              if($tallyothervotes){
                      echo "Updating Record: " . $tallyothervotes->id . " " . $surveyansot->option_id . " " . $survey->question_id . "<br>";
                      TallyOtherVote::where('id',$tallyothervotes->id)
                                      ->update(['candidate_id'=>$surveyansot->candidate_id,
                                                'user_id'=>$survey->user_id]);
              }else{
                      echo "Inserting Record: " . $surveyansot->option_id . " " . $survey->question_id . "<br>";
                      $tallyovs = new TallyOtherVote;
                      $tallyovs->survey_detail_id = $survey->survey_detail_id;
                      $tallyovs->question_id = $survey->question_id;
                      $tallyovs->candidate_id = $surveyansot->candidate_id;
                      $tallyovs->voter_id = $survey->voter_id;
                      $tallyovs->user_id = $survey->user_id;
                      $tallyovs->option_id = $survey->option_id;
                      $tallyovs->save();
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
