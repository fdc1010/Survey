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
		$this->crud->addField([
            'name' => 'position_id',
            'type' => 'select',
            'label' => 'Positions',
			'entity' => 'positions', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\PositionCandidate",
			/*'fake' => true,
			'store_in' => 'extras'*/
	    ]);
		$this->crud->addField([
            'name' => 'option_id',
            'type' => 'select2',
            'label' => 'Tagged Options for Qualities',
			'entity' => 'options', // the relationship name in your Model
			'attribute' => 'option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionOption"
	    ]);
		/*$this->crud->addField([
            'name' => 'option_id',
            'type' => 'select2',
            'label' => 'Tagged Options for Qualities',
			'entity' => 'options', // the relationship name in your Model
			'attribute' => 'option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionOption",
			'fake' => true,
			'store_in' => 'extras_2'
	    ]);*/
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
