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
    protected $fillable = ['candidate_id','voter_id','tally','survey_detail_id'];
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
	public function tally($candidateid=1,$surveydetailid=1, $agebrackets = [], $brgy = [], $genders = [], $empstatus = [],
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
