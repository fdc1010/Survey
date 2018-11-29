<?php

namespace App\Http\Controllers\Mobile;
use App\Models\Question;
use App\Models\QuestionDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
										});	
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
