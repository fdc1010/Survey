<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AgeBracketRequest as StoreRequest;
use App\Http\Requests\AgeBracketRequest as UpdateRequest;

/**
 * Class AgeBracketCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AgeBracketCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\AgeBracket');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/agebracket');
        $this->crud->setEntityNameStrings('age bracket', 'Age Brackets');
        if(backpack_user()->hasRole('Admin')){
          $this->crud->allowAccess(['create','update','delete']);
        }else{
          $this->crud->denyAccess(['create','update','delete']);
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in AgeBracketRequest
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
