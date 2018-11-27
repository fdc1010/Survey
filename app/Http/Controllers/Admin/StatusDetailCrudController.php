<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\StatusDetailRequest as StoreRequest;
use App\Http\Requests\StatusDetailRequest as UpdateRequest;

/**
 * Class StatusDetailCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StatusDetailCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\StatusDetail');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/statusdetail');
        $this->crud->setEntityNameStrings('status detail', 'Status Details');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		$this->crud->removeColumn(['status_id','voter_id']);
		$this->crud->removeField(['status_id','voter_id']);
		$this->crud->addColumn([
            'name' => 'voter_id',
            'type' => 'select',
            'label' => 'Voter',
			'entity' => 'voter', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Voter"
	    ]);
		$this->crud->addColumn([
            'name' => 'status_id',
            'type' => 'select',
            'label' => 'Status',
			'entity' => 'status', // the relationship name in your Model
			'attribute' => 'status_name', // attribute on Article that is shown to admin
			'model' => "App\Models\VoterStatus"
	    ]);
		$this->crud->addField([
			'label' => "Status",
			'type' => 'select',
			'name' => 'status_id', // the relationship name in your Model
			'entity' => 'status', // the relationship name in your Model
			'attribute' => 'status_name', // attribute on Article that is shown to admin
			'model' => "App\Models\VoterStatus", // on create&update, do you need to add/delete pivot table entries?
			//'attribute2' => 'name', // attribute on Article that is shown to admin
			//'entity2' => "barangay"
		]);
		$this->crud->addField([
			'name' => 'voter_id',
            'type' => 'select',
            'label' => 'Voter',
			'entity' => 'voter', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Voter"
		]);
        // add asterisk for fields that are required in StatusDetailRequest
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
