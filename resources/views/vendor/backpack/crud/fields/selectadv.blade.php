<!-- select -->
@php
	$current_value = old($field['name']) ?? $field['value'] ?? $field['default'] ?? '';
@endphp

<div @include('crud::inc.field_wrapper_attributes') id="div_{{ $field['name'] }}" >

    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    <?php $entity_model = $crud->getRelationModel($field['entity'],  - 1); ?>
    <select
        name="{{ $field['name'] }}"
        @include('crud::inc.field_attributes')
        >

        @if ($entity_model::isColumnNullable($field['name']))
            <option value="">-</option>
        @endif

        @if (isset($field['model']))
            @foreach ($field['model']::all() as $connected_entity_entry)
                @if($current_value == $connected_entity_entry->getKey())
                    <option value="{{ $connected_entity_entry->getKey() }}" selected>{{ $connected_entity_entry->{$field['attribute']} }} ({{ $connected_entity_entry->{$field['attribute2']} }})</option>
                @else
                    <option value="{{ $connected_entity_entry->getKey() }}">{{ $connected_entity_entry->{$field['attribute']} }} ({{ $connected_entity_entry->{$field['attribute2']} }})</option>
                @endif
            @endforeach
        @endif
    </select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

</div>
