<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\QuestionOptionDetailRequest as StoreRequest;
use App\Http\Requests\QuestionOptionDetailRequest as UpdateRequest;

/**
 * Class QuestionOptionDetailCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class QuestionOptionDetailCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\QuestionOptionDetail');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/questionoptiondetail');
        $this->crud->setEntityNameStrings('question option detail', 'Question Option Details');
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
		$this->crud->removeColumns(['question_id','option_id','sub_option_id','description']);
		$this->crud->removeFields(['question_id','option_id','sub_option_id']);
    
		$this->crud->addColumn([
      'name' => 'question_id',
      'type' => 'select',
      'label' => 'Question',
			'entity' => 'question', // the relationship name in your Model
			'attribute' => 'question', // attribute on Article that is shown to admin
			'model' => "App\Models\Question"
	    ]);
		$this->crud->addColumn([
      'name' => 'option_id',
      'type' => 'select',
      'label' => 'Options',
			'entity' => 'option', // the relationship name in your Model
			'attribute' => 'option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionOption"
	    ]);
		$this->crud->addColumn([
      'name' => 'sub_option_id',
      'type' => 'select',
      'label' => 'Sub Option',
			'entity' => 'suboption', // the relationship name in your Model
			'attribute' => 'sub_option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionSubOption"
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
      'name' => 'option_id',
      'type' => 'select2',
      'label' => 'Options',
			'entity' => 'option', // the relationship name in your Model
			'attribute' => 'option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionOption"
	    ]);
		$this->crud->addField([
            'name' => 'sub_option_id',
            'type' => 'select2',
            'label' => 'Sub Option',
			'entity' => 'suboption', // the relationship name in your Model
			'attribute' => 'sub_option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionSubOption"
	    ]);
        // add asterisk for fields that are required in QuestionOptionDetailRequest
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
