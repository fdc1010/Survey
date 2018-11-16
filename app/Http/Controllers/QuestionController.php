<?php

namespace App\Http\Controllers;
use App\Models\Question;
use App\Models\QuestionDetail;
use Illuminate\Http\Request;

class QuestionController extends Controller
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
	public function getQuestions(Request $request){
		$result = QuestionDetail::with(['question'=>function($q){
											$q->with('choices')
											  ->select(['id','question','number_answers','priority',
													'type_id','for_position','with_other_ans',
													'with_partyselect']);
										])->get();
		/*$result = Question::with(['choices'=>function($c){
								$c->select(['id','option','priority']);
							}])
							->select(['id','question','number_answers','priority',
													'type_id','for_position','with_other_ans',
													'with_partyselect'])
							->get();*/
		return response()->json($result);
		
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
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        //
    }
}
