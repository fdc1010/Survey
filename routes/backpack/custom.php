<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
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
}); // this should be the absolute last line of this file