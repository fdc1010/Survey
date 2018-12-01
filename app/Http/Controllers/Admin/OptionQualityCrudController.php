<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OptionQualityRequest as StoreRequest;
use App\Http\Requests\OptionQualityRequest as UpdateRequest;

/**
 * Class OptionQualityCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OptionQualityCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\OptionQuality');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/optionquality');
        $this->crud->setEntityNameStrings('option quality', 'Option Qualities');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		//$this->crud->removeColumns(['option_id','position_id','extras','extras_2']);
		$this->crud->removeFields(['option_id','description']);
		$this->crud->addColumn([
            'name' => 'option_id',
            'type' => 'select',
            'label' => 'Qualities',
			'entity' => 'option', // the relationship name in your Model
			'attribute' => 'option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionOption"
	    ]);
		/*$this->crud->addField([
			'label' => "Qualities",
			'type' => 'select2',
			'name' => 'option_id', // the relationship name in your Model
			'entity' => 'options', // the relationship name in your Model
			'attribute' => 'option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionOption", // on create&update, do you need to add/delete pivot table entries?
			//'pivot' => true
		]);
		$this->crud->addField([
			'label' => "Positions",
			'type' => 'checklist',
			'name' => 'position_id', 
			'entity' => 'positions',
			'attribute' => 'name', 
			'model' => "App\Models\PositionCandidate", 
			//'pivot' => false
		]);*/
		$this->crud->addField(
		[   // two interconnected entities
			'label'             => 'Candidate Qualities',
			'field_unique_name' => 'option_positions',
			'type'              => 'checklist_dependency',
			'name'              => 'options_and_positions', // the methods that defines the relationship in your Model
			'subfields'         => [
				'primary' => [
					'label'            => 'Qualities',
					'name'             => 'option_id', // the method that defines the relationship in your Model
					'entity'           => 'options', // the method that defines the relationship in your Model
					'entity_secondary' => 'positions', // the method that defines the relationship in your Model
					'attribute'        => 'option', // foreign key attribute that is shown to user
					'model'            => "App\Models\QuestionOption", // foreign key model
					'pivot'            => false, // on create&update, do you need to add/delete pivot table entries?]
					'number_columns'   => 3, //can be 1,2,3,4,6
				],
				'secondary' => [
					'label'          => 'Positions',
					'name'           => 'position_id', // the method that defines the relationship in your Model
					'entity'         => 'positions', // the method that defines the relationship in your Model
					'entity_primary' => 'options', // the method that defines the relationship in your Model
					'attribute'      => 'name', // foreign key attribute that is shown to user
					'model'          => "App\Models\PositionCandidate", // foreign key model
					'pivot'          => false, // on create&update, do you need to add/delete pivot table entries?]
					'number_columns' => 3, //can be 1,2,3,4,6
				],
			],
			'fake' => true
		]
		);
        // add asterisk for fields that are required in OptionQualityRequest
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
