<x-canvas-layout>
    {{ $slot }}
    @if (!Widget::group('post-sidebar')->isEmpty())
        <x-slot name="sidebar">
            <x-application-sidebar>
                @widgetGroup('post-sidebar')
            </x-application-sidebar>
        </x-slot>
    @endif
</x-canvas-layout>
