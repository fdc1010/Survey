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
    	$tallypoll = new App\Models\TallyVote;
        $tallyotherpoll = new App\Models\TallyOtherVote;
        
    	$tallysurvey = (!empty($rdata['selsurvey']))?$rdata['selsurvey']:1; 
        $tallyage = (!empty($rdata['selagebracket']))?0:18; 

        $tallyagebrackets=[];
        $tempagebrackets = App\Models\AgeBracket::all();        
		foreach($tempagebrackets as $ageb){
        	for($iage = $ageb->from; $iage<=$ageb->to; $iage++){
        		array_push($tallyagebrackets,$iage);
            }
        }      
        $tallybrgy=App\Models\Barangay::get()->pluck('id')->toArray();
        $tallygenders=App\Models\Gender::get()->pluck('id')->toArray();
        $tallyempstatus=[];
        $tallycivilstatus=[];
        $tallyoccstatus = [];
        $tallyvoterstatus = [];
        
        $surveypos = !empty($rdata['selposition'])?$rdata['selposition']:1;
        $surveydetails = App\Models\SurveyDetail::all();
        $brgyarr = !empty($rdata['to'])?$rdata['to']:array(rand(0,80),rand(0,80),rand(0,80),rand(0,80));        
        $brgysurveys = App\Models\Barangay::whereIn('id',$brgyarr)->get();
        $selinitpositions = App\Models\PositionCandidate::with('candidates')->get();
        if(!empty($rdata['position'])){
        	$selinitcandidates = App\Models\Candidate::with('voter')->whereIn('position_id',$rdata['position'])->get();
        }else{
        	$selinitcandidates = App\Models\Candidate::with('voter')->where('position_id',$surveypos)->get();
        }
        $selinitgenders = App\Models\Gender::all();
        $selinitagebrackets = App\Models\AgeBracket::all();
        $selinitcivilstatuses = App\Models\CivilStatus::all();
        $selinitempstatuses = App\Models\EmploymentStatus::all(); 
        $problems = App\Models\OptionProblem::with('option')->get();
        if(!empty($rdata['gender'])){	
            $genders = App\Models\Gender::whereIn('id',$rdata['gender'])->get(); 
            $tallygenders=$genders->pluck('id')->toArray();
        }else{
        	if(!empty($rdata['selgender'])){
        		$genders = App\Models\Gender::where('id',$rdata['selgender'])->get();
                $tallygenders=$genders->pluck('id')->toArray();
            }else{
            	$genders = App\Models\Gender::all();
            }
        }
        
        $barangays = App\Models\Barangay::all();     
        	
        
        if(!empty($rdata['agebracket'])){
        	$agebrackets = App\Models\AgeBracket::whereIn('id',$rdata['agebracket'])->get(); 
            $tallyagebrackets=[];
            foreach($agebrackets as $agebracket){
                for($iage = $agebracket->from; $iage<=$agebracket->to; $iage++){
                    array_push($tallyagebrackets,$iage);
                }                
            }
        }else{
        	if(!empty($rdata['selagebracket'])){
        		$agebrackets = App\Models\AgeBracket::where('id',$rdata['selagebracket'])->get(); 
                $tallyagebrackets=[];
                foreach($agebrackets as $agebracket){
                    for($iage = $agebracket->from; $iage<=$agebracket->to; $iage++){
                        array_push($tallyagebrackets,$iage);
                    }
                }
            }else{
            	$agebrackets = App\Models\AgeBracket::all(); 
            }
        }
        if(!empty($rdata['civilstatus'])){
        	$civilstatuses = App\Models\CivilStatus::whereIn('id',$rdata['civilstatus'])->get();
            $tallycivilstatus=$civilstatuses->pluck('id')->toArray();
        }else{
        	if(!empty($rdata['selcivil'])){
        		$civilstatuses = App\Models\CivilStatus::where('id',$rdata['selcivil'])->get();
                $tallycivilstatus=$civilstatuses->pluck('id')->toArray();
            }else{
            	$civilstatuses = App\Models\CivilStatus::all();
            }
        }
        if(!empty($rdata['empstatus'])){
        	$empstatuses = App\Models\EmploymentStatus::whereIn('id',$rdata['empstatus'])->get(); 
            $tallyempstatus=$empstatuses->pluck('id')->toArray();
        }else{
        	if(!empty($rdata['selemp'])){
        		$empstatuses = App\Models\EmploymentStatus::where('id',$rdata['selemp'])->get(); 
                $tallyempstatus=$empstatuses->pluck('id')->toArray();
            }else{
            	$empstatuses = App\Models\EmploymentStatus::all(); 
            }
        }
        
        if(!empty($rdata['position'])){
        	$qualities = App\Models\OptionPosition::with('options','positions')
                                                        ->whereIn('position_id',$rdata['position'])->get();
   		}else{
        	$qualities = App\Models\OptionPosition::with('options','positions')->where('position_id',$surveypos)->get();
        }
        $positions = App\Models\PositionCandidate::with('candidates')->where('id',$surveypos)->get();
        if(!empty($rdata['position']) && empty($rdata['selcandidate'])){
            $positions = App\Models\PositionCandidate::with('candidates')->whereIn('id',$rdata['position'])->get();
        }else if(!empty($rdata['position'])){
        	if(!empty($rdata['selcandidate'])){
                $positions = App\Models\PositionCandidate::with(['candidates'=>function($q)use($rdata){
                													$q->where('id',$rdata['selcandidate']);
                												}])
                                                            ->whereIn('id',$rdata['position'])
                                                            ->get();
            }else if(!empty($rdata['candidate'])){
            	$positions = App\Models\PositionCandidate::with(['candidates'=>function($q)use($rdata){
                													$q->whereIn('id',$rdata['candidate']);
                												}])
                                                            ->whereIn('id',$rdata['position'])
                                                            ->get();
            }
        }else{
            if(!empty($rdata['selcandidate'])){
                $positions = App\Models\PositionCandidate::with(['candidates'=>function($q)use($rdata){
                													$q->where('id',$rdata['selcandidate']);
                												}])
                                                            ->get();
            }else if(!empty($rdata['candidate'])){
            	$positions = App\Models\PositionCandidate::with(['candidates'=>function($q)use($rdata){
                													$q->whereIn('id',$rdata['candidate']);
                												}])
                                                            ->get();
            }
        }
    @endphp
    <div class="row">
    	<form method="post" id="my_form" action="{{ backpack_url('stats') }}">
        @csrf
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title"> 
                            	<div class="col-md-2">Survey:</div>
                            	<div class="col-md-5"> 
                                    <select name="selsurvey" id="selsurvey">
                                    @foreach($surveydetails as $surveydetail)	
                                        <option value="{{ $surveydetail->id }}" {{ ((!empty($rdata['selsurvey'])&&$rdata['selsurvey']==$surveydetail->id)?"selected='selected'":"") }}>{{ $surveydetail->subject }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5"> 
                                    <a href="#"><span class="fa fa-file-pdf-o"></span> Print Preview</a>
                                </div>
                            </div>                	                        	
                    </div>
                </div>
				
                <div class="box-body">
                	<table id="tblviewdetails" class="table table-striped table-hover display responsive nowrap">
                    	<thead>
                            <tr>
                                <th width="5%">Barangays:</th>
                                <td width="5%" align="center"><span id="countbrgy">{{ (!empty($rdata['to'])?count($rdata['to']):count($barangays)) }}</span></td>
                                <th width="5%" align="center"><a href="#" id="btn_brgydetails"><span class="fa fa-plus" id="spanbrgydetails"> </span></a></th>
                                <td width="5%" align="center">
                                	<select name="selposition" id="selposition">
                                    	<option value="0">Run for</option>
                                    @foreach($selinitpositions as $position)	
                                    	<option value="{{ $position->id }}" {{ ((!empty($rdata['selposition'])&&$rdata['selposition']==$position->id)?"selected='selected'":"") }}>{{ $position->name }}</option>
                                    @endforeach
                                	</select>
                                </td>
                                <th width="5%" align="center"><a href="#" id="btn_posdetails"><span class="fa fa-plus" id="spanposdetails"> </span></a></th>
                                <td width="5%" align="center">
                                	<select name="selcandidate" id="selcandidate">
                                    	<option value="0">Candidate</option>
                                    @foreach($positions as $position)
                                    	<optgroup label="{{ $position->name }}" >
										@foreach($position->candidates as $candidate)
                                    		<option value="{{ $candidate->id }}" {{ ((!empty($rdata['selcandidate'])&&$rdata['selcandidate']==$candidate->id)?"selected='selected'":"") }}>{{ $candidate->voter->full_name }}</option>
                                    	@endforeach
                                        </optgroup>
                                    @endforeach
                                	</select>
                                </td>
                                <th width="5%" align="center"><a href="#" id="btn_candetails"><span class="fa fa-plus" id="spancandetails"> </span></a></th>
                                <th width="5%">Demographics:</th>
                                <td width="5%" align="center">
                                	<select name="selagebracket" id="selagebracket">
                                    	<option value="0">Age</option>
                                    @foreach($selinitagebrackets as $agebracket)	
                                    	<option value="{{ $agebracket->id }}" {{ ((!empty($rdata['selagebracket'])&&$rdata['selagebracket']==$agebracket->id)?"selected='selected'":"") }}>{{ $agebracket->title }}</option>
                                    @endforeach
                                	</select>
                                </td>
                                <th width="5%" align="center"><a href="#" id="btn_agedetails"><span class="fa fa-plus" id="spanagedetails"> </span></a></th>
                                <td width="5%" align="center">
                                	<select name="selgender" id="selgender">
                                    	<option value="0">Gender</option>
                                    @foreach($selinitgenders as $gender)	
                                    	<option value="{{ $gender->id }}" {{ ((!empty($rdata['selgender'])&&$rdata['selgender']==$gender->id)?"selected='selected'":"") }}>{{ $gender->name }}</option>
                                    @endforeach
                                	</select>
                                </td>
                                <th width="5%" align="center"><a href="#" id="btn_gendetails"><span class="fa fa-plus" id="spangendetails"> </span></a></th>
                                <td width="5%" align="center">
                                	<select name="selcivil" id="selcivil">
                                    	<option value="0">Civil</option>
                                    @foreach($selinitcivilstatuses as $civilstatus)	
                                    	<option value="{{ $civilstatus->id }}" {{ ((!empty($rdata['selcivil'])&&$rdata['selcivil']==$civilstatus->id)?"selected='selected'":"") }}>{{ $civilstatus->name }}</option>
                                    @endforeach
                                	</select>
                                </td>
                                <th width="5%" align="center"><a href="#" id="btn_civdetails"><span class="fa fa-plus" id="spancivdetails"> </span></a></th>
                                <td width="5%" align="center">
                                	<select name="selemp" id="selemp">
                                    	<option value="0">Employment</option>
                                    @foreach($selinitempstatuses as $empstatus)	
                                    	<option value="{{ $empstatus->id }}" {{ ((!empty($rdata['selemp'])&&$rdata['selemp']==$empstatus->id)?"selected='selected'":"") }}>{{ $empstatus->name }}</option>
                                    @endforeach
                                	</select>
                                </td>
                                <th width="5%" align="center"><a href="#" id="btn_empdetails"><span class="fa fa-plus" id="spanempdetails"> </span></a></th>
                                <th width="5%" align="center">
                                    <button type="submit" class="btn btn-primary">
                                        <span class="fa fa-search"></span> View
                                    </button>
                              </th>
                            </tr>                                    
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-12" id="brgydetails">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Barangays</div>                	                        	
                    </div>
                </div>
                <div class="box-body">
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
                              <select name="to[]" id="brgycriteria_to" class="form-control" size="8" multiple="multiple">                                                                        
                                    @if(!empty($rdata['to']))	
                                    	@foreach($barangays as $barangay)
                                        	@if(in_array($barangay->id,$rdata['to']))
                                        		<option value="{{ $barangay->id }}">{{ $barangay->name }}</option>                                        	
                                            @endif
                                    	@endforeach
                                        @php
                                            $tallybrgy=$rdata['to'];
                                        @endphp
                                    @endif                                    
                              </select>
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
                        <div class="col-md-12"><label class="control-label"><input type="checkbox" id="checkAllPosition" /> Check All</label></div>
                        @foreach($selinitpositions as $position)                        		
                                <div class="col-md-4">
                                    <label class="control-label">
                                    	@if(!empty($rdata['position']) && in_array($position->id,$rdata['position']))
                                        	<input type="checkbox" id="{{ $position->id }}" name="position[]" value=" {{ $position->id }}" checked="checked" />
                                        @else
                                        	<input type="checkbox" id="{{ $position->id }}" name="position[]" value=" {{ $position->id }}" />
                                        @endif
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
                        @foreach($selinitpositions as $position)                        	
                        	<div class="col-md-12"><h5>{{ $position->name }}</h5>
                            @foreach($position->candidates as $candidate)
                                    <div class="col-md-3">
                                        <label class="control-label">
                                            @if(!empty($rdata['candidate']) && in_array($candidate->id,$rdata['candidate']))
                                            	<input type="checkbox" id="{{ $candidate->id }}" name="candidate[]" value=" {{ $candidate->id }}" checked="checked" />
                                           	@else
                                            	<input type="checkbox" id="{{ $candidate->id }}" name="candidate[]" value=" {{ $candidate->id }}" />
                                            @endif
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
                        @foreach($selinitagebrackets as $agebracket)
                                <div class="col-md-4">
                                    <label class="control-label">
                                        @if(!empty($rdata['agebracket']) && in_array($agebracket->id,$rdata['agebracket']))
                                        	<input type="checkbox" id="{{ $agebracket->id }}" name="agebracket[]" value=" {{ $agebracket->id }}" checked="checked" />
										@else
                                        	<input type="checkbox" id="{{ $agebracket->id }}" name="agebracket[]" value=" {{ $agebracket->id }}" />                                          
                                        @endif
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
                        @foreach($selinitgenders as $gender)
                                <div class="col-md-4">
                                    <label class="control-label">
                                        @if(!empty($rdata['gender']) && in_array($gender->id,$rdata['gender']))	
                                            <input type="checkbox" id="{{ $gender->id }}" name="gender[]" value=" {{ $gender->id }}" checked="checked" />
                                        @else
                                        	<input type="checkbox" id="{{ $gender->id }}" name="gender[]" value=" {{ $gender->id }}" />
                                        @endif
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
                        @foreach($selinitcivilstatuses as $civilstatus)
                                <div class="col-md-4">
                                    <label class="control-label">
                                        @if(!empty($rdata['civilstatus']) && in_array($civilstatus->id,$rdata['civilstatus']))		
                                            <input type="checkbox" id="{{ $civilstatus->id }}" name="civilstatus[]" value=" {{ $civilstatus->id }}" checked="checked" />
                                        @else
                                        	<input type="checkbox" id="{{ $civilstatus->id }}" name="civilstatus[]" value=" {{ $civilstatus->id }}" />
                                        @endif
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
                        @foreach($selinitempstatuses as $empstatus)
                                <div class="col-md-4">
                                    <label class="control-label">
                                        @if(!empty($rdata['empstatus']) && in_array($empstatus->id,$rdata['empstatus']))	
                                            <input type="checkbox" id="{{ $empstatus->id }}" name="empstatus[]" value=" {{ $empstatus->id }}" checked="checked" />
                                        @else
                                        	<input type="checkbox" id="{{ $empstatus->id }}" name="empstatus[]" value=" {{ $empstatus->id }}" />
                                        @endif
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
                                        <th>Cadidates</th>
                                        <th>Tally</th>
                                    </tr>                                    
                                </thead>
                            	@php
                                    $tally = array();                                    
                                @endphp
            					@foreach($positions as $position)
                                  <thead>
                                      <tr>
                                          <th>{{ $position->name }}</th>
                                          <th></th>
                                      </tr>                                    
                                  </thead>
                                  <tbody>                                  
                                  @foreach($position->candidates as $candidate)
                                      @php
                                          $tally[$candidate->id]=$tallypoll->tally($candidate->id,$tallysurvey,$tallyagebrackets,$tallybrgy,
                                                                                  $tallygenders, $tallyempstatus,$tallycivilstatus,
                                                                                  $tallyoccstatus,$tallyvoterstatus);   
                                      @endphp
                                      <tr>
                                          <td>{{ $candidate->voter->full_name }}</td>
                                          <td>{{ $tally[$candidate->id] }}</td>
                                      </tr>
                                  @endforeach                                
                                  </tbody>
                                @endforeach
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
                                        <th>Cadidates</th>
                                        @foreach($genders as $gender)
                                        <th>{{ $gender->name }}</th>
                                        @endforeach
                                    </tr>                                    
                                </thead>
                                @php
                                	$tallyg = array();                                    
                                @endphp
                                @foreach($positions as $position)
                                  <thead>
                                      <tr>
                                          <th>{{ $position->name }}</th>
                                          @foreach($genders as $gender)
                                          <th></th>
                                          @endforeach
                                      </tr>                                    
                                  </thead>                                  
                                <tbody>
                               	
                                 @foreach($position->candidates as $candidate)                             	
                                	<tr>
                                    	<td>{{ $candidate->voter->full_name }}</td>
                                        @foreach($genders as $gender)
                                        @php
                                        	$tallyg[$candidate->id][$gender->id]=$tallypoll->tally($candidate->id,$tallysurvey,$tallyagebrackets,$tallybrgy,
                                                                                                      [$gender->id], $tallyempstatus,$tallycivilstatus,
                                                                                                      $tallyoccstatus,$tallyvoterstatus);
                                                                                        
                                        @endphp
                                        <td>{{ $tallyg[$candidate->id][$gender->id] }}</td>
                                        @endforeach
                                    </tr>
                                  @endforeach                                
                                  </tbody>
                                @endforeach
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
                      <div id="tblagebracket" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
            					<thead>
                                	<tr>
                                    	<th>Candidates</th>
                                        @foreach($agebrackets as $agebracket)
                                        <th>{{ $agebracket->title }}</th>
                                        @endforeach
                                    </tr>                             
                                </thead>
                                @php
                                	$tallyab = array();                                    
                                @endphp
                                @foreach($positions as $position)
                                  <thead>
                                      <tr>
                                          <th>{{ $position->name }}</th>
                                          @foreach($agebrackets as $agebracket)
                                       	  <th></th>
                                          @endforeach
                                      </tr>                                    
                                  </thead>
                                  <tbody>
                                  
                                  @foreach($position->candidates as $candidate)                               	
                                      <tr>
                                          <td>{{ $candidate->voter->full_name }}</td>
                                          @foreach($agebrackets as $agebracket)
                                          @php
                                              $gtallyagebrackets=[];
                                              for($tallyiage = $agebracket->from; $tallyiage<=$agebracket->to; $tallyiage++){
                                                  array_push($gtallyagebrackets,$tallyiage);
                                              }
                                              $tallyab[$candidate->id][$agebracket->id]=$tallypoll->tally($candidate->id,$tallysurvey,$gtallyagebrackets,$tallybrgy,
                                                                                                        $tallygenders, $tallyempstatus,$tallycivilstatus,
                                                                                                        $tallyoccstatus,$tallyvoterstatus);                                            
                                          @endphp
                                          <td>{{ $tallyab[$candidate->id][$agebracket->id] }}</td>
                                          @endforeach
                                      </tr>
                                  @endforeach                                
                                  </tbody>
                                @endforeach
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
                                    	<th>Candidates</th>
                                        @foreach($civilstatuses as $civilstatus)
                                        <th>{{ $civilstatus->name }}</th>
                                        @endforeach
                                    </tr>                                    
                                </thead>
                                @php
                                	$tallycv = array();                                    
                                @endphp
                                @foreach($positions as $position)
                                  <thead>
                                      <tr>
                                          <th>{{ $position->name }}</th>
                                          @foreach($civilstatuses as $civilstatus)
                                          <th></th>
                                          @endforeach
                                      </tr>                                    
                                  </thead>
                                  <tbody>                                
                                	@foreach($position->candidates as $candidate)                               	
                                        <tr>
                                            <td>{{ $candidate->voter->full_name }}</td>
                                            @foreach($civilstatuses as $civilstatus)
                                            @php
                                                $tallycv[$candidate->id][$civilstatus->id]=$tallypoll->tally($candidate->id,$tallysurvey,$tallyagebrackets,$tallybrgy,
                                                                                                                    $tallygenders, $tallyempstatus,[$civilstatus->id],
                                                                                                                    $tallyoccstatus,$tallyvoterstatus);                                            
                                            @endphp
                                            <td>{{ $tallycv[$candidate->id][$civilstatus->id] }}</td>
                                            @endforeach
                                        </tr>
                                  	@endforeach                                
                                  </tbody>
                                @endforeach
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
                                @foreach($positions as $position)
                                  <thead>
                                      <tr>
                                          <th>{{ $position->name }}</th>
                                          @foreach($empstatuses as $empstatus)
                                          <th></th>
                                          @endforeach
                                      </tr>                                    
                                  </thead>
                                  <tbody>                                
                                	@foreach($position->candidates as $candidate)                                    	             	
                                          <tr>
                                              <td>{{ $candidate->voter->full_name }}</td>
                                              @foreach($empstatuses as $empstatus)
                                              @php
                                                  $tallyemp[$candidate->id][$empstatus->id]=$tallypoll->tally($candidate->id,$tallysurvey,$tallyagebrackets,$tallybrgy,
                                                                                                                      $tallygenders,[$empstatus->id],$tallycivilstatus,
                                                                                                                      $tallyoccstatus,$tallyvoterstatus);                                            
                                              @endphp
                                              <td>{{ $tallyemp[$candidate->id][$empstatus->id] }}</td>
                                              @endforeach
                                          </tr>
                                  	@endforeach                                
                                  </tbody>
                                @endforeach
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
                                <table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
            					<thead>
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
                                @foreach($positions as $position)
                                  <thead>
                                      <tr>
                                          <th>{{ $position->name }}</th>
                                          @foreach($qualities as $quality)
                                          <th></th>
                                          @endforeach
                                      </tr>                                    
                                  </thead>
                                  <tbody>                                
                                	@foreach($position->candidates as $candidate)                                   	             	
                                          <tr>
                                            <td>{{ $candidate->voter->full_name }}</td>
                                            @foreach($qualities as $quality)
                                            @php
                                                $tallyq[$candidate->id][$quality->option_id]=$tallyotherpoll->tally($candidate->id,$quality->option_id,$tallysurvey,$tallyagebrackets,$tallybrgy,
                                                                                                                $tallygenders, $tallyempstatus,$tallycivilstatus,
                                                                                                                $tallyoccstatus,$tallyvoterstatus);
                                            @endphp
                                            <td>{{ $tallyq[$candidate->id][$quality->option_id] }}</td>
                                            @endforeach
                                          </tr>
                                  	@endforeach                                
                                  </tbody>
                                @endforeach
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
                                                $tallyp[$barangay->id][$problem->option_id]=$tallyotherpoll->tallyproblem($barangay->id,$problem->option_id,$tallysurvey,$tallyagebrackets,$tallybrgy,
                                                                                                                	$tallygenders, $tallyempstatus,$tallycivilstatus,
                                                                                                                	$tallyoccstatus,$tallyvoterstatus);
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
	$("#tblagebracket").mCustomScrollbar({
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
			@foreach($positions as $position)
				@foreach($position->candidates as $candidate)
					'{{ $candidate->voter->full_name }}',
				@endforeach
			@endforeach
			],
			['Votes',
			@foreach($positions as $position)
				@foreach($position->candidates as $candidate)
					{{ $tally[$candidate->id] }},
				@endforeach
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
			@foreach($positions as $position)
				@foreach($position->candidates as $candidate)
					'{{ $candidate->voter->full_name }}',
				@endforeach
			@endforeach
			],
			@foreach($genders as $gender)
				['{{ $gender->name }}',
				@foreach($positions as $position)
					@foreach($position->candidates as $candidate)
						{{ $tallyg[$candidate->id][$gender->id] }},
					@endforeach
				@endforeach
				],
			@endforeach
          ],
		  //labels: true,
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
			@foreach($positions as $position)
				@foreach($position->candidates as $candidate)
					'{{ $candidate->voter->full_name }}',
				@endforeach
			@endforeach
			],
			@foreach($agebrackets as $agebracket)
				['{{ $agebracket->title }}',
				@foreach($positions as $position)
					@foreach($position->candidates as $candidate)
						{{ $tallyab[$candidate->id][$agebracket->id] }},
					@endforeach
				@endforeach
				],
			@endforeach
          ],
		  //labels: true,
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
			@foreach($positions as $position)
				@foreach($position->candidates as $candidate)
					'{{ $candidate->voter->full_name }}',
				@endforeach
			@endforeach
			],
			@foreach($civilstatuses as $civilstatus)
				['{{ $civilstatus->name }}',
				@foreach($positions as $position)
					@foreach($position->candidates as $candidate)
						{{ $tallycv[$candidate->id][$civilstatus->id] }},
					@endforeach
				@endforeach
				],
			@endforeach
          ],
		  //labels: true,
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
			@foreach($positions as $position)
				@foreach($position->candidates as $candidate)
					'{{ $candidate->voter->full_name }}',
				@endforeach
			@endforeach
			],
			@foreach($empstatuses as $empstatus)
				['{{ $empstatus->name }}',
				@foreach($positions as $position)
					@foreach($position->candidates as $candidate)
						{{ $tallyemp[$candidate->id][$empstatus->id] }},
					@endforeach
				@endforeach
				],
			@endforeach
          ],
		  //labels: true,
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
			@foreach($positions as $position)
				@foreach($position->candidates as $candidate)
					'{{ $candidate->voter->full_name }}',
				@endforeach
			@endforeach
			],
			@foreach($qualities as $quality)
				['{{ $quality->options->option }}',
				@foreach($positions as $position)
					@foreach($position->candidates as $candidate)
						{{ $tallyq[$candidate->id][$quality->option_id] }},
					@endforeach
				@endforeach
				],
			@endforeach
          ],
		  //labels: true,
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
		  //labels: true,
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