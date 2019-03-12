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
    protected $fillable = ['assignment_id','barangay_id','sitio_id','quota','count','progress','task','description'];
    // protected $hidden = [];
    // protected $dates = [];
  	public function assignareas()
      {
          return $this->belongsToMany('App\Models\SurveyAssignment','barangays','assignment_id','barangay_id');
      }
    public function assignareassitios()
      {
          return $this->belongsToMany('App\Models\SurveyAssignment','sitios','assignment_id','sitio_id');
      }
      public function barangaysurveyable()
        {
            return $this->hasOne('App\Models\BarangaySurveyable','barangay_id');
        }
  	public function barangay()
      {
          return $this->belongsTo('App\Models\Barangay','barangay_id');
      }
  	public function sitio()
      {
          return $this->belongsTo('App\Models\Sitio','sitio_id')->with('voters');
      }
  	public function surveyor(){
  		return $this->belongsTo('App\Models\SurveyorAssignment','assignment_id');
  	}
  	public function getProgressPercentB(){
  		return number_format((($this->count/$this->quota)*100),2) . " %";
  	}
    public function getProgressPercent(){
  		return number_format((($this->getSurveyCount()/$this->quota)*100),2) . " %";
  	}
  	public function getSurveyCount(){
  		$surveyassignment = SurveyorAssignment::find($this->assignment_id);
  		if($surveyassignment){
  				//$precincts = Precinct::where('barangay_id',$this->barangay_id)->get()->pluck('id')->toArray();
          // $voters = Voter::where('barangay_id',$this->barangay_id)
          //                 ->get()
          //                 ->pluck('id')
          //                 ->toArray();
  				$countsurvey = SurveyAnswer::where('survey_detail_id',$surveyassignment->survey_detail_id)
  											->where('user_id',$surveyassignment->user_id)
  											//->whereIn('voter_id',$voters)
                        ->where('barangay_id',$this->barangay_id)
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
  	public function getProgressB(){

  		return (($this->count/$this->quota)*100);
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
