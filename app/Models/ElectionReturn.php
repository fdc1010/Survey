<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class ElectionReturn extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'election_returns';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['election_id','candidate_id','voter_id','precinct_id','subject','description'];
    // protected $hidden = [];
    // protected $dates = [];
	public function election()
    {
        return $this->belongsTo('App\Models\Election','election_id');
    }
	public function candidate()
    {
        return $this->belongsTo('App\Models\Candidate','candidate_id');
    }
	public function voter()
    {
        return $this->belongsTo('App\Models\Voter','voter_id');
    }
	public function precinct()
    {
        return $this->belongsTo('App\Models\Precinct','precinct_id');
    }
	public function getPrecinct(){
		$precinct = Precinct::find($this->precinct_id)->with('barangay');
		return $precinct->precinct_number . " (" . $precinct->barangay->name . ")";
	}
	public function tally($candidateid=1,$electionid=1, $agebrackets = [], $brgy = [], $genders = [], $empstatus = [],
							$civilstatus = [], $occstatus = [], $voterstatus = []){
		return $this->where('candidate_id',$candidateid)
					->where('election_id',$electionid)
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
									/*->where(function($query)use($empstatus){
											 $query->whereNotNull('employment_status_id')
											 		->whereIn('employment_status_id',$empstatus);										
										})
									->where(function($query)use($civilstatus){
											 $query->whereNotNull('civil_status_id')
											 		->whereIn('civil_status_id',$civilstatus);
										})										
									->where(function($query)use($occstatus){
											 $query->whereNotNull('occupancy_status_id')
											 		->whereIn('occupancy_status_id',$occstatus);
										})
									->whereHas('statuses',function($qv)use($voterstatus){
															$qv->whereIn('status_id',$voterstatus);
												})
									->orWhereHas('precinct',function($qb)use($brgy){
															$qb->whereIn('barangay_id',$brgy);
												});*/											
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
