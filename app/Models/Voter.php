<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Voter extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'voters';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['precinct_id', 'first_name','last_name','middle_name', 'birth_date','contact',
							'address', 'birth_place','age','gender', 'profilepic','baranggay','status_id'];
    // protected $hidden = [];
    // protected $dates = [];
	public function statusname()
    {
        return $this->belongsTo('App\Models\VoterStatus','status_id');
    }
	
	public function getStatusName(){
		$voterstatus = VoterStatus::find($this->status_id);
		return $voterstatus;//->status . "(" . $voterstatus->name . ")";
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
