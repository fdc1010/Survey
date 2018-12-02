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
        return $this->belongsTo('App\Models\Candidate','voter_id');
    }
	public function precinct()
    {
        return $this->belongsTo('App\Models\Precinct','precinct_id');
    }
	public function getPrecinct(){
		$precinct = Precinct::find($this->precinct_id)->with('barangay');
		return $precinct->precinct_number . " (" . $precinct->barangay->name . ")";
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
