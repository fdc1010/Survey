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
    protected $fillable = ['option','priority'];
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
	/*
	public function optionposition(){
		return $this->hasMany('App\Models\OptionPosition','option_id');
	}*/
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
