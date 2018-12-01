<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class OptionQuality extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'option_qualities';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['option_id','description'];
    // protected $hidden = [];
    // protected $dates = [];
	public function option()
    {
        return $this->belongsTo('App\Models\QuestionOption','option_id');
    }
	public function position()
    {
        return $this->hasMany('App\Models\PositionCandidate','position_id');
    }
	public function options()
    {
        return $this->hasMany('App\Models\OptionPosition','option_id');
    }
	public function positions(){
		return $this->hasMany('App\Models\OptionPosition','position_id');
    }
	public function options_and_positions()
    {
        return $this->belongsToMany('App\Models\QuestionOption','App\Models\PositionCandidate');
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
