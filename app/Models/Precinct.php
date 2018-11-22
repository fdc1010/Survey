<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Precinct extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'precincts';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['precinct_number', 'barangay_id'];
    // protected $hidden = [];
    // protected $dates = [];
	protected $appends = ['precinct_info'];
	public function barangay()
    {
        return $this->belongsTo('App\Models\Barangay','barangay_id');
    }
	public function getPrecinctInfoAttribute()
    {		
        return $this->attributes['precinct_number'] . ' (' . $this->barangay->name . ')';
    }
	public function getPrecinctBarangayAttribute()
    {		
        return $this->barangay->name;
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
