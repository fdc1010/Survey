<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SurveyAnswerRequest as StoreRequest;
use App\Http\Requests\SurveyAnswerRequest as UpdateRequest;

/**
 * Class SurveyAnswerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SurveyAnswerCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SurveyAnswer');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/surveyanswer');
        $this->crud->setEntityNameStrings('survey answer', 'Survey Answers');
		$this->crud->denyAccess(['update', 'create', 'delete']);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
        $this->crud->removeColumns(['user_id','voter_id','question_id','option_id','answered_option','survey_detail_id']);
        $this->crud->addColumn([
            'label' => "User",
      			'type' => 'select',
      			'name' => 'user_id', // the relationship name in your Model
      			'entity' => 'user', // the relationship name in your Model
      			'attribute' => 'name', // attribute on Article that is shown to admin
      			'model' => "App\User" // on create&update, do you need to add/delete pivot table entries?
    		])->makeFirstColumn();
        $this->crud->addColumn([
            'label' => "Voter",
      			'type' => 'select',
      			'name' => 'voter_id', // the relationship name in your Model
      			'entity' => 'voter', // the relationship name in your Model
      			'attribute' => 'full_name', // attribute on Article that is shown to admin
      			'model' => "App\Models\Voter" // on create&update, do you need to add/delete pivot table entries?
    		])->afterColumn('user_id');
        $this->crud->addColumn([
            'label' => "Question",
      			'name' => 'question_id'
    		])->beforeColumn('question_id');
        $this->crud->addColumn([
            'label' => "Question",
      			'type' => 'select',
      			'name' => 'question_id', // the relationship name in your Model
      			'entity' => 'question', // the relationship name in your Model
      			'attribute' => 'question', // attribute on Article that is shown to admin
      			'model' => "App\Models\Question" // on create&update, do you need to add/delete pivot table entries?
    		])->afterColumn('voter_id');
        $this->crud->addColumn([
            'label' => "Answer",
      			'type' => 'select',
      			'name' => 'option_id', // the relationship name in your Model
      			'entity' => 'option', // the relationship name in your Model
      			'attribute' => 'option', // attribute on Article that is shown to admin
      			'model' => "App\Models\QuestionOption" // on create&update, do you need to add/delete pivot table entries?
    		])->afterColumn('question_id');
        $this->crud->addColumn([
                'name' => 'answered_option',
                'label' => 'Answered Option',
                'type' => 'model_function',
    			'function_name' => 'getAnsweredOption',
    			//'fake' => true
    	    ]);
        // add asterisk for fields that are required in SurveyAnswerRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
