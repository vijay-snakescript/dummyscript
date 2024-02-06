<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <div class="box-shadow mb-3 pt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center mb-5">
                        <span class="h1 text-success" id="result-unit-number"></span>
                        <span class="text-muted" id="result-unit"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <x-input-label class="text-center d-block h4">
                            @lang('tools.from'):
                            <span class="ms-1" id="fromUnit"></span>
                        </x-input-label>
                        <div class="input-group">
                            <x-text-input value="1" class="form-control" name="from" type="number"
                                id="input_1" step="0.01" required min="0" />
                            <x-copy-target-group target="input_1" />
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <select name="from_unit" id="unit_1" required class="form-control form-select">
                            <option>Newton/sq.meter</option>
                            <option>Atmosphere (normal)</option>
                            <option>Atmosphere (techinical)</option>
                            <option>Bar</option>
                            <option>Centimeter mercury(cmHg)</option>
                            <option>Centimeter water (4'C)</option>
                            <option>Decibar</option>
                            <option>Kgr force/sq.centimeter</option>
                            <option>Kgr force/sq.meter</option>
                            <option>Kip/square inch</option>
                            <option>Millibar</option>
                            <option>Millimeter mercury(mmHg)</option>
                            <option>Pascal (Pa)</option>
                            <option>Kilopascal (kPa)</option>
                            <option>Megapascal (Mpa)</option>
                            <option>Poundal/sq.foot</option>
                            <option>Pound-force/sq.foot</option>
                            <option>Pound-force/sq.inch (psi)</option>
                            <option>Torr (mmHg,0'C)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <x-input-label class="text-center d-block h4">
                            @lang('tools.to'):
                            <span class="ms-1" id="toUnit"></span>
                        </x-input-label>
                        <div class="input-group">
                            <x-text-input value="1" class="form-control" name="from" type="number"
                                id="input_2" step="0.01" required min="0" />
                            <x-copy-target-group target="input_2" />
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <select name="to_unit" id="unit_2" required class="form-control form-select">
                            <option>Newton/sq.meter</option>
                            <option>Atmosphere (normal)</option>
                            <option>Atmosphere (techinical)</option>
                            <option>Bar</option>
                            <option>Centimeter mercury(cmHg)</option>
                            <option>Centimeter water (4'C)</option>
                            <option>Decibar</option>
                            <option>Kgr force/sq.centimeter</option>
                            <option>Kgr force/sq.meter</option>
                            <option>Kip/square inch</option>
                            <option>Millibar</option>
                            <option>Millimeter mercury(mmHg)</option>
                            <option>Pascal (Pa)</option>
                            <option>Kilopascal (kPa)</option>
                            <option>Megapascal (Mpa)</option>
                            <option>Poundal/sq.foot</option>
                            <option>Pound-force/sq.foot</option>
                            <option>Pound-force/sq.inch (psi)</option>
                            <option>Torr (mmHg,0'C)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </x-tool-wrapper>
    <x-ad-slot :advertisement="get_advert_model('below-result')" />
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script>
            const APP = function() {
                const factor = new Array();
                factor[0] = new Array(1, 101325, 98066.5, 100000, 1333.22, 98.0638, 10000, 98066.5, 9.80665, 6894757, 100,
                    133.3224, 1, 1000, 1000000, 47.88026, 47.88026, 6894.757, 133.322);
                const attachEvents = function() {
                        document.getElementById('fromUnit').innerHTML = document.querySelector('#unit_1').value
                        document.getElementById('toUnit').innerHTML = document.querySelector('#unit_2').value

                        document.querySelector('#input_1').addEventListener('keyup', () => {
                            CalculateUnit('1', '2');
                        });
                        document.querySelector('#input_2').addEventListener('keyup', () => {
                            CalculateUnit('2', '1');
                        });
                        document.querySelector('#input_1').addEventListener('change', () => {
                            CalculateUnit('1', '2');
                        });
                        document.querySelector('#input_2').addEventListener('change', () => {
                            CalculateUnit('2', '1');
                        });
                        document.querySelector('#unit_1').addEventListener('change', () => {
                            document.getElementById('fromUnit').innerHTML = document.querySelector('#unit_1').value
                            CalculateUnit('1', '2');
                        });
                        document.querySelector('#unit_2').addEventListener('change', () => {
                            document.getElementById('toUnit').innerHTML = document.querySelector('#unit_2').value
                            CalculateUnit('1', '2');
                        });
                    },
                    CalculateUnit = function(source, target) {
                        var val_1 = document.getElementById('input_1').value;
                        var val_2 = document.getElementById('input_2').value;
                        if (val_1 < 0 || val_2 < 0 || val_1 == "" || val_2 == "") {
                            ArtisanApp.toastError('{{ __('tools.powerConverterError') }}');
                            document.getElementById('input_1').value = 1;
                            document.getElementById('input_2').value = 1;
                            return;
                        }
                        sourceIndex = document.getElementById("unit_" + source).selectedIndex;
                        sourceFactor = factor[0][sourceIndex];

                        targetIndex = document.getElementById("unit_" + target).selectedIndex;
                        targetFactor = factor[0][targetIndex];

                        var get_input_id = "input_" + source;
                        result = document.getElementById(get_input_id).value;

                        result = result * sourceFactor;
                        result = result / targetFactor;

                        var get_input2_id = "input_" + target;
                        document.getElementById(get_input2_id).value = result;
                        document.getElementById('result-unit-number').innerHTML = result;
                        document.getElementById('result-unit').innerHTML = document.getElementById("unit_" + target).value;
                    };
                return {
                    init: function() {
                        attachEvents()
                        CalculateUnit('1', '2')
                    },
                }
            }()
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
