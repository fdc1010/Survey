<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class PositionCandidate extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'position_candidates';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'description'];
    // protected $hidden = [];
    // protected $dates = [];
	protected $appends = ['option_selections'];
	
	public function optionsposition(){
		return $this->hasMany('App\Models\QuestionOption','position_id');
	}
	public function getOptionSelectionsAttribute(){
		/*$options = OptionPosition::with('options')->where('position_id',$this->id)->get();
		$result = "<ul>";
		foreach($options as $option){
			$result .= "<li>".$option->options->option."</li>";
		}
		$result .= "</ul>";*/
		
		//return $this->optionsposition;
		return $this->optionsposition;
	}
	public function getOptionsSelections(){
		$options = OptionPosition::with('options')->where('position_id',$this->id)->get();
		$result = "<ul>";
		foreach($options as $option){
			$result .= "<li>".$option->options->option."</li>";
		}
		$result .= "</ul>";
		
		return $result;
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
