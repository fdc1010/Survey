<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class TallyVote extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'tally_votes';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['candidate_id','voter_id','tally','survey_detail_id','barangay_id','user_id'];
    // protected $hidden = [];
    // protected $dates = [];
	public function candidate()
    {
        return $this->belongsTo('App\Models\Candidate','candidate_id');
    }
	public function voter()
    {
        return $this->belongsTo('App\Models\Voter','voter_id');
    }
	public function surveydetail()
    {
        return $this->belongsTo('App\Models\SurveyDetail','survey_detail_id');
    }
    public function surveyanswer()
      {
          return $this->belongsTo('App\Models\Voter','voter_id');
      }
	/*public function tally($candidateid=1,$surveydetailid=1,$agebrackets = [],$brgy=[],$genders = [], $empstatus = [],
							$civilstatus = [],$occstatus = [],$voterstatus = []){
		return $this->where('candidate_id',$candidateid)
					->where('survey_detail_id',$surveydetailid)
					->whereHas('voter',function($q)use($agebrackets,$brgy,$genders,
															$empstatus,$civilstatus,
															$occstatus,$voterstatus){
								$q->whereIn('age',$agebrackets)
									->orWhereIn('gender_id',$genders)
									->orWhereIn('employment_status_id',$empstatus)
									->orWhereIn('civil_status_id',$civilstatus)
									->orWhereIn('occupancy_status_id',$occstatus)
									->orWhereHas('statuses',function($qv)use($voterstatus){
															$qv->whereIn('status_id',$voterstatus);
												})
									->orWhereHas('precinct',function($qb)use($brgy){
															$qb->whereIn('barangay_id',$brgy);
												});
							})
						->sum('tally');
	}*/
	/*public function tally($candidateid=1,$surveydetailid=1, $agebrackets = [], $brgy = [], $genders = [], $empstatus = [],
							$civilstatus = [], $occstatus = [], $voterstatus = []){
		return $this->where('candidate_id',$candidateid)
					->where('survey_detail_id',$surveydetailid)
					->whereHas('voter',function($q)use($agebrackets,$brgy,$genders,
															$empstatus,$civilstatus,
															$occstatus,$voterstatus){
								$q->whereIn('age',$agebrackets)
									->orWhere(function($query)use($genders){
											 $query->whereNotNull('gender_id')
											 		->whereIn('gender_id',$genders);
										})
									->orWhere(function($query)use($empstatus){
											 $query->whereNotNull('employment_status_id')
											 		->whereIn('employment_status_id',$empstatus);
										})
									->orWhere(function($query)use($civilstatus){
											 $query->whereNotNull('civil_status_id')
											 		->whereIn('civil_status_id',$civilstatus);
										})
									->orWhere(function($query)use($occstatus){
											 $query->whereNotNull('occupancy_status_id')
											 		->whereIn('occupancy_status_id',$occstatus);
										})
									->orWhereHas('statuses',function($qv)use($voterstatus){
															$qv->whereIn('status_id',$voterstatus);
												})
									->orWhereHas('precinct',function($qb)use($brgy){
															$qb->whereIn('barangay_id',$brgy);
												});
							})
						->sum('tally');
	}*/
	public function tally($candidateid=1,$surveydetailid=1,$agebrackets = [], $brgy = [], $genders = [], $empstatus = [],
							$civilstatus = [], $occstatus = [], $voterstatus = []){
    $questionidsfortally = Question::where('isfor_tallyvotes',1)->get()->pluck('id')->toArray();
		$tallyraw = $this->where('candidate_id',$candidateid)
					->where('survey_detail_id',$surveydetailid)
          ->whereIn('question_id',$questionidsfortally)
          ->has('surveyanswer')
					->whereHas('voter',function($q)use($agebrackets,$brgy,$genders,
															$empstatus,$civilstatus,
															$occstatus,$voterstatus,$candidateid){

								if(count($agebrackets)>0){

                  // info('\$agebrackets: '.$candidateid);
                  // info($agebrackets);

									$q->whereIn('age',$agebrackets)->whereNotNull('age');//->orWhereNull('age');
									//info("agebrackets: ");info($agebrackets);
								}
								if(count($genders)>0){

                  // info('\$genders '.$candidateid);
                  // info($genders);

									$q->whereIn('gender_id',$genders)->whereNotNull('gender_id');//->orWhereNull('gender_id');
									//info("genders: ");info($genders);
								}
								if(count($empstatus)>0){

                  // info('\$empstatus '.$candidateid);
                  // info($empstatus);

									$q->whereIn('employment_status_id',$empstatus);//->orWhereNull('employment_status_id');
									//info("empstatus: ");info($empstatus);
								}
								if(count($civilstatus)>0){

                  // info('\$civilstatus '.$candidateid);
                  // info($civilstatus);

									$q->whereIn('civil_status_id',$civilstatus);//->orWhereNull('civil_status_id');
									//info("civilstatus: ");info($civilstatus);
								}

								if(count($occstatus)>0){

                  // info('\$occstatus '.$candidateid);
                  // info($occstatus);

									$q->whereIn('occupancy_status_id',$occstatus);//->orWhereNull('occupancy_status_id');
									//info("occstatus: ");info($occstatus);
								}
								if(count($voterstatus)>0){

                  // info('\$voterstatus '.$candidateid);
                  // info($voterstatus);

									$q->whereHas('statuses',function($qv)use($voterstatus){

              									$qv->whereIn('status_id',$voterstatus);

												});//->orWhereNull('status_id');
									//info("voterstatus: ");info($voterstatus);
								}

								if(count($brgy)>0){

                    // info('\$brgy '.$candidateid);
                    // info($brgy);

										$q->whereIn('barangay_id',$brgy);

									//});
									//info("brgy: ");info($brgy);
								}
							})->with('voter');
			$tally = $tallyraw->sum('tally');

      info("Tally: ".$candidateid);
      info($tally);

      $tallyrecs = $tallyraw->get();
      foreach($tallyrecs as $tallyrec){
        info("#".$tallyrec->id." | qid: ".$tallyrec->question_id." | optid: ".$tallyrec->option_id." ".$tallyrec->voter->id." : ".$tallyrec->voter->full_name);
      }
      //$sqlmsg = $tally->toSql();
      //info($sqlmsg);
      return $tally;
	}
  public function tallydetails($candidateid=1,$surveydetailid=1,$agebrackets,$brgyid=0,$civilstatusid=0,$empstatusid=0,$occstatusid=0,$voterstatusid=0,$genderid=0){
    $questionidsfortally = Question::where('isfor_tallyvotes',1)->get()->pluck('id')->toArray();
    return $this->where('candidate_id',$candidateid)
					->where('survey_detail_id',$surveydetailid)
          ->whereIn('question_id',$questionidsfortally)
          ->has('surveyanswer')
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

												});//->orWhereNull('status_id');
									//info("voterstatus: ");info($voterstatus);
								}
							})
            //->groupBy('voter_id')
						->sum('tally');
	}
  public function tallyqualities($candidateid=1,$surveydetailid=1,$agebrackets,$brgyid=0,$civilstatusid=0,$empstatusid=0,$occstatusid=0,$voterstatusid=0,$genderid=0){
		return $this->where('candidate_id',$candidateid)
					->where('survey_detail_id',$surveydetailid)
          ->whereIn('question_id',[5,7,9,10,11,12])
          ->has('surveyanswer')
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

												});//->orWhereNull('status_id');
									//info("voterstatus: ");info($voterstatus);
								}
							})
            //->groupBy('voter_id')
						->sum('tally');
	}
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
