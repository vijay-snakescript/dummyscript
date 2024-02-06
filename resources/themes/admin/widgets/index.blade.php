<x-app-layout>
    <div class="row">
        @if ($sidebars)
            <div class="col-md-6" id="widgets-placeholder">
                <h4>@lang('widgets.availableWidgets')</h4>
                <hr>
                @if (is_array($widgets))
                    @php
                        $widgets = collect($widgets);
                        $half = ceil($widgets->count() / 2);
                        $widgetsSlice = $widgets->chunk($half);
                    @endphp
                    <div class="row">
                        @foreach ($widgetsSlice as $widgets)
                            <div class="col-md-6">
                                @foreach ($widgets as $index => $widget)
                                    <div class="card card-accent-primary mb-0">
                                        <div class="card-header d-flex" role="button" data-coreui-toggle="collapse"
                                            data-coreui-target="#placeholder-{{ $index }}" aria-expanded="false">
                                            <h6 class="mb-0">{{ app($widget)->get_title() }}</h6>
                                            <div class="card-header-actions ms-auto" data-coreui-toggle="tooltip"
                                                data-coreui-placement="top"
                                                title="{{ app($widget)->get_description() }}">
                                                <i class="lni lni-question-circle"></i>
                                            </div>
                                        </div>
                                        <div class="collapse" data-parent="#widgets-placeholder"
                                            id="placeholder-{{ $index }}">
                                            <div class="card-body">
                                                <nav class="nav nav-pills flex-column">
                                                    @foreach ($sidebars as $key => $sidebar)
                                                        <a class="nav-link widgetSelection" href="#"
                                                            data-widget="{{ $widget }}"
                                                            data-area="{{ $sidebar->name }}"
                                                            data-area-id="{{ $sidebar->id }}">@lang('widgets.addTo', ['name' => $sidebar->title])</a>
                                                    @endforeach
                                                </nav>
                                            </div>
                                            <div class="card-footer d-none text-end">
                                                <button type="button"
                                                    class="btn btn-sm btn-primary text-white addWidget">@lang('widgets.addWidget')</button>
                                                <button class="btn btn-danger text-white btn-sm d-none" type="button"
                                                    disabled>
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                        aria-hidden="true"></span>
                                                    @lang('common.loading')
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-muted small mb-2">
                                        {{ app($widget)->get_description() }}
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-md-6" id="widgets-area">
                <div class="row">
                    @php
                        $half = ceil($sidebars->count() / 2);
                        $sidebarsSlice = $sidebars->chunk($half);
                    @endphp
                    @foreach ($sidebarsSlice as $sidebars)
                        <div class="col-md-6">
                            @foreach ($sidebars as $key => $sidebar)
                                <div class="card card-accent-secondary mb-3">
                                    <div class="card-header d-flex" role="button" data-coreui-toggle="collapse"
                                        data-coreui-target="#wa-{{ $sidebar->name }}"
                                        aria-expanded="@if ($key == 0) true @else false @endif"
                                        aria-controls="wa-{{ $sidebar->name }}">
                                       <h6 class="mb-0"> {{ $sidebar->title }}</h6>
                                        <div class="card-header-actions ms-auto" data-coreui-toggle="tooltip"
                                            data-coreui-placement="top" title="{{ $sidebar->description }}">
                                            <i class="lni lni-question-circle"></i>
                                        </div>
                                    </div>
                                    <div id="wa-{{ $sidebar->name }}"
                                        class="collapse @if ($key == 0) show @endif"
                                        aria-labelledby="wa-{{ $sidebar->name }}-1">
                                        <div class="card-body">
                                            <div class="sortable-widgets-wrapper" id="wa-{{ $sidebar->name }}-1">
                                                @if ($sidebar->widgets->count())
                                                    @foreach ($sidebar->widgets as $sidebar_widget)
                                                        @if (method_exists($sidebar_widget->name, 'build'))
                                                            {{ app($sidebar_widget->name)->build($sidebar->name, $sidebar_widget) }}
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    @section('footer_scripts')
        <script>
            const addWidget = '{{ route('admin.widgets.store') }}';
            const sortWidget = '{{ route('admin.widgets.sort') }}';
            document.addEventListener("DOMContentLoaded", function(event) {
                DotArtisan.initWidgets(addWidget, sortWidget);
            });
        </script>
    @endsection
</x-app-layout>
