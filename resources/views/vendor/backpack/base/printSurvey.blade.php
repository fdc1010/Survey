<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if (config('backpack.base.meta_robots_content'))
    <meta name="robots" content="{{ config('backpack.base.meta_robots_content', 'noindex, nofollow') }}">
    @endif

    {{-- Encrypted CSRF token for Laravel, in order for Ajax requests to work --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>
      {{ isset($title) ? $title.' :: '.config('backpack.base.project_name').' Admin' : config('backpack.base.project_name').' Admin' }}
    </title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/skins/_all-skins.min.css">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/plugins/pace/pace.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/pnotify/pnotify.custom.min.css') }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- BackPack Base CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/backpack/backpack.base.css') }}?v=2">
    <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />-->
    <link href="{{ asset('css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('css/c3.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/jquery.mCustomScrollbar.css') }}" />
    <link href="{{ asset('css/iCheck/flat/green.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Site wrapper -->
    <div class="wrapper">
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">

          @php
    	$tallypoll = new App\Models\TallyVote;
        $tallyotherpoll = new App\Models\TallyOtherVote;
        
    	$tallysurvey = (!empty($rdata['selsurvey']))?$rdata['selsurvey']:1; 
        
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
		  
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

    </div>
    <!-- ./wrapper -->

    <!-- jQuery 2.2.3 -->
    <script src="{{ asset('vendor/adminlte') }}/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('vendor/adminlte') }}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/pace/pace.min.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/dist/js/adminlte.min.js"></script>
	<script src="{{ asset('js/jquery-1.12.4.js') }}"></script>
    <script src="{{ asset('js/d3.v5.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/c3.js') }}"></script>
    <script>
	$(document).ready(function ($) {
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
	      
        // Ajax calls should always have the CSRF token attached to them, otherwise they won't work
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });    <!-- JavaScripts -->
	});
	</script>
</body>
</html>
