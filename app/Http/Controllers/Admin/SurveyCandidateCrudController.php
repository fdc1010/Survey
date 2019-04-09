<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SurveyCandidateRequest as StoreRequest;
use App\Http\Requests\SurveyCandidateRequest as UpdateRequest;
use App\Models\SurveyorAssignment;
use App\Models\AssignmentDetail;
use App\Models\SurveyDetail;
/**
 * Class SurveyCandidateCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SurveyCandidateCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PositionCandidate');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/surveycandidate');
        $this->crud->setEntityNameStrings('survey candidate', 'Survey Candidates');
        //$this->crud->setListView('listsurveyassignment');
        $this->crud->denyAccess(['update', 'create', 'delete']);
        $this->crud->enableExportButtons();
        $this->crud->removeAllButtonsFromStack('line');
        $this->crud->enableDetailsRow();
		    $this->crud->allowAccess('details_row');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
        $this->crud->removeColumn('extras');
    		$this->crud->removeField('extras');
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
    public function showDetailsRow($id){
    //   $brgysur = $this->crud->getModel()::find($id);
    //   $surveyors = SurveyorAssignment::whereHas('assignments',function($a)use($brgysur){
    //                                         $a->where('barangay_id',$brgysur->barangay_id);
    //                                     })->with(['user','assignments'=>function($q)use($brgysur){
    //                                         $q->where('barangay_id',$brgysur->barangay_id);
    //                                     }])
    //                                     ->get();
  	// 	$result = "<h4>Assigned Surveyor(s):</h4><div class='col-lg-8'>";
    //
  	// 	foreach($surveyors as $surveyor){
    //         $totalquotaperbrgy = 0;
    //         $totalcountperbrgy = 0;
    //         $totalprogressperbrgy = 0;
    //         foreach($surveyor->assignments as $assignment){
    //       			$result .= "<div class='col-lg-2'>#".$surveyor->user->id." ".$surveyor->user->name."</div>".
    //           						 "<div class='col-lg-2'>quota: ".$assignment->quota."</div>".
    //             					 "<div class='col-lg-2'>count: ".$assignment->getSurveyCount()."</div>".
    //             					 "<div class='col-lg-2'>progress: </div>".
    //             					 "<div class='col-lg-4'>".$assignment->getProgressBar()."</div>";
    //         }
    //   }
  	// 	$result .= "</div>";
  	// 	return $result;
  	// }
}
