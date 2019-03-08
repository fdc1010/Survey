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
                    ->where('question_id',$relquestion->related_question_id)
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
                                      echo "<br>Updating tally for candidate qualities: #".$relquestion->question_id." ".$optioncandidate->candidate_id." ".$optioncandidate->option;
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
                    ->where('question_id',$relquestion->related_question_id)
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
                                      echo "<br>Updating tally for candidate qualities: #".$relquestion->question_id." ".$optioncandidate->candidate_id." ".$optioncandidate->option;
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
  public function insertupdateOtherTallyVotesQuality(Request $request){
    $questionid = $request->qid;
    $surveydetailid = $request->sid;

    $relquestion = RelatedQuestion::where('question_id',$questionid)->first();
    if($relquestion){
      if(!empty($relquestion->cardinality) && $relquestion->cardinality>0){
        SurveyAnswer::where('survey_detail_id',$surveydetailid)
                    ->where('question_id',$relquestion->related_question_id)
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
                                      $csurans = SurveyAnswer::where('survey_detail_id',$surveydetailid)
                                                  ->where('question_id',$relquestion->question_id)
                                                  ->where('voter_id',$lsuans->voter_id)
                                                  ->first();
                                      echo "<br>Updating/Storing tally #".$csurans->question_id." , from related question #".$relquestion->related_question_id." for candidate qualities: #".$relquestion->question_id." ".$optioncandidate->candidate_id." ".$optioncandidate->option;
                                      $tallyothervotes = TallyOtherVote::where('survey_detail_id',$surveydetailid)
                                                      ->where('question_id',$relquestion->question_id)
                                                      ->where('voter_id',$lsuans->voter_id)
                                                      ->first();
                                      if($tallyothervotes){
                                            TallyOtherVote::where('survey_detail_id',$surveydetailid)
                                                            ->where('question_id',$relquestion->question_id)
                                                            ->where('voter_id',$lsuans->voter_id)
                                                            ->update(['candidate_id'=>$optioncandidate->candidate_id]);
                                      }else{
                                            $tallyothervotedata = [
                                                                'survey_detail_id'=>$surveydetailid,
                                                                'question_id'=>$relquestion->question_id,
                                                                'option_id'=>$csurans->option_id,
                                                                'voter_id'=>$csurans->voter_id,
                                                                'candidate_id'=>$optioncandidate->candidate_id,
                                                                'user_id'=>$csurans->user_id
                                                              ];
                                            TallyOtherVote::insert($tallyothervotedata);
                                      }
                                    }

                                  }
                                }
                            }
                          }
                    });

      }else{
        SurveyAnswer::where('survey_detail_id',$surveydetailid)
                    ->where('question_id',$relquestion->related_question_id)
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
                                      $csurans = SurveyAnswer::where('survey_detail_id',$surveydetailid)
                                                  ->where('question_id',$relquestion->question_id)
                                                  ->where('voter_id',$lsuans->voter_id)
                                                  ->first();
                                      echo "<br>Updating/Storing tally #".$csurans->question_id." , from related question #".$relquestion->related_question_id." for candidate qualities: #".$relquestion->question_id." ".$optioncandidate->candidate_id." ".$optioncandidate->option;
                                      $tallyothervotes = TallyOtherVote::where('survey_detail_id',$surveydetailid)
                                                      ->where('question_id',$relquestion->question_id)
                                                      ->where('voter_id',$lsuans->voter_id)
                                                      ->first();
                                      if($tallyothervotes){
                                            TallyOtherVote::where('survey_detail_id',$surveydetailid)
                                                            ->where('question_id',$relquestion->question_id)
                                                            ->where('voter_id',$lsuans->voter_id)
                                                            ->update(['candidate_id'=>$optioncandidate->candidate_id]);
                                      }else{
                                            $tallyothervotedata = [
                                                                'survey_detail_id'=>$surveydetailid,
                                                                'question_id'=>$relquestion->question_id,
                                                                'option_id'=>$csurans->option_id,
                                                                'voter_id'=>$csurans->voter_id,
                                                                'candidate_id'=>$optioncandidate->candidate_id,
                                                                'user_id'=>$csurans->user_id
                                                              ];
                                            TallyOtherVote::insert($tallyothervotedata);
                                      }
                                    }

                                  }
                                }
                            }
                          }
                    });
      }

    }
  }
  public function testOtherVotesRelQ(Request $request){
    $surveydetailid = $request->sid;
    $questionId = $request->qid;
    $curquestion = Question::find($questionId);
    if($relquestion){
      echo "Current Question: #".$curquestion->id." ".$curquestion->question;
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
    }else{
        echo "Question Info not found!";
    }
  }
  public function testOtherVotesProblem(Request $request){
    $surveydetailid = $request->sid;
    $questionId = $request->qid;
    $curquestion = Question::find($questionId);
    if($curquestion){
      echo "Current Question: #".$questionId." ".$curquestion->question;
      $csurans = SurveyAnswer::where('survey_detail_id',$surveydetailid)
                ->where('question_id',$questionId)
                ->first();
      if($csurans){
          $cquestionoption = QuestionOption::find($csurans->option_id);
          echo "<br>Your Answer: ".$csurans->option_id." ".$cquestionoption->option;
      }else{
          echo "<br>Voter's Answer not found!";
      }
    }else{
        echo "Question Info not found!";
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
