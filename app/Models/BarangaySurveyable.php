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
    public function assignmentsumquota()
    {
        return $this->hasMany('App\Models\AssignmentDetail','barangay_id')->sum('quota');
    }
    public function getQuota(){
        $countquota = $this->assignmentsumquota;
        if($countquota)
          return $countquota;
        else
          return 1;
    }
    public function getProgressPercent(){
  		return number_format((($this->getSurveyCount()/$this->getQuota())*100),2) . " %";
  	}
  	public function getSurveyCount(){
          $voters = Voter::where('barangay_id',$this->barangay_id)
                          ->get()
                          ->pluck('id')
                          ->toArray();
  				$countsurvey = SurveyAnswer::where('survey_detail_id',$this->survey_detail_id)
  											->whereIn('voter_id',$voters)
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

  		return (($this->getSurveyCount()/$this->getQuota())*100);
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
