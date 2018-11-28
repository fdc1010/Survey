<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class AssignmentDetail extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'assignment_details';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['assignment_id','barangay_id','sitio_id','quota','progress','task','description'];
    // protected $hidden = [];
    // protected $dates = [];
	public function assignareas()
    {
        return $this->belongsToMany('App\Models\SurveyAssignment','sitios','assignment_id','sitio_id');
    }
	public function barangay()
    {
        return $this->belongsTo('App\Models\Barangay','barangay_id');
    }
	public function sitio()
    {
        return $this->belongsTo('App\Models\Sitio','sitio_id');
    }
	public function surveyor(){
		return $this->belongsTo('App\Models\SurveyorAssignment','assignment_id');
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
