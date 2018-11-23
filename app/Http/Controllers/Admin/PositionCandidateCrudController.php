<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PositionCandidateRequest as StoreRequest;
use App\Http\Requests\PositionCandidateRequest as UpdateRequest;

/**
 * Class PositionCandidateCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PositionCandidateCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PositionCandidate');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/positioncandidate');
        $this->crud->setEntityNameStrings('position candidate', 'Position Candidates');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		$this->crud->removeColumn(['option_id','position_id']);
		$this->crud->removeField(['option_id','position_id']);
        // add asterisk for fields that are required in PositionCandidateRequest
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
