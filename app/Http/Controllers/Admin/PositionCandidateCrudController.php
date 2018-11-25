<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PositionCandidateRequest as StoreRequest;
use App\Http\Requests\PositionCandidateRequest as UpdateRequest;
use App\Models\OptionPosition;
use App\Models\PositionCandidate;
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
		$this->crud->removeColumn(['position_id','extras']);
		$this->crud->removeField(['position_id','extras']);
		$this->crud->addColumn([
            'name' => 'extras',			
            'label' => 'Options',
            'type' => 'model_function',
			'function_name' => 'getOptionSelections',
			'fake' => true
	    ]);
		/*$this->crud->addField([
            'name' => 'extras',
            'type' => 'checklist',
            'label' => 'Qualities Tagged Options',
			'entity' => 'optionspositions', // the relationship name in your Model
			'attribute' => 'option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionOption",
			'fake' => true, 
    		'store_in' => 'extras'
	    ]);*/
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
		dd($this->crud->entry);
		$position = $this->crud->entry->id; // <-- SHOULD WORK
		$options = $this->crud->entry->options;		
		foreach($options['options'] as $option){
			$optionposition = OptionPosition::create([
				'position_id' => $position,
				'option_id' => $option
			]);			
		}
		
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
		$position = $this->crud->entry->id; // <-- SHOULD WORK
		$opdetail = OptionPosition::where('position_id',$position)->delete();		
		$options = $this->crud->entry->extras;
		dd($options);
		foreach($options as $option){
			
			$optionposition = OptionPosition::create([
				'position_id' => $position,
				'option_id' => $option
			]);			
		}
		
        return $redirect_location;
    }
}
