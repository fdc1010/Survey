<?php

namespace App\Http\Controllers;

use App\Models\Voter;
use App\User;
use App\Models\Candidate;
use App\Models\TallyVote;
use App\Models\SurveyAnswer;
use App\Models\QuestionOption;
use App\Models\Question;
use App\Models\Barangay;
use App\Models\Precinct;
use App\Models\PositionCandidate;
use App\Models\AssignmentDetail;
use App\Models\SurveyDetail;
use App\Models\SurveyorAssignment;
use App\Models\BarangaySurveyable;
use Illuminate\Http\Request;

class TallyVoteController extends Controller
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
     * @param  \App\TallyVote  $tallyVote
     * @return \Illuminate\Http\Response
     */
    public function show(TallyVote $tallyVote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TallyVote  $tallyVote
     * @return \Illuminate\Http\Response
     */
    public function edit(TallyVote $tallyVote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TallyVote  $tallyVote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TallyVote $tallyVote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TallyVote  $tallyVote
     * @return \Illuminate\Http\Response
     */
    public function destroy(TallyVote $tallyVote)
    {
        //
    }
    public function deleteVoterDuplicateTally(Request $request){
      $delTallyVotetotal = 0;
      $duptallyvote = 0;
      $cnt = 1;
      $markeddelcnt = 1;

      $sid = 2;
      if($request->has('sid') && $request->sid > 0){
        $sid = $request->sid;
      }

      $optid = 48;
      if($request->has('optid') && $request->optid > 0){
        $optid = $request->optid;
      }

      $vid = 1;
      if($request->has('vid ') && $request->vid > 0){
        $vid = $request->vid;
      }

      $questionId = array(4,6,8,3);
      if($request->has('qid')){
        $questionId = array($request->qid);
      }

      $tallyVotes = TallyVote::where('survey_detail_id',$sid)
                               //->where('id','<>',7634)
                               ->whereIn('question_id',$questionId)
                               ->where('option_id','>=',$optid)
                               ->where('voter_id','>=',$vid)
                               ->orderBy('voter_id')
                               ->get();//chunk(400, function ($tallyVotes)use(&$delTallyVotetotal){
                                    foreach($tallyVotes as $tallyVote){
                                        $delTallyVotes = TallyVote::where('survey_detail_id',$tallyVote->survey_detail_id)
                                                                  ->where('id','>',$tallyVote->id)
                                                                  ->where('question_id',$tallyVote->question_id)
                                                                  //->where('user_id',$tallyVote->user_id)
                                                                  ->where('voter_id',$tallyVote->voter_id)
                                                                  //->where('option_id','>',48)
                                                                  ->get();
                                                                  //->delete();
                                        $user = User::find($tallyVote->user_id);
                                        $voter = Voter::find($tallyVote->voter_id);
                                        $tallyOption = QuestionOption::find($tallyVote->option_id);
                                        if(!empty($delTallyVotes) && count($delTallyVotes)>0){
                                          echo "<span style='color: red;'>(".$markeddelcnt++.") ".$cnt++.".) Current Entry: #".$tallyVote->id." Survey ID:#".$tallyVote->survey_detail_id." | user: ".$tallyVote->user_id." (".$user->name.") | voter: ".$tallyVote->voter_id." (".$voter->full_name.") | Question & Answer: ".$tallyVote->question_id." | option: ".$tallyVote->option_id." (".$tallyOption->option.")</span><br>";
                                        }else{
                                          echo $cnt++.".) Current Entry:#".$tallyVote->id." Survey ID:#".$tallyVote->survey_detail_id." | user: ".$tallyVote->user_id." (".$user->name.") | voter: ".$tallyVote->voter_id." (".$voter->full_name.") | Question & Answer: ".$tallyVote->question_id." | option: ".$tallyVote->option_id." (".$tallyOption->option.")<br>";
                                        }
                                        if(!empty($delTallyVotes) && count($delTallyVotes)>0){
                                            echo "======================================<br>";
                                            foreach($delTallyVotes as $delTallyVote){
                                              $delUser = User::find($delTallyVote->user_id);
                                              $delTallyOption = QuestionOption::find($delTallyVote->option_id);
                                              //if(in_array($delTallyVote->option_id,[49,50,51,52])){
                                                echo "Duplicate Entry:#".$delTallyVote->id." Survey ID:#".$delTallyVote->survey_detail_id." | user: ".$delTallyVote->user_id." (".$delUser->name.") | voter: ".$delTallyVote->voter_id." (".$voter->full_name.") | Question & Answer: ".$delTallyVote->question_id." | option: ".$delTallyVote->option_id." (".$delTallyOption->option.")<br>";
                                                //$delTallyVotetotal++;// += $delTallyVotes->count();
                                                //SurveyAnswer::find($delTallyVote->id)->delete();
                                              //}
                                            }
                                            echo "======================================<br>";
                                        }
                                        if(!empty($delTallyVotes) && count($delTallyVotes)>0){
                                          if($request->has('delete') && $request->delete==1){
                                            $tallyVote->delete();
                                            $delTallyVotetotal++;
                                          }
                                        }
                                    }
                                //});
      echo "<br>Record(s):".($cnt-1)."<br>Record(s) to be Deleted: ".($markeddelcnt-1)."<br>Deleted Record(s): ".$delTallyVotetotal;
    }
    public function deleteVoterDuplicateSurveyAnswer(Request $request){
      $delTallyVotetotal = 0;
      $duptallyvote = 0;
      $cnt = 1;
      $markeddelcnt = 1;
      $sid = 2;
      if($request->has('sid') && $request->sid > 0){
        $sid = $request->sid;
      }
      $optid = 48;
      if($request->has('optid') && $request->optid > 0){
        $optid = $request->optid;
      }
      $vid = 1;
      if($request->has('vid ') && $request->vid > 0){
        $vid = $request->vid;
      }
      $questionId = array(4,6,8,3);
      if($request->has('qid')){
          $questionId = array($request->qid);
      }
      $tallyVotes = SurveyAnswer::where('survey_detail_id',$sid)
                               //->where('id','<>',7634)
                               ->whereIn('question_id',$questionId)
                               ->where('option_id','>=',$optid)
                               ->where('voter_id','>=',$vid)
                               ->orderBy('voter_id')
                               ->get();//chunk(400, function ($tallyVotes)use(&$delTallyVotetotal){
                                    foreach($tallyVotes as $tallyVote){
                                        $delTallyVotes = SurveyAnswer::where('survey_detail_id',$tallyVote->survey_detail_id)
                                                                  ->where('id','>',$tallyVote->id)
                                                                  ->where('question_id',$tallyVote->question_id)
                                                                  //->where('user_id',$tallyVote->user_id)
                                                                  ->where('voter_id',$tallyVote->voter_id)
                                                                  //->where('option_id','>',48)
                                                                  ->get();
                                                                  //->delete();
                                        $user = User::find($tallyVote->user_id);
                                        $voter = Voter::find($tallyVote->voter_id);
                                        $tallyOption = QuestionOption::find($tallyVote->option_id);
                                        if(!empty($delTallyVotes) && count($delTallyVotes)>0){
                                          echo "<span style='color: red;'>(".$markeddelcnt++.") ".$cnt++.".) Current Entry: #".$tallyVote->id." Survey ID:#".$tallyVote->survey_detail_id." | user: ".$tallyVote->user_id." (".$user->name.") | voter: ".$tallyVote->voter_id." (".$voter->full_name.") | Question & Answer: ".$tallyVote->question_id." | option: ".$tallyVote->option_id." (".$tallyOption->option.")</span><br>";
                                        }else{
                                          echo $cnt++.".) Current Entry:#".$tallyVote->id." Survey ID:#".$tallyVote->survey_detail_id." | user: ".$tallyVote->user_id." (".$user->name.") | voter: ".$tallyVote->voter_id." (".$voter->full_name.") | Question & Answer: ".$tallyVote->question_id." | option: ".$tallyVote->option_id." (".$tallyOption->option.")<br>";
                                        }
                                        if(!empty($delTallyVotes) && count($delTallyVotes)>0){
                                            echo "======================================<br>";
                                            foreach($delTallyVotes as $delTallyVote){
                                              $delUser = User::find($delTallyVote->user_id);
                                              $delTallyOption = QuestionOption::find($delTallyVote->option_id);
                                              //if(in_array($delTallyVote->option_id,[49,50,51,52])){
                                                echo "Duplicate Entry:#".$delTallyVote->id." Survey ID:#".$delTallyVote->survey_detail_id." | user: ".$delTallyVote->user_id." (".$delUser->name.") | voter: ".$delTallyVote->voter_id." (".$voter->full_name.") | Question & Answer: ".$delTallyVote->question_id." | option: ".$delTallyVote->option_id." (".$delTallyOption->option.")<br>";
                                                //$delTallyVotetotal++;// += $delTallyVotes->count();
                                                //SurveyAnswer::find($delTallyVote->id)->delete();
                                              //}
                                            }
                                            echo "======================================<br>";
                                        }
                                        if(!empty($delTallyVotes) && count($delTallyVotes)>0){
                                          if($request->has('delete') && $request->delete==1){
                                            $tallyVote->delete();
                                            $delTallyVotetotal++;
                                          }
                                        }
                                    }
                                //});
      echo "<br>Record(s):".($cnt-1)."<br>Record(s) to be Deleted: ".($markeddelcnt-1)."<br>Deleted Record(s): ".$delTallyVotetotal;
    }
    public function updateVoterUndecidedAnonymous(Request $request){
      $cnt = 1;
      $tallyVotes = TallyVote::where('survey_detail_id',2)
                               //->where('option_id','>',48)
                               //->where('question_id','>',3)
                               //->where('voter_id','>',231937)
                               //->where('barangay_id',82)
                               ->orderBy('voter_id')
                               ->get();//chunk(400, function ($tallyVotes)use(&$delTallyVotetotal){
                                    foreach($tallyVotes as $tallyVote){
                                        $delTallyVotes = TallyVote::where('survey_detail_id',$tallyVote->survey_detail_id)
                                                                  //->where('id','>',$tallyVote->id)
                                                                  //->where('question_id',$tallyVote->question_id)
                                                                  //->where('user_id',$tallyVote->user_id)
                                                                  ->where('voter_id',$tallyVote->voter_id)
                                                                  //->where('option_id','>',48)
                                                                  //->where('barangay_id','<>',$tallyVote->barangay_id)
                                                                  ->where('user_id','<>',$tallyVote->user_id)
                                                                  ->get();
                                                                  //->delete();
                                        $user = User::find($tallyVote->user_id);
                                        $voter = Voter::find($tallyVote->voter_id);
                                        $tallyOption = QuestionOption::find($tallyVote->option_id);
                                        if(!empty($delTallyVotes) && count($delTallyVotes)>0){
                                          echo $cnt++.".) Current Entry:#".$tallyVote->id." Survey ID:#".$tallyVote->survey_detail_id." | brgy: ".$tallyVote->barangay_id." | user: ".$tallyVote->user_id." (".$user->name.") | voter: ".$tallyVote->voter_id." (".$voter->full_name.") | Question & Answer: ".$tallyVote->question_id." | option: ".$tallyVote->option_id." (".$tallyOption->option.") | rec(s)".count($delTallyVotes)."<br>";
                                          //echo "<br>".count($delTallyVotes)."</br>";
                                          // $anonymousvoterbrgy = Barangay::find(83); // Barangay for Anonymous
                                          // $anonymousvoterprec = Precinct::where('barangay_id',$anonymousvoterbrgy->id)->first();
                                          // $voter->barangay_id=$anonymousvoterbrgy->id;
                                          // $voter->barangay_name = $anonymousvoterbrgy->name;
                                          // $voter->precinct_id = $anonymousvoterprec->id;
                                          // $voter->precinct_number = $anonymousvoterprec->precinct_number;
                                          // $vmsg = $voter->save();
                                          // echo "<br>".$vmsg."<br>";
                                          // $tallyVote->barangay_id=$anonymousvoterbrgy->id;
                                          // $tallyVote->save();
                                        }
                                    }
                                //});
      echo "<br>Record(s):".($cnt-1);
    }
    public function getRecTallyDetails(Request $request){
      $cnt = 1;
      $agebrackets=[];
      $brgyid=0;
      $civilstatusid=0;
      $empstatusid=0;
      $occstatusid=0;
      $voterstatusid=0;
      $genderid=0;
      $id = 37;
      if($request->has('sid'))
        $id = $request->sid;

      $questionidsfortally = Question::where('isfor_tallyvotes',1)->get()->pluck('id')->toArray();
      if($request->has('qid')){
          $questionidsfortally = array($request->qid);
      }
      $brgyarr = BarangaySurveyable::get()->pluck('barangay_id')->toArray();
      if($request->has('brgyid')){
          $brgyarr = array($request->brgyid);
      }
      $tallypoll = new TallyVote;
      $surveyassignment = SurveyorAssignment::find($id);
      $tally = array();

  		$areas = AssignmentDetail::where('assignment_id',$id)
                      ->whereIn('barangay_id',$brgyarr)
  										->with('barangay')
  										->get();
  		echo "<h4>Assigned Areas: #".$id."</h4><table border='1'>";
  		foreach($areas as $area){
        $positions = PositionCandidate::with('candidates')->get();
        foreach($positions as $position){
          $votes = 0;

          foreach($position->candidates as $candidate){
            $votes += $tallypoll->tallydetails($candidate->id,$surveyassignment->survey_detail_id,[],$area->barangay->id,0,0,0,0,0);
          }

          echo "<tr><td style='text-align: right;'>".$position->name."</td>".
                       "<td>".$votes."</td></tr>";

        }
        $brgyid=$area->barangay->id;
        $Voterssurvey = TallyVote::where('survey_detail_id',$surveyassignment->survey_detail_id)
                        ->where('user_id',$surveyassignment->user_id)
                        ->whereIn('question_id',$questionidsfortally)
                        ->whereIn('barangay_id',$brgyarr)
                        //->has('surveyanswer')
                        ->has('voter')
                        ->with(['voter'=>function($q){$q->with('statuses')->orderBy('id');}])
                        //->select(['voter_id','survey_detail_id'])
                        //->groupBy('voter_id')
                        ->orderBy('voter_id')
                        ->get();
        foreach($positions as $position){
          echo "<tr><td style='vertical-align: top;'><table border='1'>";
          foreach($position->candidates as $candidate){
            $Votersvote = TallyVote::where('candidate_id',$candidate->id)
          					->where('survey_detail_id',$surveyassignment->survey_detail_id)
                    ->whereIn('question_id',$questionidsfortally)
                    //->has('surveyanswer')
          					->whereHas('voter',function($q)use($agebrackets,$brgyid,$civilstatusid,$empstatusid,$occstatusid,$voterstatusid,$genderid){
                          if(count($agebrackets)>0){
                            $q->whereIn('age',$agebrackets);
                          }
          								if($brgyid>0){
          									$q->where('barangay_id',$brgyid);
          								}
                          if($genderid>0){
          									$q->where('gender_id',$genderid);
          								}
          								if($civilstatusid>0){
          									$q->where('civil_status_id',$civilstatusid);
          								}
                          if($empstatusid>0){
          									$q->where('employment_status_id',$empstatusid);
          								}
                          if($occstatusid>0){
          									$q->where('occupancy_status_id',$occstatusid);
          								}
                          if($voterstatusid>0){
          									$q->whereHas('statuses',function($qv)use($voterstatusid){
                        						$qv->where('status_id',$voterstatusid);
          												});
          								}
          							})
                      ->with(['voter'=>function($q){$q->with('statuses')->orderBy('id');}])
                      //->select(['voter_id','barangay_id','question_id','candidate_id','option_id','user_id','survey_detail_id'])
                      ->orderBy('voter_id')
                      ->get();

                foreach($Votersvote as $Votervote){
                  //dd($Votervote->voter);
                  //foreach($Votervote->voter as $Vvoter){

                    echo "<tr>".
                          "<td>".($cnt++)."</td>".
                          "<td>".$Votervote->voter->id_full_name."</td>".
                          "<td>".$Votervote->voter->barangay_id."</td>".
                          "<td>".$Votervote->voter->barangay_name."</td>".
                          "<td>".$Votervote->survey_detail_id."</td>".
                          "<td>".$Votervote->voter->gender_id."</td>".
                          "<td>".$Votervote->voter->civil_status_id."</td>".
                          "<td>".$Votervote->voter->employment_status_id."</td>".
                          "<td>".$Votervote->voter->occupancy_status_id."</td>".
                          "<td>";
                    foreach($Votervote->voter->statuses as $status){
                      echo $status->id.",";
                    }
                      echo "</td>";
                    echo "</tr>";
                  //}
              }
          }
          echo "</table></td>";
          echo "<td style='vertical-align: top;'><table border='1'>";
          $cnt = 1;
          foreach($Voterssurvey as $Votersurvey){
            echo "<tr>".
                  "<td>".($cnt++)."</td>".
                  "<td>".$Votersurvey->voter->id_full_name."</td>".
                  "<td>".$Votersurvey->voter->barangay_id."</td>".
                  "<td>".$Votersurvey->voter->barangay_name."</td>".
                  "<td>".$Votersurvey->survey_detail_id."</td>".
                  "<td>".$Votersurvey->voter->gender_id."</td>".
                  "<td>".$Votersurvey->voter->civil_status_id."</td>".
                  "<td>".$Votersurvey->voter->employment_status_id."</td>".
                  "<td>".$Votersurvey->voter->occupancy_status_id."</td>".
                  "<td>";
            foreach($Votersurvey->voter->statuses as $status){
              echo $status->id.",";
            }
              echo "</td>";
            echo "</tr>";
          }
          echo "</table></td></tr>";
        }
      }
      echo "</table>";
    }
}
