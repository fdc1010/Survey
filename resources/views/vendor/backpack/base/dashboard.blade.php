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
	<div class="row">
    	<div class="col-md-12">
        	<div class="box box-default">
            	<div class="box-header with-border">
                    <div class="col-md-12">                      
                        <div class="box-title">
                        	@php
                            	$surveypos = !empty($_REQUEST['selposition'])?$_REQUEST['selposition']:1;
                                $brgysurveys = App\Models\BarangaySurveyable::with('barangay')->get();
                                $problems = App\Models\OptionProblem::with('option')->get();
                                $voterstatuses = App\Models\VoterStatus::all();
                                $genders = App\Models\Gender::all();
                                $candidates = App\Models\Candidate::with('voter')->where('position_id',$surveypos)->get();
                                $barangays = App\Models\Barangay::all();
                                $positions = App\Models\PositionCandidate::all();  
                                $qualities = App\Models\OptionPosition::with('options','positions')
                                                                        ->where('position_id',$surveypos)->get();                  
                            @endphp
                        	<form method="post" id="my_form" action="{{ backpack_url('postdashboard') }}">                    	
                            	@csrf
                            	<div class="col-md-12">	
                                	<div class="col-md-4">	
                                        <select id="selposition" name="selposition">                                    
                                                @foreach($positions as $position)
                                                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select id="selvoterstatuses" name="selvoterstatuses[]" multiple="multiple">                                  
                                            @foreach($voterstatuses as $voterstatus)
                                                <option value="{{ $voterstatus->id }}">{{ $voterstatus->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <a class="btn btn-primary" onclick="document.getElementById('my_form').submit();">
                                            <span class="fa fa-search"></span> View
                                        </a>
                                    </div>
                                </div>
                            </form>
                            @if(!empty($vstatus))
                            	{{ $vstatus }}
                            @endif
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    	<div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Tabular Stats</div>
                    </div>
                </div>

                <div class="box-body">                	
                      <div id="tblvotes" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
            					<thead>
                                	<tr>
                                    	<th>Cadidate</th>
                                        <th>Votes</th>
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
    	<div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Main Chart</div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chart"></div></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title"></div>
                    </div>
                </div>

                <div class="box-body">                	
                      <div id="tblqualities" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
                      		<table class="table table-striped table-hover display responsive" cellspacing="0">
            					<thead>
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
                      		<div class="box-title"></div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartqualities"></div></div>
            </div>
        </div>  
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title"></div>
                    </div>
                </div>

                <div class="box-body">                	
                      <div id="tblgender" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
            					<thead>
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
    	<div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title"></div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartgender"></div></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title"></div>
                    </div>
                </div>

                <div class="box-body">                	
                      <div id="tblvoterstatus" class="mCustomScrollbar custom-css" data-mcs-theme="dark" style="height:320px;">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
            					<thead>
                                	<tr>
                                    	<th>Candidates</th>
                                        @foreach($voterstatuses as $voterstatus)
                                        <th>{{ $voterstatus->name }}</th>
                                        @endforeach
                                    </tr>                                    
                                </thead>
                                <tbody>
                               @php
                                	$tallyvs = array();                                    
                                @endphp
                                @foreach($candidates as $candidate)                                	
                                	<tr>
                                    	<td>{{ $candidate->voter->full_name }}</td>
                                        @foreach($voterstatuses as $voterstatus)
                                        @php
                                            $tallyvs[$candidate->id][$voterstatus->id]=rand(1,100);
                                        @endphp
                                        <td>{{ $tallyvs[$candidate->id][$voterstatus->id] }}</td>
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
                      		<div class="box-title"></div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartvoterstatus"></div></div>
            </div>
        </div> 
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title"></div>
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
                                @foreach($barangays as $barangay)
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
                      		<div class="box-title"></div>                	                        	
                    </div>
                </div>

                <div class="box-body"><div id="chartproblem"></div></div>
            </div>
        </div>
        @php
            $tallybrgy = array();
            $tallycbrgy = array();                                    
        @endphp
        @foreach($barangays as $barangay)
        	@foreach($problems as $problem)
            @php
                $tallybrgy[$barangay->id][$problem->option_id]=rand(1,100);
            @endphp
            @endforeach
            @foreach($candidates as $candidate)
            @php
                $tallycbrgy[$barangay->id][$candidate->id]=rand(1,100);
            @endphp
            @endforeach
        <div class="col-md-4">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">{{ $barangay->name }}</div>                	                        	
                    </div>
                </div>

                <div class="box-body">
                	<div class="col-md-12">
                    	<div id="chart_candidates_{{ $barangay->id }}"></div>
                    </div>
                	<div class="col-md-12">
                    	<div id="chart_{{ $barangay->id }}"></div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection
@section('chartcss')
	<link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />-->
    <link href="{{ asset('css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('css/c3.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/jquery.mCustomScrollbar.css') }}" />
@endsection
@section('chartsjs')		
	<script src="{{ asset('js/jquery-1.12.4.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
    <!--<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>-->
	<script src="{{ asset('js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ asset('js/d3.v5.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/c3.js') }}"></script>
    <script>
$(document).ready(function ($) {
	/*$('#tblvotes').slimScroll({
		height: '320px',
		opacity: 0.5,
		width: '100%',
    	axis: 'yx'
	});
	$('#tblqualities').slimScroll({
		height: '320px',
		opacity: 0.5,
		width: '100%',
    	axis: 'yx'
	});
	$('#tblgender').slimScroll({
		height: '320px',
		opacity: 0.5,
		width: '100%',
    	axis: 'yx'
	});
	$('#tblproblem').slimScroll({
		height: '320px',
		opacity: 0.5,
		width: '100%',
    	axis: 'yx'
	});*/
	$('select').select2({
		theme: "bootstrap"
	});
	$("#tblvotes").mCustomScrollbar({
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
	$("#tblvoterstatus").mCustomScrollbar({
		axis:"yx",
		scrollButtons:{enable:true},
		theme:"3d",
		scrollbarPosition:"outside"
	});	
	@php
		//$barangays = App\Models\Barangay::all();
		$i=0;	
	@endphp	
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
	  var chartvoterstatus = c3.generate({
		bindto: '#chartvoterstatus',				
        data: {
		  x: 'Candidates',
		  columns: [
		  	['Candidates', 
			@foreach($candidates as $candidate)
				'{{ $candidate->voter->full_name }}',
			@endforeach
			],
			@foreach($voterstatuses as $voterstatus)
				['{{ $voterstatus->name }}',
				@foreach($candidates as $candidate)
					{{ $tallyvs[$candidate->id][$voterstatus->id] }},
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
			@foreach($brgysurveys as $brgysurvey)
				'{{ $brgysurvey->barangay->name }}',
			@endforeach
			],
			@foreach($problems as $problem)
				['{{ $problem->option->option }}',
				@foreach($brgysurveys as $brgysurvey)
					{{ $tallyp[$brgysurvey->id][$problem->option_id] }},
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
	@foreach($barangays as $barangay)      
	  var chart_candidates_{{ $barangay->id }} = c3.generate({
		bindto: '#chart_candidates_{{ $barangay->id }}',
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
				{{ $tallycbrgy[$barangay->id][$candidate->id] }},
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
	  var chart_{{ $barangay->id }} = c3.generate({
		bindto: '#chart_{{ $barangay->id }}',
        data: {
          x: 'Problema',
		  columns: [
		  	['Problema', 
			@foreach($problems as $problem)
				'{{ $problem->option->option }}',
			@endforeach
			],
			['Tally',
			@foreach($problems as $problem)				
				{{ $tallybrgy[$barangay->id][$problem->option_id] }},
			@endforeach
				],
          ],
		  color: function (color, d) { return "#008000"; },
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
        },
		legend: {
			show: false
		}
      });
	@endforeach

});
    </script>
@endsection