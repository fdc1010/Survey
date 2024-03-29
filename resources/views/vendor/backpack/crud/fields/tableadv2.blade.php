<!-- array input -->

<?php
    $max = isset($field['max']) && (int) $field['max'] > 0 ? $field['max'] : -1;
    $min = isset($field['min']) && (int) $field['min'] > 0 ? $field['min'] : -1;
    $item_name = strtolower(isset($field['entity_singular']) && !empty($field['entity_singular']) ? $field['entity_singular'] : $field['label']);

    $items = old($field['name']) ?? $field['value'] ?? $field['default'] ?? '';

    // make sure not matter the attribute casting
    // the $items variable contains a properly defined JSON
    if (is_array($items)) {
        if (count($items)) {
            $items = json_encode($items);
        } else {
            $items = '[]';
        }
    } elseif (is_string($items) && !is_array(json_decode($items))) {
        $items = '[]';
    }

?>
<div ng-app="backPackTableApp" ng-controller="tableController" @include('crud::inc.field_wrapper_attributes') >

    <label>{!! $field['label'] !!} (count:<span id="trcount">1</span>)(Quota:<span id="trquota">0</span>)</label>
    @include('crud::inc.field_translatable_icon')

    <input class="array-json" type="hidden" id="{{ $field['name'] }}" name="{{ $field['name'] }}">

    <div class="array-container form-group">

        <table class="table table-bordered table-striped m-b-0" ng-init="field = '#{{ $field['name'] }}'; items = {{ $items }}; max = {{$max}}; min = {{$min}}; maxErrorTitle = '{{trans('backpack::crud.table_cant_add', ['entity' => $item_name])}}'; maxErrorMessage = '{{trans('backpack::crud.table_max_reached', ['max' => $max])}}'">

            <thead>
                <tr>
                    @foreach( $field['columns'] as $prop => $label ) 
                    	@if($prop=="checkbox" || $prop=="select" || $prop=="select_group" || $prop=="number" || $prop=="input")
                    		<th style="font-weight: 600!important;">
                        		{{ $label }}
                    		</th>
                        @endif
                    @endforeach
                    <th class="text-center" ng-if="max == -1 || max > 1"> {{-- <i class="fa fa-sort"></i> --}} </th>
                    <th class="text-center" ng-if="max == -1 || max > 1"> {{-- <i class="fa fa-trash"></i> --}} </th>
                </tr>
            </thead>

            <tbody ui-sortable="sortableOptions" ng-model="items" class="table-striped">

                <tr ng-repeat="item in items" class="array-row">
					
                    @foreach( $field['columns'] as $prop => $label)                    		
                            @if($prop=="checkbox")
                            <td>
                                <input type="checkbox" class="checkbox" value="0" ng-model="item.{{ $prop }}" />
                            </td>
                            @elseif($prop=="select")
                            <td>                                
								<!-- select2 -->
                                @php
                                    $current_value = old($field['columns']['name']) ?? $field['columns']['value'] ?? $field['columns']['default'] ?? '';
                                @endphp
                                
                                <div @include('crud::inc.field_wrapper_attributes') >
                                    @include('crud::inc.field_translatable_icon')
                                
                                    <?php $entity_model = $crud->getRelationModel($field['columns']['entity'],  - 1); ?>
                                    <select ng-model="item.{{ $prop }}"
                                        style="width: 100%"
                                        @include('crud::inc.field_attributes', ['default_class' =>  'form-control select2_field'])
                                        >
                                
                                        @if ($entity_model::isColumnNullable($field['columns']['name']))
                                            <option value="">-</option>
                                        @endif
                                
                                        @if (isset($field['columns']['model']))
                                            @foreach ($field['columns']['model']::all() as $connected_entity_entry)
                                                @if( (old($field['columns']["name"]) && in_array($connected_entity_entry->getKey(), old($field['columns']["name"]))) || (is_null(old($field['columns']["name"])) && isset($field['columns']['value']) && in_array($connected_entity_entry->getKey(), $field['columns']['value']->pluck($connected_entity_entry->getKeyName(), $connected_entity_entry->getKeyName())->toArray())))
                                                    <option value="{{ $connected_entity_entry->getKey() }}" selected>{{ $connected_entity_entry->{$field['columns']['attribute']} }}</option>
                                                @else
                                                    <option value="{{ $connected_entity_entry->getKey() }}">{{ $connected_entity_entry->{$field['columns']['attribute']} }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                
                                    {{-- HINT --}}
                                    @if (isset($field['columns']['hint']))
                                        <p class="help-block">{!! $field['columns']['hint'] !!}</p>
                                    @endif
                                </div>
                            </td>
                            @elseif($prop=="select_group")
                            <td>                                
								<!-- select2 -->
                                @php
                                    $current_value = old($field['columns']['name']) ?? $field['columns']['value'] ?? $field['columns']['default'] ?? '';
                                @endphp
                                
                                <div @include('crud::inc.field_wrapper_attributes') >
                                    @include('crud::inc.field_translatable_icon')
                                
                                    <?php $entity_model = $crud->getRelationModel($field['columns']['entity'],  - 1); ?>
                                    <select ng-model="item.{{ $prop }}"
                                    	style="width: 100%;"
                                        @include('crud::inc.field_attributes', ['default_class' =>  'form-control select2_field'])
                                        >
                                
                                        @if ($entity_model::isColumnNullable($field['columns']['name']))
                                            <option value="">-</option>
                                        @endif
                                
                                        @if (isset($field['columns']['model']))
                                            @foreach ($field['columns']['model']::with($field['columns']['entity'])->get() as $parent_connected_entity_entry)
											     <optgroup label="{{ $parent_connected_entity_entry->{$field['columns']['attribute']} }}" style="font-size:21px;">
													@foreach ($parent_connected_entity_entry->{$field['columns']['entity']} as $connected_entity_entry)
                                                          @if($current_value == $connected_entity_entry->getKey())
                                                              <option value="{{ $connected_entity_entry->getKey() }}" selected>{{ $connected_entity_entry->{$field['columns']['attribute']} }}</option>
                                                          @else
                                                              <option value="{{ $connected_entity_entry->getKey() }}">{{ $connected_entity_entry->{$field['columns']['attribute']} }}</option>
                                                          @endif
                                                    @endforeach
                                                 </optgroup>
                                            @endforeach
                                        @endif
                                    </select>
                                
                                    {{-- HINT --}}
                                    @if (isset($field['columns']['hint']))
                                        <p class="help-block">{!! $field['columns']['hint'] !!}</p>
                                    @endif
                                </div>
                            </td>
                            @elseif($prop=="input")
                            <td>
                                <input class="form-control input-sm" type="text" ng-model="item.{{ $prop }}">
                            </td>
                            @elseif($prop=="number")
                            <td>
                            	<!-- number input -->
                                <div @include('crud::inc.field_wrapper_attributes') >
                                    @include('crud::inc.field_translatable_icon')
                                
                                    @if(isset($field['columns']['prefix']) || isset($field['columns']['suffix'])) <div class="input-group"> @endif
                                        @if(isset($field['columns']['prefix'])) <div class="input-group-addon">{!! $field['columns']['prefix'] !!}</div> @endif
                                        <input
                                            type="number"
                                            ng-model="item.{{ $prop }}"                                            
                                            @include('crud::inc.field_attributes')
                                            >
                                        @if(isset($field['columns']['suffix'])) <div class="input-group-addon">{!! $field['columns']['suffix'] !!}</div> @endif
                                
                                    @if(isset($field['columns']['prefix']) || isset($field['columns']['suffix'])) </div> @endif
                                
                                    {{-- HINT --}}
                                    @if (isset($field['columns']['hint']))
                                        <p class="help-block">{!! $field['columns']['hint'] !!}</p>
                                    @endif
                                </div>
                            </td>
                            @endif
                       
                    @endforeach
                    <td ng-if="max == -1 || max > 1">
                        <span class="btn btn-sm btn-default sort-handle"><span class="sr-only">sort item</span><i class="fa fa-sort" role="presentation" aria-hidden="true"></i></span>
                    </td>
                    <td ng-if="max == -1 || max > 1">
                        <button ng-hide="min > -1 && $index < min" class="btn btn-sm btn-default" type="button" ng-click="removeItem(item)"><span class="sr-only">delete item</span><i class="fa fa-trash" role="presentation" aria-hidden="true"></i></button>
                    </td>
                </tr>

            </tbody>

        </table>

        <div class="array-controls btn-group m-t-10">
            <button ng-if="max == -1 || items.length < max" class="btn btn-sm btn-default" type="button" ng-click="addItem()"><i class="fa fa-plus"></i> {{trans('backpack::crud.add')}} {{ $item_name }}</button>
        </div>

    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
    {{-- @push('crud_fields_styles')
        {{-- YOUR CSS HERE --}}
        <!-- include select2 css-->
        <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />-->
        <link href="{{ asset('css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css" />
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        {{-- YOUR JS HERE --}}
        <!-- include select2 js-->
        <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/angular.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/jquery-ui.min.js') }}"></script>
        <!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>-->
		<script type="text/javascript" src="{{ asset('js/sortable.min.js') }}"></script>        
		<script>

            window.angularApp = window.angularApp || angular.module('backPackTableApp', ['ui.sortable'], function($interpolateProvider){
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });
			
            window.angularApp.controller('tableController', function($scope){
				//resetSelect2();
                $scope.sortableOptions = {
                    handle: '.sort-handle',
                    axis: 'y',
                    helper: function(e, ui) {
                        ui.children().each(function() {
                            $(this).width($(this).width());
                        });
                        return ui;
                    },
                };
				
				/*$('#number_answers').on('change',function(e){
					$scope.max = $(this).val();					
				});*/
				
                $scope.addItem = function(){
					//resetSelect2();
					
                    if( $scope.max > -1 ){
                        if( $scope.items.length < $scope.max ){
                            var item = {};
                            $scope.items.push(item);
							$('#trcount').html($scope.items.length);
							
                        } else {
                            new PNotify({
                                title: $scope.maxErrorTitle,
                                text: $scope.maxErrorMessage,
                                type: 'error'
                            });
                        }
                    }
                    else {
                        var item = {};
                        $scope.items.push(item);
                    }
					var totalquota=0;
					$.each($scope.items,function(key,value){
						totalquota += value.number;
					});
					$('#trquota').html(totalquota);
					isSameQuota(totalquota);
					
                }

                $scope.removeItem = function(item){
					//resetSelect2();
                    var index = $scope.items.indexOf(item);
                    $scope.items.splice(index, 1);
					$('#trcount').html($scope.items.length);
					var totalquota=0;
					$.each($scope.items,function(key,value){
						totalquota += value.number;
					});
					$('#trquota').html(totalquota);
					isSameQuota(totalquota);
					
                }

                $scope.$watch('items', function(a, b){
					
                    if( $scope.min > -1 ){
                        while($scope.items.length < $scope.min){
                            $scope.addItem();
                        }
						$('#trcount').html($scope.items.length);
						//$('#trquota').html($scope.item[1].value);
						
                    }

                    if( typeof $scope.items != 'undefined' ){

                        if( typeof $scope.field != 'undefined'){
                            if( typeof $scope.field == 'string' ){
                                $scope.field = $($scope.field);
                            }
                            $scope.field.val( $scope.items.length ? angular.toJson($scope.items) : null );
                        }
                    }
					var totalquota=0;
					$.each($scope.items,function(key,value){
						totalquota += value.number;
					});
					$('#trquota').html(totalquota);
					isSameQuota(totalquota);
					
                }, true);

                if( $scope.min > -1 ){
                    for(var i = 0; i < $scope.min; i++){
                        $scope.addItem();						
                    }
                }
            });

            angular.element(document).ready(function(){
                angular.forEach(angular.element('[ng-app]'), function(ctrl){
                    var ctrlDom = angular.element(ctrl);
                    if( !ctrlDom.hasClass('ng-scope') ){
                        angular.bootstrap(ctrl, [ctrlDom.attr('ng-app')]);
                    }
                });							
            })
			function resetSelect2(){
				$('.select2_field').each(function (i, obj) {
					if (!$(obj).hasClass("select2-hidden-accessible"))
					{
						$(obj).select2({
							theme: "bootstrap"
						});
					}
				});	
			}
			function isSameQuota(mquota){
				if(typeof $('#quota') != "undefined"){
					if($('#quota').val()==mquota){
						$('button[type="submit"]').show('slow');
						$('button[type="button"][class="btn btn-success dropdown-toggle"]').show('slow');
					}else{
						$('button[type="submit"]').hide('slow');
						$('button[type="button"][class="btn btn-success dropdown-toggle"]').hide('slow');
					}
				}
			}
			jQuery(document).ready(function($) {
				  $('.select2_field').each(function (i, obj) {
					  if (!$(obj).hasClass("select2-hidden-accessible"))
					  {
						  $(obj).select2({
							  theme: "bootstrap"
						  });
					  }
				  });	
				  
				  $(document).on('mouseenter','.select2_field',function(e){
					  
					  $(this).select2({
						  theme: "bootstrap"
					  });
				  });
				  $('#quota').on('change',function(e){
						var totalquota=0;
						$.each($scope.items,function(key,value){
							totalquota += value.number;
						});
						$('#trquota').html(totalquota);
						isSameQuota(totalquota);
				  });
				  /*$(document).on('change','.select2_field',function(e){
					  $('.select2_field option:selected').each(function (i, obj) {
						 
					  });
				  });*/
			});
        </script>
    @endpush
@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
