<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\QuestionOptionRequest as StoreRequest;
use App\Http\Requests\QuestionOptionRequest as UpdateRequest;

/**
 * Class QuestionOptionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class QuestionOptionCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\QuestionOption');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/questionoption');
        $this->crud->setEntityNameStrings('question option', 'Question Options');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		$this->crud->removeField('has_sub_options');
		$this->crud->addField([
			  // Checkbox
			  'name' => 'has_sub_options',
			  'label' => 'Has Sub Option',
		  	  'type' => 'toggle',
			  'inline' => true,
			  'options' => [
				  0 => 'No',
				  1 => 'Yes'
			  ],
			  'hide_when' => [
				  0 => ['optiondetail'],
				  ],
			  'default' => 0
		]);
		$this->crud->addField(
			[  // Select2
			   'label' => "Options",
			   'type' => 'select2_multiple',
			   'name' => 'suboptiondetail', // the db column for the foreign key
			   'entity' => 'suboptiondetail', // the method that defines the relationship in your Model
			   'attribute' => 'sub_option', // foreign key attribute that is shown to user
			   'model' => "App\Models\QuestionSubOption", // foreign key model
			   'pivot' => true,
			   'select_all' => true
			]);
        // add asterisk for fields that are required in QuestionOptionRequest
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
