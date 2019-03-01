<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Barangay extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'barangays';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name','province_id','district_id','municipality_id','description'];
    // protected $hidden = [];
    // protected $dates = [];
	public function province()
    {
        return $this->belongsTo('App\Models\Province','province_id');
    }
	public function district()
    {
        return $this->belongsTo('App\Models\District','district_id');
    }
	public function municipality()
    {
        return $this->belongsTo('App\Models\Municipality','municipality_id');
    }
	public function sitio(){
		return $this->hasMany('App\Models\Sitio','barangay_id');
	}
  public function precincts(){
		return $this->hasMany('App\Models\Precinct','barangay_id');
	}
  public function surveyor(){
		return $this->hasMany('App\Models\AssignmentDetail','barangay_id');
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
