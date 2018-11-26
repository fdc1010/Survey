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
    	<div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Tabular Stats</div>
                    </div>
                </div>

                <div class="box-body">                	
                      <div id="tblvotes">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
            					<thead>
                                	<tr>
                                    	<th>Cadidate</th>
                                        <th>Votes</th>
                                    </tr>                                    
                                </thead>
                                <tbody>
                                @php
                                	$barangays = App\Models\Barangay::all();
                                
                                	$surveypos = !empty($posid)?$posid:1;
                                	$tally = array();
                                    $candidates = App\Models\Candidate::with('voter')->where('position_id',$surveypos)->get();
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
                      <div id="tblqualities">
                      		<table class="table table-striped table-hover display responsive" cellspacing="0">
            					<thead>
                                	<tr>
                                    	<th>Candidates</th>
                                        @php
                                        	
                                            
                                            
                                        	$qualities = App\Models\OptionPosition::with('options','positions')
                                            									->where('position_id',$surveypos)->get();
                                        @endphp
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
                      <div id="tblgender">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
            					<thead>
                                	<tr>
                                    	<th>Candidates</th>
                                        @php
                                        	$genders = App\Models\Gender::all();
                                        @endphp
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
                      <div id="tblproblem">
                      		<table class="table table-striped table-hover display responsive nowrap" cellspacing="0">
            					<thead>
                                	<tr>
                                    	<th>Barangays</th>
                                        @php
                                        	$brgysurveys = App\Models\BarangaySurveyable::with('barangay')->get();
                                        	$problems = App\Models\OptionProblem::with('option')->get();
                                        @endphp
                                        @foreach($problems as $problem)
                                        <th>{{ $problem->option->option }}</th>
                                        @endforeach
                                    </tr>                                    
                                </thead>
                                <tbody>
                               	@php
                                	$tallyp = array();                                    
                                @endphp
                                @foreach($brgysurveys as $brgysurvey)                          	
                                	<tr>
                                    	<td>{{ $brgysurvey->barangay->name }}</td>
                                        @foreach($problems as $problem)
                                        @php
                                            $tallyp[$brgysurvey->id][$problem->option_id]=rand(1,100);
                                        @endphp
                                        <td>{{ $tallyp[$brgysurvey->id][$problem->option_id] }}</td>
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
        @endphp
        @foreach($barangays as $barangay)
        	@foreach($problems as $problem)
            @php
                $tallybrgy[$barangay->id][$problem->option_id]=rand(1,100);
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
                	<div id="chart_{{ $barangay->id }}"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection
@section('chartcss')
	<link href="{{ asset('css/c3.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('chartsjs')	
	<script src="{{ asset('js/jquery-1.12.4.js') }}"></script>
	<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('js/d3.v5.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/c3.js') }}"></script>
    <script>
$(document).ready(function ($) {
	$('#tblvotes').slimScroll({
		height: '320px',
		opacity: 0.5,
		width: '100%',
    	axis: 'both'
	});
	$('#tblqualities').slimScroll({
		height: '320px',
		opacity: 0.5,
		width: '100%',
    	axis: 'both'
	});
	$('#tblgender').slimScroll({
		height: '320px',
		opacity: 0.5,
		width: '100%',
    	axis: 'both'
	});
	$('#tblproblem').slimScroll({
		height: '320px',
		opacity: 0.5,
		width: '100%',
    	axis: 'both'
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