<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LocationCoordinateRequest as StoreRequest;
use App\Http\Requests\LocationCoordinateRequest as UpdateRequest;

/**
 * Class LocationCoordinateCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LocationCoordinateCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\LocationCoordinate');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/locationcoordinate');
        $this->crud->setEntityNameStrings('coordinate', 'Coordinates');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		$this->crud->removeColumns(['location_id','shape_id','name','description','coordinate','latitude','longitude']);
		$this->crud->removeFields(['location_id','shape_id','name','description','coordinate','latitude','longitude']);
		$this->crud->addColumn([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Name'
	    ]);
		$this->crud->addColumn([
            'name' => 'location_id',
            'type' => 'select',
            'label' => 'Location',
			'entity' => 'location', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Location"
	    ]);
		$this->crud->addColumn([
            'name' => 'shape_id',
            'type' => 'select',
            'label' => 'Shape',
			'entity' => 'shape', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\LocationShape"
	    ]);
		$this->crud->addField([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Name'
	    ]);
		$this->crud->addField([
            'name' => 'location_id',
            'type' => 'select2',
            'label' => 'Location',
			'entity' => 'location', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Location"
	    ]);
		$this->crud->addField([
            'name' => 'shape_id',
            'type' => 'checklist',
            'label' => 'Shape',
			'entity' => 'shape', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\LocationShape"
	    ]);
        // add asterisk for fields that are required in LocationCoordinateRequest
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
