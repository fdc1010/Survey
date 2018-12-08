<!-- select2 -->
<div @include('crud::inc.field_wrapper_attributes') id="div_{{ $field['name'] }}" >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <?php $entity_model = $crud->getModel(); ?>
	<div class="row">
    	<div class="col-sm-12">
            <div class="checkbox">
              <label><input type="checkbox" name="checkAll" id="checkAll" /> <strong>Check All</strong></label>
            </div>
        </div>
    </div>
    <div class="row">    		
        @foreach ($field['model1']::all() as $connected_entity_entry)
            <div class="col-sm-4">
                <div class="checkbox">
                  <label>
                    <input type="checkbox"
                      name="{{ $field['name'] }}[]"
                      value="{{ $connected_entity_entry->getKey() }}"

                      @if(in_array($connected_entity_entry->getKey(), $field['model2']::where('option_id',$id)->get()->pluck($field['attribute2'])->toArray())))
                             checked = "checked"
                      @endif > {!! $connected_entity_entry->{$field['attribute1']} !!}
                  </label>
                </div>
            </div>
        @endforeach
    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
<script type="text/javascript" src="{{ asset('js/jquery-1.12.4.js') }}"></script>
<script>
	jQuery(document).ready(function($) {
		$('#checkAll').on('change',function(e){
			$("input[type='checkbox'][name='{{ $field['name'] }}[]']").prop('checked',$(this).is(":checked"));
		});			
	});
</script>
