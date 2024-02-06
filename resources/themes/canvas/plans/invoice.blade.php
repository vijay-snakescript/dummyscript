<x-application-no-widget-wrapper>
    <x-page-wrapper>
       @include('plans.invoiceSheet')
        <x-ad-slot />
    </x-page-wrapper>
    @push('page_scripts')
    @endpush
</x-application-no-widget-wrapper>
