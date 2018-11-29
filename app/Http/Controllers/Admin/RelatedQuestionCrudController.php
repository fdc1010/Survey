<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\RelatedQuestionRequest as StoreRequest;
use App\Http\Requests\RelatedQuestionRequest as UpdateRequest;

/**
 * Class RelatedQuestionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class RelatedQuestionCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\RelatedQuestion');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/relatedquestion');
        $this->crud->setEntityNameStrings('relatedquestion', 'related_questions');
		
		$this->crud->removeColumns(['question_id','related_question_id','description']);
		$this->crud->removeFields(['question_id','related_question_id','description']);
		$this->crud->addColumn([
            'name' => 'question_id',
            'type' => 'select',
            'label' => 'Question',
			'entity' => 'question', // the relationship name in your Model
			'attribute' => 'question', // attribute on Article that is shown to admin
			'model' => "App\Models\Question"
	    ]);
		$this->crud->addColumn([
            'name' => 'related_question_id',
            'type' => 'select',
            'label' => 'Related Question',
			'entity' => 'relatedquestion', // the relationship name in your Model
			'attribute' => 'question', // attribute on Article that is shown to admin
			'model' => "App\Models\Question"
	    ]);
		$this->crud->addField([
            'name' => 'question_id',
            'type' => 'select2',
            'label' => 'Question',
			'entity' => 'question', // the relationship name in your Model
			'attribute' => 'question', // attribute on Article that is shown to admin
			'model' => "App\Models\Question"
	    ]);
		$this->crud->addField([
            'name' => 'related_question_id',
            'type' => 'select2',
            'label' => 'Related Question',
			'entity' => 'relatedquestion', // the relationship name in your Model
			'attribute' => 'question', // attribute on Article that is shown to admin
			'model' => "App\Models\Question"
	    ]);
        // add asterisk for fields that are required in RelatedQuestionRequest
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
