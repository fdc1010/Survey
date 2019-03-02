<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Voter extends Model
{
    use CrudTrait;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'voters';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['precinct_id','barangay_id','is_candidate', 'first_name','last_name','middle_name', 'birth_date','contact',
							'address', 'birth_place','age','gender_id', 'profilepic','status_id','employment_status_id',
							'civil_status_id','occupancy_status_id','occupancy_length','monthly_household',
							'yearly_household','work'];
    // protected $hidden = [];
    // protected $dates = [];
	protected $appends = ['full_name'];

	public function sitio()
    {
        return $this->belongsTo('App\Models\Sitio','sitio_id');
    }
    public function barangay()
      {
          return $this->belongsTo('App\Models\Sitio','barangay_id');
      }
	public function status()
    {
        return $this->belongsTo('App\Models\VoterStatus','status_id');
    }
	public function statuses()
    {
        return $this->hasMany('App\Models\StatusDetail','voter_id');
    }
	public function precinct()
    {
        return $this->belongsTo('App\Models\Precinct','precinct_id')->with('barangay');
    }
	public function employmentstatus()
    {
        return $this->belongsTo('App\Models\EmploymentStatus','employment_status_id');
    }
	public function civilstatus()
    {
        return $this->belongsTo('App\Models\CivilStatus','civil_status_id');
    }
	public function gender(){
		return $this->belongsTo('App\Models\Gender','gender_id');
	}
	public function occupancystatus()
    {
        return $this->belongsTo('App\Models\OccupancyStatus','occupancy_status_id');
    }
	public function surveyvoter()
	{
		return $this->hasMany('App\Models\SurveyAnswer','voter_id');
	}
	public function candidate()
	{
		return $this->hasOne('App\Models\Candidate','voter_id');
	}
  public function positions()
    {
        return $this->belongsToMany('App\Models\PositionCandidate','candidates','position_id','voter_id');
    }
	public function getStatusName(){
		$voterstatus = StatusDetail::with('status')->where('voter_id',$this->id)->get();
		$result = "<ul>";
		foreach($voterstatus as $vstatus){
			$result .= "<li>".$vstatus->status->status." (".$vstatus->status->name.")</li>";
		}
		$result .= "</ul>";
		return $result;

	}
	public function getPrecinct(){
		$precinct = Precinct::find($this->precinct_id);
		return $precinct->precinct_number;
	}
	public function getVoterBarangay(){
		//$barangay = Precinct::where('id',$this->barangay_id)->with('barangay')->first();
		return $this->barangay->name;
	}
	public function getSurveyor(){
		$surveyor = AssignmentDetail::with(['surveyor'=>function($q){$q->with('user');}])->where('sitio_id',$this->sitio_id)->first();
		if(!empty($surveyor->surveyor->user))
			return $surveyor->surveyor->user->name;
	}
	public function getFullNameAttribute()
    {
        return ucwords($this->attributes['first_name'] . ' ' . $this->attributes['middle_name'] . ' ' . $this->attributes['last_name']);
    }
	public function getStatusArrAttribute()
    {
		return $this->status->toArray();
	}
	public function setProfilepicAttribute($value)
    {
        $attribute_name = "profilepic";
        $disk = "profile_pic";
        $destination_path = 'public/profilepic';

        // if the image was erased
        if ($value==null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (starts_with($value, 'data:image'))
        {
            // 0. Make the image
            $image = \Image::make($value)->encode('jpg', 90);
            // 1. Generate a filename.
            $filename = md5($value.time()).'.jpg';
            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());
            // 3. Save the path to the database
            $this->attributes[$attribute_name] = config('app.url')."/profilepic/".$filename;//$destination_path.'/'.$filename;
        }
    }
	/*public static function boot()
	{
		parent::boot();
		static::deleting(function($obj) {
			\Storage::disk('profile_pic')->delete($obj->image);
		});
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
