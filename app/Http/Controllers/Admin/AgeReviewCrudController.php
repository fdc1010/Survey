<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AgeReviewRequest as StoreRequest;
use App\Http\Requests\AgeReviewRequest as UpdateRequest;

/**
 * Class AgeReviewCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AgeReviewCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\AgeReview');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/agereview');
        $this->crud->setEntityNameStrings('age review', 'Age Reviews');
        $this->crud->enableExportButtons();
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
        $this->crud->removeColumns(['voter_id']);
    		$this->crud->removeFields(['voter_id']);
        $this->crud->addColumn([
          'label' => "Voter",
    			'type' => 'select',
    			'name' => 'voter_id', // the relationship name in your Model
    			'entity' => 'voter', // the relationship name in your Model
    			'attribute' => 'full_name', // attribute on Article that is shown to admin
    			'model' => "App\Models\Voter" // on create&update, do you need to add/delete pivot table entries?
  	    ]);
        $this->crud->addField([
    			'label' => "Voter",
    			'type' => 'select2',
    			'name' => 'voter_id', // the relationship name in your Model
    			'entity' => 'voter', // the relationship name in your Model
    			'attribute' => 'full_name', // attribute on Article that is shown to admin
    			'model' => "App\Models\Voter"
    		]);
        // add asterisk for fields that are required in AgeReviewRequest
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
