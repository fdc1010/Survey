<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SurveyDetailRequest as StoreRequest;
use App\Http\Requests\SurveyDetailRequest as UpdateRequest;

/**
 * Class SurveyDetailCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SurveyDetailCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SurveyDetail');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/surveydetail');
        $this->crud->setEntityNameStrings('survey detail', 'Survey Details');
        if(backpack_user()->hasPermissionTo('Edit')){
          $this->crud->allowAccess(['update']);
        }else{
          $this->crud->denyAccess(['update']);
        }
        if(backpack_user()->hasPermissionTo('Add')){
          $this->crud->allowAccess(['create']);
        }else{
          $this->crud->denyAccess(['create']);
        }
        if(backpack_user()->hasPermissionTo('Delete')){
          $this->crud->allowAccess(['delete']);
        }else{
          $this->crud->denyAccess(['delete']);
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		$this->crud->removeColumn(['for_position']);
		$this->crud->removeField(['for_position']);
		$this->crud->addColumn([
            'name' => 'for_position',
            'type' => 'select',
            'label' => 'Position',
			'entity' => 'position', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\PositionCandidate"
	    ]);
		$this->crud->addField([
            'name' => 'for_position',
            'type' => 'select',
            'label' => 'Position',
			'entity' => 'position', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\PositionCandidate"
	    ]);
        // add asterisk for fields that are required in SurveyDetailRequest
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
