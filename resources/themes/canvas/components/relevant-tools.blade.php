@props([
    'relevant_tools' => null,
])
<div class="container pt-5">
    <div class="hero-title">
        <h2 class="h1">@lang('common.relevantTools')</h2>
    </div>
    <div class="products list-view">
        <div class="row">
            <ul class="list-view transparent list-view-sm p-0 pb-5">
                @foreach ($relevant_tools as $relevant_tool)
                    <x-tool-item :tool="$relevant_tool" />
                @endforeach
            </ul>
        </div>
    </div>
</div>
