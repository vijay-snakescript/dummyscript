@props(['property', 'tool' => null, 'plan' => null])

@if (!$tool)
    <div class="form-group mb-3 col-md-6">
        <div class="form-group mb-3">
            <label for="property_{{ $property->id }}" class="form-label">{{ $property->name }}</label>
            <div class="form-switch form-switch-xl mb-3">
                <input {{ $attributes->merge(['class' => 'form-check-input']) }} data-child="{{ $property->id }}"
                    id="property_{{ $property->id }}" name="property_{{ $property->id }}" value="1"
                    @if (isset($property) && $property->value == 1) checked @endif type="checkbox">
                <span class="small text-muted">{{ $property->description }}</span>
            </div>
        </div>
    </div>
@else
    @php
        $checked = '';
        if (isset($plan)) {
            if ($tool->planProperty('', $property->prop_key, $plan->id)['value'] == 1) {
                $checked = 'checked';
            }
        } else {
            if ($property->value == 1) {
                $checked = 'checked';
            }
        }
    @endphp
    <div class="form-check form-switch form-switch-xl mb-3 col-md-6">
        <div class="form-group mb-3">
            <label for="property_{{ $tool->id }}_{{ $property->id }}"
                class="form-label">{{ $property->name }}</label>
            <input {{ $attributes->merge(['class' => 'form-check-input']) }}
                id="property_{{ $tool->id }}_{{ $property->id }}"
                name="property_{{ $tool->id }}_{{ $property->id }}" value="1" {{ $checked }}
                type="checkbox">
            <span class="small d-block text-muted">{{ $property->description }}</span>
        </div>
    </div>
@endif
