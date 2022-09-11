<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\QuestionDetailRequest as StoreRequest;
use App\Http\Requests\QuestionDetailRequest as UpdateRequest;

/**
 * Class QuestionDetailCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class QuestionDetailCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\QuestionDetail');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/questiondetail');
        $this->crud->setEntityNameStrings('questiondetail', 'question_details');
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

        // add asterisk for fields that are required in QuestionDetailRequest
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
