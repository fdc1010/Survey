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
    protected $fillable = ['option_id','position_id'];
    // protected $hidden = [];
    // protected $dates = [];
	
	protected $appends = ['option_selection'];
	/*protected $casts = [
        'option_id' => 'array',
		'position_id' => 'array'
    ];*/
	/*	
	public function options()
    {
        return $this->hasMany('App\Models\QuestionOption','option_positions','option_id','position_id');
    }
	public function positions()
    {
        return $this->belongsToMany('App\Models\PositionCandidate','option_positions','position_id','option_id');
    }*/
	
	public function positionoptions(){
		return $this->hasMany('App\Models\PositionCandidate','position_id');
	}
	public function optionpositions(){
		return $this->hasMany('App\Models\QuestionOption','option_id');
	}
	public function getOptionSelectionAttribute(){
		return $this->options->option;	
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
