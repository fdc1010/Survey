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
    protected $fillable = ['option_id','voter_id','tally','survey_detail_id'];
    // protected $hidden = [];
    // protected $dates = [];
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
	public function tally($survey = 1, $age = 18, $agebrackets = [],$brgy=[],$genders = [], $empstatus = [],
							$civilstatus = [],$occstatus = [],$voterstatus = []){
		return $this->where('option_id',$this->option_id)
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
