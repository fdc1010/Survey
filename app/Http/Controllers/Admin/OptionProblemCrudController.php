<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OptionProblemRequest as StoreRequest;
use App\Http\Requests\OptionProblemRequest as UpdateRequest;

/**
 * Class OptionProblemCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OptionProblemCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\OptionProblem');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/optionproblem');
        $this->crud->setEntityNameStrings('option problem', 'Option Problems');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		$this->crud->addColumn([
            'name' => 'option_id',
            'type' => 'select',
            'label' => 'Problem',
			'entity' => 'option', // the relationship name in your Model
			'attribute' => 'option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionOption"
	    ]);
		$this->crud->addField([
			'label' => "Problem",
			'type' => 'select2',
			'name' => 'option_id', // the relationship name in your Model
			'entity' => 'option', // the relationship name in your Model
			'attribute' => 'option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionOption", // on create&update, do you need to add/delete pivot table entries?
			//'pivot' => true
		]);
        // add asterisk for fields that are required in OptionProblemRequest
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
