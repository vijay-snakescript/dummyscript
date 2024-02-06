<x-app-layout>
    @asyncWidget('admin.countersWidget')
    <div class="row match-height">
        @asyncWidget('admin.graphWidget')
        @asyncWidget('admin.recentActivityWidget')
    </div>
    <div class="row match-height">
        @asyncWidget('admin.toolListsWidget')
        @asyncWidget('admin.userListsWidget')
    </div>
    @push('page_scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.2.0/chart.umd.js"
            integrity="sha512-B51MzT4ksAo6Y0TcUpmvZnchoPYfIcHadIaFqV5OR5JAh6dneYAeYT1xIlaNHhhFAALd5FLDTWNt/fkxhwE/oQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @endpush
</x-app-layout>
