<?php

namespace App\Http\Controllers\Admin;
use Response;
use Illuminate\Http\Request;
use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\QuestionRequest as StoreRequest;
use App\Http\Requests\QuestionRequest as UpdateRequest;
use App\Models\QuestionDetail;
/**
 * Class QuestionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class QuestionCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Question');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/question');
        $this->crud->setEntityNameStrings('question', 'questions');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

		$this->crud->removeColumns(['number_answers','with_other_ans','with_partyselect','for_position','type_id','options']);
		$this->crud->removeFields(['number_answers','with_other_ans','with_partyselect','for_position','type_id','options']);
    $this->crud->addColumn([
            'name' => 'id',
            'label' => 'ID'
	    ])->makeFirstColumn();
		$this->crud->addColumn([
            'name' => 'type_id',
            'type' => 'select',
            'label' => 'Type',
			'entity' => 'type', // the relationship name in your Model
			'attribute' => 'type_name', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionType"
	    ]);
		$this->crud->addColumn([
            'name' => 'number_answers',
            'type' => 'number',
            'label' => 'Number of Req. Answers',
	    ]);
		$this->crud->addField([
            'name' => 'number_answers',
            'type' => 'number',
            'label' => 'Number of Req. Answers'
	    ]);

		$this->crud->addField([
			'label' => "Type",
			'type' => 'select',
			'name' => 'type_id', // the relationship name in your Model
			'entity' => 'type', // the relationship name in your Model
			'attribute' => 'type_name', // attribute on Article that is shown to admin
			'model' => "App\Models\QuestionType" // on create&update, do you need to add/delete pivot table entries?
		]);
		$this->crud->addField([
			'name' => 'options',
			'label' => 'Choices',
			'type' => 'tableadv',
			'entity_singular' => 'option', // used on the "Add X" button
			'columns' => [
				'name' => 'options',
				'select' => 'Option',
				'checkbox' => 'With Other Answer',
				'entity' => 'choices', // the method that defines the relationship in your Model
				'attribute' => 'option', // foreign key attribute that is shown to user
				'model' => "App\Models\QuestionOption"
			],
			'max' => 100, // maximum rows allowed in the table
			'min' => 1 // minimum rows allowed in the table
		]);
		$this->crud->addField([
            'name' => 'with_other_ans',
			'label' => 'With other answer',
			'type' => 'checkbox'
	    ]);
		$this->crud->addField([
            'name' => 'with_partyselect',
			'label' => 'Enable for Vote Straight',
			'type' => 'checkbox'
	    ]);
		$this->crud->addField([
			'label' => "For Position",
			'type' => 'select',
			'name' => 'for_position', // the relationship name in your Model
			'entity' => 'forposition', // the relationship name in your Model
			'attribute' => 'name', // attribute on Article that is shown to admin
			'model' => "App\Models\PositionCandidate" // on create&update, do you need to add/delete pivot table entries?
		]);
		$this->crud->orderBy('priority');
        // add asterisk for fields that are required in QuestionRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
		$qid = $this->crud->entry->id; // <-- SHOULD WORK
		$options = $this->crud->entry->options;
		foreach($options as $option){
			$optid = $option['select'];
			$chkhasother = !empty($option['checkbox'])?$option['checkbox']:false;
			$questionoptions = QuestionDetail::create([
				'question_id' => $qid,
				'option_id' => $optid,
				'with_option_other_ans' => $chkhasother
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
		$qid = $this->crud->entry->id; // <-- SHOULD WORK
		$qdetail = QuestionDetail::where('question_id',$qid)->delete();

		$options = $this->crud->entry->options;
		foreach($options as $option){
			$optid = $option['select'];
			$chkhasother = !empty($option['checkbox'])?$option['checkbox']:false;
			$questionoptions = QuestionDetail::create([
				'question_id' => $qid,
				'option_id' => $optid,
				'with_option_other_ans' => $chkhasother
			]);
		}
        return $redirect_location;
    }
	public function destroy($id)
	{
		$this->crud->hasAccessOrFail('delete');
		QuestionDetail::where('question_id',$id)->delete();
		return $this->crud->delete($id);
	}

}
