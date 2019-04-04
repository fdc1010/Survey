<?php

namespace App\Http\Controllers;

use App\Models\TallyVote;
use App\Models\SurveyAnswer;
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
      $tallyVotes = TallyVote::where('survey_detail_id',2)
                               //->where('id','<>',7634)
                               //->whereNotIn('id',[7634,7651,7657])
                               ->whereNotIn('question_id',[3])
                               ->orderBy('question_id')
                               //->whereIn('option_id',[49,50,51,52])
                               ->chunk(400, function ($tallyVotes)use(&$delTallyVotetotal){

                                    foreach($tallyVotes as $tallyVote){
                                        $delTallyVotes = TallyVote::where('survey_detail_id',$tallyVote->survey_detail_id)
                                                                  ->where('id','>',$tallyVote->id)
                                                                  //->whereNotIn('id',[7635,7636,7652,7653])
                                                                  ->where('question_id',$tallyVote->question_id)
                                                                  //->where('user_id',$tallyVote->user_id)
                                                                  ->where('voter_id',$tallyVote->voter_id)
                                                                  //->whereIn('option_id',[49,50,51,52])
                                                                  ->get();
                                                                  //->delete();
                                        if(!empty($delTallyVotes) && count($delTallyVotes)>0){
                                            echo "Current Entry:#".$tallyVote->id." Survey ID:#".$tallyVote->survey_detail_id." | ".$tallyVote->user_id." | ".$tallyVote->voter_id." | Question & Answer: ".$tallyVote->question_id." | ".$tallyVote->option_id."<br>";
                                            echo "======================================<br>";
                                            foreach($delTallyVotes as $delTallyVote){
                                              //if(in_array($delTallyVote->option_id,[49,50,51,52])){
                                                echo "Duplicate Entry:#".$delTallyVote->id." Survey ID:#".$delTallyVote->survey_detail_id." | ".$delTallyVote->user_id." | ".$delTallyVote->voter_id." | Question & Answer: ".$delTallyVote->question_id." | ".$delTallyVote->option_id."<br>";
                                                $delTallyVotetotal++;// += $delTallyVotes->count();
                                                TallyVote::find($delTallyVote->id)->delete();
                                              //}
                                            }
                                            echo "======================================<br>";
                                        }
                                    }

                                });
      echo $delTallyVotetotal;
    }
    public function deleteVoterDuplicateSurveyAnswer(Request $request){
      $delTallyVotetotal = 0;
      $tallyVotes = SurveyAnswer::where('survey_detail_id',2)
                               //->where('id','<>',7634)
                               ->orderBy('question_id')
                               //->whereIn('option_id',[49,50,51,52])
                               ->chunk(400, function ($tallyVotes)use(&$delTallyVotetotal){
                                    foreach($tallyVotes as $tallyVote){
                                        $delTallyVotes = SurveyAnswer::where('survey_detail_id',$tallyVote->survey_detail_id)
                                                                  ->where('id','>',$tallyVote->id)
                                                                  //->whereNotIn('id',[7635,7636])
                                                                  ->where('question_id',$tallyVote->question_id)
                                                                  ->where('user_id',$tallyVote->user_id)
                                                                  ->where('voter_id',$tallyVote->voter_id)
                                                                  ->get();
                                                                  //->delete();
                                        if(!empty($delTallyVotes) && count($delTallyVotes)>0){
                                            echo "Current Entry:#".$tallyVote->id." Survey ID:#".$tallyVote->survey_detail_id." | ".$tallyVote->user_id." | ".$tallyVote->voter_id." | Question & Answer: ".$tallyVote->question_id." | ".$tallyVote->option_id."<br>";
                                            echo "======================================<br>";
                                            foreach($delTallyVotes as $delTallyVote){
                                              //if(in_array($delTallyVote->option_id,[49,50,51,52])){
                                                echo "Duplicate Entry:#".$delTallyVote->id." Survey ID:#".$delTallyVote->survey_detail_id." | ".$delTallyVote->user_id." | ".$delTallyVote->voter_id." | Question & Answer: ".$delTallyVote->question_id." | ".$delTallyVote->option_id."<br>";
                                                $delTallyVotetotal++;// += $delTallyVotes->count();
                                                //SurveyAnswer::find($delTallyVote->id)->delete();
                                              //}
                                            }
                                            echo "======================================<br>";
                                        }
                                    }

                                });
      echo $delTallyVotetotal;
    }
}
