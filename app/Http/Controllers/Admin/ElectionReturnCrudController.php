<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ElectionReturnRequest as StoreRequest;
use App\Http\Requests\ElectionReturnRequest as UpdateRequest;

/**
 * Class ElectionReturnCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ElectionReturnCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ElectionReturn');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/electionreturn');
        $this->crud->setEntityNameStrings('election return', 'Election Returns');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();
		$this->crud->addColumn([
            'name' => 'precinct_id',			
            'label' => 'Precinct',
            'type' => 'model_function',
			'function_name' => 'getPrecinct'
	    ])->makeFirstColumn();
		$this->crud->addColumn([
            'name' => 'election_id',
            'type' => 'select',
            'label' => 'Election',
			'entity' => 'election', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Election"
	    ]);
		$this->crud->addColumn([
            'name' => 'candidate_id',
            'type' => 'select',
            'label' => 'Candidate',
			'entity' => 'candidate', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Candidate"
	    ]);
		$this->crud->addColumn([
            'name' => 'voter_id',
            'type' => 'select',
            'label' => 'Voter',
			'entity' => 'voter', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Voter"
	    ]);
		$this->crud->addField([
            'name' => 'election_id',
            'type' => 'select2',
            'label' => 'Election',
			'entity' => 'election', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Election"
	    ]);
		$this->crud->addField([
            'name' => 'precinct_id',
            'type' => 'select2',
            'label' => 'Precinct',
			'entity' => 'precinct', // the relationship name in your Model
			'attribute' => 'precinct_info', // attribute on Article that is shown to admin
			'model' => "App\Models\Precinct"
	    ]);
		$this->crud->addField([
            'name' => 'candidate_id',
            'type' => 'select2',
            'label' => 'Candidate',
			'entity' => 'candidate', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Candidate"
	    ]);
		$this->crud->addField([
            'name' => 'voter_id',
            'type' => 'select2',
            'label' => 'Voter',
			'entity' => 'voter', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Voter"
	    ]);
        // add asterisk for fields that are required in ElectionReturnRequest
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
