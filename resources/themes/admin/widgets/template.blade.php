<div class="card card-accent-success handle mb-3" id="widget-{{ $widget->id }}" data-id="{{ $widget->id }}">
    <div class="card-header small d-flex" role="button" data-coreui-toggle="collapse"
        data-coreui-target="#widget-toggle-{{ $widget->id }}" aria-expanded="false"
        aria-controls="widget-toggle-{{ $widget->id }}">
       <h6 class="mb-0"> {{ $title ?? __('widgets.noTitle') }}</h6>
        <div class="card-header-actions ms-auto" data-coreui-toggle="tooltip" data-placement="top"
            title="{{ $description ?? __('widgets.noDescription') }}">
            <i class="lni lni-question-circle"></i>
        </div>
    </div>
    <div class="collapse" id="widget-toggle-{{ $widget->id }}" aria-labelledby="widget-{{ $widget->id }}"
        data-parent="#{{ $sidebar }}-1">
        <div class="card-body">
            <form id="frmWidget-{{ $widget->id }}" class="widget-form"
                action="{{ route('admin.widgets.update', $widget->id) }}" method="post">
                @if ($fields['title'])
                    <div class="form-group mb-3">
                        <label for="title-{{ $widget->id }}" class="form-label">@lang('common.title')</label>
                        <input class="form-control @error('title') is-invalid @enderror" id="title-{{ $widget->id }}"
                            name="title" value="{{ $widget->title ?? old('title') }}" type="text">
                    </div>
                @endif
                @yield('widget-form-' . $widget->id)
                @if ($fields['web'])
                    <div class="form-group mb-3">
                        <label for="web-{{ $widget->id }}" class="form-label">@lang('widgets.showWeb')</label>
                        <div>
                            <label class="switch switch-label switch-primary switch-pill">
                                <input class="switch-input @error('web') is-invalid @enderror"
                                    id="web-{{ $widget->id }}" name="web" value="1"
                                    {{ isset($widget->web) && $widget->web == 1 ? 'checked' : '' }} type="checkbox">
                                <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                            </label>
                        </div>
                    </div>
                @endif
                @if ($fields['mobile'])
                    <div class="form-group mb-3">
                        <label for="mobile-{{ $widget->id }}" class="form-label">@lang('widgets.showMobile')</label>
                        <div>
                            <label class="switch switch-label switch-primary switch-pill">
                                <input class="switch-input @error('mobile') is-invalid @enderror"
                                    id="mobile-{{ $widget->id }}" name="mobile" value="1"
                                    {{ isset($widget->mobile) && $widget->mobile == 1 ? 'checked' : '' }}
                                    type="checkbox">
                                <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                            </label>
                        </div>
                    </div>
                @endif
                @if ($fields['ajax'])
                    <div class="form-group mb-3">
                        <label for="ajax-{{ $widget->id }}" class="form-label">@lang('widgets.aSyncy')</label>
                        <div>
                            <label class="switch switch-label switch-primary switch-pill">
                                <input class="switch-input @error('ajax') is-invalid @enderror"
                                    id="ajax-{{ $widget->id }}" name="ajax" value="1"
                                    {{ isset($widget->ajax) && $widget->ajax == 1 ? 'checked' : '' }} type="checkbox">
                                <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                            </label>
                        </div>
                    </div>
                @endif
                @if ($fields['status'])
                    <div class="form-group mb-3">
                        <label for="status-{{ $widget->id }}" class="form-label">@lang('common.status')</label>
                        <div>
                            <label class="switch switch-label switch-primary switch-pill">
                                <input class="switch-input @error('status') is-invalid @enderror"
                                    id="status-{{ $widget->id }}" name="status" value="1"
                                    {{ isset($widget->status) && $widget->status == 1 ? 'checked' : '' }}
                                    type="checkbox">
                                <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
                            </label>
                        </div>
                    </div>
                @endif
            </form>
        </div>
        <div class="card-footer">
            <form action="{{ route('admin.widgets.destroy', $widget->id) }}" method="POST"
                class="d-inline-block widgetDeleteFrm">
                <input type="hidden" name="_method" value="DELETE">
                @csrf
                <button class="btn btn-sm text-white btn-danger deleteWidget"
                    data-id="{{ $widget->id }}">@lang('common.delete')</button>
                <button class="btn btn-danger btn-sm d-none" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    @lang('common.loading')
                </button>
            </form>
            <div class="float-end">
                <button class="btn btn-sm text-white btn-success saveWidget"
                    data-id="#frmWidget-{{ $widget->id }}">@lang('common.save')</button>
                <button class="btn btn-success btn-sm d-none" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    @lang('common.loading')
                </button>
            </div>
        </div>
    </div>
</div>
