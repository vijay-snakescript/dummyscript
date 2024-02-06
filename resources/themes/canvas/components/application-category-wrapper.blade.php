<x-canvas-layout>
    {{ $slot }}
    @if (!Widget::group('categories-sidebar')->isEmpty())
        <x-slot name="sidebar">
            <x-application-sidebar>
                @widgetGroup('categories-sidebar')
            </x-application-sidebar>
        </x-slot>
    @endif
</x-canvas-layout>
