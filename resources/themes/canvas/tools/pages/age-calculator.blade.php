<x-tool-home-layout>
    {!! $tool->index_content !!}
    @if (setting('display_plan_homepage', 1) == 1)
        <x-plans-tools :plans="$plans ?? null" :properties="$properties" />
    @endif
    @if (setting('display_faq_homepage', 1) == 1)
        <x-faqs-tools :faqs="$faqs" />
    @endif
    <x-relevant-tools :relevant_tools=$relevant_tools />
    @push('page_scripts')
        <script>
            const APP = function() {
                const monthNumbers = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September",
                    "October", "November", "December"
                ];
                const totalYears = 200;
                const selectYear = document.getElementById("year");
                const selectMonth = document.getElementById("month");
                const selectDay = document.getElementById("day");
                const selectYear2 = document.getElementById("from_year");
                const selectMonth2 = document.getElementById("from_month");
                const selectDay2 = document.getElementById("from_day");

                const initDate = function() {
                        var currentYear = new Date().getFullYear();
                        for (var y = 0; y < totalYears; y++) {
                            let date = new Date(currentYear);
                            var yearElem = document.createElement("option");
                            yearElem.value = currentYear
                            yearElem.textContent = currentYear;
                            selectYear.append(yearElem)
                            currentYear--;
                        }

                        for (var m = 0; m < 12; m++) {
                            let month = months[m];
                            var monthElem = document.createElement("option");
                            monthElem.value = m;
                            monthElem.textContent = month;
                            selectMonth.append(monthElem)
                        }

                        var d = new Date();
                        var year = {{ isset($year) ? $year : 'd.getFullYear()' }};
                        var month = {{ isset($month) ? $month : 'd.getMonth()' }};
                        var day = {{ isset($day) ? $day : 'd.getDate()' }};
                        selectYear.addEventListener("change", AdjustDays);
                        selectMonth.addEventListener("change", AdjustDays);
                        selectYear.value = year;
                        selectMonth.value = month;
                        AdjustDays();
                        selectDay.value = day

                        function AdjustDays() {
                            var year = selectYear.value;
                            var month = parseInt(selectMonth.value) + 1;
                            var currentVal = selectDay.value;
                            removeAll(selectDay)
                            var days = new Date(year, month, 0).getDate();
                            for (var d = 1; d <= days; d++) {
                                var dayElem = document.createElement("option");
                                dayElem.value = d;
                                if (currentVal == d) {
                                    dayElem.setAttribute('selected', 'selected');
                                } else if (d < currentVal) {
                                    dayElem.setAttribute('selected', 'selected');
                                }
                                dayElem.textContent = d;
                                selectDay.append(dayElem);
                            }
                        }
                    },
                    initDate2 = function() {
                        var currentYear = new Date().getFullYear();
                        for (var y = 0; y < totalYears; y++) {
                            let date = new Date(currentYear);
                            var yearElem = document.createElement("option");
                            yearElem.value = currentYear
                            yearElem.textContent = currentYear;
                            selectYear2.append(yearElem)
                            currentYear--;
                        }

                        for (var m = 0; m < 12; m++) {
                            let month = months[m];
                            var monthElem = document.createElement("option");
                            monthElem.value = m;
                            monthElem.textContent = month;
                            selectMonth2.append(monthElem)
                        }

                        var d = new Date();
                        var year = {{ isset($year2) ? $year2 : 'd.getFullYear()' }};
                        var month = {{ isset($month2) ? $month2 : 'd.getMonth()' }};
                        var day = {{ isset($day2) ? $day2 : 'd.getDate()' }};
                        selectYear2.value = year;
                        selectYear2.addEventListener("change", AdjustDays);
                        selectMonth2.value = month;
                        selectMonth2.addEventListener("change", AdjustDays);
                        AdjustDays();
                        selectDay2.value = day

                        function AdjustDays() {
                            var year = selectYear2.value;
                            var month = parseInt(selectMonth2.value) + 1;
                            var currentVal = selectDay2.value;
                            removeAll(selectDay2)
                            var days = new Date(year, month, 0).getDate();
                            for (var d = 1; d <= days; d++) {
                                var dayElem = document.createElement("option");
                                dayElem.value = d;
                                if (currentVal == d) {
                                    dayElem.setAttribute('selected', 'selected');
                                } else if (d < currentVal) {
                                    dayElem.setAttribute('selected', 'selected');
                                }
                                dayElem.textContent = d;
                                selectDay2.append(dayElem);
                            }
                        }
                    },
                    removeAll = function(selectBox) {
                        while (selectBox.options.length > 0) {
                            selectBox.remove(0);
                        }
                    };

                return {
                    init: function() {
                        initDate()
                        initDate2()
                        const form = document.getElementById('frmAgeCalc')
                        form.addEventListener('submit', function(e) {
                            if (selectYear.value > selectYear2.value) {
                                ArtisanApp.toastError('{{ __('tools.AgeCalcGreaterError') }}');
                                e.preventDefault();
                                return false
                            }

                            if (selectYear.value == selectYear2.value) {
                                if (selectMonth.value > selectMonth2.value) {
                                    ArtisanApp.toastError('{{ __('tools.AgeCalcGreaterError') }}');
                                    e.preventDefault();
                                    return false
                                } else if (selectDay.value > selectDay2.value) {
                                    ArtisanApp.toastError('{{ __('tools.AgeCalcGreaterError') }}');
                                    e.preventDefault();
                                    return false
                                }
                            }
                            ArtisanApp.showLoader()
                        });
                    }
                }
            }();

            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-tool-home-layout>
