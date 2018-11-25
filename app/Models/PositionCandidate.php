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
    protected $fillable = ['name', 'description','options'];
    // protected $hidden = [];
    // protected $dates = [];
	protected $casts = [
        'options' => 'array'
    ];
	public function positionoptions(){
		return $this->hasMany('App\Models\OptionPosition');
	}
	public function optionsposition(){
		return $this->hasMany('App\Models\OptionPosition','option_id');
	}
	public function optionspositions(){
		return $this->belongsToMany('App\Models\PositionCandidate','option_positions','option_id','position_id');
	}
	public function position(){
		return $this->belongsTo('App\Models\OptionPosition');
	}
	public function options(){
		return $this->belongsTo('App\Models\OptionPosition');
	}
	public function optionselections(){
		return $this->belongsTo('App\Models\QuestionOption','option_id');
	}
	public function getOptionSelections(){
		$options = OptionPosition::with('options')->where('position_id',$this->id)->get();
		$result = "";
		foreach($options as $option){
			$result .= $option->options->option."<br>";
		}
		
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
