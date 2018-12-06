<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Models\OptionPosition;
use App\Models\QualityPosition;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\QualityPositionRequest as StoreRequest;
use App\Http\Requests\QualityPositionRequest as UpdateRequest;

/**
 * Class QualityPositionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class QualityPositionCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\QualityPosition');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/qualityposition');
        $this->crud->setEntityNameStrings('option tag qualities per position', 'Option Tag Qualities Per Position');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		$this->crud->removeColumns(['option_id','position_id','options','positions','description']);
		$this->crud->removeFields(['option_id','position_id','options','positions','description']);
		$this->crud->addColumn([
            'name' => 'option_id',
            'type' => 'select',
            'label' => 'Qualities',
			'entity' => 'options', // the relationship name in your Model
			'attribute' => 'option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionOption"
	    ]);
		$this->crud->addColumn([
            'name' => 'position_id',
            'type' => 'select',
            'label' => 'Positions',
			'entity' => 'positions', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\PositionCandidate"
	    ]);
		$this->crud->addField([
			'label' => "Options",
			'type' => 'checklistchkall2',
			'name' => 'options', 
			'entity' => 'options',
			'attribute' => 'option', 
			'model' => "App\Models\QuestionOption",
			'compare_value' => 1,
			'compare_field' => 'for_candidate',
			'entity2' => 'positions', // for doesntHave
			'entity3' => 'positions',
			'entity4' => 'options'
		]);
		
		$this->crud->addField([
			'label' => "Positions",
			'type' => 'checklistchkall2',
			'name' => 'positions', 
			'entity' => 'positions',
			'attribute' => 'name', 
			'model' => "App\Models\PositionCandidate",
			'compare_value' => 1,
			'compare_field' => 'for_candidate',
			'entity2' => 'positions', // for doesntHave
			'entity3' => 'positions',
			'entity4' => 'options'
		]);
        // add asterisk for fields that are required in QualityPositionRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
		
		$positions = $this->crud->entry->positions;
		$options = $this->crud->entry->options;
		foreach($options as $optid){
			foreach($positions as $posid){
				$optionquality = OptionPosition::create([
					'position_id' => $posid,
					'option_id' => $optid
				]);
			}
		}
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
		$positions = $this->crud->entry->positions;
		$options = $this->crud->entry->options;
		foreach($options as $optid){
			foreach($positions as $posid){
				OptionPosition::where('option_id',$optid)->where('position_id',$posid)->delete();
			}
		}
		
		foreach($options as $optid){
			foreach($positions as $posid){
				$optionquality = OptionPosition::create([
					'position_id' => $posid,
					'option_id' => $optid
				]);
			}
		}
        return $redirect_location;
    }
	public function destroy($id)
	{
		$this->crud->hasAccessOrFail('delete');
		$qualitiespositions = QualityPosition::find($id);
		foreach($qualitiespositions->options as $optid){
			foreach($qualitiespositions->positions as $posid){
				OptionPosition::where('option_id',$optid)->where('position_id',$posid)->delete();
			}
		}
		return $this->crud->delete($id);
	}
}
