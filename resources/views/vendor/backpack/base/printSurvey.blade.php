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
			display:block ! important;
			page-break-inside:avoid ! important;
			/*page-break-before:always !important;*/
		}
    </style>
</head>
<body class="hold-transition {{ config('backpack.base.skin') }}">
    <!-- Site wrapper -->
    <div class="wrapper">
        <section class="content">

        @php
	$showVotesBrgy = true;
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

        $tallyvote = new App\Models\ElectionReturn;
    	$tallypoll = new App\Models\TallyVote;
        $tallyotherpoll = new App\Models\TallyOtherVote;

    	$tallysurvey = (!empty($rdata['hidselsurvey']))?$rdata['hidselsurvey']:2;
        $tallyelection = (!empty($rdata['hidselelection']))?$rdata['hidselelection']:0;

        $selinitgenders = App\Models\Gender::all();
        $selinitagebrackets = App\Models\AgeBracket::all();
        $selinitcivilstatuses = App\Models\CivilStatus::all();
        $selinitempstatuses = App\Models\EmploymentStatus::all();
        $problems = App\Models\OptionProblem::with('option')->get();
        $selinitelections = App\Models\Election::all();
        $selinitsurveydetails = App\Models\SurveyDetail::all();

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

        $brgyarr = !empty($rdata['hidto'])?$rdata['hidto']:$rdata['hidbrgyrand'];
        $brgysurveys = App\Models\Barangay::whereIn('id',$brgyarr)->get();

        $selinitpositions = App\Models\PositionCandidate::with('candidates')->get();
        if(!empty($rdata['hidsurvey'])){
        	$rdatahidsurvey = $rdata['hidsurvey'];
            foreach($rdatahidsurvey as $key => $rsurvey){
                if(empty($rsurvey)){
                    unset($rdatahidsurvey[$key]);
                }
            }
            if(!empty($rdatahidsurvey)){
                $surveydetails = App\Models\SurveyDetail::whereIn('id',$rdatahidsurvey)->get();
            }else{
                $surveydetails = App\Models\SurveyDetail::where('id',$tallysurvey)->get();
            }
        }else{
            $surveydetails = App\Models\SurveyDetail::where('id',$tallysurvey)->get();
        }
        if(!empty($rdata['hidelectionreturn'])){
        	$rdatahidelection = $rdata['hidelectionreturn'];
            foreach($rdatahidelection as $key => $relection){
                if(empty($relection)){
                    unset($rdatahidelection[$key]);
                }
            }
            if(!empty($rdatahidelection)){
        		$elections = App\Models\Election::whereIn('id',$rdatahidelection)->get();
            }else{
        		$elections = App\Models\Election::where('id',$tallyelection)->get();
            }
        }else{
            $elections = App\Models\Election::where('id',$tallyelection)->get();
        }

		if(!empty($rdata['hidto'])){
        	$tallybrgy=$rdata['hidto'];
        }
        if(!empty($rdata['hidposition'])){
        	$selinitcandidates = App\Models\Candidate::with('voter')->whereIn('position_id',$rdata['hidposition'])->get();
        }else{
        	$selinitcandidates = App\Models\Candidate::with('voter')->where('position_id',$surveypos)->get();
        }

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
        $tally = array();
        $tallyg = array();
        $tallycv = array();
        $tallyemp = array();
        $tallyab = array();
        $tallyq = array();
        $tallyp = array();

        $tallyelection = array();
        $tallygelection = array();
        $tallycvelection = array();
        $tallyempelection = array();
        $tallyabelection = array();
    @endphp
    @foreach($surveydetails as $surveydetail)
    	<div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">Tabular Stats (Summary): {{ $surveydetail->subject }}</div>
                    </div>
                </div>

                <div class="box-body">
                      <div id="tblvotes">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                            <thead>
                                  <tr>
                                      <th>Cadidates</th>
                                      <th>Tally</th>
                                  </tr>
                              </thead>
                            @foreach($positions as $position)
                                @php
                                  $i = 0;
                                  $tallytotalcandidate = 0;
                                @endphp
                                <thead>
                                    <tr>
                                        <th>{{ $position->name }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($position->candidates as $candidate)
                                    @php
                                        $tallycandidate[$candidate->id] = $candidate->full_name;
                                        $tally[$position->id][$candidate->id][$surveydetail->id]=$tallypoll->tally($candidate->id,$surveydetail->id,$tallyagebrackets,$tallybrgy,
                                                                                                                  $tallygenders, $tallyempstatus,$tallycivilstatus,
                                                                                                                  $tallyoccstatus,$tallyvoterstatus);
                                        $tallytotalcandidate += $tally[$position->id][$candidate->id][$surveydetail->id];
                                    @endphp
                                @endforeach
                                @php
                                arsort($tally[$position->id]);
                                @endphp
                                @foreach($tally[$position->id] as $key => $sortedtally)
                                    <tr>
                                        <td>{{ ++$i . ".) " . $tallycandidate[$key] }}</td>
                                        <td>{{ $sortedtally[$surveydetail->id] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <thead>
                                <tr>
                                    <th>Total:</th>
                                    <th>{{ $tallytotalcandidate }}</th>
                                </tr>
                              </thead>
                              @endforeach
                            </table>
                      </div>
                </div>
            </div>
        </div>
        @endforeach
        @if($showGender)
        @foreach($surveydetails as $surveydetail)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">Tabular Tally by Gender: {{ $surveydetail->subject }}</div>
                    </div>
                </div>

                <div class="box-body">
                      <div id="tblgender">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                            <thead>
                               <tr>
                                   <th>Cadidates</th>
                                   @foreach($genders as $gender)
                                   <th>{{ $gender->name }}</th>
                                   @endforeach
                                   <th>Total</th>
                               </tr>
                           </thead>
                           @foreach($positions as $position)
                             <thead>
                                 <tr>
                                     <th>{{ $position->name }}</th>
                                     @foreach($genders as $gender)
                                     <th></th>
                                     @endforeach
                                     <th></th>
                                 </tr>
                             </thead>
                           <tbody>
                           @php
                           $i = 0;
                           $tallytotalogcandidate = 0;
                           @endphp
                            @foreach($position->candidates as $candidate)
                               @foreach($genders as $gender)
                                   @php
                                     $tallyg[$position->id][$candidate->id][$gender->id][$surveydetail->id]=$tallypoll->tallydetails($candidate->id,$surveydetail->id,[],0,0,0,0,0,$gender->id);
                                   @endphp
                               @endforeach
                             @endforeach
                             @php
                             arsort($tallyg[$position->id]);
                             @endphp
                             @foreach($tallyg[$position->id] as $key => $sortedtallyg)
                             <tr>
                                  <td>{{ ++$i . ".) " . $tallycandidate[$key] }}</td>
                                  @php
                                  $tallytotalgcandidate = 0;
                                  @endphp
                                  @foreach($genders as $gender)
                                     @php
                                         if(empty($tallytotalvgcandidate[$position->id][$gender->id][$surveydetail->id])){
                                             $tallytotalvgcandidate[$position->id][$gender->id][$surveydetail->id] = $sortedtallyg[$gender->id][$surveydetail->id];
                                         }else{
                                             $tallytotalvgcandidate[$position->id][$gender->id][$surveydetail->id] += $sortedtallyg[$gender->id][$surveydetail->id];
                                         }
                                         $tallytotalgcandidate += $sortedtallyg[$gender->id][$surveydetail->id];
                                     @endphp
                                    <td>{{ $sortedtallyg[$gender->id][$surveydetail->id] }}</td>
                                  @endforeach
                                  <th>{{ $tallytotalgcandidate }}</th>
                             </tr>
                             @php
                             $tallytotalogcandidate += $tallytotalgcandidate;
                             @endphp
                             @endforeach
                             </tbody>
                           @if($tallytotalogcandidate>0)
                           <thead>
                           <tr>
                                 <th>Total:</td>
                                 @foreach($genders as $gender)
                                   <th>{{ $tallytotalvgcandidate[$position->id][$gender->id][$surveydetail->id] }}</th>
                                 @endforeach
                                 <th>{{ $tallytotalogcandidate }}</th>
                             </tr>
                             </thead>
                           @endif
                         @endforeach
                                  </table>
                      </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif

         @if($showCivil)
         @foreach($surveydetails as $surveydetail)
        	<div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">By Civil Status: {{ $surveydetail->subject }}</div>
                    </div>
                </div>

                <div class="box-body">
                      <div id="tblcivilstatus">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                            <thead>
                                <tr>
                                  <th>Candidates</th>
                                    @foreach($civilstatuses as $civilstatus)
                                    <th>{{ $civilstatus->name }}</th>
                                    @endforeach
                                    <th>Total</th>
                                </tr>
                            </thead>
                            @foreach($positions as $position)
                              <thead>
                                  <tr>
                                      <th>{{ $position->name }}</th>
                                      @foreach($civilstatuses as $civilstatus)
                                      <th></th>
                                      @endforeach
                                      <th></th>
                                  </tr>
                              </thead>
                              <tbody>
                              @php
                                $i = 0;
                                $tallytotaloccandidate = 0;
                              @endphp
                              @foreach($position->candidates as $candidate)
                                 @foreach($civilstatuses as $civilstatus)
                                     @php
                                       $tallycv[$position->id][$candidate->id][$civilstatus->id][$surveydetail->id]=$tallypoll->tallydetails($candidate->id,$surveydetail->id,[],0,$civilstatus->id,0,0,0,0);
                                     @endphp
                                 @endforeach
                               @endforeach
                               @php
                               arsort($tallycv[$position->id]);
                               @endphp
                               @foreach($tallycv[$position->id] as $key => $sortedtallycv)
                               <tr>
                                    <td>{{ ++$i . ".) " . $tallycandidate[$key] }}</td>
                                    @php
                                    $tallytotalccandidate = 0;
                                    @endphp
                                    @foreach($civilstatuses as $civilstatus)
                                       @php
                                           if(empty($tallytotalvccandidate[$position->id][$civilstatus->id][$surveydetail->id])){
                                               $tallytotalvccandidate[$position->id][$civilstatus->id][$surveydetail->id] = $sortedtallycv[$civilstatus->id][$surveydetail->id];
                                           }else{
                                               $tallytotalvccandidate[$position->id][$civilstatus->id][$surveydetail->id] += $sortedtallycv[$civilstatus->id][$surveydetail->id];
                                           }
                                           $tallytotalccandidate += $sortedtallycv[$civilstatus->id][$surveydetail->id];
                                       @endphp
                                      <td>{{ $sortedtallycv[$civilstatus->id][$surveydetail->id] }}</td>
                                    @endforeach
                                    <th>{{ $tallytotalccandidate }}</th>
                               </tr>
                               @php
                               $tallytotaloccandidate += $tallytotalccandidate;
                               @endphp
                               @endforeach
                               </tbody>
                             @if($tallytotaloccandidate>0)
                             <thead>
                             <tr>
                                   <th>Total:</td>
                                   @foreach($civilstatuses as $civilstatus)
                                     <th>{{ $tallytotalvccandidate[$position->id][$civilstatus->id][$surveydetail->id] }}</th>
                                   @endforeach
                                   <th>{{ $tallytotaloccandidate }}</th>
                               </tr>
                               </thead>
                             @endif
                           @endforeach
                                </table>
                      </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif

        @if($showEmployment)
        @foreach($surveydetails as $surveydetail)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">By Employment Status: {{ $surveydetail->subject }}</div>
                    </div>
                </div>

                <div class="box-body">
                      <div id="tblempstatus">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                            <thead>
                              <th>Candidates</th>
                                  @foreach($empstatuses as $empstatus)
                                  <th>{{ $empstatus->name }}</th>
                                  @endforeach
                                  <th>Total</th>
                              </tr>
                            </thead>
                            <tbody>
                            @foreach($positions as $position)
                              <thead>
                                  <tr>
                                      <th>{{ $position->name }}</th>
                                      @foreach($empstatuses as $empstatus)
                                      <th></th>
                                      @endforeach
                                      <th></th>
                                  </tr>
                              </thead>
                              <tbody>
                              @php
                                $i = 0;
                                $tallytotaloecandidate = 0;
                              @endphp
                              @foreach($position->candidates as $candidate)
                                @foreach($empstatuses as $empstatus)
                                     @php
                                       $tallyemp[$position->id][$candidate->id][$empstatus->id][$surveydetail->id]=$tallypoll->tallydetails($candidate->id,$surveydetail->id,[],0,0,$empstatus->id,0,0,0);
                                     @endphp
                                @endforeach
                               @endforeach
                               @php
                               arsort($tallyemp[$position->id]);
                               @endphp
                               @foreach($tallyemp[$position->id] as $key => $sortedtallyem)
                               <tr>
                                    <td>{{ ++$i . ".) " . $tallycandidate[$key] }}</td>
                                    @php
                                    $tallytotalecandidate = 0;
                                    @endphp
                                    @foreach($empstatuses as $empstatus)
                                       @php
                                           if(empty($tallytotalvecandidate[$position->id][$empstatus->id][$surveydetail->id])){
                                               $tallytotalvecandidate[$position->id][$empstatus->id][$surveydetail->id] = $sortedtallyem[$empstatus->id][$surveydetail->id];
                                           }else{
                                               $tallytotalvecandidate[$position->id][$empstatus->id][$surveydetail->id] += $sortedtallyem[$empstatus->id][$surveydetail->id];
                                           }
                                           $tallytotalecandidate += $sortedtallyem[$empstatus->id][$surveydetail->id];
                                       @endphp
                                      <td>{{ $sortedtallyem[$empstatus->id][$surveydetail->id] }}</td>
                                    @endforeach
                                    <th>{{ $tallytotalecandidate }}</th>
                               </tr>
                               @php
                                 $tallytotaloecandidate += $tallytotalecandidate;
                               @endphp
                               @endforeach
                               </tbody>
                               @if($tallytotaloecandidate>0)
                               <thead>
                               <tr>
                                     <th>Total:</td>
                                     @foreach($empstatuses as $empstatus)
                                       <th>{{ $tallytotalvecandidate[$position->id][$empstatus->id][$surveydetail->id] }}</th>
                                     @endforeach
                                     <th>{{ $tallytotaloecandidate }}</th>
                                 </tr>
                                 </thead>
                               @endif
                             @endforeach
                                  </table>
                      </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif

        @if($showAgeBracket)
        @foreach($surveydetails as $surveydetail)
    	<div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">By Age Bracket: {{ $surveydetail->subject }}</div>
                    </div>
                </div>

                <div class="box-body">
                      <div id="tblagebracket">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                            <thead>
                              <tr>
                                  <th>Candidates</th>
                                  @foreach($agebrackets as $agebracket)
                                  <th>{{ $agebracket->title }}</th>
                                  @endforeach
                                  <th>Total</th>
                              </tr>
                            </thead>
                            <tbody>
                            @foreach($positions as $position)
                                  <thead>
                                      <tr>
                                          <th>{{ $position->name }}</th>
                                          @foreach($agebrackets as $agebracket)
                                          <th></th>
                                          @endforeach
                                          <th></th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                  @php
                                    $i = 0;
                                    $tallytotaloacandidate = 0;
                                  @endphp
                                  @foreach($position->candidates as $candidate)
                                        @foreach($agebrackets as $agebracket)
                                            @php
                                                $gtallyagebrackets=[];
                                                for($tallyiage = $agebracket->from; $tallyiage<=$agebracket->to; $tallyiage++){
                                                    array_push($gtallyagebrackets,$tallyiage);
                                                }
                                                $tallyab[$position->id][$candidate->id][$agebracket->id][$surveydetail->id]=$tallypoll->tallydetails($candidate->id,$surveydetail->id,$gtallyagebrackets,0,0,0,0,0,0);
                                            @endphp
                                        @endforeach
                                   @endforeach
                                   @php
                                   arsort($tallyab[$position->id]);
                                   @endphp
                                   @foreach($tallyab[$position->id] as $key => $sortedtallyab)
                                   <tr>
                                        <td>{{ ++$i . ".) " . $tallycandidate[$key] }}</td>
                                        @php
                                        $tallytotalacandidate = 0;
                                        @endphp
                                        @foreach($agebrackets as $agebracket)
                                           @php
                                               if(empty($tallytotalvacandidate[$position->id][$agebracket->id][$surveydetail->id])){
                                                   $tallytotalvacandidate[$position->id][$agebracket->id][$surveydetail->id] = $sortedtallyab[$agebracket->id][$surveydetail->id];
                                               }else{
                                                   $tallytotalvacandidate[$position->id][$agebracket->id][$surveydetail->id] += $sortedtallyab[$agebracket->id][$surveydetail->id];
                                               }
                                               $tallytotalacandidate += $sortedtallyab[$agebracket->id][$surveydetail->id];
                                           @endphp
                                          <td>{{ $sortedtallyab[$agebracket->id][$surveydetail->id] }}</td>
                                        @endforeach
                                        <th>{{ $tallytotalacandidate }}</th>
                                   </tr>
                                   @php
                                     $tallytotaloacandidate += $tallytotalacandidate;
                                   @endphp
                                   @endforeach
                                   </tbody>
                                   @if($tallytotaloacandidate>0)
                                   <thead>
                                   <tr>
                                       <th>Total:</td>
                                       @foreach($agebrackets as $agebracket)
                                         <th>{{ $tallytotalvacandidate[$position->id][$agebracket->id][$surveydetail->id] }}</th>
                                       @endforeach
                                       <th>{{ $tallytotaloacandidate }}</th>
                                   </tr>
                                   </thead>
                                   @endif
                                 @endforeach
                                </table>
                      </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif

        @if($showQuality)
        @foreach($surveydetails as $surveydetail)
    	<div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">Candidate Qualities: {{ $surveydetail->subject }}</div>
                    </div>
                </div>

                    <div class="box-body">
                          <div id="tblqualities">
                            <table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                              <thead>
                                  </tr>
                                    <th>Candidates</th>
                                     @foreach($qualities as $quality)
                                     <th>{{ $quality->options->option }}</th>
                                     @endforeach
                                     <th>Total</th>
                                 </tr>
                             </thead>
                             <tbody>
                             @foreach($positions as $position)
                               <thead>
                                   <tr>
                                       <th>{{ $position->name }}</th>
                                       @foreach($qualities as $quality)
                                       <th></th>
                                       @endforeach
                                       <th></th>
                                   </tr>
                               </thead>
                               <tbody>
                                 @php
                                   $i = 0;
                                   $tallytotaloqcandidate = 0;
                                 @endphp
                                 @foreach($position->candidates as $candidate)
                                     @foreach($qualities as $quality)
                                         @php
                                             $tallyq[$position->id][$candidate->id][$quality->option_id][$surveydetail->id]=$tallyotherpoll->tallydetails($candidate->id,$surveydetail->id,$quality->option_id,[],0,0,0,0,0,0);
                                         @endphp
                                     @endforeach
                                 @endforeach
                                 @php
                                 arsort($tallyq[$position->id]);
                                 @endphp
                                 @foreach($tallyq[$position->id] as $key => $sortedtallyq)
                                 <tr>
                                      <td>{{ ++$i . ".) " . $tallycandidate[$key] }}</td>
                                      @php
                                      $tallytotalqcandidate = 0;
                                      @endphp
                                      @foreach($qualities as $quality)
                                         @php
                                             if(empty($tallytotalvqcandidate[$position->id][$quality->option_id][$surveydetail->id])){
                                                 $tallytotalvqcandidate[$position->id][$quality->option_id][$surveydetail->id] = $sortedtallyq[$quality->option_id][$surveydetail->id];
                                             }else{
                                                 $tallytotalvqcandidate[$position->id][$quality->option_id][$surveydetail->id] += $sortedtallyq[$quality->option_id][$surveydetail->id];
                                             }
                                             $tallytotalqcandidate += $sortedtallyq[$quality->option_id][$surveydetail->id];
                                         @endphp
                                        <td>{{ $sortedtallyq[$quality->option_id][$surveydetail->id] }}</td>
                                      @endforeach
                                      <th>{{ $tallytotalqcandidate }}</th>
                                 </tr>
                                 @php
                                   $tallytotaloqcandidate += $tallytotalqcandidate;
                                 @endphp
                                 @endforeach
                                 </tbody>
                                 @if($tallytotaloqcandidate>0)
                                 <thead>
                                 <tr>
                                     <th>Total:</td>
                                     @foreach($qualities as $quality)
                                       <th>{{ $tallytotalvqcandidate[$position->id][$quality->option_id][$surveydetail->id] }}</th>
                                     @endforeach
                                     <th>{{ $tallytotaloqcandidate }}</th>
                                 </tr>
                                 </thead>
                                 @endif
                             @endforeach
                                    </table>
                          </div>
                    </div>
                </div>
            </div>
            @endforeach
        	@endif

          @foreach($surveydetails as $surveydetail)
          <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <div class="col-md-12">
                                <div class="box-title">Votes Per Barangay: {{ $surveydetail->subject }}</div>
                      </div>
                  </div>

                  <div class="box-body">
                          <div id="tblvotesbrgy">
                                <table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                                  <thead>
                                     <tr>
                                      <th>Candidates</th>
                                           @foreach($brgysurveys as $barangay)
                                           <th>{{ $barangay->name }}</th>
                                           @endforeach
                                           <th>Total</th>
                                     </tr>
                                 </thead>
                                 @foreach($positions as $position)
                                   <thead>
                                       <tr>
                                           <th>{{ $position->name }}</th>
                                           @foreach($brgysurveys as $barangay)
                                           <th></th>
                                           @endforeach
                                           <th></th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                     @php
                                       $i = 0;
                                       $tallytotalovbcandidate = 0;
                                     @endphp
                                     @foreach($position->candidates as $candidate)
                                        @foreach($brgysurveys as $barangay)
                                           @php
                                               $tallyvb[$position->id][$candidate->id][$barangay->id][$surveydetail->id]=$tallypoll->tallydetails($candidate->id,$surveydetail->id,[],$barangay->id,0,0,0,0);
                                           @endphp
                                        @endforeach
                                     @endforeach
                                     @php
                                     arsort($tallyvb[$position->id]);
                                     @endphp
                                     @foreach($tallyvb[$position->id] as $key => $sortedtallyvb)
                                     <tr>
                                          <td>{{ ++$i . ".) " . $tallycandidate[$key] }}</td>
                                          @php
                                          $tallytotalvbcandidate = 0;
                                          @endphp
                                          @foreach($brgysurveys as $barangay)
                                             @php
                                                 if(empty($tallytotalvvbcandidate[$position->id][$barangay->id][$surveydetail->id])){
                                                     $tallytotalvvbcandidate[$position->id][$barangay->id][$surveydetail->id] = $sortedtallyvb[$barangay->id][$surveydetail->id];
                                                 }else{
                                                     $tallytotalvvbcandidate[$position->id][$barangay->id][$surveydetail->id] += $sortedtallyvb[$barangay->id][$surveydetail->id];
                                                 }
                                                 $tallytotalvbcandidate += $sortedtallyvb[$barangay->id][$surveydetail->id];
                                             @endphp
                                            <td>{{ $sortedtallyvb[$barangay->id][$surveydetail->id] }}</td>
                                          @endforeach
                                          <th>{{ $tallytotalvbcandidate }}</th>
                                     </tr>
                                     @php
                                       $tallytotalovbcandidate += $tallytotalvbcandidate;
                                     @endphp
                                     @endforeach
                                     </tbody>
                                     @if($tallytotalovbcandidate>0)
                                     <thead>
                                     <tr>
                                         <th>Total:</td>
                                         @foreach($brgysurveys as $barangay)
                                           <th>{{ $tallytotalvvbcandidate[$position->id][$barangay->id][$surveydetail->id] }}</th>
                                         @endforeach
                                         <th>{{ $tallytotalovbcandidate }}</th>
                                     </tr>
                                     </thead>
                                     @endif<div class="box-body">
                      <div id="tblagebracket">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                                 @endforeach
                             </table>
                        </div>
                  </div>
              </div>
          </div>
      @endforeach

        	@if($showProblem)
            @foreach($surveydetails as $surveydetail)
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <div class="col-md-12">
                                <div class="box-title">Concerns Per Barangay: {{ $surveydetail->subject }}</div>
                        </div>
                    </div>

                    <div class="box-body">
                          <div id="tblproblem">
                                <table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                                  <thead>
                                       <tr>
                                           <th>Barangays</th>
                                           @foreach($problems as $problem)
                                           <th>{{ $problem->option->option }}</th>
                                           @endforeach
                                           <th>Total</th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                   @php
                                     $i = 0;
                                     $tallytotalopbarangay = 0;
                                   @endphp
                                   @foreach($brgysurveys as $barangay)
                                       @php
                                       $tallybarangay[$barangay->id] = $barangay->name;
                                       @endphp
                                       @foreach($problems as $problem)
                                           @php
                                               $tallyp[$barangay->id][$problem->option_id][$surveydetail->id]=$tallyotherpoll->tallyproblem($barangay->id,$problem->option_id,$surveydetail->id,$tallyagebrackets,$tallybrgy,
                                                                                                                 $tallygenders, $tallyempstatus,$tallycivilstatus,
                                                                                                                 $tallyoccstatus,$tallyvoterstatus);
                                           @endphp
                                       @endforeach
                                   @endforeach
                                   @php
                                   arsort($tallyp);
                                   @endphp
                                   @foreach($tallyp as $key => $sortedtallyp)
                                       <tr>
                                           <td>{{ ++$i . ".) " . $tallybarangay[$key] }}</td>
                                           @php
                                           $tallytotalpbarangay = 0;
                                           @endphp
                                           @foreach($problems as $problem)
                                               @php
                                                   if(empty($tallytotalvpbarangay[$problem->option_id][$surveydetail->id])){
                                                       $tallytotalvpbarangay[$problem->option_id][$surveydetail->id] = $sortedtallyp[$problem->option_id][$surveydetail->id];
                                                   }else{
                                                       $tallytotalvpbarangay[$problem->option_id][$surveydetail->id] += $sortedtallyp[$problem->option_id][$surveydetail->id];
                                                   }
                                                   $tallytotalpbarangay += $sortedtallyp[$problem->option_id][$surveydetail->id];
                                               @endphp
                                              <td>{{ $sortedtallyp[$problem->option_id][$surveydetail->id] }}</td>
                                            @endforeach
                                            <th>{{ $tallytotalpbarangay }}</th>
                                       </tr>
                                   @php
                                     $tallytotalopbarangay += $tallytotalpbarangay;
                                   @endphp
                                   @endforeach
                                   </tbody>
                                   @if($tallytotalopbarangay>0)
                                   <thead>
                                   <tr>
                                       <th>Total:</td>
                                       @foreach($problems as $problem)
                                         <th>{{ $tallytotalvpbarangay[$problem->option_id][$surveydetail->id] }}</th>
                                       @endforeach
                                       <th>{{ $tallytotalopbarangay }}</th>
                                   </tr>
                                   </thead>
                                   @endif
                               </table>
                          </div>
                    </div>
                </div>
            </div>
        @endforeach
        @endif

        @foreach($elections as $election)
    	<div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">Tabular Stats (Summary): {{ $election->name }}</div>
                    </div>
                </div>
                <div class="box-body">
                      <div id="tblvoteselection">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                            <thead>
                                  <tr>
                                      <th>Cadidates</th>
                                      <th>Tally</th>
                                  </tr>
                              </thead>
                            @foreach($positions as $position)
                                @php
                                  $i = 0;
                                  $tallytotalcandidateelection = 0;
                                @endphp
                                <thead>
                                    <tr>
                                        <th>{{ $position->name }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($position->candidates as $candidate)
                                    @php
                                        $tallycandidateelection[$candidate->id] = $candidate->full_name;
                                        $tallyelection[$position->id][$candidate->id][$election->id]=$tallypoll->tally($candidate->id,$election->id,$tallyagebrackets,$tallybrgy,
                                                                                                                        $tallygenders, $tallyempstatus,$tallycivilstatus,
                                                                                                                        $tallyoccstatus,$tallyvoterstatus);
                                        $tallytotalcandidateelection += $tallyelection[$position->id][$candidate->id][$election->id];
                                    @endphp
                                @endforeach
                                @php
                                arsort($tallyelection[$position->id]);
                                @endphp
                                @foreach($tallyelection[$position->id] as $key => $sortedtallyelection)
                                    <tr>
                                        <td>{{ ++$i . ".) " . $tallycandidateelection[$key] }}</td>
                                        <td>{{ $sortedtallyelection[$election->id] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <thead>
                                <tr>
                                    <th>Total:</th>
                                    <th>{{ $tallytotalcandidateelection }}</th>
                                </tr>
                              </thead>
                              @endforeach
                          </table>
                      </div>
                </div>
            </div>
        </div>
        @endforeach
        @if($showGender)
        @foreach($elections as $election)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">Tabular Tally by Gender: {{ $election->name }}</div>
                    </div>
                </div>

                <div class="box-body">
                      <div id="tblgenderelection">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                            <thead>
                               <tr>
                                   <th>Cadidates</th>
                                   @foreach($genders as $gender)
                                   <th>{{ $gender->name }}</th>
                                   @endforeach
                                   <th>Total</th>
                               </tr>
                           </thead>
                           @foreach($positions as $position)
                             <thead>
                                 <tr>
                                     <th>{{ $position->name }}</th>
                                     @foreach($genders as $gender)
                                     <th></th>
                                     @endforeach
                                     <th></th>
                                 </tr>
                             </thead>
                           <tbody>
                           @php
                           $i = 0;
                           $tallytotalogcandidateelection = 0;
                           @endphp
                            @foreach($position->candidates as $candidate)
                               @foreach($genders as $gender)
                                   @php
                                     $tallygelection[$position->id][$candidate->id][$gender->id][$election->id]=$tallypoll->tallydetails($candidate->id,$election->id,[],0,0,0,0,0,$gender->id);
                                   @endphp
                               @endforeach
                             @endforeach
                             @php
                             arsort($tallygelection[$position->id]);
                             @endphp
                             @foreach($tallygelection[$position->id] as $key => $sortedtallygelection)
                             <tr>
                                  <td>{{ ++$i . ".) " . $tallycandidateelection[$key] }}</td>
                                  @php
                                  $tallytotalgcandidateelection = 0;
                                  @endphp
                                  @foreach($genders as $gender)
                                     @php
                                         if(empty($tallytotalvgcandidateelection[$position->id][$gender->id][$election->id])){
                                             $tallytotalvgcandidateelection[$position->id][$gender->id][$election->id] = $sortedtallygelection[$gender->id][$election->id];
                                         }else{
                                             $tallytotalvgcandidateelection[$position->id][$gender->id][$election->id] += $sortedtallygelection[$gender->id][$election->id];
                                         }
                                         $tallytotalgcandidateelection += $sortedtallygelection[$gender->id][$election->id];
                                     @endphp
                                    <td>{{ $sortedtallygelection[$gender->id][$election->id] }}</td>
                                  @endforeach
                                  <th>{{ $tallytotalgcandidateelection }}</th>
                             </tr>
                             @php
                             $tallytotalogcandidateelection += $tallytotalgcandidateelection;
                             @endphp
                             @endforeach
                             </tbody>
                           @if($tallytotalogcandidateelection>0)
                           <thead>
                           <tr>
                                 <th>Total:</td>
                                 @foreach($genders as $gender)
                                   <th>{{ $tallytotalvgcandidateelection[$position->id][$gender->id][$election->id] }}</th>
                                 @endforeach
                                 <th>{{ $tallytotalogcandidateelection }}</th>
                             </tr>
                             </thead>
                           @endif
                         @endforeach
                                  </table>
                      </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif

         @if($showCivil)
         @foreach($elections as $election)
        	<div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">By Civil Status: {{ $election->name }}</div>
                    </div>
                </div>

                <div class="box-body">
                      <div id="tblcivilstatuselection">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                            <thead>
                                <tr>
                                  <th>Candidates</th>
                                    @foreach($civilstatuses as $civilstatus)
                                    <th>{{ $civilstatus->name }}</th>
                                    @endforeach
                                    <th>Total</th>
                                </tr>
                            </thead>
                            @foreach($positions as $position)
                              <thead>
                                  <tr>
                                      <th>{{ $position->name }}</th>
                                      @foreach($civilstatuses as $civilstatus)
                                      <th></th>
                                      @endforeach
                                      <th></th>
                                  </tr>
                              </thead>
                              <tbody>
                              @php
                                $i = 0;
                                $tallytotaloccandidateelection = 0;
                              @endphp
                              @foreach($position->candidates as $candidate)
                                 @foreach($civilstatuses as $civilstatus)
                                     @php
                                       $tallycvelection[$position->id][$candidate->id][$civilstatus->id][$election->id]=$tallypoll->tallydetails($candidate->id,$election->id,[],0,$civilstatus->id,0,0,0,0);
                                     @endphp
                                 @endforeach
                               @endforeach
                               @php
                               arsort($tallycvelection[$position->id]);
                               @endphp
                               @foreach($tallycvelection[$position->id] as $key => $sortedtallycvelection)
                               <tr>
                                    <td>{{ ++$i . ".) " . $tallycandidateelection[$key] }}</td>
                                    @php
                                    $tallytotalccandidateelection = 0;
                                    @endphp
                                    @foreach($civilstatuses as $civilstatus)
                                       @php
                                           if(empty($tallytotalvccandidateelection[$position->id][$civilstatus->id][$election->id])){
                                               $tallytotalvccandidateelection[$position->id][$civilstatus->id][$election->id] = $sortedtallycvelection[$civilstatus->id][$election->id];
                                           }else{
                                               $tallytotalvccandidateelection[$position->id][$civilstatus->id][$election->id] += $sortedtallycvelection[$civilstatus->id][$election->id];
                                           }
                                           $tallytotalccandidateelection += $sortedtallycvelection[$civilstatus->id][$election->id];
                                       @endphp
                                      <td>{{ $sortedtallycvelection[$civilstatus->id][$election->id] }}</td>
                                    @endforeach
                                    <th>{{ $tallytotalccandidateelection }}</th>
                               </tr>
                               @php
                               $tallytotaloccandidateelection += $tallytotalccandidateelection;
                               @endphp
                               @endforeach
                               </tbody>
                             @if($tallytotaloccandidateelection>0)
                             <thead>
                             <tr>
                                   <th>Total:</td>
                                   @foreach($civilstatuses as $civilstatus)
                                     <th>{{ $tallytotalvccandidateelection[$position->id][$civilstatus->id][$election->id] }}</th>
                                   @endforeach
                                   <th>{{ $tallytotaloccandidateelection }}</th>
                               </tr>
                               </thead>
                             @endif
                           @endforeach
                                </table>
                      </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif

        @if($showEmployment)
        @foreach($elections as $election)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">By Employment Status: {{ $election->id }}</div>
                    </div>
                </div>

                <div class="box-body">
                      <div id="tblempstatuselection">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                            <thead>
                              <th>Candidates</th>
                                  @foreach($empstatuses as $empstatus)
                                  <th>{{ $empstatus->name }}</th>
                                  @endforeach
                                  <th>Total</th>
                              </tr>
                            </thead>
                            <tbody>
                            @foreach($positions as $position)
                              <thead>
                                  <tr>
                                      <th>{{ $position->name }}</th>
                                      @foreach($empstatuses as $empstatus)
                                      <th></th>
                                      @endforeach
                                      <th></th>
                                  </tr>
                              </thead>
                              <tbody>
                              @php
                                $i = 0;
                                $tallytotaloecandidateelection = 0;
                              @endphp
                              @foreach($position->candidates as $candidate)
                                @foreach($empstatuses as $empstatus)
                                     @php
                                       $tallyempelection[$position->id][$candidate->id][$empstatus->id][$election->id]=$tallypoll->tallydetails($candidate->id,$election->id,[],0,0,$empstatus->id,0,0,0);
                                     @endphp
                                @endforeach
                               @endforeach
                               @php
                               arsort($tallyempelection[$position->id]);
                               @endphp
                               @foreach($tallyempelection[$position->id] as $key => $sortedtallyemelection)
                               <tr>
                                    <td>{{ ++$i . ".) " . $tallycandidateelection[$key] }}</td>
                                    @php
                                    $tallytotalecandidateelection = 0;
                                    @endphp
                                    @foreach($empstatuses as $empstatus)
                                       @php
                                           if(empty($tallytotalvecandidateelection[$position->id][$empstatus->id][$election->id])){
                                               $tallytotalvecandidateelection[$position->id][$empstatus->id][$election->id] = $sortedtallyemelection[$empstatus->id][$election->id];
                                           }else{
                                               $tallytotalvecandidateelection[$position->id][$empstatus->id][$election->id] += $sortedtallyemelection[$empstatus->id][$election->id];
                                           }
                                           $tallytotalecandidateelection += $sortedtallyemelection[$empstatus->id][$election->id];
                                       @endphp
                                      <td>{{ $sortedtallyemelection[$empstatus->id][$election->id] }}</td>
                                    @endforeach
                                    <th>{{ $tallytotalecandidateelection }}</th>
                               </tr>
                               @php
                                 $tallytotaloecandidateelection += $tallytotalecandidateelection;
                               @endphp
                               @endforeach
                               </tbody>
                               @if($tallytotaloecandidateelection>0)
                               <thead>
                               <tr>
                                     <th>Total:</td>
                                     @foreach($empstatuses as $empstatus)
                                       <th>{{ $tallytotalvecandidateelection[$position->id][$empstatus->id][$election->id] }}</th>
                                     @endforeach
                                     <th>{{ $tallytotaloecandidateelection }}</th>
                                 </tr>
                                 </thead>
                               @endif
                             @endforeach
                                  </table>
                      </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif

        @if($showAgeBracket)
        @foreach($elections as $election)
    	<div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">By Age Bracket: {{ $election->name }}</div>
                    </div>
                </div>

                <div class="box-body">
                      <div id="tblagebracketelection">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                            <thead>
                              <tr>
                                  <th>Candidates</th>
                                  @foreach($agebrackets as $agebracket)
                                  <th>{{ $agebracket->title }}</th>
                                  @endforeach
                                  <th>Total</th>
                              </tr>
                            </thead>
                            <tbody>
                            @foreach($positions as $position)
                                  <thead>
                                      <tr>
                                          <th>{{ $position->name }}</th>
                                          @foreach($agebrackets as $agebracket)
                                          <th></th>
                                          @endforeach
                                          <th></th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                  @php
                                    $i = 0;
                                    $tallytotaloacandidateelection = 0;
                                  @endphp
                                  @foreach($position->candidates as $candidate)
                                        @foreach($agebrackets as $agebracket)
                                            @php
                                                $gtallyagebracketselection=[];
                                                for($tallyiageelection = $agebracket->from; $tallyiageelection<=$agebracket->to; $tallyiageelection++){
                                                    array_push($gtallyagebracketselection,$tallyiageelection);
                                                }
                                                $tallyabelection[$position->id][$candidate->id][$agebracket->id][$election->id]=$tallypoll->tallydetails($candidate->id,$election->id,$gtallyagebrackets,0,0,0,0,0,0);
                                            @endphp
                                        @endforeach
                                   @endforeach
                                   @php
                                   arsort($tallyabelection[$position->id]);
                                   @endphp
                                   @foreach($tallyabelection[$position->id] as $key => $sortedtallyabelection)
                                   <tr>
                                        <td>{{ ++$i . ".) " . $tallycandidateelection[$key] }}</td>
                                        @php
                                        $tallytotalacandidateelection = 0;
                                        @endphp
                                        @foreach($agebrackets as $agebracket)
                                           @php
                                               if(empty($tallytotalvacandidateelection[$position->id][$agebracket->id][$election->id])){
                                                   $tallytotalvacandidateelection[$position->id][$agebracket->id][$election->id] = $sortedtallyabelection[$agebracket->id][$election->id];
                                               }else{
                                                   $tallytotalvacandidateelection[$position->id][$agebracket->id][$election->id] += $sortedtallyabelection[$agebracket->id][$election->id];
                                               }
                                               $tallytotalacandidateelection += $sortedtallyabelection[$agebracket->id][$election->id];
                                           @endphp
                                          <td>{{ $sortedtallyabelection[$agebracket->id][$election->id] }}</td>
                                        @endforeach
                                        <th>{{ $tallytotalacandidateelection }}</th>
                                   </tr>
                                   @php
                                     $tallytotaloacandidateelection += $tallytotalacandidateelection;
                                   @endphp
                                   @endforeach
                                   </tbody>
                                   @if($tallytotaloacandidateelection>0)
                                   <thead>
                                   <tr>
                                       <th>Total:</td>
                                       @foreach($agebrackets as $agebracket)
                                         <th>{{ $tallytotalvacandidateelection[$position->id][$agebracket->id][$election->id] }}</th>
                                       @endforeach
                                       <th>{{ $tallytotaloacandidateelection }}</th>
                                   </tr>
                                   </thead>
                                   @endif
                                 @endforeach
                                </table>
                      </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif

        @foreach($elections as $election)
        <div class="col-md-12">
              <div class="box box-default">
                  <div class="box-header with-border">
                      <div class="col-md-12">
                             <div class="box-title">Votes Per Barangay: {{ $election->name }}</div>
                     </div>
                 </div>

                 <div class="box-body">
                       <div id="tblproblemelection">
                       		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                                <thead>
                                   <tr>
                                    <th>Candidates</th>
                                         @foreach($brgysurveys as $barangay)
                                         <th>{{ $barangay->name }}</th>
                                         @endforeach
                                         <th>Total</th>
                                   </tr>
                               </thead>
                               @foreach($positions as $position)
                                 <thead>
                                     <tr>
                                         <th>{{ $position->name }}</th>
                                         @foreach($brgysurveys as $barangay)
                                         <th></th>
                                         @endforeach
                                         <th></th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                   @php
                                     $i = 0;
                                     $tallytotalovbcandidateelection = 0;
                                   @endphp
                                   @foreach($position->candidates as $candidate)
                                      @foreach($brgysurveys as $barangay)
                                         @php
                                             $tallyvbelection[$position->id][$candidate->id][$barangay->id][$election->id]=$tallypoll->tallydetails($candidate->id,$election->id,[],$barangay->id,0,0,0,0);
                                         @endphp
                                      @endforeach
                                   @endforeach
                                   @php
                                   arsort($tallyvbelection[$position->id]);
                                   @endphp
                                   @foreach($tallyvbelection[$position->id] as $key => $sortedtallyvbelection)
                                   <tr>
                                        <td>{{ ++$i . ".) " . $tallycandidateelection[$key] }}</td>
                                        @php
                                        $tallytotalvbcandidateelection = 0;
                                        @endphp
                                        @foreach($brgysurveys as $barangay)
                                           @php
                                               if(empty($tallytotalvvbcandidateelection[$position->id][$barangay->id][$election->id])){
                                                   $tallytotalvvbcandidateelection[$position->id][$barangay->id][$election->id] = $sortedtallyvbelection[$barangay->id][$election->id];
                                               }else{
                                                   $tallytotalvvbcandidateelection[$position->id][$barangay->id][$election->id] += $sortedtallyvbelection[$barangay->id][$election->id];
                                               }
                                               $tallytotalvbcandidateelection += $sortedtallyvbelection[$barangay->id][$election->id];
                                           @endphp
                                          <td>{{ $sortedtallyvbelection[$barangay->id][$election->id] }}</td>
                                        @endforeach
                                        <th>{{ $tallytotalvbcandidateelection }}</th>
                                   </tr>
                                   @php
                                     $tallytotalovbcandidateelection += $tallytotalvbcandidateelection;
                                   @endphp
                                   @endforeach
                                   </tbody>
                                   @if($tallytotalovbcandidateelection>0)
                                   <thead>
                                   <tr>
                                       <th>Total:</td>
                                       @foreach($brgysurveys as $barangay)
                                         <th>{{ $tallytotalvvbcandidateelection[$position->id][$barangay->id][$election->id] }}</th>
                                       @endforeach
                                       <th>{{ $tallytotalovbcandidateelection }}</th>
                                   </tr>
                                   </thead>
                                   @endif
                               @endforeach
                           </table>
                       </div>
                 </div>
             </div>
         </div>
     @endforeach

        @if($showGraph)
        @foreach($surveydetails as $surveydetail)
        <div class="row">
    	<div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">Main Chart (Summary): {{ $surveydetail->subject }}</div>
                    </div>
                </div>

                <div class="box-body"><div style="width:750px;" id="chart_{{ $surveydetail->id }}"></div></div>
            </div>
        </div>
        @endforeach
        @if($showGender)
        @foreach($surveydetails as $surveydetail)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">Chart Tally by Gender: {{ $surveydetail->subject }}</div>
                    </div>
                </div>

                <div class="box-body"><div style="width:750px;" id="chartgender_{{ $surveydetail->id }}"></div></div>
            </div>
        </div>
        @endforeach
        @endif
        @if($showCivil)
        @foreach($surveydetails as $surveydetail)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">By Civil Status: {{ $surveydetail->subject }}</div>
                    </div>
                </div>
                <div class="box-body"><div style="width:750px;" id="chartcivil_{{ $surveydetail->id }}"></div></div>
            </div>
        </div>
        @endforeach
        @endif
        @if($showEmployment)
        @foreach($surveydetails as $surveydetail)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">By Employment Status: {{ $surveydetail->subject }}</div>
                    </div>
                </div>
                <div class="box-body"><div style="width:750px;" id="chartemp_{{ $surveydetail->id }}"></div></div>
            </div>
        </div>
        @endforeach
        @endif
        @if($showAgeBracket)
        @foreach($surveydetails as $surveydetail)
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">By Age Bracket: {{ $surveydetail->subject }}</div>
                    </div>
                </div>
                <div class="box-body"><div style="width:750px;" id="chartagebracket_{{ $surveydetail->id }}"></div></div>
            </div>
        </div>
        @endforeach
        @endif
        @if($showQuality)
        @foreach($surveydetails as $surveydetail)
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                            <div class="box-title">Candidate Qualities: {{ $surveydetail->subject }}</div>
                    </div>
                </div>
                <div class="box-body"><div style="width:750px;" id="chartqualities_{{ $surveydetail->id }}"></div></div>
            </div>
        </div>
        @endforeach
    	@endif

      @foreach($surveydetails as $surveydetail)
      <div class="col-md-12">
          <div class="box box-default">
              <div class="box-header with-border">
                  <div class="col-md-12">
                          <div class="box-title">Votes Per Barangay: {{ $surveydetail->subject }}</div>
                  </div>
              </div>

              <div class="box-body"><div style="width:750px;" id="chartvotesbrgy_{{ $surveydetail->id }}"></div></div>
          </div>
      </div>
      @endforeach

        @if($showProblem)
        @foreach($surveydetails as $surveydetail)
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                            <div class="box-title">Concerns Per Barangay: {{ $surveydetail->subject }}</div>
                    </div>
                </div>
                <div class="box-body"><div style="width:750px;" id="chartproblem_{{ $surveydetail->id }}"></div></div>
            </div>
        </div>
        @endforeach
        @endif


        @foreach($elections as $election)
        <div class="row">
    	<div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">Main Chart (Summary): {{ $election->name }}</div>
                    </div>
                </div>

                <div class="box-body"><div style="width:750px;" id="chart_election_{{ $election->id }}"></div></div>
            </div>
        </div>
        @endforeach
        @if($showGender)
        @foreach($elections as $election)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">Chart Tally by Gender: {{ $election->name }}</div>
                    </div>
                </div>

                <div class="box-body"><div style="width:750px;" id="chartgender_election_{{ $election->id }}"></div></div>
            </div>
        </div>
        @endforeach
        @endif
        @if($showCivil)
        @foreach($elections as $election)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">By Civil Status: {{ $election->name }}</div>
                    </div>
                </div>

                <div class="box-body"><div style="width:750px;" id="chartcivil_election_{{ $election->id }}"></div></div>
            </div>
        </div>
        @endforeach
        @endif
        @if($showEmployment)
        @foreach($elections as $election)
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">By Employment Status: {{ $election->name }}</div>
                    </div>
                </div>

                <div class="box-body"><div style="width:750px;" id="chartemp_election_{{ $election->id }}"></div></div>
            </div>
        </div>
        @endforeach
        @endif
        @if($showAgeBracket)
        @foreach($elections as $election)
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                      		<div class="box-title">By Age Bracket: {{ $election->name }}</div>
                    </div>
                </div>

                <div class="box-body"><div style="width:750px;" id="chartagebracket_election_{{ $election->id }}"></div></div>
            </div>
        </div>
        @endforeach
        @endif
        @foreach($elections as $election)
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">
                            <div class="box-title">Votes Per Barangay: {{ $election->name }}</div>
                    </div>
                </div>

                <div class="box-body"><div style="width:750px;" id="chartvotesbrgy_election_{{ $election->id }}"></div></div>
            </div>
        </div>
        @endforeach
        @endif

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
  @foreach($surveydetails as $surveydetail)
	var chart = c3.generate({
		bindto: '#chart_{{ $surveydetail->id }}',
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates',
			@foreach($positions as $position)
				@foreach($tally[$position->id] as $key => $sortedtally)
					'{{ $tallycandidate[$key] }}',
				@endforeach
			@endforeach
			],
			['Votes',
			@foreach($positions as $position)
				@foreach($tally[$position->id] as $key => $sortedtally)
					{{ $sortedtally[$surveydetail->id] }},
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
	  @endforeach
      @if($showGender)
	  @foreach($surveydetails as $surveydetail)
	  var chartgender = c3.generate({
		bindto: '#chartgender_{{ $surveydetail->id }}',
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates',
			@foreach($positions as $position)
				@foreach($tallyg[$position->id] as $key => $sortedtallyg)
					'{{ $tallycandidate[$key] }}',
				@endforeach
			@endforeach
			],
			@foreach($genders as $gender)
				['{{ $gender->name }}',
				@foreach($positions as $position)
					@foreach($tallyg[$position->id] as $key => $sortedtallyg)
						{{ $sortedtallyg[$gender->id][$surveydetail->id] }},
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
	  @endforeach
	  @endif

    @if($showCivil)
	  @foreach($surveydetails as $surveydetail)
	  var chartcivil = c3.generate({
		bindto: '#chartcivil_{{ $surveydetail->id }}',
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates',
			@foreach($positions as $position)
				@foreach($tallycv[$position->id] as $key => $sortedtallycv)
					'{{ $tallycandidate[$key] }}',
				@endforeach
			@endforeach
			],
			@foreach($civilstatuses as $civilstatus)
				['{{ $civilstatus->name }}',
				@foreach($positions as $position)
					@foreach($tallycv[$position->id] as $key => $sortedtallycv)
						{{ $sortedtallycv[$civilstatus->id][$surveydetail->id] }},
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
	  @endforeach
	  @endif
      @if($showEmployment)
	  @foreach($surveydetails as $surveydetail)
	  var chartemp = c3.generate({
		bindto: '#chartemp_{{ $surveydetail->id }}',
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates',
			@foreach($positions as $position)
				@foreach($tallyemp[$position->id] as $key => $sortedtallyem)
					'{{ $tallycandidate[$key] }}',
				@endforeach
			@endforeach
			],
			@foreach($empstatuses as $empstatus)
				['{{ $empstatus->name }}',
				@foreach($positions as $position)
					@foreach($tallyemp[$position->id] as $key => $sortedtallyem)
						{{ $sortedtallyem[$empstatus->id][$surveydetail->id] }},
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
	  @endforeach
	  @endif
    @if($showAgeBracket)
	  @foreach($surveydetails as $surveydetail)
	  var chartagebracket = c3.generate({
		bindto: '#chartagebracket_{{ $surveydetail->id }}',
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates',
			@foreach($positions as $position)
				@foreach($tallyab[$position->id] as $key => $sortedtallyab)
					'{{ $tallycandidate[$key] }}',
				@endforeach
			@endforeach
			],
			@foreach($agebrackets as $agebracket)
				['{{ $agebracket->title }}',
				@foreach($positions as $position)
          @foreach($tallyab[$position->id] as $key => $sortedtallyab)
            {{ $sortedtallyab[$agebracket->id][$surveydetail->id] }},
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
	  @endforeach
	  @endif
    @if($showQuality)
	  @foreach($surveydetails as $surveydetail)
	  var chartqualities = c3.generate({
		bindto: '#chartqualities_{{ $surveydetail->id }}',
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates',
			@foreach($positions as $position)
				@foreach($tallyq[$position->id] as $key => $sortedtallyq)
					'{{ $tallycandidate[$key] }}',
				@endforeach
			@endforeach
			],
			@foreach($qualities as $quality)
				['{{ $quality->options->option }}',
				@foreach($positions as $position)
					@foreach($tallyq[$position->id] as $key => $sortedtallyq)
						{{ $sortedtallyq[$quality->option_id][$surveydetail->id] }},
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
	  @endforeach
	  @endif

	  @foreach($surveydetails as $surveydetail)
	  var chartvotesbrgy = c3.generate({
		bindto: '#chartvotesbrgy_{{ $surveydetail->id }}',
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates',
			@foreach($positions as $position)
				@foreach($tallyvb[$position->id] as $key => $sortedtallyvb)
					'{{ $tallycandidate[$key] }}',
				@endforeach
			@endforeach
			],
			@foreach($brgysurveys as $barangay)
				['{{ $barangay->name }}',
				@foreach($positions as $position)
          @foreach($tallyvb[$position->id] as $key => $sortedtallyvb)
            {{ $sortedtallyvb[$barangay->id][$surveydetail->id] }},
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
	  @endforeach

    @if($showProblem)
	  @foreach($surveydetails as $surveydetail)
	  var chartproblem = c3.generate({
		bindto: '#chartproblem_{{ $surveydetail->id }}',
        data: {
		  x: 'Barangays',
		  columns: [
		  	['Barangays',
			@foreach($tallyp as $key => $sortedtallyp)
				'{{ $tallybarangay[$key] }}',
			@endforeach
			],
			@foreach($problems as $problem)
				['{{ $problem->option->option }}',
				@foreach($tallyp as $key => $sortedtallyp)
					{{ $sortedtallyp[$problem->option_id][$surveydetail->id] }},
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
	  @endforeach
	  @endif


	  @foreach($elections as $election)
	var chart = c3.generate({
		bindto: '#chart_election_{{ $election->id }}',
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates',
			@foreach($positions as $position)
				@foreach($tallyelection[$position->id] as $key => $sortedtallyelection)
					'{{ $tallycandidateelection[$key] }}',
				@endforeach
			@endforeach
			],
			['Votes',
			@foreach($positions as $position)
				@foreach($tallyelection[$position->id] as $key => $sortedtallyelection)
					{{ $sortedtallyelection[$election->id] }},
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
	  @endforeach
      @if($showGender)
	  @foreach($elections as $election)
	  var chartgender = c3.generate({
		bindto: '#chartgender_election_{{ $election->id }}',
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates',
			@foreach($positions as $position)
				@foreach($tallygelection[$position->id] as $key => $sortedtallygelection)
					'{{ $tallycandidateelection[$key] }}',
				@endforeach
			@endforeach
			],
			@foreach($genders as $gender)
				['{{ $gender->name }}',
				@foreach($positions as $position)
					@foreach($tallygelection[$position->id] as $key => $sortedtallygelection)
						{{ $sortedtallygelection[$gender->id][$election->id] }},
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
	  @endforeach
	  @endif
      @if($showAgeBracket)
	  @foreach($elections as $election)
	  var chartagebracket = c3.generate({
		bindto: '#chartagebracket_election_{{ $election->id }}',
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates',
			@foreach($positions as $position)
				@foreach($tallyabelection[$position->id] as $key => $sortedtallyabelection)
					'{{ $tallycandidateelection[$key] }}',
				@endforeach
			@endforeach
			],
			@foreach($agebrackets as $agebracket)
				['{{ $agebracket->title }}',
				@foreach($positions as $position)
					@foreach($tallyabelection[$position->id] as $key => $sortedtallyabelection)
						{{ $sortedtallyabelection[$agebracket->id][$election->id] }},
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
	  @endforeach
	  @endif
      @if($showCivil)
	  @foreach($elections as $election)
	  var chartcivil = c3.generate({
		bindto: '#chartcivil_election_{{ $election->id }}',
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates',
			@foreach($positions as $position)
				 @foreach($tallycvelection[$position->id] as $key => $sortedtallycvelection)
					'{{ $tallycandidateelection[$key] }}',
				@endforeach
			@endforeach
			],
			@foreach($civilstatuses as $civilstatus)
				['{{ $civilstatus->name }}',
				@foreach($positions as $position)
					 @foreach($tallycvelection[$position->id] as $key => $sortedtallycvelection)
						{{ $sortedtallycvelection[$civilstatus->id][$election->id] }},
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
	  @endforeach
	  @endif
      @if($showEmployment)
	  @foreach($elections as $election)
	  var chartemp = c3.generate({
		bindto: '#chartemp_election_{{ $election->id }}',
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates',
			@foreach($positions as $position)
				@foreach($tallyempelection[$position->id] as $key => $sortedtallyemelection)
					'{{ $tallycandidateelection[$key] }}',
				@endforeach
			@endforeach
			],
			@foreach($empstatuses as $empstatus)
				['{{ $empstatus->name }}',
				@foreach($positions as $position)
					@foreach($tallyempelection[$position->id] as $key => $sortedtallyemelection)
						{{ $sortedtallyemelection[$empstatus->id][$election->id] }},
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
	  @endforeach
	  @endif

    @foreach($elections as $election)
    var chartvotesbrgy_election = c3.generate({
    bindto: '#chartvotesbrgy_election_{{ $election->id }}',
        data: {
      x: 'Candidates',
      columns: [
        ['Candidates',
      @foreach($positions as $position)
        @foreach($tallyvbelection[$position->id] as $key => $sortedtallyvbelection)
          '{{ $tallycandidateelection[$key] }}',
        @endforeach
      @endforeach
      ],
      @foreach($brgysurveys as $barangay)
        ['{{ $barangay->name }}',
        @foreach($positions as $position)
          @foreach($tallyvbelection[$position->id] as $key => $sortedtallyvbelection)
            {{ $sortedtallyvbelection[$barangay->id][$surveydetail->id] }},
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
    @endforeach
	 @endif
        // Ajax calls should always have the CSRF token attached to them, otherwise they won't work
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });    <!-- JavaScripts -->


	});
	window.print();
	</script>
</body>
</html>
