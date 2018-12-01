<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Models\OptionPosition;
use App\Models\OptionQuality;
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
        //$this->crud->setFromDb();
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
		$this->crud->addField([
			'label' => "Qualities",
			'type' => 'select2',
			'name' => 'option_id', // the relationship name in your Model
			'entity' => 'option', // the relationship name in your Model
			'attribute' => 'option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionOption", // on create&update, do you need to add/delete pivot table entries?
			//'pivot' => true
		]);
		$this->crud->addField([
			'label' => "Positions",
			'type' => 'checklist',
			'name' => 'positions', 
			'entity' => 'options',
			'attribute' => 'name', 
			'model' => "App\Models\PositionCandidate"
		]);
		
        // add asterisk for fields that are required in OptionQualityRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = $this->storeCrud($request); //parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
		dd($this->crud->entry);
		$oqid = $this->crud->entry->id; // <-- SHOULD WORK
		$options = $this->crud->entry->positions;
		foreach($options as $posid){
			$optionquality = OptionPosition::create([
				'position_id' => $posid,
				'option_id' => $optid
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
		
		$oqid = $this->crud->entry->id; // <-- SHOULD WORK
		OptionPosition::where('option_id',$oqid)->delete();
		$options = $this->crud->entry->positions;
		foreach($options as $posid){
			$optionquality = OptionPosition::create([
				'position_id' => $posid,
				'option_id' => $optid
			]);			
		}
        return $redirect_location;
    }
	public function destroy($id)
	{
		$this->crud->hasAccessOrFail('delete');
		$optionquality = OptionQuality::find($id);
		OptionPosition::where('option_id',$optionquality->option_id)->delete();
		return $this->crud->delete($id);
	}
}
