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
        $this->crud->removeColumns(['answered_option']);
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
