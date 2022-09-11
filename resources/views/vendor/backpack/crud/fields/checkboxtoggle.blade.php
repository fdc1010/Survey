<!-- checkbox field -->

<div @include('crud::inc.field_wrapper_attributes') id="div_{{ $field['name'] }}" >
    @include('crud::inc.field_translatable_icon')
    <div class="checkbox">
    	<label>
    	  <input type="hidden" name="{{ $field['name'] }}" value="0">
    	  <input type="checkbox" value="1"

          name="{{ $field['name'] }}" id="{{ $field['name'] }}"

          @if (old($field['name']) ?? $field['value'] ?? $field['default'] ?? false)
                 checked="checked"
          @endif

          @if (isset($field['attributes']))
              @foreach ($field['attributes'] as $attribute => $value)
    			{{ $attribute }}="{{ $value }}"
        	  @endforeach
          @endif
          > {!! $field['label'] !!}
    	</label>

        {{-- HINT --}}
        @if (isset($field['hint']))
            <p class="help-block">{!! $field['hint'] !!}</p>
        @endif
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/jquery-1.12.4.js') }}"></script>
<script>
	jQuery(document).ready(function($) {
		if($('#{{ $field['name'] }}').is(":checked"))
			$('#div_{{ $field['toggle_field'] }}').show('slow');
		else
			$('#div_{{ $field['toggle_field'] }}').hide('slow');
		$('#{{ $field['name'] }}').on('change',function(e){
			if($(this).is(":checked"))
				$('#div_{{ $field['toggle_field'] }}').show('slow');
			else
				$('#div_{{ $field['toggle_field'] }}').hide('slow');
		});		
	});
</script>