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
      $delTallyVotetotal = 0;
      $tallyVotes = TallyVote::where('survey_detail_id',2)->get();
      foreach($tallyVotes as $tallyVote){
          $delTallyVotes = TallyVote::where('survey_detail_id',2)
                                    ->where('id','<>',$tallyVote->id)
                                    ->where('question_id',$tallyVote->question_id)
                                    ->where('user_id',$tallyVote->user_id)
                                    ->where('voter_id',$tallyVote->voter_id)
                                    ->where('question_id',4)
                                    ->get();
                                    //->delete();
          $delTallyVotetotal += $delTallyVotes->count();
          echo "Current Entry:#".$tallyVote->id." Survey ID:#".$tallyVote->survey_detail_id." | ".$tallyVote->user_id." | ".$tallyVote->voter_id." | ".$tallyVote->question_id."<br>";
          foreach($delTallyVotes as $delTallyVote){
            echo "Duplicate Entry:#".$delTallyVote->id." Survey ID:#".$delTallyVote->survey_detail_id." | ".$delTallyVote->user_id." | ".$delTallyVote->voter_id." | ".$delTallyVote->question_id."<br>";
          }
      }
      echo $delTallyVotetotal;
    }
}
