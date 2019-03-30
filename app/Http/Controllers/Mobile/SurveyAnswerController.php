<?php

namespace App\Http\Controllers\Mobile;
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
use App\Models\DuplicateSurvey;
use App\Models\BarangaySurveyable;
use App\Models\Barangay;
use App\Models\Precinct;
use App\Models\AgeReview;
use App\User;
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
    public function getSurveyorProgressB(Request $request){
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
  					array_push($survey_per_area_count,array('barangay_id'=>$assignment->barangay->id,
  															'name'=>$assignment->barangay->name,
  															'quota'=>$assignment->quota,
  															'count'=>$assignment->count,
  															'surveyor_progress'=>$assignment->getProgressB(),
  															'surveyor_progress_percent'=>$assignment->getProgressPercentB()));
  				}

  				return response()->json(['surveyor_progress'=>$surveyorassignment->getProgressB(),
  											'surveyor_progress_percent'=>$surveyorassignment->getProgressPercentB(),
  											'survey_count'=>$surveyorassignment->count,
  											'survey_quota'=>$surveyorassignment->quota,
  											'survey_count_per_quota'=>$survey_per_area_count]);
  			}else{
  				return response()->json(['surveyor_progress'=>0,'surveyor_progress_percent'=>'0.00 %',
  											'survey_count'=>0,
  											'survey_quota'=>0,
  											'survey_count_per_quota'=>0]);
  			}
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
  					array_push($survey_per_area_count,array('barangay_id'=>$assignment->barangay->id,
  															'name'=>$assignment->barangay->name,
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
		//info($request);
			$ok = true;
			$userid = $request->user_id;
			$surveydetailid = $request->survey_detail_id;
      $user = User::find($userid);

      if(!$request->has('voter_id') || ($request->has('is_anonymous') && $request->is_anonymous==1)){
          $anonymousvoter = new Voter;
          if($request->has('barangay_id')){
            $anonymousvoterbrgy = Barangay::find($request->barangay_id);
            $anonymousvoterprec = Precinct::where('barangay_id',$request->barangay_id)->first();
            $anonymousvoter->barangay_id = $request->barangay_id;
            $anonymousvoter->barangay_name = $anonymousvoterbrgy->name;
            $anonymousvoter->precinct_id = $anonymousvoterprec->id;
            $anonymousvoter->precinct_number = $anonymousvoterprec->precinct_number;
          }else{
            $surveyablebrgy = BarangaySurveyable::inRandomOrder()->first();
            $anonymousvoterbrgy = Barangay::find($surveyablebrgy->barangay_id);
            $anonymousvoterprec = Precinct::where('barangay_id',$surveyablebrgy->barangay_id)->first();
            $anonymousvoter->barangay_id = $surveyablebrgy->barangay_id;
            $anonymousvoter->barangay_name = $anonymousvoterbrgy->name;
            $anonymousvoter->precinct_id = $anonymousvoterprec->id;
            $anonymousvoter->precinct_number = $anonymousvoterprec->precinct_number;
          }
          $anonymousvoter->last_name = "voter";
          $anonymousvoter->first_name = "anonymous";
          $anonymousvoter->is_anonymous = 1;
          $anonymousvoter->save();
          $voterid = $anonymousvoter->id;
      }else{
          $voterid = $request->voter_id;
      }

      $voter = Voter::find($voterid);
      if($voter){
            info("Storing voter survey: #".$voter->id." ".$voter->full_name);
            //if($voter->is_done_survey==0){
            $checksurveyvoter = SurveyAnswer::where('user_id',$userid)
                          ->where('voter_id',$voterid)
                          ->where('survey_detail_id',$surveydetailid)
                          ->first();
      			if(empty($checksurveyvoter) || $checksurveyvoter==null){
      				$voterdetails = json_decode($request->voter_detail,true);
      				$profilepic=null;
              info($request);
      				if($request->hasFile('profilepic') && !empty(file('profilepic'))){
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
      									'age'=>(empty($voterdetails['age'])?36:$voterdetails['age']),
      									'contact'=>$voterdetails['contactNum'],
      									'work'=>$voterdetails['work'],
      									'monthly_household'=>$voterdetails['monthlyIncome'],
      									'yearly_household'=>$voterdetails['yearlyIncome'],
      									'occupancy_length'=>$voterdetails['occuLength'],
      									'occupancy_status_id'=>(empty($voterdetails['occuStatusId'])?1:$voterdetails['occuStatusId']),
      									'civil_status_id'=>(empty($voterdetails['civilStatusId'])?1:$voterdetails['civilStatusId']),
      									'employment_status_id'=>(empty($voterdetails['empStatusId'])?1:$voterdetails['empStatusId']),
      									'gender_id'=>(empty($voterdetails['genderId'])?1:$voterdetails['genderId']),
                        'is_done_survey'=>1,
      									'profilepic'=>$profilepic
      								]);
              if($voterdetails['age']>100){
                $agereview = new AgeReview;
                $agereview->voter_id = $voterid;
                $agereview->age = $voterdetails['age'];
                $agereview->save();
              }
              $surveyassignment = SurveyorAssignment::where('user_id',$userid)
                                          ->where('survey_detail_id',$surveydetailid)
                                          ->first();
              $newcount = $surveyassignment->count + 1;
              SurveyorAssignment::where('user_id',$userid)
                                  ->where('survey_detail_id',$surveydetailid)
                                  ->update(['count'=>$newcount]);


              $assignmentdetails = AssignmentDetail::where('barangay_id',$voter->barangay_id)
                                          ->where('assignment_id',$surveyassignment->id)
                                          ->get();
              if(!empty($assignmentdetails) && count($assignmentdetails)>0){
                  foreach($assignmentdetails as $assignmentdetail){
                      $newcountad = $assignmentdetail->count + 1;
                      AssignmentDetail::where('barangay_id',$voter->barangay_id)
                                          ->where('id',$assignmentdetail->id)
                                          ->update(['count'=>$newcountad]);
                  }
              }
      				if(!empty($voterdetails['status'])){
        					$vstatusarr = json_decode($voterdetails['status'],true);
        					foreach($vstatusarr as $vstatus){
        						StatusDetail::where('voter_id',$voterid)->delete();
        						$voterstatuses = new StatusDetail;
        						$voterstatuses->voter_id = $voterid;
        						$voterstatuses->status_id = $vstatus;
        						$voterstatuses->save();
        					}
    				  }else{
                  $voterstatuses = new StatusDetail;
                  $voterstatuses->voter_id = $voterid;
                  $voterstatuses->status_id = 5;
                  $voterstatuses->save();
              }
            }
              $receivedans = json_decode($request->q_and_a, true);
              info("Storing answers: #".$voter->id." ".$voter->full_name);
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
                      $dupsurans = SurveyAnswer::with(['voter','user'])
                                                  ->where('user_id','<>',$userid)
                                                  ->where('question_id',$voteranswers['questionId'])
                                                  ->where('voter_id',$voterid)
                                                  ->first();
                      if(!empty($dupsurans)){
                            info("Current Entry!");
                            info("survey detail id: ".$surveydetailid." ,voter id: ".$voterid." ".$voter->full_name." ,question_id: ".$voteranswers['questionId']." ,option id: ".$optid." ,user id: ".$userid." ".$user->name);
                            info("Duplicate Entry!");
                            info("#".$dupsurans->id." ,survey detail id: ".$dupsurans->survey_detail_id." ,voter id: ".$dupsurans->voter_id." ".$dupsurans->voter->full_name." ,question_id: ".$dupsurans->question_id." ,option id: ".$dupsurans->option_id." ,user id: ".$dupsurans->user_id." ".$dupsurans->user->name);
                            $dupsurvey = [
                                            'voter_id' => $voterid,
                                            'user_id' => $userid,
                                            'other_user_id' => $dupsurans->user_id,
                                            'survey_detail_id' => $surveydetailid,
                                            'barangay_id' => $voter->barangay_id
                                         ];
                            $insert = DuplicateSurvey::insert($dupsurvey);
                            if($insert){
                              info("Duplicate Survey Saved!");
                            }
                      }else{
                            $candidateId = null;      				//$voter->save();

                            $optioncandidatesa = OptionCandidate::where('option_id',$optid)->first();
                            if($optioncandidatesa){
                              $candidateId = $optioncandidatesa->candidate_id;
                            }
              							$surveyans = new SurveyAnswer;
              							$surveyans->survey_detail_id = $surveydetailid;
              							$surveyans->question_id = $voteranswers['questionId'];
              							$surveyans->option_id = $optid;
              							$surveyans->user_id = $userid;
              							$surveyans->voter_id = $voterid;
                            $surveyans->barangay_id = $voter->barangay_id;
                            $surveyans->candidate_id = $candidateId;
              							$surveyans->answered_option = $voteranswers['answers'];//$voter->save();

                            if(!empty($ansid['otherAnswer'])){
              							       $surveyans->other_answer = $ansid['otherAnswer'];
                            }
              							if($request->has('latitude')){
              								$surveyans->latitude = $request->latitude;
              							}
              							if($request->has('longitude')){
              								$surveyans->longitude = $request->longitude;
              							}

              							$surveyansid=$surveyans->save();
                            info("Storing tally: #".$voter->id." ".$voter->full_name);
              							//$optioncandidate = OptionCandidate::where('option_id',$optid)->first();
              							if($optioncandidatesa){
              								$tallycandidate = new TallyVote;
                              $tallycandidate->question_id = $voteranswers['questionId'];
                              $tallycandidate->option_id = $optid;
              								$tallycandidate->candidate_id = $candidateId;
              								$tallycandidate->voter_id = $voterid;
                              $tallycandidate->user_id = $userid;
              								$tallycandidate->survey_detail_id = $surveydetailid;
                              $tallycandidate->barangay_id = $voter->barangay_id;

                              if(!empty($ansid['otherAnswer'])){
                							       $tallycandidate->other_answer = $ansid['otherAnswer'];
                              }

              								$tallycandidate->save();
              							}
                            $otoptId = null;
              							$relquestion = RelatedQuestion::where('question_id',$voteranswers['questionId'])->first();
              							if($relquestion){
              								$surans = SurveyAnswer::where('survey_detail_id',$surveydetailid)
              														->where('question_id',$relquestion->related_question_id)
                                          ->where('voter_id',$voterid)
                                          ->orderBy('id')
                                          ->get();
                              if(!empty($surans[$relquestion->cardinality-1])){
                                  $otoptId = $surans[$relquestion->cardinality-1]->option_id;
                              }
              								if(!empty($otoptId)){
              									$question = Question::find($relquestion->question_id);
              									if(!empty($question->for_position) && is_numeric($question->for_position)){
                                  info("Found linked Question: ");
              										$optioncandidate = QuestionOption::find($otoptId);
              										if($optioncandidate){
                                    info("Storing tally for candidate qualities: #".$voteranswers['questionId']." ".$optioncandidate->option);
              											$othertallycandidate = new TallyOtherVote;
                                    $othertallycandidate->question_id = $voteranswers['questionId'];
                                    $othertallycandidate->option_id = $optid;
              											$othertallycandidate->voter_id = $voterid;
                                    $othertallycandidate->user_id = $userid;
              											$othertallycandidate->candidate_id = $optioncandidate->candidate_id;
              											$othertallycandidate->survey_detail_id = $surveydetailid;
                                    $othertallycandidate->barangay_id = $voter->barangay_id;
              											$othertallycandidate->save();
              										}

              									}
              								}
              							}

              							$optionproblem = OptionProblem::where('option_id',$optid)->first();
              							if($optionproblem){
                              info("Storing tally for brgy concerns: #".$voteranswers['questionId']." ".$optionproblem->option);
              								//$voterbrgy = Voter::find($voterid);
              								$tallyproblem = new TallyOtherVote;
                              $tallyproblem->question_id = $voteranswers['questionId'];
              								$tallyproblem->option_id = $optid;
              								$tallyproblem->voter_id = $voterid;
                              $tallyproblem->user_id = $userid;
              								$tallyproblem->survey_detail_id = $surveydetailid;
              								$tallyproblem->barangay_id = $voter->barangay_id;
              								$tallyproblem->save();
              							}
              					}
                      }
      					}
    			    }

              return response()->json(['success'=>true,'msg'=>'Voter survey saved!']);

  		//		}
      //  return response()->json(['success'=>true,'msg'=>'Voter already being surveyed']);
      }
			return response()->json(['success'=>true,'msg'=>'An Error Occured!']);

	  }
    public function testAnonymousVoterAdd(Request $request){
      if(!$request->has('voter_id') || ($request->has('is_anonymous') && $request->is_anonymous==1)){
          $anonymousvoter = new Voter;
          if($request->has('barangay_id')){
            $anonymousvoterbrgy = Barangay::find($request->barangay_id);
            $anonymousvoterprec = Precinct::where('barangay_id',$request->barangay_id)->first();
            $anonymousvoter->barangay_id = $request->barangay_id;
            $anonymousvoter->barangay_name = $anonymousvoterbrgy->name;
            $anonymousvoter->precinct_id = $anonymousvoterprec->id;
            $anonymousvoter->precinct_number = $anonymousvoterprec->precinct_number;
          }else{
            $surveyablebrgy = BarangaySurveyable::inRandomOrder()->first();
            $anonymousvoterbrgy = Barangay::find($surveyablebrgy->barangay_id);
            $anonymousvoterprec = Precinct::where('barangay_id',$surveyablebrgy->barangay_id)->first();
            $anonymousvoter->barangay_id = $surveyablebrgy->barangay_id;
            $anonymousvoter->barangay_name = $anonymousvoterbrgy->name;
            $anonymousvoter->precinct_id = $anonymousvoterprec->id;
            $anonymousvoter->precinct_number = $anonymousvoterprec->precinct_number;
          }
          $anonymousvoter->last_name = "voter";
          $anonymousvoter->first_name = "anonymous";
          $anonymousvoter->is_anonymous = 1;
          $anonymousvoter->save();
          $voterid = $anonymousvoter->id;
      }else{
          $voterid = $request->voter_id;
      }

      $voter = Voter::find($voterid);

      return response()->json($voter);
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
