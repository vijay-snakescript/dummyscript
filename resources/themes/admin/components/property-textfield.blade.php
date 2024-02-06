@props(['property', 'tool' => null, 'plan' => null])

@if (!$tool)
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="property_{{ $property->id }}" class="form-label">{{ $property->name }}</label>
            <input {{ $attributes->merge(['class' => 'form-control']) }} data-child="{{ $property->id }}"
                id="property_{{ $property->id }}" name="property_{{ $property->id }}" value="{{ $property->value }}"
                type="text">
            <span class="small text-muted">{{ $property->description }}</span>
        </div>
    </div>
@else
    <div class="form-group mb-3">
        <label for="property_{{ $tool->id }}_{{ $property->id }}" class="form-label">{{ $property->name }}</label>
        <input {{ $attributes->merge(['class' => 'form-control']) }}
            id="property_{{ $tool->id }}_{{ $property->id }}"
            name="property_{{ $tool->id }}_{{ $property->id }}"
            value="{{ isset($plan) ? $tool->planProperty($property->prop_key, $plan->id) : $property->value }}"
            type="text">
        <span class="small text-muted">{{ $property->description }}</span>
    </div>
@endif
