<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')]
    //'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
	Route::auth();
	Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
    CRUD::resource('tag', 'Admin\TagCrudController');
    CRUD::resource('question', 'Admin\QuestionCrudController');
    CRUD::resource('questiontype', 'Admin\QuestionTypeCrudController');
    CRUD::resource('precinct', 'Admin\PrecinctCrudController');
    CRUD::resource('party', 'Admin\PartyCrudController');
    CRUD::resource('positioncandidate', 'Admin\PositionCandidateCrudController');
    CRUD::resource('questionoption', 'Admin\QuestionOptionCrudController');
    CRUD::resource('voter', 'Admin\VoterCrudController');
    CRUD::resource('questionsuboption', 'Admin\QuestionSubOptionCrudController');
    CRUD::resource('voterstatus', 'Admin\VoterStatusCrudController');
    CRUD::resource('candidate', 'Admin\CandidateCrudController');
    CRUD::resource('survey', 'Admin\SurveyCrudController');
    CRUD::resource('questiondetail', 'Admin\QuestionDetailCrudController');
    CRUD::resource('questionoptiondetail', 'Admin\QuestionOptionDetailCrudController');
    CRUD::resource('position', 'Admin\PositionCrudController');
	
    CRUD::resource('surveyanswer', 'Admin\SurveyAnswerCrudController');
    CRUD::resource('surveydetail', 'Admin\SurveyDetailCrudController');
    CRUD::resource('tallyvote', 'Admin\TallyVoteCrudController');
    CRUD::resource('barangay', 'Admin\BarangayCrudController');
    CRUD::resource('district', 'Admin\DistrictCrudController');
    CRUD::resource('province', 'Admin\ProvinceCrudController');
    CRUD::resource('municipality', 'Admin\MunicipalityCrudController');
    CRUD::resource('surveyorassignment', 'Admin\SurveyorAssignmentCrudController');
    CRUD::resource('sitio', 'Admin\SitioCrudController');
    CRUD::resource('location', 'Admin\LocationCrudController');
    CRUD::resource('locationcoordinate', 'Admin\LocationCoordinateCrudController');
}); // this should be the absolute last line of this file