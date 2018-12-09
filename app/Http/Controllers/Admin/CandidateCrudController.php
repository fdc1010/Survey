<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CandidateRequest as StoreRequest;
use App\Http\Requests\CandidateRequest as UpdateRequest;

/**
 * Class CandidateCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CandidateCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Candidate');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/candidate');
        $this->crud->setEntityNameStrings('candidate', 'candidates');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		$this->crud->removeColumns(['position_id', 'party_id','voter_id']);
		$this->crud->removeFields(['position_id', 'party_id','voter_id']);
        $this->crud->addColumn([
            'label' => "Position",
			'type' => 'select',
			'name' => 'position_id', // the relationship name in your Model
			'entity' => 'position', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\PositionCandidate" // on create&update, do you need to add/delete pivot table entries?
		])->makeFirstColumn();
		$this->crud->addColumn([
            'label' => "Party",
			'type' => 'select',
			'name' => 'party_id', // the relationship name in your Model
			'entity' => 'party', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Party" // on create&update, do you need to add/delete pivot table entries?
		]);
		$this->crud->addColumn([
            'label' => "Candidate",
			'type' => 'select',
			'name' => 'voter_id', // the relationship name in your Model
			'entity' => 'voter', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Voter" // on create&update, do you need to add/delete pivot table entries?
	    ]);
		$this->crud->addField([
			'label' => "Candidate",
			'type' => 'select2entity2',
			'name' => 'voter_id', // the relationship name in your Model
			'entity' => 'voter', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Voter", // on create&update, do you need to add/delete pivot table entries?
			'entity2' => "candidate" // for doesntHave 
		])->beforeField('position_id');
		$this->crud->addField([
			'label' => "Position",
			'type' => 'select',
			'name' => 'position_id', // the relationship name in your Model
			'entity' => 'position', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\PositionCandidate" // on create&update, do you need to add/delete pivot table entries?
		])->beforeField('party_id');
		$this->crud->addField([
			'label' => "Party",
			'type' => 'select',
			'name' => 'party_id', // the relationship name in your Model
			'entity' => 'party', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Party" // on create&update, do you need to add/delete pivot table entries?
		]);
        // add asterisk for fields that are required in CandidateRequest
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
