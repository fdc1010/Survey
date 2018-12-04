<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class SurveyorAssignment extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'surveyor_assignments';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['user_id','quota','progress','task','description','areas','survey_detail_id'];
    protected $casts = [
        'areas' => 'array'
    ];
	// protected $hidden = [];
    // protected $dates = [];
	public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
	public function surveyareas()
    {
        return $this->belongsToMany('App\Models\Sitio','assignment_details','sitio_id','assignment_id');
    }
	public function assignments(){
		return $this->hasMany('App\Models\AssignmentDetail','assignment_id');
	}
	public function surveydetail()
    {
        return $this->belongsTo('App\Models\SurveyDetail','survey_detail_id');
    }
	public function getAreas(){
		$areas = AssignmentDetail::where('assignment_id',$this->id)
										->with('sitio')
										->get();
		$result = "";
		foreach($areas as $area){
			$result .= $area->sitio->name." - quota: ".$area->quota."<br>";
		}
		return $result;
	}
	public function getProgress(){
		$countsurvey = SurveyAnswer::where('survey_detail_id',$this->survey_detail_id)
										->where('user_id',$this->user_id)
										->count();
		
		return (($this->getSurveyCount()/$this->quota)*100);
	}
	public function getProgressPercent(){		
		return (($this->getSurveyCount()/$this->quota)*100) . " %";
	}
	
	public function getSurveyCount(){
		$countsurvey = SurveyAnswer::where('survey_detail_id',$this->survey_detail_id)
										->where('user_id',$this->user_id)
										->select(['voter_id'])
										->groupBy('voter_id')
										->get();
		if($countsurvey)
			return count($countsurvey);
		else
			return 0;
	}
	/*public function getSurveyCountPerArea(){
		$surveyarea = $this->with(['assignments'=>function($q){
										$q->with(['sitio'=>function($qs){											
											$qs->with(['voters'=>function($qv){												
														$surveyansvoterid = SurveyAnswer::where('survey_detail_id',$this->survey_detail_id)
															->where('user_id',$this->user_id)
															->select(['voter_id'])
															->groupBy('voter_id')
															->get()->pluck('voter_id')->toArray();
														$qv->whereIn('id',$surveyansvoterid);
													}]);
										}]);
									}])
							->get();
		$survey_per_area_count = array();
		if(!empty($surveyarea->assignments)){
			foreach($surveyarea->assignments as $assignment){
				array_push($survey_per_area_count,$assignment->sitio->voters);
			}
		}
		return $survey_per_area_count;
	
	}*/
	/*
	public function barangay()
    {
        return $this->belongsTo('App\Barangay','barangay_id');
    }
	public function sitio()
    {
        return $this->belongsTo('App\Sitio','sitio_id');
    }*/
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
