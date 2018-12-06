<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class QuestionOption extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'question_options';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['option','priority','for_candidate_quality','for_candidate_votes'];
    // protected $hidden = [];
    // protected $dates = [];
	public function questions()
    {
        return $this->belongsToMany('App\Models\Question','question_details','question_id','option_id');
    }
	public function getPositions()
    {
        $optpositions = OptionPosition::with('positions')->where('option_id',$this->id)->get();		
		$result = "";
		foreach($optpositions as $optposition){
			$result .= $optposition->positions->name."<br />";
		}
		return $result;
    }
	public function forCandidateQuality(){
		if($this->for_candidate_quality){
			return "<span class='fa fa-check-circle-o'></span>";	
		}	
		return "";
	}
	public function forCandidateVotes(){
		if($this->for_candidate_votes){
			return "<span class='fa fa-check-circle-o'></span>";	
		}	
		return "";
	}
	public function barangaysurveys()
    {
        return $this->hasMany('App\Models\BarangaySurveyable','barangay_id');
    }
	public function problems()
    {
        return $this->hasMany('App\Models\OptionProblem','option_id');
    }
	public function positions(){
		return $this->hasMany('App\Models\OptionPosition','option_id');
	}
	
	public function optionspositions(){
		return $this->belongsToMany('App\Models\PositionCandidate','option_positions','option_id','position_id');
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
