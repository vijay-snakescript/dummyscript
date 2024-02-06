<x-application-tools-wrapper>
    <x-ad-slot :advertisement="get_advert_model('above-tool')" />
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="h4 mb-4">@lang('tools.rgbToHexDesc')</h3>
                </div>
                <div class="col-md-12">
                    <div class="d-flex">
                        <label class="form-label me-3" for="r-value">R</label>
                        <div class="range-slider mb-3">
                            <input id="r-value" class="range-slider__range change-listner" type="range" value="0"
                                min="0" max="255">
                            <span class="range-slider__value"></span>
                        </div>
                    </div>
                    <div class="d-flex">
                        <label class="form-label me-3" for="g-value">G</label>
                        <div class="range-slider mb-3">
                            <input id="g-value" class="range-slider__range change-listner" type="range"
                                value="0" min="0" max="255">
                            <span class="range-slider__value"></span>
                        </div>
                    </div>
                    <div class="d-flex">
                        <label class="form-label me-3" for="g-value">B</label>
                        <div class="range-slider mb-3">
                            <input id="b-value" class="range-slider__range change-listner" type="range"
                                value="0" min="0" max="255">
                            <span class="range-slider__value"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="w-100 mh-100 border-bg" id="color-preview"></div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label" for="hex-value">@lang('tools.hexValue')</label>
                        <div class="input-group">
                            <input class="form-control" id="hex-value" type="text" readonly>
                            <x-copy-target-group target="hex-value" />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label" for="rgb-value">@lang('tools.rGBValue')</label>
                        <div class="input-group">
                            <input class="form-control" id="rgb-value" type="text" readonly>
                            <x-copy-target-group target="rgb-value" />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label class="form-label" for="hsl-value">@lang('tools.hSLValue')</label>
                        <div class="input-group">
                            <input class="form-control" id="hsl-value" type="text" readonly>
                            <x-copy-target-group target="hsl-value" />
                        </div>
                    </div>
                </div>
            </div>
        </x-form>
        <x-ad-slot class="mt-3" :advertisement="get_advert_model('below-form')" />
    </x-tool-wrapper>
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script>
            const APP = function() {
                const updateValues = function(hexa, rgb, hsl) {
                        document.getElementById("hex-value").value = hexa;
                        document.getElementById("rgb-value").value = rgb;
                        document.getElementById("hsl-value").value = hsl;

                        document.getElementById('color-preview').style.backgroundColor = hexa
                    },
                    convertColors = function() {
                        const r = document.getElementById("r-value").value;
                        const g = document.getElementById("g-value").value;
                        const b = document.getElementById("b-value").value;
                        const hslArray = RGBToHSL(r, g, b);
                        const hexa = "#" + (1 << 24 | r << 16 | g << 8 | b).toString(16).slice(1);
                        const rgb = "rgb(" + r + "," + g + "," + b + ")";
                        const hsl = `hsl(${hslArray[0]}, ${hslArray[1]}%, ${hslArray[2]}%)`

                        updateValues(hexa, rgb, hsl)
                    },
                    RGBToHSL = (r, g, b) => {
                        const max = Math.max(r, g, b)
                        const min = Math.min(r, g, b)
                        const l = Math.floor((max + min) / ((0xff * 2) / 100))

                        if (max === min) return [0, 0, l]
                        const d = max - min
                        const s = Math.floor((d / (l > 50 ? 0xff * 2 - max - min : max + min)) * 100)

                        if (max === r) return [Math.floor(((g - b) / d + (g < b && 6)) * 60), s, l]
                        return max === g ? [Math.floor(((b - r) / d + 2) * 60), s, l] : [Math.floor(((r - g) / d + 4) * 60),
                            s, l
                        ]
                    },
                    attachEvents = function() {
                        document.querySelectorAll('.change-listner').forEach(input => {
                            input.addEventListener('change', elem => {
                                convertColors()
                            })
                        });
                    };

                return {
                    init: function() {
                        convertColors()
                        attachEvents();
                    }
                }
            }();

            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
