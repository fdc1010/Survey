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
                @php
                	$surveypos = 1;
                	$brgysurveys = App\Models\BarangaySurveyable::with('barangay')->get();
                    $problems = App\Models\OptionProblem::with('option')->get();
                    $voterstatuses = App\Models\VoterStatus::all();
                    $selvoterstatuses = App\Models\VoterStatus::all();
                    $genders = App\Models\Gender::all();
                    $candidates = App\Models\Candidate::with('voter')->where('position_id',$surveypos)->get();
                    $barangays = App\Models\Barangay::all();
                    $positions = App\Models\PositionCandidate::all(); 
                    $qualities = App\Models\OptionPosition::with('options','positions')
                                                                        ->where('position_id',$surveypos)->get();
                @endphp
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
    	<div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">Criteria</div>                	                        	
                    </div>
                </div>
                
                <div class="box-body">
                	<form method="post" id="my_form" action="{{ backpack_url('stats') }}">                    	
                        @csrf
                        <div class="col-md-12">
                        	<div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Position:</label>
                                    <div class="col-md-6">
                                        <select id="selposition" name="selposition" class="form-control">                                    
                                            @foreach($positions as $position)
                                                <option value="{{ $position->id }}">{{ $position->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Voter Status:</label>
                                    <div class="col-md-6">
                                        <select id="selvoterstatuses" name="selvoterstatuses" class="form-control select2_multiple">                                    
                                            @foreach($positions as $position)
                                                <option value="{{ $position->id }}">{{ $position->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                             </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Barangays:</label>
                                    <div class="col-md-6">
                                        <select id="selbrgy" name="selbrgy[]" multiple="multiple" class="form-control select2_multiple">                                  
                                            @foreach($barangays as $barangay)
                                                <option value="{{ $barangay->id }}">{{ $barangay->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label class="col-md-4 control-label">Problems:</label>
                                  <div class="col-md-6">
                                      <select id="selprob" name="selprob[]" multiple="multiple" class="form-control">                                  
                                          @foreach($barangays as $barangay)
                                              <option value="{{ $barangay->id }}">{{ $barangay->name }}</option>
                                          @endforeach
                                      </select>
                                  </div>
                              </div>
                            </div>
                        </div>                        
                        <div class="col-md-12">
                                <a class="btn btn-primary" onclick="document.getElementById('my_form').submit();">
                                    <span class="fa fa-search"></span> View
                                </a>
                        </div>
                    </form>
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
	$('#selposition').select2({
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
	});
	$("#tblvotes").mCustomScrollbar({
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
});
    </script>
@endsection