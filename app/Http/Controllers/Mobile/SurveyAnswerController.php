<?php

namespace App\Http\Controllers\Mobile;
use App\Models\Question;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyorAssignment;
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
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Session;
use Excel;
use File;
use Image;
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
	public function getSurveyorProgress(Request $request){
			$userid = $request->user_id;
			$surveydetailid = $request->survey_detail_id;
			
			$surveyorassignment = SurveyorAssignment::with(['assignments'=>function($q)use($request){
															$q->with(['sitio'=>function($qs)use($request){											
																$qs->with(['voters'=>function($qv)use($request){												
																			$surveyansvoterid = SurveyAnswer::where('survey_detail_id',$request->survey_detail_id)
																				->where('user_id',$request->user_id)
																				->select(['voter_id'])
																				->groupBy('voter_id')
																				->get()->pluck('voter_id')->toArray();
																			$qv->whereIn('id',$surveyansvoterid);
																		}]);
															}]);
														}])
														->where('survey_detail_id',$surveydetailid)
														->where('user_id',$userid)
														->first();
														
			
			
			if($surveyorassignment)	{
				$survey_per_area_count = array();
				
				foreach($surveyorassignment->assignments as $assignment){
					array_push($survey_per_area_count,array('sitio_id'=>$assignment->sitio->id,
															'name'=>$assignment->sitio->name,
															'quota'=>$assignment->quota,
															'count'=>$assignment->getSurveyCount(),
															'surveyor_progress'=>$assignment->getProgress(),
															'surveyor_progress_percent'=>$assignment->getProgressPercent()));
				}
				
				return response()->json(['surveyor_progress'=>$surveyorassignment->getProgress(),
											'surveyor_progress_percent'=>$surveyorassignment->getProgressPercent(),
											'survey_count'=>$surveyorassignment->getSurveyCount(),
											'survey_quota'=>$surveyorassignment->quota,
											'survey_count_per_quota'=>$survey_per_area_count]);
			}else{
				return response()->json(['surveyor_progress'=>0,'surveyor_progress_percent'=>'0.00 %',
											'survey_count'=>0,
											'survey_quota'=>0,
											'survey_count_per_quota'=>0]);
			}
	}
	public function storeAnswers(Request $request){
		//$sid = $request->survey_detail_id;
		//$survey = Survey::find($sid);
			info($request);
			$ok = true;
			$userid = $request->user_id;
			$voterid = $request->voter_id;
			$surveydetailid = $request->survey_detail_id;
			
			$checksurveyvoter = SurveyAnswer::where('user_id',$userid)
											->where('voter_id',$voterid)
											->where('survey_detail_id',$surveydetailid)
											->first();
			if(empty($checksurveyvoter)){
				$voterdetails = json_decode($request->voter_detail,true);
				$profilepic=null;
				if($request->hasFile('profilepic')){	
					$voter = Voter::find($voterid);				
					$path = config('app.root') . '/public/profilepic/';
					$photo=$path.basename($voter->profilepic);
					
					File::delete($photo);
					
					$md5profName = md5_file($request->file('profilepic')->getRealPath());
					$guessExtensionprof = $request->file('profilepic')->guessExtension();
		
					$srvroot = $_SERVER['DOCUMENT_ROOT'];
					$pathimage =  $srvroot . '/profilepic/';
					$path = url('/profilepic/');
					if (!File::exists($path)) {
						File::makeDirectory($path,0777);
					}
		
					$width = 160;
					$height = 160;
					$image = Image::make($request->file('profilepic')->getRealPath());
					$image->width() > $image->height() ? $width=null : $height=null;
					$image->resize($width, $height, function ($constraint) {
						$constraint->aspectRatio();
						$constraint->upsize();
					});
		
					$image->save($pathimage.$md5profName.'.'.$guessExtensionprof);
		
					$filename = $md5profName.'.'.$guessExtensionprof;
					$profilepic =  config('app.url') . '/profilepic/' . $filename;
		
				}
				//$voter->save();		
				 Voter::where('id',$voterid)
						->update([
									'age'=>$voterdetails['age'],
									'contact'=>$voterdetails['contactNum'],
									'work'=>$voterdetails['work'],
									'monthly_household'=>$voterdetails['monthlyIncome'],
									'yearly_household'=>$voterdetails['yearlyIncome'],
									'occupancy_length'=>$voterdetails['occuLength'],
									'occupancy_status_id'=>$voterdetails['occuStatusId'],
									'civil_status_id'=>$voterdetails['civilStatusId'],
									'employment_status_id'=>$voterdetails['empStatusId'],
									'gender_id'=>$voterdetails['genderId'],
									'profilepic'=>$profilepic
								]);
				$vstatusarr = json_decode($voterdetails['status'],true);
				foreach($vstatusarr as $vstatus){
					StatusDetail::where('voter_id',$voterid)->delete();
					$voterstatuses = new StatusDetail;	
					$voterstatuses->voter_id = $voterid;
					$voterstatuses->status_id = $vstatus;
					$voterstatuses->save();
				}
			
			}
				
				$receivedans = json_decode($request->q_and_a, true);
				
				foreach($receivedans as $voteranswers){
					foreach($voteranswers['answers'] as $ansid){								
						$optid = $ansid['id'];
						$surveyanswers = SurveyAnswer::where('user_id',$userid)
											->where('voter_id',$voterid)
											->where('survey_detail_id',$surveydetailid)
											->where('question_id',$voteranswers['questionId'])
											->where('option_id',$optid)
											->first();
						if(empty($surveyanswers)){
							$surveyans = new SurveyAnswer;		
							$surveyans->survey_detail_id = $surveydetailid;
							$surveyans->question_id = $voteranswers['questionId'];
							$surveyans->answered_option = $voteranswers['answers'];
							$surveyans->option_id = $optid;
							$surveyans->user_id = $userid;
							$surveyans->voter_id = $voterid;
							$surveyans->other_answer = $ansid['otherAnswer'];
							
							if($request->has('latitude')){
								$surveyans->latitude = $request->latitude;
							}
							if($request->has('longitude')){
								$surveyans->longitude = $request->longitude;
							}
							
							$surveyansid=$surveyans->save();				
							
							$optioncandidate = OptionCandidate::where('option_id',$optid)->first();
							if($optioncandidate){
								$tallycandidate = new TallyVote;
								$tallycandidate->candidate_id = $optioncandidate->candidate_id;
								$tallycandidate->voter_id = $voterid;
								$tallycandidate->survey_detail_id = $surveydetailid;
								$tallycandidate->save();
							}
							$relquestion = RelatedQuestion::where('question_id',$voteranswers['questionId'])->first();
							if($relquestion){
								$surans = SurveyAnswer::where('survey_detail_id',$surveydetailid)
														->where('question_id',$relquestion->related_question_id)										
														->first();
								if($surans){
									$question = Question::find($relquestion->question_id);
									if(!empty($question->for_position) && is_numeric($question->for_position)){							
										$optioncandidate = OptionCandidate::where('option_id',$surans->option_id)->first();
										if($optioncandidate){
											$tallycandidate = new TallyOtherVote;
											$tallycandidate->option_id = $optid;
											$tallycandidate->voter_id = $voterid;
											$tallycandidate->candidate_id = $optioncandidate->candidate_id;
											$tallycandidate->survey_detail_id = $surveydetailid;
											$tallycandidate->save();
										}
									
									}
								}
							}
							
							$optionproblem = OptionProblem::where('option_id',$optid)->first();
							if($optionproblem){
								$voterbrgy = Voter::with('precinct')->find($voterid);
								$tallyproblem = new TallyOtherVote;
								$tallyproblem->option_id = $optid;
								$tallyproblem->voter_id = $voterid;
								$tallyproblem->survey_detail_id = $surveydetailid;
								$tallyproblem->barangay_id = $voterbrgy->precinct->barangay_id;
								$tallyproblem->save();
							}	
						}
					}		
				}
				return response()->json(['success'=>true,'msg'=>'Answers are saved!']);
			
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
