<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Survey extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'surveys';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['user_id','voter_id','survey_detail_id','is_done','call_date','had_called',
							'call_times','is_sync','sync_date','sync_times','progress'];
    // protected $hidden = [];
    // protected $dates = [];
	public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
	public function voter()
    {
        return $this->belongsTo('App\Models\Voter','voter_id');
    }
	public function surveydetail()
    {
        return $this->belongsTo('App\Models\SurveyDetail','survey_detail_id');
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
