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
    protected $fillable = ['barangay_id','count','progress','quota'];
    // protected $hidden = [];
    // protected $dates = [];
	/*protected $casts = [
        'barangay_id' => 'array'
    ];*/
	  public function barangay()
    {
        return $this->belongsTo('App\Models\Barangay','barangay_id');
    }
    public function getProgressPercent(){
  		return number_format((($this->getSurveyCount()/$this->quota)*100),2) . " %";
  	}
  	public function getSurveyCount(){
  		$surveyassignment = SurveyorAssignment::find($this->assignment_id);
  		if($surveyassignment){
  				//$precincts = Precinct::where('barangay_id',$this->barangay_id)->get()->pluck('id')->toArray();
          $voters = Voter::where('barangay_id',$this->barangay_id)
                          ->get()
                          ->pluck('id')
                          ->toArray();
  				$countsurvey = SurveyAnswer::where('survey_detail_id',$surveyassignment->survey_detail_id)
  											->where('user_id',$surveyassignment->user_id)
  											->whereIn('voter_id',$voters)
  											->select(['voter_id'])
  											->groupBy('voter_id')
  											->get();
  				if($countsurvey)
  					return count($countsurvey);
  				else
  					return 0;
  		}else{
  			return 0;
  		}
  	}
  	public function getProgressBar(){

  		$result = "<div class='progress'>".
  					  "<div class='progress-bar' style='width:".$this->getProgress()."%;'>".$this->getProgressPercent()."</div>".
  					"</div>";
  		return $result;
  	}
    public function getProgress(){

  		return (($this->getSurveyCount()/$this->quota)*100);
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
