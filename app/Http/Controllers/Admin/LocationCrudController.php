<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LocationRequest as StoreRequest;
use App\Http\Requests\LocationRequest as UpdateRequest;

/**
 * Class LocationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LocationCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Location');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/location');
        $this->crud->setEntityNameStrings('Areas', 'areas');
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
		$this->crud->removeColumns(['area_id','name','description','municipality_id','barangay_id','sitio_id']);
		$this->crud->removeFields(['area_id','name','description','municipality_id','barangay_id','sitio_id']);
		/*$this->crud->addColumn([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Name'
	    ]);*/
		$this->crud->addColumn([
            'name' => 'area_id',
            'type' => 'select',
            'label' => 'Area',
			'entity' => 'area', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\LocationArea"
	    ]);
		$this->crud->addColumn([
            'name' => 'municipality_id',
            'type' => 'select',
            'label' => 'Municipality',
			'entity' => 'municipality', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Municipality"
	    ]);
		$this->crud->addColumn([
            'name' => 'barangay_id',
            'type' => 'select',
            'label' => 'Barangay',
			'entity' => 'barangay', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Barangay"
	    ]);
		$this->crud->addColumn([
            'name' => 'sitio_id',
            'type' => 'select',
            'label' => 'Sitio',
			'entity' => 'sitio', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Sitio"
	    ]);
		/*$this->crud->addField([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Name'
	    ]);*/
		/*$this->crud->addField([
            'name' => 'area_id',
            'type' => 'checklist',
            'label' => 'Area',
			'entity' => 'area', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\LocationArea"
	    ]);*/
		$this->crud->addField([
            'name' => 'municipality_id',
            'type' => 'select2',
            'label' => 'Municipality',
			'entity' => 'municipality', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Municipality"
	    ]);
		$this->crud->addField([
            'name' => 'barangay_id',
            'type' => 'select2',
            'label' => 'Barangay',
			'entity' => 'barangay', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Barangay"
	    ]);
		$this->crud->addField([
            'name' => 'sitio_id',
            'type' => 'select2',
            'label' => 'Sitio',
			'entity' => 'sitio', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Sitio"
	    ]);

        // add asterisk for fields that are required in LocationRequest
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
