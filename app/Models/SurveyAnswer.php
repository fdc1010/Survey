<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class SurveyAnswer extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'survey_answers';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['question_id','candidate_id','user_id','voter_id','survey_detail_id','option_id',
							'answered_option','other_answer','option_other_answer','barangay_id',
							'latitude','longitude'];
    // protected $hidden = [];
    // protected $dates = [];
	protected $casts = [
        'answered_option' => 'array'
    ];
	public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
	public function option()
    {
        return $this->belongsTo('App\Models\QuestionOption','option_id');
    }
    public function candidate()
      {
          return $this->belongsTo('App\Models\Candidate','candidate_id');
      }
	public function voter()
    {
        return $this->belongsTo('App\Models\Voter','voter_id');
    }
	public function question()
    {
        return $this->belongsTo('App\Models\Question','question_id')->orderBy('priority');
    }
	public function surveydetail()
    {
        return $this->belongsTo('App\Models\SurveyDetail','survey_detail_id');
    }
  public function getAnsweredOption(){
    $ansval = "";
    foreach ($this->answered_option as $value) {
        $ansval .= "ID: " . $value['id'] . " Other Answer: " . $value['otherAnswer'] . ", ";
    }
    return $ansval;
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
