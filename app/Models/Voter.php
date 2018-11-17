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
							'address', 'birth_place','age','gender', 'profilepic','status_id'];
    // protected $hidden = [];
    // protected $dates = [];
	public function status()
    {
        return $this->belongsTo('App\Models\VoterStatus','status_id');
    }
	public function precinct()
    {
        return $this->belongsTo('App\Models\Precinct','precinct_id');
    }
	public function getStatusName(){
		$voterstatus = VoterStatus::find($this->status_id);
		if($voterstatus)
			return $voterstatus->status . " (" . $voterstatus->name . ")";
		else
			return "";
	}
	public function getPrecinct(){
		$precinct = Precinct::find($this->precinct_id);
		return $precinct->precinct_number;
	}
	public function getVoterBarangay(){
		$barangay = Precinct::where('id',$this->precinct_id)->with('barangay')->first();
		return $barangay->barangay->name;
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
