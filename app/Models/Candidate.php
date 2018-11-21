<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Candidate extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'candidates';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['position_id', 'party_id','voter_id'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
	public function position()
    {
        return $this->belongsTo('App\Models\PositionCandidate','position_id');
    }
	public function party()
    {
        return $this->belongsTo('App\Models\Party','party_id');
    }
	public function voter()
    {
        return $this->belongsTo('App\Models\Voter','voter_id');
    }
	public function getCandidateName()
	{
		$voter = Voter::find($this->voter_id);
		return $voter->first_name . " " . $voter->middle_name . " " . $voter->last_name;	
	}
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
