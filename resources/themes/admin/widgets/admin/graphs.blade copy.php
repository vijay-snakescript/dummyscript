        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title mb-0">Traffic</h4>
                    </div>
                </div>
                <div class="c-chart-wrapper" style="height:300px;margin-top:40px;">
                    <canvas class="chart" id="main-chart" height="300"></canvas>
                </div>
            </div>
        </div>
        <script>
            const APP = function() {
                const ctx = document.getElementById("main-chart").getContext('2d');
                const initCharts = function() {
                    const myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ["Tokyo", "Mumbai", "Mexico City", "Shanghai", "Sao Paulo", "New York",
                                "Karachi",
                                "Buenos Aires", "Delhi", "Moscow"
                            ],
                            datasets: [{
                                    label: 'Series 1', // Name the series
                                    data: [500, 50, 2424, 14040, 14141, 4111, 4544, 47, 5555,
                                        6811
                                    ], // Specify the data values array
                                    fill: false,
                                    borderColor: '#2196f3', // Add custom color border (Line)
                                    backgroundColor: '#2196f3', // Add custom color background (Points and Fill)
                                    borderWidth: 1 // Specify bar border width
                                },
                                {
                                    label: 'Series 2', // Name the series
                                    data: [1288, 88942, 44545, 7588, 99, 242, 1417, 5504, 75,
                                        457
                                    ], // Specify the data values array
                                    fill: false,
                                    borderColor: '#4CAF50', // Add custom color border (Line)
                                    backgroundColor: '#4CAF50', // Add custom color background (Points and Fill)
                                    borderWidth: 1 // Specify bar border width
                                }
                            ]
                        },
                        options: {
                            responsive: true, // Instruct chart js to respond nicely.
                            maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height
                        }
                    });
                };
                return {
                    init: function() {
                        console.log('init charts')
                        initCharts();
                    }
                }
            }();
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
