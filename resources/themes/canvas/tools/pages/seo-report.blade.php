<x-tool-home-layout>
    {!! $tool->index_content !!}
    @if (setting('display_plan_homepage', 1) == 1)
        <x-plans-tools :plans="$plans ?? null" :properties="$properties" />
    @endif
    @if (setting('display_faq_homepage', 1) == 1)
        <x-faqs-tools :faqs="$faqs" />
    @endif
    <x-relevant-tools :relevant_tools="$relevant_tools" />
    @if (isset($results))
        @push('page_scripts')
            <script>
                const APP = function() {
                    const resources =
                        '<link rel="stylesheet" href="{{ Vite::asset('resources/themes/canvas/assets/sass/app.scss') }}" />';
                    const printReport = function() {
                            let printable = document.querySelector('.printable-container').cloneNode(true)

                            printable.querySelectorAll('.col-auto').forEach(element => {
                                element.remove()
                            });
                            printable.querySelectorAll('.collapse').forEach(element => {
                                element.classList.remove('collapse')
                            });
                            printable.querySelectorAll('.an-light').forEach(element => {
                                element.classList.remove('an-light')
                            });

                            let wrapper = document.createElement('div')
                            let children = document.createElement('div')
                            children.className = 'report-result container'
                            children.appendChild(printable)
                            wrapper.appendChild(children)
                            ArtisanApp.printResult(wrapper, {
                                title: '{{ __('seo.seoReportForDomain', ['domain' => $results['result']['domainname']]) }}',
                                header_code: resources
                            })
                            // document.querySelector('body').appendChild(wrapper)
                        },
                        attachEvents = function() {
                            document.querySelector('#printReport').addEventListener('click', elem => {
                                printReport()
                            })
                        };

                    return {
                        init: function() {
                            attachEvents();
                        }
                    }
                }();

                document.addEventListener("DOMContentLoaded", function(event) {
                    APP.init();
                });
            </script>
        @endpush
    @endif
</x-tool-home-layout>
