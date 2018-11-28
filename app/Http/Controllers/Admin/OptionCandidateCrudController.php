<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OptionCandidateRequest as StoreRequest;
use App\Http\Requests\OptionCandidateRequest as UpdateRequest;

/**
 * Class OptionCandidateCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OptionCandidateCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\OptionCandidate');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/optioncandidate');
        $this->crud->setEntityNameStrings('option candidate', 'Option Candidates');
		
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		$this->crud->removeColumns(['option_id','candidate_id']);
		$this->crud->removeFields(['option_id','candidate_id']);
		$this->crud->orderBy('candidate_id');
		
		$this->crud->addColumn([
            'name' => 'option_id',
            'type' => 'select',
            'label' => 'Option',
			'entity' => 'option', // the relationship name in your Model
			'attribute' => 'option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionOption"
	    ]);
		$this->crud->addColumn([
            'name' => 'candidate_id',
            'type' => 'select',
            'label' => 'Candidate',
			'entity' => 'candidate', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Candidate"
	    ]);
		$this->crud->addField([
            'name' => 'option_id',
            'type' => 'select2',
            'label' => 'Option',
			'entity' => 'option', // the relationship name in your Model
			'attribute' => 'option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionOption"
	    ]);
		$this->crud->addField([
            'name' => 'candidate_id',
            'type' => 'select2',
            'label' => 'Candidate',
			'entity' => 'candidate', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Candidate"
	    ]);
        // add asterisk for fields that are required in OptionCandidateRequest
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
