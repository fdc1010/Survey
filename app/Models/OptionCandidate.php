<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class OptionCandidate extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'option_candidates';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['option_id','candidate_id'];
    // protected $hidden = [];
    // protected $dates = [];
	public function option()
    {
        return $this->belongsTo('App\Models\QuestionOption','option_id');
    }
	public function candidate()
    {
        return $this->belongsTo('App\Models\Candidate','candidate_id')->with('position','party','voter');
    }
	public function voter()
    {
        return $this->belongsTo('App\Models\Voter','candidate_id');
    }
	public function optioncandidates(){
		return $this->hasMany('App\Models\QuestionOption');
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
