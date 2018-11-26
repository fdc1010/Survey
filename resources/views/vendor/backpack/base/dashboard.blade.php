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
                      <div id="divTabular" style="height:320px;">
                      		<table id="tabular" class="table table-striped table-hover display responsive nowrap" cellspacing="0">
            					<thead>
                                	<tr>
                                    	<th>Cadidate</th>
                                        <th>Votes</th>
                                    </tr>                                    
                                </thead>
                                <tbody>
                                @php
                                	$surveypos = !empty($posid)?$posid:1;
                                	$votes = array();
                                    $candidates = App\Models\Candidate::with('voter')->where('position_id',$surveypos)->get();
                                @endphp
                                @foreach($candidates as $candidate)
                                	@php
                                    	$votes[$candidate->id]=rand(1,100);
                                    @endphp
                                	<tr>
                                    	<td>{{ $candidate->voter->full_name }}</td>
                                        <td>{{ $votes[$candidate->id] }}</td>
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
                      <div id="divTabular" style="height:320px;">
                      		<table id="tabular" class="table table-striped table-hover display responsive nowrap" cellspacing="0">
            					<thead>
                                	<tr>
                                    	<th>Candidates</th>
                                        @php
                                        	
                                            $barangays = App\Models\Barangay::all();
                                            
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
                                	$votesq = array();
                                @endphp
                                @foreach($candidates as $candidate)                                	
                                	<tr>
                                    	<td>{{ $candidate->voter->full_name }}</td>
                                        @foreach($qualities as $quality)
                                        @php
                                            $votesq[$candidate->id][$quality->option_id]=rand(1,100);
                                        @endphp
                                        <td>{{ $votesq[$candidate->id][$quality->option_id] }}</td>
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
                      <div id="divTabular" style="height:320px;">
                      		<table id="tabular" class="table table-striped table-hover display responsive nowrap" cellspacing="0">
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
                                	$votesg = array();                                    
                                @endphp
                                @foreach($candidates as $candidate)                                	
                                	<tr>
                                    	<td>{{ $candidate->voter->full_name }}</td>
                                        @foreach($genders as $gender)
                                        @php
                                            $votesg[$candidate->id][$gender->id]=rand(1,100);
                                        @endphp
                                        <td>{{ $votesg[$candidate->id][$gender->id] }}</td>
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
                      <div id="divTabular" style="height:320px;">
                      		<table id="tabular" class="table table-striped table-hover display responsive nowrap" cellspacing="0">
            					<thead>
                                	<tr>
                                    	<th>Barangays</th>
                                        @php
                                        	$problems = App\Models\OptionProblem::with('option')->get();
                                        @endphp
                                        @foreach($problems as $problem)
                                        <th>{{ $problem->option->option }}</th>
                                        @endforeach
                                    </tr>                                    
                                </thead>
                                <tbody>
                               @php
                                	$votesp = array();                                    
                                @endphp
                                @foreach($barangays as $barangay)                          	
                                	<tr>
                                    	<td>{{ $barangay->name }}</td>
                                        @foreach($problems as $problem)
                                        @php
                                            $votesp[$candidate->id][$problem->option->id]=rand(1,100);
                                        @endphp
                                        <td>{{ $votesp[$candidate->id][$problem->option->id] }}</td>
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

                <div class="box-body"><div id="chartbrgy"></div></div>
            </div>
        </div>         	
        @foreach($barangays as $barangay)
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
    <script src="{{ asset('js/d3.v5.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/c3.js') }}"></script>
    <script>
	var brgy = [];
	@php
		$barangays = App\Models\Barangay::all();
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
				{{ $votes[$candidate->id] }},
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
					{{ $votesq[$candidate->id][$quality->option_id] }},
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
					{{ $votesg[$candidate->id][$gender->id] }},
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
          columns: [
            ['data1', 1030, 1200, 1100, 1400, 1150, 1250],
            ['data2', 2130, 2100, 2140, 2200, 2150, 1850]
//           ['data1', 30, 200, 100, 400, 150, 250],
//           ['data2', 130, 100, 140, 200, 150, 50]
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
        }
      });
	@endforeach
    </script>
@endsection