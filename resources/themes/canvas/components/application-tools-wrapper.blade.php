<x-canvas-layout>
    {{ $slot }}
    @if (!Widget::group('tools-sidebar')->isEmpty())
        <x-slot name="sidebar">
            <x-application-sidebar>
                @widgetGroup('tools-sidebar')
            </x-application-sidebar>
        </x-slot>
    @endif
</x-canvas-layout>
