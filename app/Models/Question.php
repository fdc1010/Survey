<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Question extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'questions';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    //protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];	
	protected $fillable = ['priority', 'question', 'number_answers', 'type_id', 'with_other_ans', 'for_position', 'with_partyselect','options'];
	/*protected $casts = [
        'options' => 'array'
    ];*/
	
	public function choices()
    {
        return $this->belongsToMany('App\Models\QuestionOption','question_details','option_id','question_id');
    }
	public function questiondetail()
    {
        return $this->hasMany('App\Models\QuestionDetail','question_id');
    }
	public function type()
    {
        return $this->belongsTo('App\Models\QuestionType','type_id');
    }
	public function forposition()
    {
        return $this->belongsTo('App\Models\PositionCandidate','for_position');
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
