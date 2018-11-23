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
	protected $appends = ['options'];
	
	public function optionsposition(){
		return $this->belongsToMany('App\Models\QuestionOption','option_positions','position_id','option_id');
	}
	public function getOptionsAttribute(){
		/*$options = OptionPosition::with('options')->where('position_id',$this->id)->get();
		$result = "<ul>";
		foreach($options as $option){
			$result .= "<li>".$option->options->option."</li>";
		}
		$result .= "</ul>";*/
		info($this->optionsposition);
		//return $this->optionsposition;
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
