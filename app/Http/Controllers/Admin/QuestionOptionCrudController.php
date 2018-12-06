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
		$this->crud->removeColumns(['positions']);
		$this->crud->addColumn([
            'name' => 'position',			
            'label' => 'Positions Tagged',
            'type' => 'model_function',
			'function_name' => 'getPositions',
			//'fake' => true
	    ])->afterColumn('priority');
		$this->crud->addColumn([
            'name' => 'for_candidate_quality',			
            'label' => 'for Candidate Qualities',
            'type' => 'model_function',
			'function_name' => 'forCandidateQuality',
			//'fake' => true
	    ])->afterColumn('position');
		$this->crud->addColumn([
            'name' => 'for_candidate_votes',			
            'label' => 'for Candidate Votes',
            'type' => 'model_function',
			'function_name' => 'forCandidateVotes',
			//'fake' => true
	    ])->afterColumn('for_candidate_quality');
		$this->crud->addColumn([
            'name' => 'candidate_id',
            'type' => 'select2',
            'label' => 'Tagged Option to Candidate',
			'entity' => 'candidate', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Candidate"
	    ])->afterColumn('for_candidate_votes');
        $this->crud->addField([
            'name' => 'for_candidate_quality',
			'label' => 'Is Option for Candidate Qualities',
			'type' => 'checkbox'
	    ]);
		$this->crud->addField([
            'name' => 'for_candidate_votes',
			'label' => 'Is Option for Candidate Votes (if Option is Name of Candidate)',
			'type' => 'checkbox'
	    ]);
		$this->crud->addField([
			'label' => "Positions",
			'type' => 'checklistchkall',
			'name' => 'positions', 
			'entity' => 'positions',
			'attribute' => 'name', 
			'model' => "App\Models\PositionCandidate"
		]);
		$this->crud->addField([
            'name' => 'candidate_id',
            'type' => 'select2',
            'label' => 'Tagged Option to Candidate',
			'entity' => 'candidate', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Candidate"
	    ]);
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
