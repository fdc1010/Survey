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
        // $countquota = $this->assignment->sum('quota');
        //
        // if($countquota){
        //   info("if: ".$countquota);
        //   return $countquota;
        // }
        // else{
        //   info("else: ".$countquota);
        //   return 1;
        // }
        $countquota = $this->assignment->sum('quota');

        return $countquota;

    }
    public function getProgressPercent(){
      $countquota = $this->assignment->sum('quota');
      if($countquota)
  		    return number_format((($this->getSurveyCount()/$countquota)*100),2) . " %";
      else
          return number_format((($this->getSurveyCount()/1)*100),2) . " %";
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
      info($this->getProgress()."% ".$this->getProgressPercent());
  		$result = "<div class='progress'>".
  					  "<div class='progress-bar' style='width:".$this->getProgress()."%;'>".$this->getProgressPercent()."</div>".
  					"</div>";
  		return $result;
  	}
    public function getProgress(){
      $countquota = $this->assignment->sum('quota');

      if($countquota)
  		    return (($this->getSurveyCount()/$countquota)*100);
      else
          return (($this->getSurveyCount()/1)*100);

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
