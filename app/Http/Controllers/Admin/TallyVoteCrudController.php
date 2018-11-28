<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TallyVoteRequest as StoreRequest;
use App\Http\Requests\TallyVoteRequest as UpdateRequest;

/**
 * Class TallyVoteCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TallyVoteCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\TallyVote');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/tallyvote');
        $this->crud->setEntityNameStrings('tally vote', 'Tally Votes');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
		$this->crud->removeColumns(['voter_id','candidate_id','survey_detail_id']);
		$this->crud->removeFields(['voter_id','candidate_id','survey_detail_id']);
		$this->crud->orderBy('candidate_id');
		
		$this->crud->addColumn([
            'name' => 'voter_id',
            'type' => 'select',
            'label' => 'Voter',
			'entity' => 'voter', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Voter"
	    ]);
		$this->crud->addColumn([
            'name' => 'candidate_id',
            'type' => 'select',
            'label' => 'Candidate',
			'entity' => 'candidate', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Candidate"
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
            'name' => 'candidate_id',
            'type' => 'select2',
            'label' => 'Candidate',
			'entity' => 'candidate', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Candidate"
	    ]);
		$this->crud->addField([
            'name' => 'survey_detail_id',
            'type' => 'select2',
            'label' => 'Survey',
			'entity' => 'surveydetail', // the relationship name in your Model
			'attribute' => 'subject', // attribute on Article that is shown to admin
			'model' => "App\Models\SurveyDetail"
	    ]);
        // add asterisk for fields that are required in TallyVoteRequest
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
