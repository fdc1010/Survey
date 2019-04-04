<?php

namespace App\Http\Controllers;

use App\Models\TallyVote;
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

      $tallyVotes = TallyVote::where('survey_detail_id',2)
                               ->orderBy('question_id')
                               //->whereIn('option_id',[49,50,51,52])
                               ->chunk(400, function ($tallyVotes){
                                    $delTallyVotetotal = 0;
                                    foreach($tallyVotes as $tallyVote){
                                        $delTallyVotes = TallyVote::where('survey_detail_id',$tallyVote->survey_detail_id)
                                                                  ->where('id','>',$tallyVote->id)
                                                                  ->where('question_id',$tallyVote->question_id)
                                                                  ->where('user_id',$tallyVote->user_id)
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
                                                $delTallyVotetotal += $delTallyVotes->count();
                                                //$delTallyVotes->delete();
                                              //}
                                            }
                                            echo "======================================<br>";
                                        }
                                    }
                                    echo $delTallyVotetotal;
                                });
    }
}
