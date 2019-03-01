<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class TallyOtherVote extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'tally_other_votes';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['candidate_id','barangay_id','option_id','voter_id','tally','survey_detail_id'];
    // protected $hidden = [];
    // protected $dates = [];
	public function candidate()
    {
        return $this->belongsTo('App\Models\Candidate','candidate_id');
    }
	public function barangay()
    {
        return $this->belongsTo('App\Models\Barangay','barangay_id');
    }
	public function option()
    {
        return $this->belongsTo('App\Models\QuestionOption','option_id');
    }
	public function voter()
    {
        return $this->belongsTo('App\Models\Voter','voter_id');
    }
	public function surveydetail()
    {
        return $this->belongsTo('App\Models\SurveyDetail','survey_detail_id');
    }	
	/*public function tally($optionid=1,$surveydetailid=1,$age = 18, $agebrackets = [],$brgy=[],$genders = [], $empstatus = [],
							$civilstatus = [],$occstatus = [],$voterstatus = []){
		return $this->where('option_id',$optionid)
						->where('survey_detail_id',$surveydetailid)
						->whereHas('voter',function($q)use($age,$agebrackets,$brgy,$genders,
															$empstatus,$civilstatus,
															$occstatus,$voterstatus){
								$q->where('age','>=',$age)
									->orWhereIn('age',$agebrackets)
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
	public function tally($candidateid=1,$optionid=1,$surveydetailid=1, $agebrackets = [], $brgy = [], $genders = [], $empstatus = [],
							$civilstatus = [], $occstatus = [], $voterstatus = []){
		return $this->where('candidate_id',$candidateid)
					->where('option_id',$optionid)
					->where('survey_detail_id',$surveydetailid)
					->whereHas('voter',function($q)use($agebrackets,$brgy,$genders,
															$empstatus,$civilstatus,
															$occstatus,$voterstatus){
								if(count($agebrackets)>0)
									$q->whereIn('age',$agebrackets);
								
								if(count($genders)>0)
									$q->whereIn('gender_id',$genders);
									
								if(count($empstatus)>0)
									$q->whereIn('employment_status_id',$empstatus);
									
								if(count($civilstatus)>0)
									$q->whereIn('civil_status_id',$civilstatus);
									
								if(count($occstatus)>0)
									$q->whereIn('occupancy_status_id',$occstatus);
									
								if(count($voterstatus)>0){
									$q->whereHas('statuses',function($qv)use($voterstatus){
															$qv->whereIn('status_id',$voterstatus);
												});			
								}
								
								if(count($brgy)>0){
									$q->whereHas('precinct',function($qb)use($brgy){
															$qb->whereIn('barangay_id',$brgy);
												});			
								}									
							})
						->sum('tally');	
	}
	public function tallyproblem($barangayid,$optionid=1,$surveydetailid=1, $agebrackets = [], $brgy = [], $genders = [], $empstatus = [],
							$civilstatus = [], $occstatus = [], $voterstatus = []){
		return $this->where('barangay_id',$barangayid)
					->where('option_id',$optionid)
					->where('survey_detail_id',$surveydetailid)
					->whereHas('voter',function($q)use($agebrackets,$brgy,$genders,
															$empstatus,$civilstatus,
															$occstatus,$voterstatus){
								if(count($agebrackets)>0)
									$q->whereIn('age',$agebrackets);
								
								if(count($genders)>0)
									$q->whereIn('gender_id',$genders);
									
								if(count($empstatus)>0)
									$q->whereIn('employment_status_id',$empstatus);
									
								if(count($civilstatus)>0)
									$q->whereIn('civil_status_id',$civilstatus);
									
								if(count($occstatus)>0)
									$q->whereIn('occupancy_status_id',$occstatus);
									
								if(count($voterstatus)>0){
									$q->whereHas('statuses',function($qv)use($voterstatus){
															$qv->whereIn('status_id',$voterstatus);
												});			
								}
								
								if(count($brgy)>0){
									$q->whereHas('precinct',function($qb)use($brgy){
															$qb->whereIn('barangay_id',$brgy);
												});			
								}									
							})
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
