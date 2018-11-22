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
                      		<div class="box-title">Main Chart</div>                	
                        	<div id="chart"></div>
                    </div>
                </div>

                <!--<div class="box-body">{{ trans('backpack::base.logged_in') }}</div>-->
            </div>
        </div>
    	@php
            $barangays = App\Models\Barangay::all();
        @endphp
        @foreach($barangays as $barangay)
        <div class="col-md-4">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-12">                      
                      		<div class="box-title">{{ $barangay->name }}</div>                	
                        	<div id="chart_{{ $barangay->id }}"></div>
                    </div>
                </div>

                <!--<div class="box-body">{{ trans('backpack::base.logged_in') }}</div>-->
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
		foreach($barangays as $barangayarr){
			echo "brgy[".$i++."]='$barangayarr->name';";
		}
		$candidates = App\Models\Candidate::with('voter')->all();
	@endphp	
	var chart = c3.generate({
		bindto: '#chart',		
        data: {
		  columns: [
					@foreach($candidates as $candidate)
						['{{ $candidate->voter->full_name }}',100],
					@endforeach
					
				],
			
			
//           ['data1', 30, 200, 100, 400, 150, 250],
//           ['data2', 130, 100, 140, 200, 150, 50]
          ],
		  labels: true,
          onclick: function (d, element) { console.log("onclick", d, element); },
          onmouseover: function (d) { console.log("onmouseover", d); },
          onmouseout: function (d) { console.log("onmouseout", d); }
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