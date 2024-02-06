@props(['property', 'tool' => null, 'plan' => null])

<div class="row mb-3">
    <div class="col-md-12">
        <h6>{{ $property->name }}</h6>
        <span class="small text-muted">{{ $property->description }}</span>
    </div>
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label class="form-label">@lang('tools.loggedInUser')</label>
            <input {{ $attributes->merge(['class' => 'form-control']) }} id="property_{{ $property->prop_key }}_auth"
                name="property_{{ $property->prop_key }}_auth"
                value="{{ $tool->properties['auth'][$property->prop_key] ?? 0 }}" type="text">
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label class="form-label">@lang('tools.guestUser')</label>
            <input {{ $attributes->merge(['class' => 'form-control']) }} id="property_{{ $property->prop_key }}_guest"
                name="property_{{ $property->prop_key }}_guest"
                value="{{ $tool->properties['guest'][$property->prop_key] ?? 0 }}" type="text">
        </div>
    </div>
</div>
