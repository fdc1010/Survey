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
		/*$result = QuestionDetail::with(['question'=>function($q){
											$q->select(['id','question','number_answers','priority',
													'type_id','for_position','with_other_ans',
													'with_partyselect']);
										},'option'=>function($op){
											$op->select(['id','option','priority']);
										}])
									->select(['question_id','option_id','with_option_other_ans'])
									->get();*/
		$result = Question::with(['questiondetail'=>function($qd){
								$qd->select(['question_id','option_id','with_option_other_ans'])
									->with(['option'=>function($op){
											$op->select(['id','option','priority'])->orderby('priority');
										}]);
								},'type'=>function($t){
									$t->select(['id','type_name']);
								},'forposition'=>function($p){
									$p->with(['candidates'=>function($c){
											$c->with('party');
										}]);	
								}])
							->select(['id','question','number_answers','priority',
													'type_id','for_position','with_other_ans',
													'with_partyselect'])
							->orderby('priority')
							->get();
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
