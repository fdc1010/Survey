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

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- BackPack Base CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/backpack/backpack.base.css') }}?v=2"> <link href="{{ asset('css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('css/c3.css') }}" rel="stylesheet" type="text/css">
	<style type="text/css"> 
		/*thead { display: table-header-group }
		tfoot { display: table-row-group }
		tr { page-break-inside: avoid }
		@media print {
			.element-that-contains-table {
				overflow: visible !important;
			}
		}*/
		.contentBlock, div, h2, h3, p { 
			/*display:block ! important; 
			page-break-inside:avoid ! important; 
			page-break-before:always !important;*/
		}
    </style>
</head>
<body class="hold-transition {{ config('backpack.base.skin') }}">
    <!-- Site wrapper -->
    <div class="wrapper">
        <section class="content">

        @php
        if(!empty($rdata['hidselsurvey'])){
            if(!empty($rdata['hidincgraph']) && $rdata['hidincgraph']=="true")
                $showGraph = true;
            else
            	$showGraph = false;
            if(!empty($rdata['hidincgen']) && $rdata['hidincgen']=="true")
                $showGender = true;
            else
            	$showGender = false;
            if(!empty($rdata['hidincageb']) && $rdata['hidincageb']=="true")
                $showAgeBracket = true;
            else
            	$showAgeBracket = false;
            if(!empty($rdata['hidincciv']) && $rdata['hidincciv']=="true")
                $showCivil = true;
            else
            	$showCivil = false;
            if(!empty($rdata['hidincemp']) && $rdata['hidincemp']=="true")
                $showEmployment = true;
            else
            	$showEmployment = false;
            if(!empty($rdata['hidincprob']) && $rdata['hidincprob']=="true")
                $showProblem = true;
            else
            	$showProblem = false;
            if(!empty($rdata['hidinccanq']) && $rdata['hidinccanq']=="true")
                $showQuality = true;
            else
            	$showQuality = false;
    	}else{
        	$showGraph = true;
            $showGender = true;
            $showAgeBracket = true;
            $showCivil = true;
            $showEmployment = true;
            $showProblem = true;
            $showQuality = true;
        }
        
        $tallyelection = (!empty($rdata['hidselelection']))?$rdata['hidselelection']:1;
        $selinitelections = App\Models\Election::all();
		$elections = App\Models\Election::find($tallyelection);
        
        $tallyvote = new App\Models\ElectionReturn;
    	$tallypoll = new App\Models\TallyVote;
        $tallyotherpoll = new App\Models\TallyOtherVote;
        
    	$tallysurvey = (!empty($rdata['hidselsurvey']))?$rdata['hidselsurvey']:1; 
        $tallysurveycompare = (!empty($rdata['hidselsurveycompare']))?$rdata['hidselsurveycompare']:1;
        $surveyinfo = App\Models\SurveyDetail::find($tallysurvey);
        $surveyinfocompare = App\Models\SurveyDetail::find($tallysurveycompare);
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
        
        $surveypos = !empty($rdata['hidselposition'])?$rdata['hidselposition']:1;
        $surveydetails = App\Models\SurveyDetail::all();
        $brgyarr = !empty($rdata['hidto'])?$rdata['hidto']:array(rand(0,80),rand(0,80),rand(0,80),rand(0,80));        
        $brgysurveys = App\Models\Barangay::whereIn('id',$brgyarr)->get();
        $selinitpositions = App\Models\PositionCandidate::with('candidates')->get();
        if(!empty($rdata['hidposition'])){
        	$selinitcandidates = App\Models\Candidate::with('voter')->whereIn('position_id',$rdata['hidposition'])->get();
        }else{
        	$selinitcandidates = App\Models\Candidate::with('voter')->where('position_id',$surveypos)->get();
        }
        $selinitgenders = App\Models\Gender::all();
        $selinitagebrackets = App\Models\AgeBracket::all();
        $selinitcivilstatuses = App\Models\CivilStatus::all();
        $selinitempstatuses = App\Models\EmploymentStatus::all(); 
        $problems = App\Models\OptionProblem::with('option')->get();
        if(!empty($rdata['hidgender'])){	
            $genders = App\Models\Gender::whereIn('id',$rdata['hidgender'])->get(); 
            $tallygenders=$genders->pluck('id')->toArray();
        }else{
        	if(!empty($rdata['hidselgender'])){
        		$genders = App\Models\Gender::where('id',$rdata['hidselgender'])->get();
                $tallygenders=$genders->pluck('id')->toArray();
            }else{
            	$genders = App\Models\Gender::all();
            }
        }
        
        $barangays = App\Models\Barangay::all();     
        	
        
        if(!empty($rdata['hidagebracket'])){
        	$agebrackets = App\Models\AgeBracket::whereIn('id',$rdata['hidagebracket'])->get(); 
            $tallyagebrackets=[];
            foreach($agebrackets as $agebracket){
                for($iage = $agebracket->from; $iage<=$agebracket->to; $iage++){
                    array_push($tallyagebrackets,$iage);
                }                
            }
        }else{
        	if(!empty($rdata['hidselagebracket'])){
        		$agebrackets = App\Models\AgeBracket::where('id',$rdata['hidselagebracket'])->get(); 
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
        if(!empty($rdata['hidcivilstatus'])){
        	$civilstatuses = App\Models\CivilStatus::whereIn('id',$rdata['hidcivilstatus'])->get();
            $tallycivilstatus=$civilstatuses->pluck('id')->toArray();
        }else{
        	if(!empty($rdata['hidselcivil'])){
        		$civilstatuses = App\Models\CivilStatus::where('id',$rdata['hidselcivil'])->get();
                $tallycivilstatus=$civilstatuses->pluck('id')->toArray();
            }else{
            	$civilstatuses = App\Models\CivilStatus::all();
            }
        }
        if(!empty($rdata['hidempstatus'])){
        	$empstatuses = App\Models\EmploymentStatus::whereIn('id',$rdata['hidempstatus'])->get(); 
            $tallyempstatus=$empstatuses->pluck('id')->toArray();
        }else{
        	if(!empty($rdata['hidselemp'])){
        		$empstatuses = App\Models\EmploymentStatus::where('id',$rdata['hidselemp'])->get(); 
                $tallyempstatus=$empstatuses->pluck('id')->toArray();
            }else{
            	$empstatuses = App\Models\EmploymentStatus::all(); 
            }
        }
        
        $qualities = App\Models\OptionQuality::with('options')->get();
        
        $positions = App\Models\PositionCandidate::with('candidates')->where('id',$surveypos)->get();
        if(!empty($rdata['hidposition']) && empty($rdata['hidselcandidate'])){
            $positions = App\Models\PositionCandidate::with('candidates')->whereIn('id',$rdata['hidposition'])->get();
        }else if(!empty($rdata['hidposition'])){
        	if(!empty($rdata['hidselcandidate'])){
                $positions = App\Models\PositionCandidate::with(['candidates'=>function($q)use($rdata){
                													$q->where('id',$rdata['hidselcandidate']);
                												}])
                                                            ->whereIn('id',$rdata['hidposition'])
                                                            ->get();
            }else if(!empty($rdata['hidcandidate'])){
            	$positions = App\Models\PositionCandidate::with(['candidates'=>function($q)use($rdata){
                													$q->whereIn('id',$rdata['hidcandidate']);
                												}])
                                                            ->whereIn('id',$rdata['hidposition'])
                                                            ->get();
            }
        }else{
            if(!empty($rdata['hidselcandidate'])){
                $positions = App\Models\PositionCandidate::with(['candidates'=>function($q)use($rdata){
                													$q->where('id',$rdata['hidselcandidate']);
                												}])
                                                            ->get();
            }else if(!empty($rdata['hidcandidate'])){
            	$positions = App\Models\PositionCandidate::with(['candidates'=>function($q)use($rdata){
                													$q->whereIn('id',$rdata['hidcandidate']);
                												}])
                                                            ->get();
            }
        }
    @endphp
    <div class="row">    	
    	<div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Tabular Stats (Summary): {{ $surveyinfo->subject }}</div>
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
        @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Tabular Stats (Summary): {{ $surveyinfocompare->subject }}</div>
                    </div>
                </div>                
                <div class="box-body">                	
                      <div id="tblvotescompare" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
                      	<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                           	<thead>
                                    <tr>
                                        <th>Cadidates</th>
                                        <th>Tally</th>
                                    </tr>                                    
                                </thead>
                            	@php
                                    $tallycompare = array();                                    
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
                                          	$tallycompare[$candidate->id]=$tallypoll->tally($candidate->id,$tallysurveycompare,$tallyagebrackets,$tallybrgy,
                                                                                  $tallygenders, $tallyempstatus,$tallycivilstatus,
                                                                                  $tallyoccstatus,$tallyvoterstatus);   
                                      	  
                                      @endphp
                                      <tr>
                                          <td>{{ $candidate->voter->full_name }}</td>
                                          <td>{{ $tallycompare[$candidate->id] }}</td>
                                      </tr>
                                  @endforeach                                
                                  </tbody>
                                @endforeach
                            </table>
                      </div>
                </div>
            </div>
        </div>
        @elseif(!empty($rdata['hidselelection']))
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Tabular Stats (Summary): {{ $elections->name }}</div>
                    </div>
                </div>                
                <div class="box-body">                	
                      <div id="tblvotescompare" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
                      	<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                           	<thead>
                                    <tr>
                                        <th>Cadidates</th>
                                        <th>Votes</th>
                                    </tr>                                    
                                </thead>
                            	@php
                                    $tallycompare = array();                                    
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
                                          	$tallycompare[$candidate->id]=$tallyvote->tally($candidate->id,$tallyelection,$tallyagebrackets,$tallybrgy,
                                                                                  $tallygenders, $tallyempstatus,$tallycivilstatus,
                                                                                  $tallyoccstatus,$tallyvoterstatus);
                                          
                                      @endphp
                                      <tr>
                                          <td>{{ $candidate->voter->full_name }}</td>
                                          <td>{{ $tallycompare[$candidate->id] }}</td>
                                      </tr>
                                  @endforeach                                
                                  </tbody>
                                @endforeach
                            </table>
                      </div>
                </div>
            </div>
        </div>
        @endif
    	@if($showGender)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Tabular Tally by Gender: {{ $surveyinfo->subject }}</div>
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
        @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Tabular Tally by Gender: {{ $surveyinfocompare->subject }}</div>
                    </div>
                </div>

                <div class="box-body">                	
                      <div id="tblgendercompare" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
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
                                	$tallygcompare = array();                                    
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
                                        	$tallygcompare[$candidate->id][$gender->id]=$tallypoll->tally($candidate->id,$tallysurveycompare,$tallyagebrackets,$tallybrgy,
                                                                                                      [$gender->id], $tallyempstatus,$tallycivilstatus,
                                                                                                      $tallyoccstatus,$tallyvoterstatus);
                                                                                        
                                        @endphp
                                        <td>{{ $tallygcompare[$candidate->id][$gender->id] }}</td>
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
        @elseif(!empty($rdata['hidselelection']))
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Tabular Tally by Gender: {{ $elections->name }}</div>
                    </div>
                </div>

                <div class="box-body">                	
                      <div id="tblgendercompare" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
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
                                	$tallygcompare = array();                                    
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
                                        	$tallygcompare[$candidate->id][$gender->id]=$tallyvote->tally($candidate->id,$tallyelection,$tallyagebrackets,$tallybrgy,
                                                                                                      [$gender->id], $tallyempstatus,$tallycivilstatus,
                                                                                                      $tallyoccstatus,$tallyvoterstatus);
                                                                                        
                                        @endphp
                                        <td>{{ $tallygcompare[$candidate->id][$gender->id] }}</td>
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
        @endif
       @endif
       @if($showCivil)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Civil Status: {{ $surveyinfo->subject }}</div>
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
        @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Civil Status: {{ $surveyinfocompare->subject }}</div>
                    </div>
                </div>

                <div class="box-body">                	
                       <div id="tblcivilstatuscompare" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
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
                                	$tallycvcompare = array();                                    
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
                                                $tallycvcompare[$candidate->id][$civilstatus->id]=$tallypoll->tally($candidate->id,$tallysurveycompare,$tallyagebrackets,$tallybrgy,
                                                                                                                    $tallygenders, $tallyempstatus,[$civilstatus->id],
                                                                                                                    $tallyoccstatus,$tallyvoterstatus);                                            
                                            @endphp
                                            <td>{{ $tallycvcompare[$candidate->id][$civilstatus->id] }}</td>
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
        @elseif(!empty($rdata['hidselelection']))
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Civil Status: {{ $elections->name }}</div>
                    </div>
                </div>

                <div class="box-body">                	
                       <div id="tblcivilstatuscompare" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
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
                                	$tallycvcompare = array();                                    
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
                                                $tallycvcompare[$candidate->id][$civilstatus->id]=$tallyvote->tally($candidate->id,$tallyelection,$tallyagebrackets,$tallybrgy,
                                                                                                                    $tallygenders, $tallyempstatus,[$civilstatus->id],
                                                                                                                    $tallyoccstatus,$tallyvoterstatus);                                            
                                            @endphp
                                            <td>{{ $tallycvcompare[$candidate->id][$civilstatus->id] }}</td>
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
        @endif
        @endif
        @if($showEmployment)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Employment Status: {{ $surveyinfo->subject }}</div>
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
        @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Employment Status: {{ $surveyinfocompare->subject }}</div>
                    </div>
                </div>

                <div class="box-body">                	
                      <div id="tblempstatuscompare" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
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
                                	$tallyempcompare = array();                                    
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
                                                  $tallyempcompare[$candidate->id][$empstatus->id]=$tallypoll->tally($candidate->id,$tallysurveycompare,$tallyagebrackets,$tallybrgy,
                                                                                                                      $tallygenders,[$empstatus->id],$tallycivilstatus,
                                                                                                                      $tallyoccstatus,$tallyvoterstatus);                                            
                                              @endphp
                                              <td>{{ $tallyempcompare[$candidate->id][$empstatus->id] }}</td>
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
       	@elseif(!empty($rdata['hidselelection']))
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Employment Status: {{ $elections->name }}</div>
                    </div>
                </div>

                <div class="box-body">                	
                      <div id="tblempstatuscompare" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
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
                                	$tallyempcompare = array();                                    
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
                                                  $tallyempcompare[$candidate->id][$empstatus->id]=$tallyvote->tally($candidate->id,$tallyelection,$tallyagebrackets,$tallybrgy,
                                                                                                                      $tallygenders,[$empstatus->id],$tallycivilstatus,
                                                                                                                      $tallyoccstatus,$tallyvoterstatus);                                            
                                              @endphp
                                              <td>{{ $tallyempcompare[$candidate->id][$empstatus->id] }}</td>
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
        @endif  
        @endif           
    </div>
   
    <div class="row">
    	
        @if($showAgeBracket)
    	<div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Age Bracket: {{ $surveyinfo->subject }}</div>
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
        @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Age Bracket: {{ $surveyinfocompare->subject }}</div>
                    </div>
                </div>

                <div class="box-body">                	
                      <div id="tblagebracketcompare" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
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
                                	$tallyabcompare = array();                                    
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
                                              $tallyabcompare[$candidate->id][$agebracket->id]=$tallypoll->tally($candidate->id,$tallysurveycompare,$gtallyagebrackets,$tallybrgy,
                                                                                                        $tallygenders, $tallyempstatus,$tallycivilstatus,
                                                                                                        $tallyoccstatus,$tallyvoterstatus);                                            
                                          @endphp
                                          <td>{{ $tallyabcompare[$candidate->id][$agebracket->id] }}</td>
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
        @elseif(!empty($rdata['hidselelection']))
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Age Bracket: {{ $elections->name }}</div>
                    </div>
                </div>

                <div class="box-body">                	
                      <div id="tblagebracketcompare" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
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
                                	$tallyabcompare = array();                                    
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
                                              $tallyabcompare[$candidate->id][$agebracket->id]=$tallyvote->tally($candidate->id,$tallyelection,$gtallyagebrackets,$tallybrgy,
                                                                                                        $tallygenders, $tallyempstatus,$tallycivilstatus,
                                                                                                        $tallyoccstatus,$tallyvoterstatus);                                            
                                          @endphp
                                          <td>{{ $tallyabcompare[$candidate->id][$agebracket->id] }}</td>
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
        @endif
        @endif
        @if($showQuality)
    	<div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Candidate Qualities: {{ $surveyinfo->subject }}</div>                	                        	
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
            @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
            <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Candidate Qualities: {{ $surveyinfocompare->subject }}</div>                	                        	
                    </div>
                </div>
           
                    <div class="box-body">                	
                         <div id="tblqualitiescompare" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">                                
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
                                	$tallyqcompare = array();                                    
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
                                                $tallyqcompare[$candidate->id][$quality->option_id]=$tallyotherpoll->tally($candidate->id,$quality->option_id,$tallysurveycompare,$tallyagebrackets,$tallybrgy,
                                                                                                                $tallygenders, $tallyempstatus,$tallycivilstatus,
                                                                                                                $tallyoccstatus,$tallyvoterstatus);
                                            @endphp
                                            <td>{{ $tallyqcompare[$candidate->id][$quality->option_id] }}</td>
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
            @endif
            @endif
        	@if($showProblem)
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <div class="col-md-12">                      
                                <div class="box-title">Concerns Per Barangay: {{ $surveyinfo->subject }}</div>
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
            @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <div class="col-md-12">                      
                                <div class="box-title">Concerns Per Barangay: {{ $surveyinfocompare->subject }}</div>
                        </div>
                    </div>
    
                    <div class="box-body">                	
                          <div id="tblproblemcompare" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
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
                                        $tallypcompare = array();                                    
                                    @endphp
                                    @foreach($brgysurveys as $barangay)
                                        <tr>
                                            <td>{{ $barangay->name }}</td>
                                            @foreach($problems as $problem)
                                            @php
                                                $tallypcompare[$barangay->id][$problem->option_id]=$tallyotherpoll->tallyproblem($barangay->id,$problem->option_id,$tallysurveycompare,$tallyagebrackets,$tallybrgy,
                                                                                                                	$tallygenders, $tallyempstatus,$tallycivilstatus,
                                                                                                                	$tallyoccstatus,$tallyvoterstatus);
                                            @endphp
                                            <td>{{ $tallypcompare[$barangay->id][$problem->option_id] }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                          </div>
                    </div>
                </div>
            </div>  
            @endif 
            @endif          
        </div>     
           
        @if($showGraph)
        <div class="row">
    	<div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Main Chart (Summary): {{ $surveyinfo->subject }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chart"></div></div>
            </div>
        </div>
        @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
        <div class="row">
    	<div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Main Chart (Summary): {{ $surveyinfocompare->subject }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartcompare"></div></div>
            </div>
        </div>
        @elseif(!empty($rdata['hidselelection']))
        <div class="row">
    	<div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Main Chart (Summary): {{ $elections->name }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartelection"></div></div>
            </div>
        </div>
        @endif
        @if($showGender)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Chart Tally by Gender: {{ $surveyinfo->subject }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartgender"></div></div>
            </div>
        </div>
        @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Chart Tally by Gender: {{ $surveyinfocompare->subject }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartgendercompare"></div></div>
            </div>
        </div>
        @elseif(!empty($rdata['hidselelection']))
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Chart Tally by Gender: {{ $elections->name }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartgenderelection"></div></div>
            </div>
        </div>
        @endif
        @endif
        @if($showCivil)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Civil Status: {{ $surveyinfo->subject }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartcivil"></div></div>
            </div>
        </div>
        @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Civil Status: {{ $surveyinfocompare->subject }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartcivilcompare"></div></div>
            </div>
        </div>
        @elseif(!empty($rdata['hidselelection']))
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Civil Status: {{ $elections->name }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartcivilelection"></div></div>
            </div>
        </div>
        @endif
        @endif
        @if($showEmployment)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Employment Status: {{ $surveyinfo->subject }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartemp"></div></div>
            </div>
        </div>
        @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Employment Status: {{ $surveyinfocompare->subject }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartempcompare"></div></div>
            </div>
        </div>
        @elseif(!empty($rdata['hidselelection']))
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Employment Status: {{ $elections->name }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartempelection"></div></div>
            </div>
        </div>
        @endif
        @endif
        @if($showAgeBracket)
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Age Bracket: {{ $surveyinfo->subject }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartagebracket"></div></div>
            </div>
        </div>
        @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Age Bracket: {{ $surveyinfocompare->subject }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartagebracketcompare"></div></div>
            </div>
        </div>
        @elseif(!empty($rdata['hidselelection']))
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">By Age Bracket: {{ $elections->name }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartagebracketelection"></div></div>
            </div>
        </div>
        @endif
        @endif
        @if(empty($rdata['hidselelection']) && $showQuality)
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                            <div class="box-title">Candidate Qualities: {{ $surveyinfo->subject }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartqualities"></div></div>
            </div>
        </div>
        @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                            <div class="box-title">Candidate Qualities: {{ $surveyinfocompare->subject }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartqualitiescompare"></div></div>
            </div>
        </div>
    	@endif
    	@endif
        @if(empty($rdata['hidselelection']) && $showProblem)
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                            <div class="box-title">Concerns Per Barangay: {{ $surveyinfo->subject }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartproblem"></div></div>
            </div>
        </div>
        @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                            <div class="box-title">Concerns Per Barangay: {{ $surveyinfocompare->subject }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartproblemcompare"></div></div>
            </div>
        </div>
        @endif
       	@endif
    </div>
     @endif
    </div>
	 	  
    </section>
</div>
<!-- ./wrapper -->

    <!-- jQuery 2.2.3 -->
    <script src="{{ asset('vendor/adminlte') }}/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('vendor/adminlte') }}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/dist/js/adminlte.min.js"></script>
	<script src="{{ asset('js/jquery-1.12.4.js') }}"></script>
    <script src="{{ asset('js/d3.v5.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/c3.js') }}"></script>
    <script>
	$(document).ready(function ($) {
	@if($showGraph)
	Function.prototype.bind = Function.prototype.bind || function (thisp) {
		var fn = this;
		return function () {
			return fn.apply(thisp, arguments);
		};
	};
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
	  @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
	  var chartcompare = c3.generate({
		bindto: '#chartcompare',				
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
	  @elseif(!empty($rdata['hidselelection']))
	  var chartelection = c3.generate({
		bindto: '#chartelection',				
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
	  @endif
      @if($showGender)
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
	  @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
	  var chartgendercompare = c3.generate({
		bindto: '#chartgendercompare',				
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
	  @elseif(!empty($rdata['hidselelection']))
	  var chartgenderelection = c3.generate({
		bindto: '#chartgenderelection',				
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
	  @endif
	  @endif
      @if($showAgeBracket)
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
	  @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
	  var chartagebracketcompare = c3.generate({
		bindto: '#chartagebracketcompare',				
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
	  @elseif(!empty($rdata['hidselelection']))
	  var chartagebracketelection = c3.generate({
		bindto: '#chartagebracketelection',				
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
	  @endif
	  @endif
      @if($showCivil)
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
	  @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
	  var chartcivilcompare = c3.generate({
		bindto: '#chartcivilcompare',				
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
	  @elseif(!empty($rdata['hidselelection']))
	  var chartcivilelection = c3.generate({
		bindto: '#chartcivilelection',				
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
	  @endif
	  @endif
      @if($showEmployment)
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
	  @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
	  var chartempcompare = c3.generate({
		bindto: '#chartempcompare',				
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
	  @elseif(!empty($rdata['hidselelection']))
	  var chartempelection = c3.generate({
		bindto: '#chartempelection',				
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
	  @endif
	  @endif
      @if(empty($rdata['hidselelection']) && $showQuality)
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
	  @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
	  var chartqualitiescompare = c3.generate({
		bindto: '#chartqualitiescompare',				
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
	  @endif
	  @endif
      @if(empty($rdata['hidselelection']) && $showProblem)
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
	  @if(empty($rdata['hidselelection']) && $tallysurvey!=$tallysurveycompare)
	  var chartproblemcompare = c3.generate({
		bindto: '#chartproblemcompare',				
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
	  @endif
	  @endif
	 @endif
        // Ajax calls should always have the CSRF token attached to them, otherwise they won't work
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });    <!-- JavaScripts -->
	
	window.print();
	});
	</script>
</body>
</html>
