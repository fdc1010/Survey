<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class BarangaySurveyable extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'barangay_surveyables';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['barangay_id','count','progress','quota','survey_detail_id'];
    // protected $hidden = [];
    // protected $dates = [];
	/*protected $casts = [
        'barangay_id' => 'array'
    ];*/
	  public function barangay()
    {
        return $this->belongsTo('App\Models\Barangay','barangay_id');
    }
    public function assignment()
    {
        return $this->hasMany('App\Models\AssignmentDetail','barangay_id');
    }
    public function getQuota(){
        $countquota = $this->assignment->sum('quota');

        if($countquota){
          return $countquota;
        }
        else{
          return 0;
        }

    }
    public function getProgressPercent(){
      if($this->getQuota())
          return number_format((($this->getSurveyCount()/$this->getQuota())*100),2) . " %";

      return "0.00 %";
  	}
  	public function getSurveyCount(){
          // $voters = Voter::where('barangay_id',$this->barangay_id)
          //                 ->get()
          //                 ->pluck('id')
          //                 ->toArray();
  				$countsurvey = SurveyAnswer::where('survey_detail_id',$this->survey_detail_id)
  											//->whereIn('voter_id',$voters)
                        ->where('barangay_id',$this->barangay_id)
  											->select(['voter_id'])
  											->groupBy('voter_id')
  											->get();
  				if($countsurvey)
  					return count($countsurvey);
  				else
  					return 0;
  	}
  	public function getProgressBar(){
  		$result = "<div class='progress'>".
  					  "<div class='progress-bar' style='width:".$this->getProgress()."%;'>".$this->getProgressPercent()."</div>".
  					"</div>";
  		return $result;
  	}
    public function getProgress(){

      if($this->getQuota())
          return (($this->getSurveyCount()/$this->getQuota())*100);

      return 0;
  	}
    public function getAllSurveyCount(){
      $countsurvey = SurveyAnswer::where('question_id',4) // Set by default to Question ID 4. for Mayor... Update this if id changes..
  										            ->get();
  		if($countsurvey)
  			return count($countsurvey);
  		else
  			return 0;
    }
    public function getAllSurveyQuota(){

      return  AssignmentDetail::sum('quota');
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
