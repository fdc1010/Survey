<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SurveyorAssignmentRequest as StoreRequest;
use App\Http\Requests\SurveyorAssignmentRequest as UpdateRequest;
use App\Models\AssignmentDetail;
/**
 * Class SurveyorAssignmentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SurveyorAssignmentCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SurveyorAssignment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/surveyorassignment');
        $this->crud->setEntityNameStrings('surveyor assignment', 'Surveyor Assignments');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		$this->crud->removeColumn(['user_id','task','description','areas','survey_detail_id']);
		$this->crud->removeField(['user_id','progress','areas','survey_detail_id']);
        $this->crud->addColumn([
            'label' => "User",
			'type' => 'select',
			'name' => 'user_id', // the relationship name in your Model
			'entity' => 'user', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\User" // on create&update, do you need to add/delete pivot table entries?
		])->makeFirstColumn();
		/*$this->crud->addColumn([
            'label' => "Barangay",
			'type' => 'select',
			'name' => 'barangay_id', // the relationship name in your Model
			'entity' => 'barangay', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\Barangay" // on create&update, do you need to add/delete pivot table entries?
		])->afterColumn('user_id');*/
		$this->crud->addColumn([
            'name' => 'areas',			
            'label' => 'Assigned Areas',
            'type' => 'model_function',
			'function_name' => 'getAreas'
		])->afterColumn('user_id');		
		$this->crud->addField([
			'label' => "User",
			'type' => 'select',
			'name' => 'user_id', // the relationship name in your Model
			'entity' => 'user', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\User" // on create&update, do you need to add/delete pivot table entries?
		])->beforeField('task');
		$this->crud->addField([
			'name' => 'areas',
			'label' => 'Areas',
			'type' => 'tableadv',
			'entity_singular' => 'area', // used on the "Add X" button
			'columns' => [
				'name' => 'areas',				
				'select_group' => 'Area',
				'number' => 'Quota',
				'entity' => 'sitio', // the method that defines the relationship in your Model
				'attribute' => 'name', // foreign key attribute that is shown to user				
				'model' => "App\Models\Barangay",
			],
			'max' => 1000, // maximum rows allowed in the table
			'min' => 1 // minimum rows allowed in the table
		]);
		$this->crud->addField([
			'label' => "Survey Detail ID",
			'type' => 'select',
			'name' => 'survey_detail_id', // the relationship name in your Model
			'entity' => 'surveydetail', // the relationship name in your Model
			'attribute' => 'subject', // attribute on Article that is shown to admin
			'model' => "App\SurveyDetail" // on create&update, do you need to add/delete pivot table entries?
		]);
		/*$this->crud->addField([
			'label'     => 'Assignment Area',
			'type'      => 'checklist',
			'name'      => 'sitio_id',
			'entity'    => 'sitio',
			'attribute' => 'name',
			'model'     => "App\Models\Sitio",
			'pivot'     => true,
		])->afterField('user_id');*/
		// add asterisk for fields that are required in SurveyorAssignmentRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
		$sid = $this->crud->entry->id; // <-- SHOULD WORK
		$options = $this->crud->entry->areas;
		foreach($options as $option){
			$optid = $option['select_group'];
			$quota = $option['number'];
			$areaoptions = AssignmentDetail::create([
				'assignment_id' => $sid,
				'sitio_id' => $optid,
				'quota' => $quota
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
		$sid = $this->crud->entry->id; // <-- SHOULD WORK
		$adetail = AssignmentDetail::where('assignment_id',$sid)->delete();
				
		$options = $this->crud->entry->areas;
		foreach($options as $option){
			$optid = $option['select_group'];
			$quota = $option['number'];
			$areaoptions = AssignmentDetail::create([
				'assignment_id' => $sid,
				'sitio_id' => $optid,
				'quota' => $quota
			]);			
		}
        return $redirect_location;
    }
	public function destroy($id)
	{
		$this->crud->hasAccessOrFail('delete');
		AssignmentDetail::where('assignment_id',$id)->delete();
		return $this->crud->delete($id);
	}
}
