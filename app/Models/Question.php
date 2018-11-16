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
	 protected $casts = [
        'options' => 'array'
    ];
	
	protected $fillable = ['priority', 'question', 'number_answers', 'type_id', 'with_other_ans', 'for_position', 'with_partyselect','options'];
	
	public function choices()
    {
        return $this->belongsToMany('App\Models\QuestionOption','App\Models\QuestionDetail')->withPivot('question_id','option_id');
    }
	public function questiondetail()
    {
        return $this->belongsToMany('App\Models\Question','App\Models\QuestionDetail')->withPivot('question_id','option_id');
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
