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
	public function tally($age = 18, $agebrackets = [],$brgy=[],$genders = [], $empstatus = [],
							$civilstatus = [],$occstatus = [],$voterstatus = []){
				//where('candidate_id',$this->candidate_id)
				//->where('survey_detail_id',$survey)
		return $this->whereHas('voter',function($q)use($age,$agebrackets,$brgy,$genders,
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
