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
use App\User;
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
  public function insertUpdateSurvey(Request $request){
    if($request->has('sid')){
        $surveydetailid = $request->sid;
    }else{
        $surveydetailid = 1;
    }
    if($request->has('qid')){
        $qids = explode(',',$request->qid);
        $questionId = $qids;
    }else{
        $questionId = array(4,6,8,3);
    }
    foreach($questionId as $qid){
        echo $qid.",";
    }
    $i=0;
    $curquestions = Question::whereIn('id',$questionId)->get();
    foreach($curquestions as $curquestion){
      echo "<br>Current Question: #".$curquestion->id." ".$curquestion->question;
      SurveyAnswer::where('survey_detail_id',$surveydetailid)
                  ->where('question_id',$curquestion->id)
                  ->chunk(400, function ($results)use($surveydetailid,&$i){
                        foreach ($results as $suranswer) {
                              $cquestionoption = QuestionOption::find($suranswer->option_id);
                              echo "<br>Voter's #".$suranswer->voter_id." Answer: ".$suranswer->option_id." ".$cquestionoption->option;
                              $tallyvotes = TallyVote::where('survey_detail_id',$surveydetailid)
                                                      ->where('voter_id',$suranswer->voter_id)
                                                      ->where('candidate_id',$cquestionoption->candidate_id)
                                                      ->where('question_id',$suranswer->question_id)
                                                      ->first();
                              // if($tallyvotes){
                              //       TallyVote::where('id',$tallyvotes->id)
                              //                 ->update([
                              //                           'question_id'=>$suranswer->question_id,
                              //                           'option_id'=>$suranswer->option_id,
                              //                           'user_id'=>$suranswer->user_id
                              //                         ]);
                              // }else{
                                $tallyvotedata = [
                                                    'survey_detail_id'=>$surveydetailid,
                                                    'question_id'=>$suranswer->question_id,
                                                    'option_id'=>$suranswer->option_id,
                                                    'voter_id'=>$suranswer->voter_id,
                                                    'candidate_id'=>$cquestionoption->candidate_id,
                                                    'user_id'=>$suranswer->user_id
                                                  ];
                                TallyVote::insert($tallyvotedata);
                              //}
                              $i++;
                        }
                  });
    }
    echo "<br>Record(s) Affected: ".$i;
    if(!empty($curquestions) && count($curquestions)<=0){
        echo "Question Info not found!";
    }else if(empty($curquestions)){
        echo "Question Info not found!";
    }
  }
  public function insertUpdateSurveyQualities(Request $request){
    if($request->has('sid')){
        $surveydetailid = $request->sid;
    }else{
        $surveydetailid = 1;
    }
    $doUpdate = 0;
    if($request->has('doupdate')){
      $doUpdate = $request->doupdate;
    }
    $doInsert = 0;
    if($request->has('doinsert')){
      $doInsert = $request->doinsert;
    }
    $qids = array(5,7,9,10,11,12);
    if($request->has('qid')){
        if(in_array($request->qid,$qids)){
            $questionId = $qids;
        }else{
            $questionId = array($request->qid);
        }
    }
    $curquestions = Question::whereIn('id',$questionId)->get();
    foreach($curquestions as $curquestion){
          $relquestion = RelatedQuestion::where('question_id',$curquestion->id)->first();
          if($relquestion){
            if(!empty($relquestion->cardinality) && $relquestion->cardinality>0){
              SurveyAnswer::where('survey_detail_id',$surveydetailid)
                          ->where('question_id',$relquestion->related_question_id)
                          ->chunk(400, function ($results)use($surveydetailid,$relquestion){
                                foreach ($results as $suranswer) {
                                  $otoptId = null;
                                  $surans = SurveyAnswer::where('survey_detail_id',$surveydetailid)
                                              ->where('question_id',$relquestion->related_question_id)
                                              ->where('voter_id',$suranswer->voter_id)
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
                                                        ->where('voter_id',$suranswer->voter_id)
                                                        ->first();
                                            echo "<br>Updating/Storing tally #".$csurans->question_id." , from related question #".$relquestion->related_question_id." for candidate qualities: #".$relquestion->question_id." ".$optioncandidate->candidate_id." ".$optioncandidate->option;
                                            $tallyothervotes = TallyOtherVote::where('survey_detail_id',$surveydetailid)
                                                            ->where('question_id',$relquestion->question_id)
                                                            ->where('voter_id',$suranswer->voter_id)
                                                            ->first();
                                            if($tallyothervotes){
                                              if($doUpdate){
                                                  TallyOtherVote::where('id',$tallyothervotes->id)
                                                                  ->update(['candidate_id'=>$optioncandidate->candidate_id]);
                                              }
                                            }else{
                                              if($doInsert){
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
                                }
                          });

            }else{
              SurveyAnswer::where('survey_detail_id',$surveydetailid)
                          ->where('question_id',$relquestion->related_question_id)
                          ->chunk(400, function ($results)use($surveydetailid,$relquestion){
                                foreach ($results as $suranswer) {
                                  $otoptId = null;
                                  $surans = SurveyAnswer::where('survey_detail_id',$surveydetailid)
                                              ->where('question_id',$relquestion->related_question_id)
                                              ->where('voter_id',$suranswer->voter_id)
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
                                                        ->where('voter_id',$suranswer->voter_id)
                                                        ->first();
                                            echo "<br>Updating/Storing tally #".$csurans->question_id." , from related question #".$relquestion->related_question_id." for candidate qualities: #".$relquestion->question_id." ".$optioncandidate->candidate_id." ".$optioncandidate->option;
                                            $tallyothervotes = TallyOtherVote::where('survey_detail_id',$surveydetailid)
                                                            ->where('question_id',$relquestion->question_id)
                                                            ->where('voter_id',$suranswer->voter_id)
                                                            ->first();
                                            if($tallyothervotes){
                                                  TallyOtherVote::where('id',$tallyothervotes->id)
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
      $suranswers = SurveyAnswer::whereIn('question_id',$questionId)->get();
      echo "<br>Record(s) Affected: ".(!empty($suranswers)?count($suranswers):0);
      if(!empty($curquestions) && count($curquestions)<=0){
          echo "Questions Info not found!";
      }else if(empty($curquestions)){
          echo "Questions Info not found!";
      }
  }
  public function insertUpdateSurveyProblems(Request $request){
    if($request->has('sid')){
        $surveydetailid = $request->sid;
    }else{
        $surveydetailid = 1;
    }
    $qids = array(1,2);
    if($request->has('qid')){
        if(in_array($request->qid,$qids)){
            $questionId = $qids;
        }else{
            $questionId = array($request->qid);
        }
    }
    $i = 0;
    $curquestions = Question::whereIn('id',$questionId)->get();
    foreach($curquestions as $curquestion){
      echo "Current Question: #".$curquestion->id." ".$curquestion->question;

      SurveyAnswer::where('survey_detail_id',$surveydetailid)
                  ->where('question_id',$curquestion->id)
                  ->chunk(400, function ($results)use($surveydetailid,&$i){
                        foreach ($results as $suranswer) {
                              $cquestionoption = QuestionOption::find($suranswer->option_id);
                              echo "<br>Voter's #".$suranswer->voter_id." Answer: ".$suranswer->option_id." ".$cquestionoption->option;
                              $voterbrgy = Voter::find($suranswer->voter_id);
                              $tallyothervotes = TallyOtherVote::where('survey_detail_id',$surveydetailid)
                                              ->where('question_id',$suranswer->question_id)
                                              ->where('voter_id',$suranswer->voter_id)
                                              ->first();
                              if($tallyothervotes){
                                  TallyOtherVote::where('id',$tallyothervotes->id)
                                                ->update(['barangay_id'=>$voterbrgy->barangay_id,
                                                          'option_id'=>$suranswer->option_id]);
                              }else{
                                  $tallyothervotedata = [
                                                      'survey_detail_id'=>$surveydetailid,
                                                      'barangay_id'=>$voterbrgy->barangay_id,
                                                      'question_id'=>$suranswer->question_id,
                                                      'option_id'=>$suranswer->option_id,
                                                      'voter_id'=>$suranswer->voter_id,
                                                      'user_id'=>$suranswer->user_id
                                                    ];
                                  TallyOtherVote::insert($tallyothervotedata);
                              }
                              $i++;
                        }
                  });
    }
    echo "<br>Record(s) Affected: ".$i;
    if(!empty($curquestions) && count($curquestions)<=0){
        echo "Questions Info not found!";
    }else if(empty($curquestions)){
        echo "Questions Info not found!";
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

      SurveyAnswer::where('survey_detail_id',$surveydetailid)
                  ->where('question_id',$questionId)
                  ->chunk(400, function ($results){
                        foreach ($results as $suranswer) {
                              $cquestionoption = QuestionOption::find($suranswer->option_id);
                              echo "<br>Voter's #".$suranswer->voter_id." Answer: ".$suranswer->option_id." ".$cquestionoption->option;
                        }
                  });
    }else{
        echo "Question Info not found!";
    }
  }
  public function updateSurveyQualities(Request $request){
    if($request->has('sid')){
        $surveydetailid = $request->sid;
    }else{
        $surveydetailid = 1;
    }
    $doUpdate = 0;
    if($request->has('doupdate')){
      $doUpdate = $request->doupdate;
    }
    $questionId = array(5,7,9,10,11,12);
    if($request->has('qid')){
        $questionId = array($request->qid);
    }
    $i=0;
    //$curquestions = Question::whereIn('id',$questionId)->select(['id'])->groupBy('id')->get();
    //foreach($curquestions as $curquestion){
    TallyOtherVote::with(['voter','user','candidate'])
                ->where('survey_detail_id',$surveydetailid)
                ->whereIn('question_id',$questionId)
                ->chunk(400, function ($results)use($surveydetailid,&$i){
                      foreach ($results as $suranswer) {
                              $surans = SurveyAnswer::with(['voter','user','candidate'])
                                          ->where('survey_detail_id',$suranswer->survey_detail_id)
                                          ->where('question_id',$suranswer->question_id)
                                          ->where('voter_id',$suranswer->voter_id)
                                          ->first();
                              if($surans){
                                    $i++;
                                    echo "<br>Qualities Survey #".$suranswer->question_id.
                                        " for candidate qualities: #".$suranswer->candidate_id." ".$suranswer->option->option.
                                        " ".$suranswer->candidate->full_name.
                                        " ,voter id: #".$surans->voter_id.
                                        " ".$surans->voter->full_name.
                                        " ,user id: #".$surans->user_id.
                                        " ".$surans->user->name;
                              }
                          }
                    });

      echo "<br>".$i." Record(s) Affected";
      $i = 0;
      if($doUpdate){
        TallyOtherVote::with(['voter','user','candidate'])
                    ->where('survey_detail_id',$surveydetailid)
                    ->whereIn('question_id',$questionId)
                    ->chunk(400, function ($results)use($surveydetailid,&$i){
                          foreach ($results as $suranswer) {
                                  $surans = SurveyAnswer::where('survey_detail_id',$suranswer->survey_detail_id)
                                              ->where('question_id',$suranswer->question_id)
                                              ->where('voter_id',$suranswer->voter_id)
                                              ->update(['candidate_id'=>$suranswer->candidate_id]);
                                  if($surans){
                                        $i++;
                                  }
                              }
                        });
        echo "<br>".$i." Record(s) Updated!";
      }
  }
  public function updateTallyOtherAnsQualities(Request $request){
    if($request->has('sid')){
        $surveydetailid = $request->sid;
    }else{
        $surveydetailid = 1;
    }
    $doUpdate = 0;
    if($request->has('doupdate')){
      $doUpdate = $request->doupdate;
    }
    $questionId = array(5,7,9,10,11,12);
    if($request->has('qid')){
        $questionId = array($request->qid);
    }
    $i=0;
    //$curquestions = Question::whereIn('id',$questionId)->select(['id'])->groupBy('id')->get();
    //foreach($curquestions as $curquestion){
    SurveyAnswer::with(['voter','user','candidate'])
                ->where('survey_detail_id',$surveydetailid)
                ->whereIn('question_id',$questionId)
                ->chunk(400, function ($results)use($surveydetailid,&$i){
                      foreach ($results as $suranswer) {
                              $surans = TallyOtherVote::with(['voter','user','candidate'])
                                          ->where('survey_detail_id',$suranswer->survey_detail_id)
                                          ->where('question_id',$suranswer->question_id)
                                          ->where('voter_id',$suranswer->voter_id)
                                          ->where('candidate_id',$suranswer->candidate_id)
                                          ->first();
                              if($surans){
                                    $i++;
                                    echo "<br>Qualities Survey #".$suranswer->question_id.
                                        " for candidate qualities: #".$suranswer->candidate_id." ".$suranswer->option->option.
                                        " ".$suranswer->candidate->full_name.
                                        " ,voter id: #".$surans->voter_id.
                                        " ".$surans->voter->full_name.
                                        " ,user id: #".$surans->user_id.
                                        " ".$surans->user->name;
                              }
                          }
                    });

      echo "<br>".$i." Record(s) Affected";
      $i = 0;
      if($doUpdate){
        SurveyAnswer::with(['voter','user','candidate'])
                    ->where('survey_detail_id',$surveydetailid)
                    ->whereIn('question_id',$questionId)
                    ->chunk(400, function ($results)use($surveydetailid,&$i){
                          foreach ($results as $suranswer) {
                                  $surans = TallyOtherVote::where('survey_detail_id',$suranswer->survey_detail_id)
                                              ->where('question_id',$suranswer->question_id)
                                              ->where('voter_id',$suranswer->voter_id)
                                              ->where('candidate_id',$suranswer->candidate_id)
                                              ->update(['other_answer'=>$suranswer->other_answer]);
                                  if($surans){
                                        $i++;
                                  }
                              }
                        });
        echo "<br>".$i." Record(s) Updated!";
      }
  }
  public function checkMissingTally(Request $request){

    $surveydetailid = 1;
    if($request->has('sid'))
      $surveydetailid = $request->sid;

    $questionId = array(4,6,8,3);
    if($request->has('qid')){
        $questionId = array($request->qid);
    }

    $doInsertMissing = 0;
    if($request->has('doinsertmissing'))
        $doInsertMissing = $request->doinsertmissing;

    //$curquestions = Question::whereIn('id',$questionId)->get();
    //foreach($curquestions as $curquestion){
      $i=0;
      $y=0;
      SurveyAnswer::with(['voter','user','question'])
                  ->where('survey_detail_id',$surveydetailid)
                  ->whereIn('question_id',$questionId)
                  ->orderBy('question_id')
                  ->orderBy('voter_id')
                  ->chunk(400, function ($results)use(&$i,&$y,$doInsertMissing){
                        foreach ($results as $suranswer) {
                              echo "<hr>Current Question: #".$suranswer->question_id." ".$suranswer->question->question."<hr>";
                              $cquestionoption = QuestionOption::find($suranswer->option_id);
                              $tally = TallyVote::where('question_id',$suranswer->question_id)
                                                ->where('voter_id',$suranswer->voter_id)
                                                ->where('option_id',$suranswer->option_id)
                                                ->where('candidate_id',$suranswer->candidate_id)
                                                ->where('user_id',$suranswer->user_id)
                                                ->first();
                              if(empty($tally)){
                                $y++;
                                echo "<hr>".$y.".) #".$suranswer->id." ,survey detail id: ".$suranswer->survey_detail_id." ,voter id: ".$suranswer->voter_id." ".$suranswer->voter->full_name." ,question_id: ".$suranswer->question_id." ,option id: ".$suranswer->option_id." ,user id: ".$suranswer->user_id." ".$suranswer->user->name;
                                echo " ,But not found in tally_votes table!";
                                echo "<hr>";
                              }else{
                                echo "<br>#".$suranswer->id." Voter's #".$suranswer->voter_id." Answer: ".$suranswer->option_id." ".$cquestionoption->option;
                              }
                            $i++;
                        }
                  });

    if($i==0){
        echo "Question Info not found!";
    }else{
        echo "<br><br>Record(s) Affected: ".$i;
        echo "<br>Record(s) Not Found: ".$y;
    }
    if($doInsertMissing){

            $i=0;
            $y=0;
            SurveyAnswer::with(['voter','user'])
                        ->where('survey_detail_id',$surveydetailid)
                        ->whereIn('question_id',$questionId)
                        ->orderBy('question_id')
                        ->orderBy('voter_id')
                        ->chunk(400, function ($results)use(&$i,&$y,$doInsertMissing){
                              foreach ($results as $suranswer) {
                                    $cquestionoption = QuestionOption::find($suranswer->option_id);
                                    $tally = TallyVote::where('question_id',$suranswer->question_id)
                                                      ->where('voter_id',$suranswer->voter_id)
                                                      ->where('option_id',$suranswer->option_id)
                                                      ->where('candidate_id',$suranswer->candidate_id)
                                                      ->where('user_id',$suranswer->user_id)
                                                      ->first();
                                    if(empty($tally)){
                                        $tallyothervotedata = [
                                                                'survey_detail_id'=>$suranswer->survey_detail_id,
                                                                'question_id'=>$suranswer->question_id,
                                                                'option_id'=>$suranswer->option_id,
                                                                'voter_id'=>$suranswer->voter_id,
                                                                'candidate_id'=>$optioncandidate->candidate_id,
                                                                'user_id'=>$suranswer->user_id,
                                                                'barangay_id'=>$suranswer->barangay_id
                                                              ];
                                        $insertdata = TallyOtherVote::insert($tallyothervotedata);
                                        if($insertdata){
                                          $y++;
                                        }
                                    }
                                    $i++;
                              }
                        });

                if($y>0){
                    echo $y." TOtal Record(s) Inserted!";
                }
    }
  }
  public function checkMissingTallyQualities(Request $request){

    $surveydetailid = 1;
    if($request->has('sid'))
      $surveydetailid = $request->sid;

    $questionId = array(5,7,9,10,11,12);
    if($request->has('qid')){
        $questionId = array($request->qid);
    }

    $doInsertMissing = 0;
    if($request->has('doinsertmissing'))
        $doInsertMissing = $request->doinsertmissing;

    $curquestions = Question::whereIn('id',$questionId)->get();
    foreach($curquestions as $curquestion){
      echo "<hr>Current Question: #".$curquestion->id." ".$curquestion->question."<hr>";
      $i=0;
      $y=0;
      SurveyAnswer::with(['voter','user'])
                  ->where('survey_detail_id',$surveydetailid)
                  ->where('question_id',$curquestion->id)
                  ->orderBy('question_id')
                  ->orderBy('voter_id')
                  ->chunk(400, function ($results)use(&$i,&$y,$doInsertMissing){
                        foreach ($results as $suranswer) {
                              $i++;
                              $cquestionoption = QuestionOption::find($suranswer->option_id);

                              $tallyovq = TallyOtherVote::where('question_id',$suranswer->question_id)
                                                          ->where('voter_id',$suranswer->voter_id)
                                                          ->where('option_id',$suranswer->option_id)
                                                          ->where('user_id',$suranswer->user_id)
                                                          ->first();
                              if(empty($tallyovq)){
                                $y++;
                                echo "<hr>".$y.".) #".$suranswer->id." ,survey detail id: ".$suranswer->survey_detail_id." ,voter id: ".$suranswer->voter_id." ".$suranswer->voter->full_name." ,question_id: ".$suranswer->question_id." ,option id: ".$suranswer->option_id." ".$cquestionoption->option." ,user id: ".$suranswer->user_id." ".$suranswer->user->name;
                                echo " ,But not found in tally_other_votes table!";

                                $relquestion = RelatedQuestion::where('question_id',$suranswer->question_id)->first();
                                if($relquestion){
                                  $otoptId = null;
                                  $surans = SurveyAnswer::where('survey_detail_id',$suranswer->survey_detail_id)
                                              ->where('question_id',$relquestion->related_question_id)
                                              ->where('voter_id',$suranswer->voter_id)
                                              ->orderBy('id')
                                              ->get();
                                  if(!empty($surans[$relquestion->cardinality-1])){
                                      $otoptId = $surans[$relquestion->cardinality-1]->option_id;
                                  }
                                  if(!empty($otoptId)){
                                    echo "<br>Option ID: ".$otoptId;
                                    $question = Question::find($suranswer->question_id);
                                    if(!empty($question->for_position) && is_numeric($question->for_position)){
                                      $optioncandidate = QuestionOption::find($otoptId);
                                      if($optioncandidate){
                                        echo "<br>Related Question: #".$suranswer->id." ,survey detail id: ".$suranswer->survey_detail_id." ,voter id: ".$suranswer->voter_id." ".$suranswer->voter->full_name." ,question_id: ".$suranswer->question_id." ,option id: ".$suranswer->option_id." ".$cquestionoption->option." candidate id: ".$optioncandidate->candidate_id." ".$$optioncandidate->option." ,user id: ".$suranswer->user_id." ".$suranswer->user->name;
                                      }

                                    }
                                  }
                                }
                                echo "<hr>";

                              }else{
                                echo "<br>#".$suranswer->id." ,survey detail id: ".$suranswer->survey_detail_id." ,voter id: ".$suranswer->voter_id." ".$suranswer->voter->full_name." ,question_id: ".$suranswer->question_id." ,option id: ".$suranswer->option_id." ".$cquestionoption->option." ,user id: ".$suranswer->user_id." ".$suranswer->user->name;
                              }

                        }
                  });
    }
    if($i==0){
        echo "Question Info not found!";
    }else{
        echo "<br><br>Record(s) Affected: ".$i;
        echo "<br>Record(s) Not Found: ".$y;
    }
    if($doInsertMissing){
        foreach($curquestions as $curquestion){
          $i=0;
          $y=0;
          SurveyAnswer::with(['voter','user'])
                      ->where('survey_detail_id',$surveydetailid)
                      ->where('question_id',$curquestion->id)
                      ->orderBy('question_id')
                      ->orderBy('voter_id')
                      ->chunk(400, function ($results)use(&$i,&$y,$doInsertMissing){
                            foreach ($results as $suranswer) {
                                  $i++;
                                  $cquestionoption = QuestionOption::find($suranswer->option_id);

                                  $tallyovq = TallyOtherVote::where('question_id',$suranswer->question_id)
                                                              ->where('voter_id',$suranswer->voter_id)
                                                              ->where('option_id',$suranswer->option_id)
                                                              ->where('user_id',$suranswer->user_id)
                                                              ->first();
                                  if(empty($tallyovq)){
                                          $relquestion = RelatedQuestion::where('question_id',$suranswer->question_id)->first();
                                          if($relquestion){
                                            $otoptId = null;
                                            $surans = SurveyAnswer::where('survey_detail_id',$suranswer->survey_detail_id)
                                                        ->where('question_id',$relquestion->related_question_id)
                                                        ->where('voter_id',$suranswer->voter_id)
                                                        ->orderBy('id')
                                                        ->get();
                                            if(!empty($surans[$relquestion->cardinality-1])){
                                                $otoptId = $surans[$relquestion->cardinality-1]->option_id;
                                            }
                                            if(!empty($otoptId)){
                                              $question = Question::find($suranswer->question_id);
                                              if(!empty($question->for_position) && is_numeric($question->for_position)){
                                                $optioncandidate = QuestionOption::find($otoptId);
                                                if($optioncandidate){
                                                  $tallyothervotedata = [
                                                                      'survey_detail_id'=>$suranswer->survey_detail_id,
                                                                      'question_id'=>$suranswer->question_id,
                                                                      'option_id'=>$suranswer->option_id,
                                                                      'voter_id'=>$suranswer->voter_id,
                                                                      'candidate_id'=>$optioncandidate->candidate_id,
                                                                      'user_id'=>$suranswer->user_id,
                                                                      'barangay_id'=>$suranswer->barangay_id
                                                                    ];
                                                  $insertdata = TallyOtherVote::insert($tallyothervotedata);
                                                  if($insertdata){
                                                    $y++;
                                                  }else{
                                                    echo $insertdata;
                                                  }
                                                }

                                              }
                                            }
                                          }

                                  }

                            }
                      });
            }
            if($y>0){
                echo $y." TOtal Record(s) Inserted!";
            }
    }
  }
  public function checkMissingTallyProblems(Request $request){
    $surveydetailid = 1;
    if($request->has('sid'))
      $surveydetailid = $request->sid;

    $questionId = array(1,2);
    if($request->has('qid')){
        $questionId = array($request->qid);
    }

    $doInsertMissing = 0;
    if($request->has('doinsertmissing'))
        $doInsertMissing = $request->doinsertmissing;

    $curquestions = Question::whereIn('id',$questionId)->get();
    foreach($curquestions as $curquestion){
      $i=0;
      $y=0;
      echo "<hr>Current Question: #".$curquestion->id." ".$curquestion->question."<hr>";

      SurveyAnswer::with(['voter','user'])
                  ->where('survey_detail_id',$surveydetailid)
                  ->where('question_id',$curquestion->id)
                  ->orderBy('question_id')
                  ->orderBy('voter_id')
                  ->chunk(400, function ($results)use(&$i,&$y){
                        foreach ($results as $suranswer) {
                              $cquestionoption = QuestionOption::find($suranswer->option_id);
                              $voterbrgy = Voter::find($suranswer->voter_id);
                              $tallyproblems = TallyOtherVote::where('survey_detail_id',$suranswer->survey_detail_id)
                                              ->where('question_id',$suranswer->question_id)
                                              ->where('voter_id',$suranswer->voter_id)
                                              ->first();
                              if(empty($tallyproblems)){
                                $y++;
                                echo "<hr>".$y.".) #".$suranswer->id." ,survey detail id: ".$suranswer->survey_detail_id." ,voter id: ".$suranswer->voter_id." ".$suranswer->voter->full_name." ,question_id: ".$suranswer->question_id." ,option id: ".$suranswer->option_id." ,user id: ".$suranswer->user_id." ".$suranswer->user->name;
                                echo " ,But not found in tally_other_votes table!";
                                echo "<hr>";
                              }else{
                                echo "<br>#".$suranswer->id." ,survey detail id: ".$suranswer->survey_detail_id." ,voter id: ".$suranswer->voter_id." ".$suranswer->voter->full_name." ,question_id: ".$suranswer->question_id." ,option id: ".$suranswer->option_id." ,user id: ".$suranswer->user_id." ".$suranswer->user->name;
                              }
                            $i++;
                        }
                  });
      }
      if($i==0){
          echo "Question Info not found!";
      }else{
          echo "<br><br>Record(s) Affected: ".$i;
          echo "<br>Record(s) Not Found: ".$y;
      }
      if($doInsertMissing){
          foreach($curquestions as $curquestion){
            $i=0;
            $y=0;
            SurveyAnswer::with(['voter','user'])
                        ->where('survey_detail_id',$surveydetailid)
                        ->where('question_id',$curquestion->id)
                        ->orderBy('question_id')
                        ->orderBy('voter_id')
                        ->chunk(400, function ($results)use(&$i,&$y){
                              foreach ($results as $suranswer) {
                                    $cquestionoption = QuestionOption::find($suranswer->option_id);
                                    $voterbrgy = Voter::find($suranswer->voter_id);
                                    $tallyproblems = TallyOtherVote::where('survey_detail_id',$suranswer->survey_detail_id)
                                                    ->where('question_id',$suranswer->question_id)
                                                    ->where('voter_id',$suranswer->voter_id)
                                                    ->first();
                                    if(empty($tallyproblems)){
                                      $tallyothervotedata = [
                                                              'survey_detail_id'=>$suranswer->survey_detail_id,
                                                              'barangay_id'=>$voterbrgy->barangay_id,
                                                              'question_id'=>$suranswer->question_id,
                                                              'option_id'=>$suranswer->option_id,
                                                              'voter_id'=>$suranswer->voter_id,
                                                              'user_id'=>$suranswer->user_id
                                                            ];
                                      $insertdata = TallyOtherVote::insert($tallyothervotedata);
                                      if($insertdata){
                                        $y++;
                                      }
                                    }
                                  $i++;
                              }
                        });
                }
            if($y>0){
                echo $y." TOtal Record(s) Inserted!";
            }
        }
  }
  public function checkDuplicateSurvey(Request $request){
    $surveydetailid = 1;
    if($request->has('sid'))
      $surveydetailid = $request->sid;

    $qidsproblems = array(1,2);
    $qidstally = array(4,6,8,3);
    $qidsqualities = array(5,7,9,10,11,12);
    $questionId = array(1,2,4,6,8,3,5,7,9,10,11,12); // array(4,6,8,3)  array(5,7,9,10,11,12)
    if($request->has('qid')){
        $questionId = array($request->qid);
    }
    $doDeleteDuplicate = 0;
    if($request->has('dodeleteduplicate'))
        $doDeleteDuplicate = $request->dodeleteduplicate;

    $curquestions = Question::whereIn('id',$questionId)->get();
    foreach($curquestions as $curquestion){
      echo "<hr>Current Question: #".$curquestion->id." ".$curquestion->question."<hr>";
      $i=0;
      $y=0;
      SurveyAnswer::with(['voter','user'])
                  ->where('survey_detail_id',$surveydetailid)
                  ->where('question_id',$curquestion->id)
                  ->orderBy('id')
                  ->chunk(400, function ($results)use(&$i,&$y,$doDeleteDuplicate,$qidstally,$qidsproblems,$qidsqualities){
                        foreach ($results as $suranswer) {
                              $dupsurans = SurveyAnswer::with(['voter','user'])
                                                          ->where('user_id','<>',$suranswer->user_id)
                                                          ->where('question_id',$suranswer->question_id)
                                                          ->where('voter_id',$suranswer->voter_id)
                                                          ->first();

                              if(!empty($dupsurans)){
                                $y++;
                                echo "<hr>Entry! ";
                                echo "<br>#".$suranswer->id." ,survey detail id: ".$suranswer->survey_detail_id." ,voter id: ".$suranswer->voter_id." ".$suranswer->voter->full_name." ,question_id: ".$suranswer->question_id." ,option id: ".$suranswer->option_id." ,user id: ".$suranswer->user_id." ".$suranswer->user->name;
                                echo "<br>Duplicate Entry! ";
                                echo "<br>#".$dupsurans->id." ,survey detail id: ".$dupsurans->survey_detail_id." ,voter id: ".$dupsurans->voter_id." ".$dupsurans->voter->full_name." ,question_id: ".$dupsurans->question_id." ,option id: ".$dupsurans->option_id." ,user id: ".$dupsurans->user_id." ".$dupsurans->user->name;
                              }
                              $i++;
                        }
                  });

    }

    if($i==0){
        echo "Question Info not found!";
    }else{
      echo "<br><br>Record(s) Affected: ".$i;
      echo "<br>Duplicate Record(s): ".$y;
    }
    if($doDeleteDuplicate){
        foreach($curquestions as $curquestion){
          $i=0;
          $y=0;
          SurveyAnswer::with(['voter','user'])
                      ->where('survey_detail_id',$surveydetailid)
                      ->where('question_id',$curquestion->id)
                      ->orderBy('voter_id')
                      ->chunk(400, function ($results)use(&$i,&$y,$doDeleteDuplicate,$qidstally,$qidsproblems,$qidsqualities){
                            foreach ($results as $suranswer) {
                                  $dupsurans = SurveyAnswer::with(['voter','user'])
                                                              ->where('user_id','<>',$suranswer->user_id)
                                                              ->where('question_id',$suranswer->question_id)
                                                              ->where('voter_id',$suranswer->voter_id)
                                                              ->first();

                                  if(!empty($dupsurans)){
                                      $duptallyvotes = TallyVote::where('question_id',$dupsurans->question_id)
                                                                  ->where('voter_id',$dupsurans->voter_id)
                                                                  ->where('user_id',$dupsurans->user_id);
                                      $deleteduptallyvotes = $duptallyvotes->delete();
                                      echo "<br>".$deleteduptallyvotes;
                                      //if($deleteduptallyvotes){
                                      $duptallyqp = TallyOtherVote::where('question_id',$dupsurans->question_id)
                                                                    ->where('voter_id',$dupsurans->voter_id)
                                                                    ->where('user_id',$dupsurans->user_id);
                                      $deleteduptallyqp = $duptallyqp->delete();
                                      echo "<br>".$deleteduptallyqp;
                                          //if($deleteduptallyqp){
                                              // $deletedupsurvey= SurveyAnswer::find($dupsurans->id)->delete();
                                              // echo "<br>".$deletedupsurvey;
                                              // if($deletedata){

                                              //}
                                          //}
                                      //}
                                      $y++;
                                  }
                                  $i++;
                            }
                      });
            }
        if($y>0){
            echo "<br>".$y." Total Record(s) Deleted!";
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
