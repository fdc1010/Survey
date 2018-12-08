<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Models\OptionQuality;
use App\Models\OptionCandidate;
use App\Models\OptionPosition;
use App\Models\OptionProblem;
use App\Models\QuestionOption;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\QuestionOptionRequest as StoreRequest;
use App\Http\Requests\QuestionOptionRequest as UpdateRequest;

/**
 * Class QuestionOptionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class QuestionOptionCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\QuestionOption');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/questionoption');
        $this->crud->setEntityNameStrings('question option', 'Question Options');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();	
		$this->crud->removeColumns(['option','priority','for_candidate_quality','for_candidate_votes','positions','candidate_id','for_issues','position_id']);
		$this->crud->removeFields(['option','priority','for_candidate_quality','for_candidate_votes','positions','candidate_id','for_issues','position_id']);
		$this->crud->addColumn([
            'name' => 'option',
			'label' => 'Option',
			'type' => 'text'
	    ]);
		$this->crud->addColumn([
            'name' => 'priority',
			'label' => 'Priority',
			'type' => 'number'
	    ]);
		$this->crud->addColumn([
            'name' => 'position',			
            'label' => 'Positions Tagged',
            'type' => 'model_function',
			'function_name' => 'getPositions',
			//'fake' => true
	    ]);
		$this->crud->addColumn([
            'name' => 'for_candidate_quality',			
            'label' => 'for Candidate Qualities',
            'type' => 'model_function',
			'function_name' => 'forCandidateQuality',
			//'fake' => true
	    ]);
		$this->crud->addColumn([
            'name' => 'for_candidate_votes',			
            'label' => 'for Candidate Votes',
            'type' => 'model_function',
			'function_name' => 'forCandidateVotes',
			//'fake' => true
	    ]);
		$this->crud->addColumn([
            'name' => 'candidate_id',
            'type' => 'select2',
            'label' => 'Tagged Option to Candidate',
			'entity' => 'candidate', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Candidate"
	    ]);
		$this->crud->addColumn([
            'name' => 'for_issues',			
            'label' => 'Tagged Option for Issues/Concerns/Problems',
            'type' => 'model_function',
			'function_name' => 'forIssues',
			//'fake' => true
	    ]);
		$this->crud->addField([
            'name' => 'option',
			'label' => 'Option',
			'type' => 'text'
	    ]);
		$this->crud->addField([
            'name' => 'priority',
			'label' => 'Priority',
			'type' => 'number'
	    ]);
        $this->crud->addField([
            'name' => 'for_candidate_quality',
			'label' => 'Is Option for Candidate Qualities',
			'type' => 'checkboxtoggle',
			'toggle_field' => 'positions'
	    ]);
		$this->crud->addField([
			'label' => "Positions",
			'type' => 'checklistchkall',
			'name' => 'positions', 
			'value' => 'positions',
			'entity' => 'positions',
			'attribute' => 'name', 
			'model' => "App\Models\PositionCandidate",
		]);
		$this->crud->addField([
            'name' => 'for_candidate_votes',
			'label' => 'Is Option for Candidate Votes (if Option is Name of Candidate)',
			'type' => 'checkboxtoggle',
			'toggle_field' => 'candidate_id'
	    ]);
		$this->crud->addField([
            'name' => 'candidate_id',
            'type' => 'select2',
            'label' => 'Tagged Option to Candidate',
			'entity' => 'candidate', // the relationship name in your Model
			'attribute' => 'full_name', // attribute on Article that is shown to admin
			'model' => "App\Models\Candidate"
	    ]);
		$this->crud->addField([
            'name' => 'for_issues',
			'label' => 'Is Option Tagged for Issues/Concerns/Problems',
			'type' => 'checkbox'
	    ]);
		$this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
		//$positionsarr = array();
		$optid = $this->crud->entry->id;
		$candid = $this->crud->entry->candidate_id;
		$positions = $this->crud->entry->positions;
		
		if(intval($this->crud->entry->for_candidate_quality)){
			foreach($positions as $posid){
				$optionposition = OptionPosition::updateOrCreate([
					'position_id' => $posid,
					'option_id' => $optid
				]);
				//array_push($positionsarr,array('positions'=>$posid));
			}	
			/*$questionoption = QuestionOption::find($optid);
			$questionoption->positions = $positionsarr;
			$questionoption->save();*/
		}
		if(intval($this->crud->entry->for_candidate_votes)){
			foreach($positions as $posid){
				$optionposition = OptionPosition::updateOrCreate([
					'position_id' => $posid,
					'option_id' => $optid
				]);
				//array_push($positionsarr,array('positions'=>$posid));
			}
			$optioncandidate = OptionCandidate::updateOrCreate([
				'option_id' => $optid,
				'candidate_id' => $candid
			]);	
			
		}
		if(intval($this->crud->entry->for_issues)){
			$optionproblem = OptionProblem::updateOrCreate([
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
		//$positionsarr = array();
		$optid = $this->crud->entry->id;
		$candid = $this->crud->entry->candidate_id;
		$positions = $this->crud->entry->positions;
		//dd($this->crud->entry);
		if(intval($this->crud->entry->for_candidate_quality)){
			//OptionQuality::where('option_id',$optid)->delete();
			foreach($positions as $posid){
				$optionposition = OptionPosition::updateOrCreate([
					'position_id' => $posid,
					'option_id' => $optid
				]);
				//array_push($positionsarr,array('positions'=>$posid));
			}
			
			/*$questionoption = QuestionOption::find($optid);
			$questionoption->positions = $positionsarr;
			$questionoption->save();*/
			
			$optionquality = OptionQuality::updateOrCreate([
				'option_id' => $optid,
				'positions'=>$positionsarr
			]);	
		}
		if(intval($this->crud->entry->for_candidate_votes)){
			//OptionPosition::where('option_id',$optid)->delete();
			//OptionCandidate::where('option_id',$optid)->delete();
			
			$optioncandidate = OptionCandidate::updateOrCreate([
				'option_id' => $optid,
				'candidate_id' => $candid
			]);		
		}
		if(intval($this->crud->entry->for_issues)){
			//OptionProblem::where('option_id',$optid)->delete();
			$optionproblem = OptionProblem::updateOrCreate([
				'option_id' => $optid
			]);
		}
        return $redirect_location;
    }
	public function destroy($id)
	{
		$this->crud->hasAccessOrFail('delete');
		
		OptionQuality::where('option_id',$id)->delete();
		OptionCandidate::where('option_id',$id)->delete();
		OptionPosition::where('option_id',$id)->delete();
		OptionProblem::where('option_id',$id)->delete();
		
		return $this->crud->delete($id);
	}
}
