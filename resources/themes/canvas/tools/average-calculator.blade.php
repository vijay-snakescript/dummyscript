<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow mb-3">
                <div class="row" id="extra_fields">
                    @if (isset($results))
                        @foreach ($results['numbers'] as $num)
                            <div class="col-md-6" id="remove_div_{{ $loop->iteration }}">
                                <div class="input-icon mb-3">
                                    <span class="icon">
                                        <i class="an an-long-arrow-up text-muted"></i>
                                    </span>
                                    <x-text-input class="form-control" name="number[]" type="number" step="0.01" :error="$errors->has('number')"
                                        required placeholder="#:" value="{{ $num }}" />
                                    <i class="an an-times-circle text-danger remove-field-icon"
                                        onclick="APP.removeField({{ $loop->iteration }})" class="remove-field-icon"></i>
                                    <x-input-error :messages="$errors->get('number')" />
                                </div>
                            </div>
                        @endforeach
                    @else
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="col-md-6" id="remove_div_{{ $i }}">
                                <div class="input-icon mb-3">
                                    <span class="icon">
                                        <i class="an an-long-arrow-up text-muted"></i>
                                    </span>
                                    <x-text-input class="form-control" name="number[]" type="number" step="0.01" :error="$errors->has('number')"
                                        required placeholder="#:" />
                                    <i class="an an-times-circle text-danger remove-field-icon"
                                        onclick="APP.removeField({{ $i }})" class="remove-field-icon"></i>
                                    <x-input-error :messages="$errors->get('number')" />
                                </div>
                            </div>
                        @endfor
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <x-button type="button" class="btn btn-primary rounded-pill ps-5 pe-5 mt-3"
                            id="add_more_fields">
                            @lang('common.addMore')
                        </x-button>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('common.generate')
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')">
                <div class="result mt-4">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-style mb-0">
                                <tbody>
                                    <tr>
                                        <th width="200">@lang('tools.count')</th>
                                        <td class="text-start ps-3">
                                            <div class="text-break">
                                                {{ $results['count'] }}
                                            </div>
                                        </td>
                                        <td>
                                            <x-copy-text :text="$results['count']" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.average')</th>
                                        <td class="text-start ps-3">
                                            <div class="text-break">
                                                {{ $results['average'] }}
                                            </div>
                                        </td>
                                        <td>
                                            <x-copy-text :text="$results['average']" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.geomatricSum')</th>
                                        <td class="text-start ps-3">
                                            <div class="text-break">
                                                {{ $results['geomatric_sum'] }}
                                            </div>
                                        </td>
                                        <td>
                                            <x-copy-text :text="$results['geomatric_sum']" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.harmonicMean')</th>
                                        <td class="text-start ps-3">
                                            <div class="text-break">
                                                {{ $results['harmonic_mean'] }}
                                            </div>
                                        </td>
                                        <td>
                                            <x-copy-text :text="$results['harmonic_mean']" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.median')</th>
                                        <td class="text-start ps-3">
                                            <div class="text-break">
                                                {{ $results['median'] }}
                                            </div>
                                        </td>
                                        <td>
                                            <x-copy-text :text="$results['median']" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.largest')</th>
                                        <td class="text-start ps-3">
                                            <div class="text-break">
                                                {{ $results['largest'] }}
                                            </div>
                                        </td>
                                        <td>
                                            <x-copy-text :text="$results['largest']" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.smallest')</th>
                                        <td class="text-start ps-3">
                                            <div class="text-break">
                                                {{ $results['smallest'] }}
                                            </div>
                                        </td>
                                        <td>
                                            <x-copy-text :text="$results['smallest']" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.range')</th>
                                        <td class="text-start ps-3">
                                            <div class="text-break">
                                                {{ $results['range'] }}
                                            </div>
                                        </td>
                                        <td>
                                            <x-copy-text :text="$results['range']" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
        </div>
    @endif
    <x-ad-slot :advertisement="get_advert_model('below-result')" />
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script>
            const APP = function() {
                const maxField = 50;
                const addButton = document.getElementById('add_more_fields');
                var cursor = {{ isset($results['numbers']) ? count($results['numbers']) + 1 : 5 }};
                const attachEvents = function() {
                        addButton.addEventListener("click", () => {
                            addField()
                        });
                    },
                    addField = function() {
                        if (cursor > maxField) return;
                        var wrapper = document.getElementById('extra_fields');
                        var fieldElement = `<div class="input-icon mb-3">
                                            <span class="icon"><i class="an an-long-arrow-up text-muted"></i></span>
                                            <input class="form-control" name="number[]" type="number" step="0.01" placeholder="#:" required />
                                            <i class="an an-times-circle text-danger remove-field-icon" onclick="APP.removeField(${cursor})" class="remove-field-icon"></i>
                                        </div>`;
                        var tempNode = document.createElement('div');
                        tempNode.className = 'col-md-6'
                        tempNode.id = `remove_div_${cursor}`
                        tempNode.innerHTML = fieldElement;
                        wrapper.appendChild(tempNode)
                        tempNode.querySelector('input').focus()
                        if (cursor == maxField) addButton.classList.add('d-none');
                        cursor++;
                    };

                return {
                    init: function() {
                        attachEvents();
                    },
                    removeField: function(id) {
                        if (3 < cursor) {
                            document.getElementById("remove_div_" + id).remove();
                            cursor--;
                        }
                    }
                };

            }();
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
