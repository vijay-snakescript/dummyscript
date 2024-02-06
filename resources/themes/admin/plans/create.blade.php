<x-app-layout>
    <div class="row">
        <x-tool-plan-form :tools="$tools" :locales="$locales" :properties="$properties" />
    </div>

    @section('footer_scripts')
        <script>
            document.querySelectorAll('.property-value').forEach(element => {
                let state = null
                element.addEventListener('keydown', event => {
                    state = event.target.type == 'checkbox' ? event.target.checked : event.target.value
                })

                if (element.type == 'checkbox') {
                    element.addEventListener('mousedown', event => {
                        state = event.target.checked
                    })
                    element.addEventListener('change', event => {
                        var data = event.target.checked;
                        var dataset = element.dataset;
                        var className = '.property-sub-' + dataset.child;
                        document.querySelectorAll(className).forEach((x) => {
                            if (state == x.checked) {
                                x.checked = event.target.checked
                            }
                        });
                    })
                }

                element.addEventListener('keyup', event => {
                    var data = element.value;
                    var dataset = element.dataset;
                    var className = '.property-sub-' + dataset.child;
                    document.querySelectorAll(className).forEach((x) => {
                            x.value = data
                    });
                });
            });
        </script>
    @endsection
</x-app-layout>
