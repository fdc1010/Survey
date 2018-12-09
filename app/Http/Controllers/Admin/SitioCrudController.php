<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SitioRequest as StoreRequest;
use App\Http\Requests\SitioRequest as UpdateRequest;

/**
 * Class SitioCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SitioCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Sitio');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/sitio');
        $this->crud->setEntityNameStrings('sitio', 'sitios');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		$this->crud->removeColumns(['barangay_id','description']);
		$this->crud->removeFields(['barangay_id','description']);
		$this->crud->addColumn([
            'name' => 'barangay_id',
            'type' => 'select',
            'label' => 'Barangay',
			'entity' => 'barangay', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Barangay"
	    ]);
		$this->crud->addField([
            'name' => 'barangay_id',
            'type' => 'select2',
            'label' => 'Barangay',
			'entity' => 'barangay', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Barangay"
	    ]);
        // add asterisk for fields that are required in SitioRequest
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
