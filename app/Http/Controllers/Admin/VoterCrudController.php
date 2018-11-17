<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\VoterRequest as StoreRequest;
use App\Http\Requests\VoterRequest as UpdateRequest;

/**
 * Class VoterCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class VoterCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Voter');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/voter');
        $this->crud->setEntityNameStrings('voter', 'voters');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		$this->crud->removeColumn(['precinct_id','address','age','contact','birth_date','birth_place','status_id']);
	
		$this->crud->addColumn([
            'name' => 'precinct_id',			
            'label' => 'Precinct',
            'type' => 'model_function',
			'function_name' => 'getPrecinct'
	    ])->makeFirstColumn();
		$this->crud->addColumn([
            'name' => 'status_id',			
            'label' => 'Status',
            'type' => 'model_function',
			'function_name' => 'getStatusName'
	    ]);
		$this->crud->addColumn([
            'name' => 'barangay',			
            'label' => 'Barangay',
            'type' => 'model_function',
			'function_name' => 'getVoterBarangay',
			'fake' => true
	    ]);
		$this->crud->addField([
			'label' => "Status",
			'type' => 'selectadv',
			'name' => 'status_id', // the relationship name in your Model
			'entity' => 'status', // the relationship name in your Model
			'attribute' => 'status', // attribute on Article that is shown to admin
			'attribute2' => 'name',
			'model' => "App\Models\VoterStatus" // on create&update, do you need to add/delete pivot table entries?
		]);
        // add asterisk for fields that are required in VoterRequest
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
