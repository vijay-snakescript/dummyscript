<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">@lang('widgets.admin.trafficGraph')</h6>
    </div>
    <div class="card-body card-height">
        <div class="c-chart-wrapper" style="height:300px;margin-top:40px;">
            <canvas class="chart" id="main-chart" height="300"></canvas>
        </div>
    </div>
</div>
<script>
    var ctx = document.getElementById("main-chart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($graphData['labels']),
            datasets: [{
                    label: '{{ __('admin.views') }}',
                    data: @json($graphData['tools']),
                    fill: false,
                    borderColor: '#FA8617',
                    backgroundColor: '#FA8617',
                    borderWidth: 2,
                    borderDashOffset: 1
                },
                {
                    label: '{{ __('admin.usage') }}',
                    data: @json($graphData['visits']),
                    fill: false,
                    borderColor: '#39f',
                    backgroundColor: '#39f',
                    borderWidth: 2,
                    borderDashOffset: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            bezierCurve: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            elements: {
                point: {
                    radius: 0
                },
                line: {
                    tension: .4
                }
            }
        },
    });
</script>
