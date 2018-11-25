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
    protected $fillable = ['name', 'description','extras'];
    // protected $hidden = [];
    // protected $dates = [];
	protected $casts = [
        'extras' => 'array'
    ];
	
	public function optionspositions(){
		return $this->belongsToMany('App\Models\PositionCandidate','option_positions','position_id','option_id');
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
