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
    protected $fillable = ['precinct_id', 'first_name','last_name','middle_name', 'birth_date','contact',
							'address', 'birth_place','age','gender', 'profilepic','status_id'];
    // protected $hidden = [];
    // protected $dates = [];
	public function status()
    {
        return $this->belongsTo('App\Models\VoterStatus','status_id');
    }
	public function precinct()
    {
        return $this->belongsTo('App\Models\Precinct','precinct_id');
    }
	public function getStatusName(){
		$voterstatus = VoterStatus::find($this->status_id);
		if($voterstatus)
			return $voterstatus->status . " (" . $voterstatus->name . ")";
		else
			return "";
	}
	public function getPrecinct(){
		$precinct = Precinct::find($this->precinct_id);
		return $precinct->precinct_number;
	}
	public function getVoterBarangay(){
		$barangay = Precinct::where('id',$this->precinct_id)->with('barangay')->first();
		return $barangay->barangay->name;
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
            $this->attributes[$attribute_name] = config('app.url')."/".$filename;//$destination_path.'/'.$filename;
        }
    }
	public static function boot()
	{
		parent::boot();
		static::deleting(function($obj) {
			\Storage::disk('profile_pic')->delete($obj->image);
		});
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
