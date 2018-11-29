<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TallyOtherVoteRequest as StoreRequest;
use App\Http\Requests\TallyOtherVoteRequest as UpdateRequest;

/**
 * Class TallyOtherVoteCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TallyOtherVoteCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\TallyOtherVote');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/tallyothervote');
        $this->crud->setEntityNameStrings('tally other vote', 'Tally Other Votes');
		$this->crud->denyAccess(['update', 'create', 'delete']);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		$this->crud->removeColumns(['voter_id','option_id','survey_detail_id']);
		$this->crud->removeFields(['voter_id','option_id','survey_detail_id']);
		$this->crud->orderBy('survey_detail_id');
		
		$this->crud->addColumn([
            'name' => 'voter_id',
            'type' => 'select',
            'label' => 'Voter',
			'entity' => 'voter', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Voter"
	    ]);
		$this->crud->addColumn([
            'name' => 'option_id',
            'type' => 'select',
            'label' => 'Option',
			'entity' => 'option', // the relationship name in your Model
			'attribute' => 'option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionOption"
	    ]);
		$this->crud->addColumn([
            'name' => 'survey_detail_id',
            'type' => 'select',
            'label' => 'Survey',
			'entity' => 'surveydetail', // the relationship name in your Model
			'attribute' => 'subject', // attribute on Article that is shown to admin
			'model' => "App\Models\SurveyDetail"
	    ]);
		$this->crud->addField([
            'name' => 'voter_id',
            'type' => 'select2',
            'label' => 'Voter',
			'entity' => 'voter', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Voter"
	    ]);
		$this->crud->addField([
            'name' => 'option_id',
            'type' => 'select2',
            'label' => 'Option',
			'entity' => 'option', // the relationship name in your Model
			'attribute' => 'option', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionOption"
	    ]);
		$this->crud->addField([
            'name' => 'survey_detail_id',
            'type' => 'select2',
            'label' => 'Survey',
			'entity' => 'surveydetail', // the relationship name in your Model
			'attribute' => 'subject', // attribute on Article that is shown to admin
			'model' => "App\Models\SurveyDetail"
	    ]);
        // add asterisk for fields that are required in TallyOtherVoteRequest
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
