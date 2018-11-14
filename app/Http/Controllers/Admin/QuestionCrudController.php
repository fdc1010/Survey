<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\QuestionRequest as StoreRequest;
use App\Http\Requests\QuestionRequest as UpdateRequest;

/**
 * Class QuestionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class QuestionCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Question');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/question');
        $this->crud->setEntityNameStrings('question', 'questions');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		
		$this->crud->removeColumn('number_answers');
		$this->crud->addColumn([
            'name' => 'number_answers',
            'type' => 'number',
            'label' => 'Number of Req. Answers',
	    ]);
		$this->crud->removeColumn('options');
		$this->crud->removeField('options');
		$this->crud->removeField('number_answers');
		$this->crud->removeField('type_id');
		$this->crud->addField([
            'name' => 'number_answers',
            'type' => 'number',
            'label' => 'Number of Req. Answers',
			'value' => 0,
	    ]);
		
		$this->crud->addField([
			'label' => "Type",
			'type' => 'select',
			'name' => 'type_id', // the relationship name in your Model
			'entity' => 'type', // the relationship name in your Model
			'attribute' => 'type_name', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionType" // on create&update, do you need to add/delete pivot table entries?
		]);
		$this->crud->addField([
			'name' => 'options',
			'label' => 'Options',
			'type' => 'table',
			'entity_singular' => 'option', // used on the "Add X" button
			'columns' => [
				'option' => 'Name'
			],
			'max' => 5, // maximum rows allowed in the table
			'min' => 0, // minimum rows allowed in the table
		]);
        // add asterisk for fields that are required in QuestionRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
		$id = $this->crud->entry->id; // <-- SHOULD WORK
		$options = $this->crud->entry->options;
		
		foreach($options as $option){
			$opsval = $option['option'];
			$questionoptions = QuestionOption::create([
				'question_id' => $id,
				'option' => $opsval
			]);			
		}
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
