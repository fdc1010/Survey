<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class OptionPosition extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'option_positions';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['option_id','position_id','extras','extras_2'];
    // protected $hidden = [];
    // protected $dates = [];
	protected $casts = [
        'extras' => 'array','extras_2'=>'array'
    ];
	protected $appends = ['option_selection','position_selection'];
	/*protected $casts = [
        'option_id' => 'array',
		'position_id' => 'array'
    ];
		
	public function options()
    {
        return $this->hasMany('App\Models\QuestionOption','option_positions','option_id','position_id');
    }
	public function positions()
    {
        return $this->belongsToMany('App\Models\PositionCandidate','option_positions','position_id','option_id');
    }*/
	public function positions(){
		return $this->belongsTo('App\Models\PositionCandidate','position_id');
	}
	public function options(){
		return $this->belongsTo('App\Models\QuestionOption','option_id');
	}
	public function positionoptions(){
		return $this->hasMany('App\Models\PositionCandidate');
	}
	public function optionpositions(){
		return $this->hasMany('App\Models\QuestionOption');
	}
	public function optionspositions()
    {
        return $this->belongsToMany('App\Models\OptionQuality','option_positions','position_id','option_id');
    }
	public function positionspositions()
    {
        return $this->belongsToMany('App\Models\PositionCandidate','option_positions','option_id','position_id');
    }
	public function getOptionSelectionAttribute(){
		return $this->options->option;	
	}
	public function getPositionSelectionAttribute(){
		return $this->positions->name;	
	}
	public function getOptionSelections(){
		$options = OptionPosition::with('options')->where('position_id',$this->position_id)->get();
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
