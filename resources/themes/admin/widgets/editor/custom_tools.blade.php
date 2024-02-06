@extends('widgets.template')

@section('widget-form-' . $widget->id)
    <div class="mh-300 mb-3 border rounded">
        <ul class="list-group list-group-flush">
            @foreach ($tools as $tool)
                <li class="list-group-item">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{ $tool->id }}"
                            id="settings-{{ $widget->id }}-tool-{{ $tool->id }}" name="settings[ids][]"
                            @if (isset($widget->settings->ids) && in_array($tool->id, $widget->settings->ids)) checked @endif>
                        <label class="form-check-label" for="settings-{{ $widget->id }}-tool-{{ $tool->id }}">
                            {{ $tool->name }}
                        </label>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
