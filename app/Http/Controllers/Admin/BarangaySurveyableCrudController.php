<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\BarangaySurveyableRequest as StoreRequest;
use App\Http\Requests\BarangaySurveyableRequest as UpdateRequest;

/**
 * Class BarangaySurveyableCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class BarangaySurveyableCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\BarangaySurveyable');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/barangaysurveyable');
        $this->crud->setEntityNameStrings('barangay surveyable', 'Barangay Surveyables');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		$this->crud->addColumn([
            'name' => 'barangay_id',
            'type' => 'select',
            'label' => 'Municipality',
			'entity' => 'barangay', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Barangay"
	    ]);
		$this->crud->addField([
			'label' => "Barangay",
			'type' => 'select2',
			'name' => 'barangay_id', // the relationship name in your Model
			'entity' => 'barangay', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Barangay", // on create&update, do you need to add/delete pivot table entries?
			//'pivot' => true
		]);
        // add asterisk for fields that are required in BarangaySurveyableRequest
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
