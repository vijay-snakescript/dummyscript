@props(['messages'])

@if ($messages)
    <div class="invalid-feedback text-start">{{ $messages[0] }}</div>
@endif
