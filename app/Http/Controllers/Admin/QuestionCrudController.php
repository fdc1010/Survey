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
		$this->crud->removeColumn('with_other_ans');
		$this->crud->removeColumn('with_partyselect');
		$this->crud->removeColumn('for_position');
		$this->crud->removeField('with_partyselect');
		$this->crud->removeField('number_answers');
		$this->crud->removeField('options');
		$this->crud->removeField('type_id');
		$this->crud->removeField('with_other_ans');
		$this->crud->removeField('for_position');
		$this->crud->addColumn([
            'name' => 'number_answers',
            'type' => 'number',
            'label' => 'Number of Req. Answers',
	    ]);				
		$this->crud->addField([
            'name' => 'number_answers',
            'type' => 'number',
            'label' => 'Number of Req. Answers',
			'value' => 1,
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
			'label' => 'Choices',
			'type' => 'tableadv',
			'entity_singular' => 'questionoptions', // used on the "Add X" button
			'columns' => [
				'select' => 'Option',
				'checkbox' => 'With Other Answer'
			],
			'max' => 100, // maximum rows allowed in the table
			'min' => 1 // minimum rows allowed in the table
			'entity' => 'optiondetail', // the method that defines the relationship in your Model
			'attribute' => 'option', // foreign key attribute that is shown to user
			'model' => "App\Models\QuestionOption"			
		]);
		$this->crud->addField([
            'name' => 'with_other_ans',
			'label' => 'With other answer',
			'type' => 'checkbox'
	    ]);
		$this->crud->addField([
            'name' => 'with_partyselect',
			'label' => 'Enable for Vote Straight',
			'type' => 'checkbox'
	    ]);
		$this->crud->addField([
			'label' => "For Position",
			'type' => 'select',
			'name' => 'for_position', // the relationship name in your Model
			'entity' => 'forposition', // the relationship name in your Model
			'attribute' => 'position_name', // attribute on Article that is shown to admin
			'model' => "App\Models\PositionCandidate" // on create&update, do you need to add/delete pivot table entries?
		]);
		/*$this->crud->addField(
			[  // Select2
			   'label' => "Options",
			   'type' => 'select2_multiple',
			   'name' => 'optiondetail', // the db column for the foreign key
			   'entity' => 'optiondetail', // the method that defines the relationship in your Model
			   'attribute' => 'option', // foreign key attribute that is shown to user
			   'model' => "App\Models\QuestionOption", // foreign key model
			   'pivot' => true,
			   'select_all' => true
			]);
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
		]);*/
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
		//$qid = $this->crud->entry->id; // <-- SHOULD WORK
		$options = $this->crud->entry;
		dd($options);
		foreach($options as $option){
			$opsval = $option['option'];
			$questionoptions = QuestionDetail::create([
				'question_id' => $qid,
				'option_id' => $optid
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
