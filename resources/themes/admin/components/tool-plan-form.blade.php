@props([
    'plan' => null,
    'tools',
    'locales',
    'properties',
])
<form action="{{ isset($plan) ? route('admin.plans.update', $plan) : route('admin.plans.store') }}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $plan->id ?? null }}">

    <div class="row match-height">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ isset($tag) ? __('common.edit') : __('common.createNew') }}</h6>
                </div>
                <div class="card-body">
                    @if ($locales->count() > 1)
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach ($locales as $index => $locale)
                                <li class="nav-item">
                                    <a class="nav-link @if ($index == 0) active @endif"
                                        data-coreui-toggle="tab" href="#locale_{{ $locale->locale }}" role="tab"
                                        aria-controls="{{ $locale->name }}">
                                        <i class="icon-arrow-right"></i> {{ $locale->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="tab-content">
                        @foreach ($locales as $index => $locale)
                            @if (isset($plan))
                                @php($plan_locale = $plan->translate($locale->locale))
                            @endif
                            <div class="tab-pane @if ($index == 0) active @endif"
                                id="locale_{{ $locale->locale }}" role="tabpanel">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">@lang('common.name')</label>
                                    <input
                                        class="form-control @error($locale->locale . '.name') is-invalid @enderror slug_title"
                                        id="{{ $locale->locale }}[name]" name="{{ $locale->locale }}[name]"
                                        value="{{ $plan_locale->name ?? old($locale->locale . '.name') }}"
                                        type="text" placeholder="@lang('common.enterName')"
                                        @if ($index == 0) required autofocus @endif>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">@lang('common.description')</label>
                                    <textarea class="form-control @error($locale->locale . '.description') is-invalid @enderror"
                                        id="{{ $locale->locale }}[description]" name="{{ $locale->locale }}[description]">{{ $plan_locale->description ?? old($locale->locale . '.description') }}</textarea>
                                    <span class="small text-muted">@lang('admin.descriptionHelpPlan')</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="form-group mb-3 col-md-4">
                            <label for="yearly_price" class="form-label">@lang('admin.yearlyPrice')</label>
                            <input class="form-control" id="yearly_price" name="yearly_price"
                                value="{{ $plan->yearly_price ?? old('yearly_price') }}" type="number"
                                placeholder="@lang('admin.yearlyPrice')" step="0.01">
                        </div>
                        <div class="form-group mb-3 col-md-4">
                            <label for="monthly_price" class="form-label">@lang('admin.monthlyPrice')</label>
                            <input class="form-control" id="monthly_price" name="monthly_price"
                                value="{{ $plan->monthly_price ?? old('monthly_price') }}" type="number"
                                placeholder="@lang('admin.monthlyPrice')" step="0.01">
                        </div>
                        {{-- <div class="form-group mb-3 col-md-4">
                            <label for="discount" class="form-label">@lang('admin.discount')</label>
                            <input class="form-control" id="discount" name="discount"
                                value="{{ $plan->discount ?? old('discount') }}" type="number"
                                placeholder="@lang('admin.discount')" step="0.01">
                        </div> --}}
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check form-switch form-switch-xl mb-3">
                                <input class="form-check-input" value="1"
                                    @if (isset($plan) && $plan->is_api_allowed == 1) checked @endif id="is_api_allowed"
                                    name="is_api_allowed" type="checkbox">
                                <label class="form-check-label ms-2" for="isApi">@lang('admin.isApi')</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch form-switch-xl mb-3">
                                <input class="form-check-input" value="1"
                                    @if (isset($plan) && $plan->is_ads == 1) checked @endif id="is_ads" name="is_ads"
                                    type="checkbox">
                                <label class="form-check-label ms-2" for="isAds">@lang('admin.isAds')</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">@lang('admin.options')</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($properties->whereIn('type', '1') as $property)
                            <x-dynamic-component :component="$property->field_type" :property="$property" class="property-value" />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">@lang('admin.tools')</h6>
            </div>
            <div class="card-body">
                <div class="accordion" id="accordionExample">
                    <div class="row">
                        @foreach ($tools->chunk(floor($tools->count() / 3)) as $chunk)
                            <div class="col-md-4">
                                @foreach ($chunk as $tool)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="accordion-item mb-3">
                                                <h2 class="accordion-header" id="heading_{{ $tool->id }}">
                                                    <button class="accordion-button collapsed py-4" type="button"
                                                        data-coreui-toggle="collapse"
                                                        data-coreui-target="#collapse_{{ $tool->id }}"
                                                        aria-expanded="false" aria-controls="collapseOne">
                                                        @if ($tool->icon_type == 'class')
                                                            <i
                                                                class="an-duotone an-2x an-{{ $tool->icon_class }} me-2"></i>
                                                        @elseif ($tool->getFirstMediaUrl('tool-icon'))
                                                            <img src="{{ $tool->getFirstMediaUrl('tool-icon') }}"
                                                                alt="{{ $tool->name }}" width="36">
                                                        @endif
                                                        {{ $tool->name }}
                                                    </button>
                                                </h2>
                                                <div class="accordion-collapse collapse"
                                                    id="collapse_{{ $tool->id }}"
                                                    aria-labelledby="heading_{{ $tool->id }}"
                                                    data-coreui-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        @foreach ($properties->whereIn('prop_key', $tool->properties['properties'] ?? [0]) as $property)
                                                            <x-dynamic-component :component="$property->field_type" :property="$property"
                                                                :tool="$tool" :plan="isset($plan) ? $plan : null"
                                                                class="property-sub-{{ $property->id }}" />
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <button type="submit" class="btn btn-primary">@lang('common.save')</button>
    </div>
</form>
