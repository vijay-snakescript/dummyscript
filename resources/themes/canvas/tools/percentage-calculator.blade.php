<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow mb-3 wrap-content">
                <div class="row">
                    <div class="form-group mb-3">
                        <x-input-label>@lang('tools.formula')</x-input-label>
                        <select name="formula" id="formula" required
                            class="form-control form-select @if ($errors->has('formula')) is-invalid @endif">
                            <option value="1" @if (isset($results) && $results['formula'] == '1') selected @endif>
                                @lang('tools.whatIsPOfwhat')</option>
                            <option value="2" @if (isset($results) && $results['formula'] == '2') selected @endif>
                                @lang('tools.yIsWhatPercentage')</option>
                            <option value="3" @if (isset($results) && $results['formula'] == '3') selected @endif>
                                @lang('tools.yisPofWhat')</option>
                            <option value="4" @if (isset($results) && $results['formula'] == '4') selected @endif>
                                @lang('tools.whatOfXisY')</option>
                            <option value="5" @if (isset($results) && $results['formula'] == '5') selected @endif>
                                @lang('tools.pOfWhatIsY')</option>
                            <option value="6" @if (isset($results) && $results['formula'] == '6') selected @endif>
                                @lang('tools.pOfXisWhat')</option>
                            <option value="7" @if (isset($results) && $results['formula'] == '7') selected @endif>
                                @lang('tools.yOfOutWhat')</option>
                            <option value="8" @if (isset($results) && $results['formula'] == '8') selected @endif>
                                @lang('tools.whatOutOfX')</option>
                            <option value="9" @if (isset($results) && $results['formula'] == '9') selected @endif>
                                @lang('tools.yOutOfXis')</option>
                            <option value="10" @if (isset($results) && $results['formula'] == '10') selected @endif>
                                @lang('tools.xPlusPis')</option>
                            <option value="11" @if (isset($results) && $results['formula'] == '11') selected @endif>
                                @lang('tools.xPlusWhatIs')</option>
                            <option value="12" @if (isset($results) && $results['formula'] == '12') selected @endif>
                                @lang('tools.whatPlusPisY')</option>
                            <option value="13" @if (isset($results) && $results['formula'] == '13') selected @endif>
                                @lang('tools.xMinusPisWhat')</option>
                            <option value="14" @if (isset($results) && $results['formula'] == '14') selected @endif>
                                @lang('tools.XminusWhatisP')</option>
                            <option value="15" @if (isset($results) && $results['formula'] == '15') selected @endif>
                                @lang('tools.whatMinusPisY')</option>
                        </select>
                        <x-input-error :messages="$errors->get('formula')" />
                    </div>
                </div>
                <div class="form-inline">
                    <div class="form-group">
                        <label class="form-label" id="beforeLabel"></label>
                        <input class="form-control" name="first" id="number_1" placeholder="0" type="number"
                            min="0" step="0.01" required="required">
                    </div>
                    <div class="form-group">
                        <label class="form-label" id="afterLabel"></label>
                        <input class="form-control" name="second" id="number_2" placeholder="0" type="number"
                            min="0" step="0.01" required="required">
                    </div>
                    <div class="form-group">
                        <label class="form-label" id="lastLabel"></label>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end mt-3">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.calculate')
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')">
                <div class="result mt-4 result-printable">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-style">
                                <tbody>
                                    <tr>
                                        <th>@lang('tools.firstNo')</th>
                                        <td>
                                            <div id="first">{{ $results['first'] }}</div>
                                        </td>
                                        <td class="d-print-none">
                                            <x-copy-target target="first" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.secondNo')</th>
                                        <td>
                                            <div id="second">{{ $results['second'] }}</div>
                                        </td>
                                        <td class="d-print-none">
                                            <x-copy-target second="" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.result')</th>
                                        <td>
                                            <div id="calculation">
                                                {{ round($results['solution']['calculation'], 3) }}</div>
                                        </td>
                                        <td class="d-print-none">
                                            <x-copy-target target="calculation" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <div class="box-shadow mb-3 py-5">
                                <h4>@lang('tools.howWeGet')</h4>
                                <ul class="list-group list-group-flush">
                                    @foreach (preg_split('/\R/', $results['solution']['equation']) as $string)
                                        <li class="list-group-item">{{ $string }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="text-end tool-actions d-print-none">
                                <x-print-button
                                    onclick="ArtisanApp.printResult(document.querySelector('.result-printable'), {title: '{{ $tool->name }}'})"
                                    :text="__('tools.printResult')" />
                                <x-reload-button :tooltip="__('tools.calculateAnother')" :link="route('tool.show', ['tool' => $tool->slug])" />
                            </div>
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
        </div>
        <x-ad-slot :advertisement="get_advert_model('below-result')" />
    @endif
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script>
            const APP = function() {
                const beforeText = new Array("{{ __('tools.whatIs') }}", "", "", "{{ __('tools.whatPercentOf') }}", "", "",
                    "", "{{ __('tools.whatoutOf') }}", "", "", "", "{{ __('tools.whatPlus') }}", "", "",
                    "{{ __('tools.whatMinus') }}");
                const afterText = new Array("{{ __('tools.percentOf') }}", "{{ __('tools.isWhatPercentOf') }}",
                    "{{ __('tools.is') }}", "{{ __('tools.is') }}", "{{ __('tools.percentOfWhatIs') }}",
                    "{{ __('tools.percentOf') }}", "{{ __('tools.outOfWhatIs') }}", "{{ __('tools.is') }}",
                    "{{ __('tools.outOf') }}", "{{ __('tools.plus') }}", "{{ __('tools.plusWhatPercentIs') }}",
                    "{{ __('tools.percentIs') }}", "{{ __('tools.minus') }}", "{{ __('tools.minusWhatPercentIs') }}",
                    "{{ __('tools.percentIs') }}");
                const lastText = new Array("?", "?", "{{ __('tools.percentofWhatQ') }}", "?", "?",
                    "{{ __('tools.isWhatQ') }}", "% ?", "% ?", "{{ __('tools.isWhatPercentQ') }}",
                    "{{ __('tools.percentIsWhatQ') }}", "?", "?", "{{ __('tools.percentIsWhatQ') }}", "?", "?");
                const firstPlaceholder = new Array("P", "Y", "Y", "X", "P", "P", "Y", "X", "Y", "X", "X", "P", "X", "X",
                    "P");
                const secondPlaceholder = new Array("X", "X", "P", "Y", "Y", "X", "P", "P", "X", "P", "Y", "Y", "P", "Y",
                    "Y");

                const attachEvents = function() {
                        document.querySelector('#formula').addEventListener('change', () => {
                            setText();
                        });
                    },
                    setText = function() {
                        var selectedIndex = document.getElementById("formula").selectedIndex
                        const firstLabel = document.getElementById("beforeLabel");
                        if (beforeText[selectedIndex] == '') {
                            firstLabel.classList.add('d-none')
                        } else {
                            firstLabel.classList.remove('d-none')
                        }
                        firstLabel.innerHTML = beforeText[selectedIndex];
                        document.getElementById("afterLabel").innerHTML = afterText[selectedIndex];
                        document.getElementById("lastLabel").innerHTML = lastText[selectedIndex];
                        document.getElementById("number_1").placeholder = firstPlaceholder[selectedIndex];
                        document.getElementById("number_2").placeholder = secondPlaceholder[selectedIndex];
                    };
                return {
                    init: function() {
                        attachEvents()
                        setText()
                    }
                }
            }()
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
