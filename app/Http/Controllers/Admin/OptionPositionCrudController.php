<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OptionPositionRequest as StoreRequest;
use App\Http\Requests\OptionPositionRequest as UpdateRequest;

/**
 * Class OptionPositionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OptionPositionCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\OptionPosition');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/optionposition');
        $this->crud->setEntityNameStrings('option position', 'option positions');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		
		$this->crud->removeColumns(['option_id','position_id','extras','extras_2']);
		$this->crud->removeFields(['option_id','position_id','extras','extras_2']);
		$this->crud->orderBy('position_id');
		$this->crud->addColumn([
            'name' => 'position_id',
            'type' => 'select',
            'label' => 'Position',
			'entity' => 'positions', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\PositionCandidate"
	    ]);
		$this->crud->addColumn([
            'name' => 'option_id',
            'type' => 'select',
            'label' => 'Tagged Options for Qualities',
			'entity' => 'options', // the relationship name in your Model
			'attribute' => 'option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionOption"
	    ]);		
		/*$this->crud->addField([
            'name' => 'position_id',
            'type' => 'select',
            'label' => 'Positions',
			'entity' => 'positions', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\PositionCandidate",
	    ]);
		$this->crud->addField([
            'name' => 'option_id',
            'type' => 'select2',
            'label' => 'Tagged Options for Qualities',
			'entity' => 'options', // the relationship name in your Model
			'attribute' => 'option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionOption"
	    ]);*/
		$this->crud->addField(
		[   // two interconnected entities
			'label'             => 'Candidate Qualities',
			'field_unique_name' => 'option_positions',
			'type'              => 'checklist_dependency',
			'name'              => 'optionspositions', // the methods that defines the relationship in your Model
			'subfields'         => [
				'primary' => [
					'label'            => 'Qualities',
					'name'             => 'options', // the method that defines the relationship in your Model
					'entity'           => 'options', // the method that defines the relationship in your Model
					'entity_secondary' => 'positions', // the method that defines the relationship in your Model
					'attribute'        => 'option', // foreign key attribute that is shown to user
					'model'            => "App\Models\QuestionOption", // foreign key model
					'pivot'            => false, // on create&update, do you need to add/delete pivot table entries?]
					'number_columns'   => 3, //can be 1,2,3,4,6
				],
				'secondary' => [
					'label'          => 'Positions',
					'name'           => 'positions', // the method that defines the relationship in your Model
					'entity'         => 'positions', // the method that defines the relationship in your Model
					'entity_primary' => 'options', // the method that defines the relationship in your Model
					'attribute'      => 'name', // foreign key attribute that is shown to user
					'model'          => "App\Models\PositionCandidate", // foreign key model
					'pivot'          => false, // on create&update, do you need to add/delete pivot table entries?]
					'number_columns' => 3, //can be 1,2,3,4,6
				],
			],
		]
		);
		// add asterisk for fields that are required in OptionPositionRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
		/*$positions = $this->crud->entry->position_id;
		foreach($positions as $position){
			$options = $this->crud->entry->option_id;		
			foreach($options['options'] as $option){
				$optionposition = OptionPosition::create([
					'position_id' => $position,
					'option_id' => $option
				]);			
			}
		}*/
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
		/*$positions = $this->crud->entry->position_id;
		foreach($positions as $position){
		  $opdetail = OptionPosition::where('position_id',$position)->delete();		
		  $options = $this->crud->entry->option_id;
		  foreach($options as $option){			
			  $optionposition = OptionPosition::create([
				  'position_id' => $position,
				  'option_id' => $option
			  ]);			
		  }
		}*/
        return $redirect_location;
    }
}
