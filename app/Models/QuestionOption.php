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
        $optpositions = OptionPosition::with('position')->where('option_id',$this->id)->get();		
		$result = "<ul>";
		foreach($optpositions as $optposition){
			$result .= "<li>".$optposition->position->name."</li>";
		}
		$result .= "</ul>";
		return $result;
    }
	
	public function optionsposition(){
		return $this->belongsToMany('App\Models\QuestionOption','option_positions','position_id','option_id');
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
