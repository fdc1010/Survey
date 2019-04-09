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
    protected $fillable = ['position_id', 'party_id','voter_id','priority'];
    // protected $hidden = [];
    // protected $dates = [];
  	protected $appends = ['full_name','id_full_name'];
      /*
      |--------------------------------------------------------------------------
      | FUNCTIONS
      |--------------------------------------------------------------------------
      */
  	public function options()
      {
          return $this->hasMany('App\Models\OptionCandidate','candidate_id');
      }
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
  	public function tally()
  	{
  		return $this->hasMany('App\Models\TallyVote','candidate_id');
  	}
  	public function getCandidateName()
  	{
  		$voter = Voter::find($this->voter_id);
  		return $voter->first_name . " " . $voter->middle_name . " " . $voter->last_name;
  	}
  	public function getFullNameAttribute()
    {
		$voter = Voter::find($this->voter_id);
        return ucwords($voter->first_name . ' ' . $voter->middle_name . ' ' . $voter->last_name);
    }
    public function getIdFullNameAttribute()
      {
          return $this->attributes['id'] . "  " . ucwords($this->attributes['first_name'] . ' ' . $this->attributes['middle_name'] . ' ' . $this->attributes['last_name']);
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
