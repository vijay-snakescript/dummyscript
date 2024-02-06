<x-canvas-layout>
    {{ $slot }}
    @if (!Widget::group('pages-sidebar')->isEmpty())
        <x-slot name="sidebar">
            <x-application-sidebar>
                @widgetGroup('pages-sidebar')
            </x-application-sidebar>
        </x-slot>
    @endif
</x-canvas-layout>
