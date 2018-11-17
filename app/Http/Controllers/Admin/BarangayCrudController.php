<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\BarangayRequest as StoreRequest;
use App\Http\Requests\BarangayRequest as UpdateRequest;

/**
 * Class BarangayCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class BarangayCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Barangay');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/barangay');
        $this->crud->setEntityNameStrings('barangay', 'barangays');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		$this->crud->removeColumn(['province_id','district_id','municipality_id','description']);
        $this->crud->addColumn([
            'name' => 'province_id',
            'type' => 'select',
            'label' => 'Province',
			'entity' => 'province', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Province"
	    ]);
		$this->crud->addColumn([
            'name' => 'district_id',
            'type' => 'select',
            'label' => 'District',
			'entity' => 'district', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\District"
	    ]);
		$this->crud->addColumn([
            'name' => 'municipality_id',
            'type' => 'select',
            'label' => 'Municipality',
			'entity' => 'municipality', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Municipality"
	    ]);
		// add asterisk for fields that are required in BarangayRequest
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
