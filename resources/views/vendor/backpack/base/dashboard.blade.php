@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        {{ trans('backpack::base.dashboard') }}<small> Statistical Data</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">{{ trans('backpack::base.dashboard') }}</li>
      </ol>
    </section>
@endsection


@section('content')
	@php
        $surveypos = 1;
        $brgyarr = array(rand(0,80),rand(0,80),rand(0,80),rand(0,80));
        $brgysurveys = App\Models\Barangay::whereIn('id',$brgyarr)->get();
        $problems = App\Models\OptionProblem::with('option')->get();
        $voterstatuses = App\Models\VoterStatus::all();
        $selvoterstatuses = App\Models\VoterStatus::all();
        $genders = App\Models\Gender::all();
        $candidates = App\Models\Candidate::with('voter')->where('position_id',$surveypos)->get();
        $barangays = App\Models\Barangay::all();
        $agebrackets = App\Models\AgeBracket::all();
        $civilstatuses = App\Models\CivilStatus::all();
        $empstatuses = App\Models\EmploymentStatus::all();
        $positions = App\Models\PositionCandidate::all(); 
        $qualities = App\Models\OptionPosition::with('options','positions')
                                                            ->where('position_id',$surveypos)->get();
    @endphp
    <div class="row">
    	<div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Criteria</div>                	                        	
                    </div>
                </div>

                <div class="box-body">
                	<table id="tblviewdetails" class="table table-striped table-hover display responsive nowrap" width="100%">
                    	<thead>
                            <tr>
                                <th>Barangays:</th>
                                <td><span id="countbrgy">{{ count($barangays) }}</span></td>
                                <th><a href="#" id="btn_brgydetails"><span class="fa fa-plus" id="spanbrgydetails"> </span></a></th>
                                <th>Run for:</th>
                                <td>
                                	<select>
                                    @foreach($positions as $position)	
                                    	<option value="{{ $position->id }}">{{ $position->name }}</option>
                                    @endforeach
                                	</select>
                                </td>
                                <th><a href="#" id="btn_posdetails"><span class="fa fa-plus" id="spanposdetails"> </span></a></th>
                                <th>Candidate:</th>
                                <td>
                                	<select>
                                    @foreach($candidates as $candidate)	
                                    	<option value="{{ $candidate->id }}">{{ $candidate->voter->full_name }}</option>
                                    @endforeach
                                	</select>
                                </td>
                                <th><a href="#" id="btn_candetails"><span class="fa fa-plus" id="spancandetails"> </span></a></th>
                                <th>Demographics:</th>
                                <td>
                                	<select>
                                    	<option>Age</option>
                                    @foreach($agebrackets as $agebracket)	
                                    	<option value="{{ $agebracket->id }}">{{ $agebracket->title }}</option>
                                    @endforeach
                                	</select>
                                </td>
                                <th><a href="#" id="btn_agedetails"><span class="fa fa-plus" id="spanagedetails"> </span></a></th>
                                <td>
                                	<select>
                                    	<option>Gender</option>
                                    @foreach($genders as $gender)	
                                    	<option value="{{ $gender->id }}">{{ $gender->name }}</option>
                                    @endforeach
                                	</select>
                                </td>
                                <th><a href="#" id="btn_gendetails"><span class="fa fa-plus" id="spangendetails"> </span></a></th>
                                <td>
                                	<select>
                                    	<option>Civil</option>
                                    @foreach($civilstatuses as $civilstatus)	
                                    	<option value="{{ $civilstatus->id }}">{{ $civilstatus->name }}</option>
                                    @endforeach
                                	</select>
                                </td>
                                <th><a href="#" id="btn_civdetails"><span class="fa fa-plus" id="spancivdetails"> </span></a></th>
                                <td>
                                	<select>
                                    	<option>Employment</option>
                                    @foreach($empstatuses as $empstatus)	
                                    	<option value="{{ $empstatus->id }}">{{ $empstatus->name }}</option>
                                    @endforeach
                                	</select>
                                </td>
                                <th><a href="#" id="btn_empdetails"><span class="fa fa-plus" id="spanempdetails"> </span></a></th>
                                <th>
                                    <a class="btn btn-primary" onclick="document.getElementById('my_form').submit();">
                                        <span class="fa fa-search"></span> View
                                    </a>
                              </th>
                            </tr>                                    
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <form method="post" id="my_form" action="{{ backpack_url('stats') }}">
        <div class="col-md-12" id="brgydetails">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Barangays</div>                	                        	
                    </div>
                </div>
                <div class="box-body">
                	                    	
                        @csrf
                        <div class="col-ls-12">
                        	<div class="col-lg-5">    
                                <div class="form-group">  
                                    <div class="col-lg-12">                             	
										<label class="col-lg-12 control-label">Select</label>                                     
                                    </div>
                                </div>                                            
                                <select name="from[]" id="brgycriteria" class="form-control" size="8" multiple="multiple">               
                                    @foreach($barangays as $barangay)	
                                        <option value="{{ $barangay->id }}">{{ $barangay->name }}</option>
                                    @endforeach
                                </select>                                        
                          </div>
                          
                          <div class="col-lg-2">
                              <div class="form-group" style="padding-bottom:10px;"> 
                                  <div class="col-lg-12">&nbsp;</div>
                              </div>
                              <button type="button" id="brgycriteria_undo" class="btn btn-primary btn-block">undo</button>
                              <button type="button" id="brgycriteria_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
                              <button type="button" id="brgycriteria_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
                              <button type="button" id="brgycriteria_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
                              <button type="button" id="brgycriteria_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
                              <button type="button" id="brgycriteria_redo" class="btn btn-success btn-block">redo</button>
                          </div>
                          
                          <div class="col-lg-5">
                                  <div class="form-group">
                                      <div class="col-lg-12">
                                          <label class="col-lg-12 control-label">Selected</label>
                                      </div>
                                  </div>
                              <select name="to[]" id="brgycriteria_to" class="form-control" size="8" multiple="multiple"></select>
                          </div> 
                      </div>     
                </div>
            </div>
        </div>
        <div class="col-md-12" id="posdetails">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Position</div>                	                        	
                    </div>
                </div>

                <div class="box-body">
                	<div class="col-md-12">
                        <div class="form-group">
                        @foreach($positions as $position)
                                <div class="col-md-4">
                                    <label class="control-label">
                                        <input type="checkbox" id="{{ $position->id }}" name="position[]" value=" {{ $position->id }}" />
                                        {{ $position->name }}
                                    </label>
                                </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12" id="candetails">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Candidates</div>                	                        	
                    </div>
                </div>

                <div class="box-body">
                	<div class="col-md-12">
                        <div class="form-group">
                        @foreach($positions as $position)                        	
                        	<div class="col-md-12"><h5>{{ $position->name }}</h5>
                            @php
                            	$poscandidates = App\Models\Candidate::where('position_id',$position->id)->get();
                            @endphp
                            @foreach($poscandidates as $candidate)
                                    <div class="col-md-3">
                                        <label class="control-label">
                                            <input type="checkbox" id="{{ $candidate->id }}" name="candidate[]" value=" {{ $candidate->id }}" />
                                            {{ $candidate->voter->full_name }}
                                        </label>
                                    </div>
                            @endforeach
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12" id="agedetails">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Age Brackets</div>                	                        	
                    </div>
                </div>

                <div class="box-body">
                	<div class="col-md-12">
                        <div class="form-group">
                        @foreach($agebrackets as $agebracket)
                                <div class="col-md-4">
                                    <label class="control-label">
                                        <input type="checkbox" id="{{ $agebracket->id }}" name="agebracket[]" value=" {{ $agebracket->id }}" />
                                        {{ $agebracket->title }}
                                    </label>
                                </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12" id="gendetails">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Gender</div>                	                        	
                    </div>
                </div>

                <div class="box-body">
                	<div class="col-md-12">
                        <div class="form-group">
                        @foreach($genders as $gender)
                                <div class="col-md-4">
                                    <label class="control-label">
                                        <input type="checkbox" id="{{ $gender->id }}" name="gender[]" value=" {{ $gender->id }}" />
                                        {{ $gender->name }}
                                    </label>
                                </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12" id="civdetails">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Civil Status</div>                	                        	
                    </div>
                </div>

                <div class="box-body">
                	<div class="col-md-12">
                        <div class="form-group">
                        @foreach($civilstatuses as $civilstatus)
                                <div class="col-md-4">
                                    <label class="control-label">
                                        <input type="checkbox" id="{{ $civilstatus->id }}" name="civilstatus[]" value=" {{ $civilstatus->id }}" />
                                        {{ $civilstatus->name }}
                                    </label>
                                </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12" id="empdetails">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Employment Status</div>                	                        	
                    </div>
                </div>

                <div class="box-body">
                	<div class="col-md-12">
                        <div class="form-group">
                        @foreach($empstatuses as $empstatus)
                                <div class="col-md-4">
                                    <label class="control-label">
                                        <input type="checkbox" id="{{ $empstatus->id }}" name="empstatus[]" value=" {{ $empstatus->id }}" />
                                        {{ $empstatus->name }}
                                    </label>
                                </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    	<div class="col-md-3">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Tabular Stats (Summary)</div>
                    </div>
                </div>                
                <div class="box-body">                	
                      <div id="tblvotes" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
            					<thead>
                                	<tr>
                                    	<th>{{ $positions[0]->name }}</th>
                                        <th></th>
                                    </tr>
                                	<tr>
                                    	<th>Cadidate</th>
                                        <th>Tally</th>
                                    </tr>                                    
                                </thead>
                                <tbody>
                                @php
                                	$tally = array();                                    
                                @endphp
                                @foreach($candidates as $candidate)
                                	@php
                                    	$tally[$candidate->id]=rand(1,100);
                                    @endphp
                                	<tr>
                                    	<td>{{ $candidate->voter->full_name }}</td>
                                        <td>{{ $tally[$candidate->id] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                      </div>
                </div>
            </div>
        </div>
    	<div class="col-md-3">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Main Chart (Summary)</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chart"></div></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Tabular Tally by Gender</div>
                    </div>
                </div>

                <div class="box-body">                	
                      <div id="tblgender" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
            					<thead>
                                	<tr>
                                    	<th>{{ $positions[0]->name }}</th>
                                        @foreach($genders as $gender)
                                        <th></th>
                                        @endforeach
                                    </tr>
                                	<tr>
                                    	<th>Candidates</th>
                                        @foreach($genders as $gender)
                                        <th>{{ $gender->name }}</th>
                                        @endforeach
                                    </tr>                                    
                                </thead>
                                <tbody>
                               @php
                                	$tallyg = array();                                    
                                @endphp
                                @foreach($candidates as $candidate)                                	
                                	<tr>
                                    	<td>{{ $candidate->voter->full_name }}</td>
                                        @foreach($genders as $gender)
                                        @php
                                            $tallyg[$candidate->id][$gender->id]=rand(1,100);
                                        @endphp
                                        <td>{{ $tallyg[$candidate->id][$gender->id] }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                      </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Chart Tally by Gender</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartgender"></div></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Age Bracket</div>
                    </div>
                </div>

                <div class="box-body">                	
                      <div id="tblgender" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
            					<thead>
                                	<tr>
                                    	<th>{{ $positions[0]->name }}</th>
                                        @foreach($agebrackets as $agebracket)
                                        <th></th>
                                        @endforeach
                                    </tr>
                                	<tr>
                                    	<th>Candidates</th>
                                        @foreach($agebrackets as $agebracket)
                                        <th>{{ $agebracket->title }}</th>
                                        @endforeach
                                    </tr>                                    
                                </thead>
                                <tbody>
                               @php
                                	$tallyab = array();                                    
                                @endphp
                                @foreach($candidates as $candidate)                                	
                                	<tr>
                                    	<td>{{ $candidate->voter->full_name }}</td>
                                        @foreach($agebrackets as $agebracket)
                                        @php
                                            $tallyab[$candidate->id][$agebracket->id]=rand(1,100);
                                        @endphp
                                        <td>{{ $tallyab[$candidate->id][$agebracket->id] }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                      </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Age Bracket</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartagebracket"></div></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Civil Status</div>
                    </div>
                </div>

                <div class="box-body">                	
                      <div id="tblcivilstatus" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
            					<thead>
                                	<tr>
                                    	<th>{{ $positions[0]->name }}</th>
                                        @foreach($civilstatuses as $civilstatus)
                                        <th></th>
                                        @endforeach
                                	</tr>
                                    <tr>
                                    	<th>Candidates</th>
                                        @foreach($civilstatuses as $civilstatus)
                                        <th>{{ $civilstatus->name }}</th>
                                        @endforeach
                                    </tr>                                    
                                </thead>
                                <tbody>
                               @php
                                	$tallycv = array();                                    
                                @endphp
                                @foreach($candidates as $candidate)                                	
                                	<tr>
                                    	<td>{{ $candidate->voter->full_name }}</td>
                                        @foreach($civilstatuses as $civilstatus)
                                        @php
                                            $tallycv[$candidate->id][$civilstatus->id]=rand(1,100);
                                        @endphp
                                        <td>{{ $tallycv[$candidate->id][$civilstatus->id] }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                      </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Civil Status</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartcivil"></div></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Employment Status</div>
                    </div>
                </div>

                <div class="box-body">                	
                      <div id="tblempstatus" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
            					<thead>
                                	<tr>
                                    	<th>{{ $positions[0]->name }}</th>
                                        @foreach($empstatuses as $empstatus)
                                        <th></th>
                                        @endforeach
                                	<tr>
                                    	<th>Candidates</th>
                                        @foreach($empstatuses as $empstatus)
                                        <th>{{ $empstatus->name }}</th>
                                        @endforeach
                                    </tr>                                    
                                </thead>
                                <tbody>
                               @php
                                	$tallyemp = array();                                    
                                @endphp
                                @foreach($candidates as $candidate)                                	
                                	<tr>
                                    	<td>{{ $candidate->voter->full_name }}</td>
                                        @foreach($empstatuses as $empstatus)
                                        @php
                                            $tallyemp[$candidate->id][$empstatus->id]=rand(1,100);
                                        @endphp
                                        <td>{{ $tallyemp[$candidate->id][$empstatus->id] }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                      </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Employment Status</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartemp"></div></div>
            </div>
        </div>     
    </div>
    <div class="row">
    	<div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Candidate Qualities</div>                	                        	
                    </div>
                </div>
           
                    <div class="box-body">                	
                          <div id="tblqualities" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
                                <table class="table table-striped table-hover display responsive" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>{{ $positions[0]->name }}</th>
                                            @foreach($qualities as $quality)
                                            <th></th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>Candidates</th>
                                            @foreach($qualities as $quality)
                                            <th>{{ $quality->options->option }}</th>
                                            @endforeach
                                        </tr>                                    
                                    </thead>
                                    <tbody>
                                   @php
                                        $tallyq = array();
                                    @endphp
                                    @foreach($candidates as $candidate)                                	
                                        <tr>
                                            <td>{{ $candidate->voter->full_name }}</td>
                                            @foreach($qualities as $quality)
                                            @php
                                                $tallyq[$candidate->id][$quality->option_id]=rand(1,100);
                                            @endphp
                                            <td>{{ $tallyq[$candidate->id][$quality->option_id] }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                          </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <div class="col-md-12">                      
                                <div class="box-title">Candidate Qualities</div>                	                        	
                        </div>
                    </div>
    
                    <div class="box-body"><div id="chartqualities"></div></div>
                </div>
            </div>
        
            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <div class="col-md-12">                      
                                <div class="box-title">Concerns Per Barangay</div>
                        </div>
                    </div>
    
                    <div class="box-body">                	
                          <div id="tblproblem" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
                                <table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Barangays</th>
                                            @foreach($problems as $problem)
                                            <th>{{ $problem->option->option }}</th>
                                            @endforeach
                                        </tr>                                    
                                    </thead>
                                    <tbody>
                                    @php
                                        $tallyp = array();                                    
                                    @endphp
                                    @foreach($brgysurveys as $barangay)
                                        <tr>
                                            <td>{{ $barangay->name }}</td>
                                            @foreach($problems as $problem)
                                            @php
                                                $tallyp[$barangay->id][$problem->option_id]=rand(1,100);
                                            @endphp
                                            <td>{{ $tallyp[$barangay->id][$problem->option_id] }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                          </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <div class="col-md-12">                      
                                <div class="box-title">Concerns Per Barangay</div>                	                        	
                        </div>
                    </div>
    
                    <div class="box-body"><div id="chartproblem"></div></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('chartcss')
	<link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />-->
    <link href="{{ asset('css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('css/c3.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/jquery.mCustomScrollbar.css') }}" />
    <link href="{{ asset('css/iCheck/flat/green.css') }}" rel="stylesheet">
@endsection
@section('chartsjs')		
	<script src="{{ asset('js/jquery-1.12.4.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/multiselect.js') }}"></script>
    <script src="{{ asset('js/icheck.min.js') }}"></script>
    <!--<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>-->
	<script src="{{ asset('js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ asset('js/d3.v5.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/c3.js') }}"></script>
    <script>
$(document).ready(function ($) {
	/*$('#selposition').select2({
		theme: "bootstrap"
	});
	$('#selvoterstatuses').select2({
		theme: "bootstrap"
	});
	$('#selbrgy').select2({
		theme: "bootstrap"
	});
	$('#selprob').select2({
		theme: "bootstrap"
	});*/
	$('#brgydetails').hide('slow');
	$('#posdetails').hide('slow');
	$('#candetails').hide('slow');
	$('#agedetails').hide('slow');
	$('#gendetails').hide('slow');
	$('#civdetails').hide('slow');
	$('#empdetails').hide('slow');
	$('#btn_brgydetails').on('click',function(e){
		$('#brgydetails').toggle('slow');
		if($('#spanbrgydetails').hasClass('fa-plus')){
			$('#spanbrgydetails').removeClass('fa-plus');
			$('#spanbrgydetails').addClass('fa-minus');
		}else{
			$('#spanbrgydetails').removeClass('fa-minus');
			$('#spanbrgydetails').addClass('fa-plus');
		}
	});
	$('#btn_posdetails').on('click',function(e){
		$('#posdetails').toggle('slow');
		if($('#spanposdetails').hasClass('fa-plus')){
			$('#spanposdetails').removeClass('fa-plus');
			$('#spanposdetails').addClass('fa-minus');
		}else{
			$('#spanposdetails').removeClass('fa-minus');
			$('#spanposdetails').addClass('fa-plus');
		}
	});
	$('#btn_candetails').on('click',function(e){
		$('#candetails').toggle('slow');
		if($('#spancandetails').hasClass('fa-plus')){
			$('#spancandetails').removeClass('fa-plus');
			$('#spancandetails').addClass('fa-minus');
		}else{
			$('#spancandetails').removeClass('fa-minus');
			$('#spancandetails').addClass('fa-plus');
		}
	});
	$('#btn_agedetails').on('click',function(e){
		$('#agedetails').toggle('slow');
		if($('#spanagedetails').hasClass('fa-plus')){
			$('#spanagedetails').removeClass('fa-plus');
			$('#spanagedetails').addClass('fa-minus');
		}else{
			$('#spanagedetails').removeClass('fa-minus');
			$('#spanagedetails').addClass('fa-plus');
		}
	});
	$('#btn_gendetails').on('click',function(e){
		$('#gendetails').toggle('slow');
		if($('#spangendetails').hasClass('fa-plus')){
			$('#spangendetails').removeClass('fa-plus');
			$('#spangendetails').addClass('fa-minus');
		}else{
			$('#spangendetails').removeClass('fa-minus');
			$('#spangendetails').addClass('fa-plus');
		}
	});
	$('#btn_civdetails').on('click',function(e){
		$('#civdetails').toggle('slow');
		if($('#spancivdetails').hasClass('fa-plus')){
			$('#spancivdetails').removeClass('fa-plus');
			$('#spancivdetails').addClass('fa-minus');
		}else{
			$('#spancivdetails').removeClass('fa-minus');
			$('#spancivdetails').addClass('fa-plus');
		}
	});
	$('#btn_empdetails').on('click',function(e){
		$('#empdetails').toggle('slow');
		if($('#spanempdetails').hasClass('fa-plus')){
			$('#spanempdetails').removeClass('fa-plus');
			$('#spanempdetails').addClass('fa-minus');
		}else{
			$('#spanempdetails').removeClass('fa-minus');
			$('#spanempdetails').addClass('fa-plus');
		}
	});
	$('#brgycriteria').multiselect({
		submitAllLeft: false,
		keepRenderingSort: true,
		search: {
			left: '<div class="input-group"><input type="text" name="q" id="searchbrgycriterialeftq" class="form-control" placeholder="Search..." /><span class="input-group-addon" id="searchbrgycriterialeft" style="cursor:pointer;"><span class="fa fa-search"></span></span>'
			,right: '<div class="input-group"><input type="text" name="q" id="searchbrgycriteriarightq"  class="form-control" placeholder="Search..." /><span class="input-group-addon" id="searchbrgycriteriaright" style="cursor:pointer;"><span class="fa fa-search"></span></span>',
		},
		fireSearch: function(value) {
			return value.length > 0;
		}
	});
	$("#tblviewdetails").mCustomScrollbar({
		axis:"yx",
		scrollButtons:{enable:true},
		theme:"3d",
		scrollbarPosition:"outside"
	});
	$("#tblvotes").mCustomScrollbar({
		axis:"yx",
		scrollButtons:{enable:true},
		theme:"3d",
		scrollbarPosition:"outside"
	});
	$("#tblcivilstatus").mCustomScrollbar({
		axis:"yx",
		scrollButtons:{enable:true},
		theme:"3d",
		scrollbarPosition:"outside"
	});
	$("#tblempstatus").mCustomScrollbar({
		axis:"yx",
		scrollButtons:{enable:true},
		theme:"3d",
		scrollbarPosition:"outside"
	});
	$("#tblqualities").mCustomScrollbar({
		axis:"yx",
		scrollButtons:{enable:true},
		theme:"3d",
		scrollbarPosition:"outside"
	});
	$("#tblgender").mCustomScrollbar({
		axis:"yx",
		scrollButtons:{enable:true},
		theme:"3d",
		scrollbarPosition:"outside"
	});
	$("#tblproblem").mCustomScrollbar({
		axis:"yx",
		scrollButtons:{enable:true},
		theme:"3d",
		scrollbarPosition:"outside"
	});
	var chart = c3.generate({
		bindto: '#chart',				
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates', 
			@foreach($candidates as $candidate)
				'{{ $candidate->voter->full_name }}',
			@endforeach
			],
			['Votes',
            @foreach($candidates as $candidate)
				{{ $tally[$candidate->id] }},
			@endforeach
			]
          ],
		  labels: true,
          type: 'bar',
          onclick: function (d, element) { console.log("onclick", d, element); },
          onmouseover: function (d) { console.log("onmouseover", d); },
          onmouseout: function (d) { console.log("onmouseout", d); }
        },
        axis: {
          x: {
            type: 'categorized'
          }
        },
        bar: {
          width: {
            ratio: 0.3,
//            max: 30
          },
        }
      });
	  var chartgender = c3.generate({
		bindto: '#chartgender',				
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates', 
			@foreach($candidates as $candidate)
				'{{ $candidate->voter->full_name }}',
			@endforeach
			],
			@foreach($genders as $gender)
				['{{ $gender->name }}',
				@foreach($candidates as $candidate)
					{{ $tallyg[$candidate->id][$gender->id] }},
				@endforeach
				],
			@endforeach
          ],
		  labels: true,
          type: 'bar',
          onclick: function (d, element) { console.log("onclick", d, element); },
          onmouseover: function (d) { console.log("onmouseover", d); },
          onmouseout: function (d) { console.log("onmouseout", d); }
        },
        axis: {
          x: {
            type: 'categorized'
          }
        },
        bar: {
          width: {
            ratio: 0.3,
//            max: 30
          },
        }
      });
	  var chartagebracket = c3.generate({
		bindto: '#chartagebracket',				
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates', 
			@foreach($candidates as $candidate)
				'{{ $candidate->voter->full_name }}',
			@endforeach
			],
			@foreach($agebrackets as $agebracket)
				['{{ $agebracket->title }}',
				@foreach($candidates as $candidate)
					{{ $tallyab[$candidate->id][$agebracket->id] }},
				@endforeach
				],
			@endforeach
          ],
		  labels: true,
          type: 'bar',
          onclick: function (d, element) { console.log("onclick", d, element); },
          onmouseover: function (d) { console.log("onmouseover", d); },
          onmouseout: function (d) { console.log("onmouseout", d); }
        },
        axis: {
          x: {
            type: 'categorized'
          }
        },
        bar: {
          width: {
            ratio: 0.3,
//            max: 30
          },
        }
      });
	  var chartcivil = c3.generate({
		bindto: '#chartcivil',				
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates', 
			@foreach($candidates as $candidate)
				'{{ $candidate->voter->full_name }}',
			@endforeach
			],
			@foreach($civilstatuses as $civilstatus)
				['{{ $civilstatus->name }}',
				@foreach($candidates as $candidate)
					{{ $tallycv[$candidate->id][$civilstatus->id] }},
				@endforeach
				],
			@endforeach
          ],
		  labels: true,
          type: 'bar',
          onclick: function (d, element) { console.log("onclick", d, element); },
          onmouseover: function (d) { console.log("onmouseover", d); },
          onmouseout: function (d) { console.log("onmouseout", d); }
        },
        axis: {
          x: {
            type: 'categorized'
          }
        },
        bar: {
          width: {
            ratio: 0.3,
//            max: 30
          },
        }
      });
	  var chartemp = c3.generate({
		bindto: '#chartemp',				
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates', 
			@foreach($candidates as $candidate)
				'{{ $candidate->voter->full_name }}',
			@endforeach
			],
			@foreach($empstatuses as $empstatus)
				['{{ $empstatus->name }}',
				@foreach($candidates as $candidate)
					{{ $tallyemp[$candidate->id][$empstatus->id] }},
				@endforeach
				],
			@endforeach
          ],
		  labels: true,
          type: 'bar',
          onclick: function (d, element) { console.log("onclick", d, element); },
          onmouseover: function (d) { console.log("onmouseover", d); },
          onmouseout: function (d) { console.log("onmouseout", d); }
        },
        axis: {
          x: {
            type: 'categorized'
          }
        },
        bar: {
          width: {
            ratio: 0.3,
//            max: 30
          },
        }
      });
	  var chartqualities = c3.generate({
		bindto: '#chartqualities',				
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates', 
			@foreach($candidates as $candidate)
				'{{ $candidate->voter->full_name }}',
			@endforeach
			],
			@foreach($qualities as $quality)
				['{{ $quality->options->option }}',
				@foreach($candidates as $candidate)
					{{ $tallyq[$candidate->id][$quality->option_id] }},
				@endforeach
				],
			@endforeach
          ],
		  labels: true,
          type: 'bar',
          onclick: function (d, element) { console.log("onclick", d, element); },
          onmouseover: function (d) { console.log("onmouseover", d); },
          onmouseout: function (d) { console.log("onmouseout", d); }
        },
        axis: {
          x: {
            type: 'categorized'
          }
        },
        bar: {
          width: {
            ratio: 0.3,
//            max: 30
          },
        }
      });
	  var chartproblem = c3.generate({
		bindto: '#chartproblem',				
        data: {
		  x: 'Barangays',
		  columns: [
		  	['Barangays', 
			@foreach($brgysurveys as $barangay)
				'{{ $barangay->name }}',
			@endforeach
			],
			@foreach($problems as $problem)
				['{{ $problem->option->option }}',
				@foreach($brgysurveys as $barangay)
					{{ $tallyp[$barangay->id][$problem->option_id] }},
				@endforeach
				],
			@endforeach
          ],
		  labels: true,
          type: 'bar',
          onclick: function (d, element) { console.log("onclick", d, element); },
          onmouseover: function (d) { console.log("onmouseover", d); },
          onmouseout: function (d) { console.log("onmouseout", d); }
        },
        axis: {
          x: {
            type: 'categorized'
          }
        },
        bar: {
          width: {
            ratio: 0.3,
//            max: 30
          },
        }
      });
});
    </script>
@endsection