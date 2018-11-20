<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.
info(config('backpack.base.middleware_key'));
Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', 'auth'],//config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    CRUD::resource('tag', 'TagCrudController');
    CRUD::resource('question', 'QuestionCrudController');
    CRUD::resource('questiontype', 'QuestionTypeCrudController');
    CRUD::resource('precinct', 'PrecinctCrudController');
    CRUD::resource('party', 'PartyCrudController');
    CRUD::resource('positioncandidate', 'PositionCandidateCrudController');
    CRUD::resource('questionoption', 'QuestionOptionCrudController');
    CRUD::resource('voter', 'VoterCrudController');
    CRUD::resource('questionsuboption', 'QuestionSubOptionCrudController');
    CRUD::resource('voterstatus', 'VoterStatusCrudController');
    CRUD::resource('candidate', 'CandidateCrudController');
    CRUD::resource('survey', 'SurveyCrudController');
    CRUD::resource('questiondetail', 'QuestionDetailCrudController');
    CRUD::resource('questionoptiondetail', 'QuestionOptionDetailCrudController');
    CRUD::resource('position', 'PositionCrudController');
	
    CRUD::resource('surveyanswer', 'SurveyAnswerCrudController');
    CRUD::resource('surveydetail', 'SurveyDetailCrudController');
    CRUD::resource('tallyvote', 'TallyVoteCrudController');
    CRUD::resource('barangay', 'BarangayCrudController');
    CRUD::resource('district', 'DistrictCrudController');
    CRUD::resource('province', 'ProvinceCrudController');
    CRUD::resource('municipality', 'MunicipalityCrudController');
    CRUD::resource('surveyorassignment', 'SurveyorAssignmentCrudController');
    CRUD::resource('sitio', 'SitioCrudController');
    CRUD::resource('location', 'LocationCrudController');
    CRUD::resource('locationcoordinate', 'LocationCoordinateCrudController');
}); // this should be the absolute last line of this file