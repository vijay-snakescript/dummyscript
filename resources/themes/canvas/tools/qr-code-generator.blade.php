<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <div class="row">
            <div class="col-md-12">
                <div class="tab-content rounded-bottom">
                    <div class="tabbar">
                        <ul class="nav nav-tabs nav-fill mb-3" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if ($type == 0 || $type == 1) active @endif" id="url-tab"
                                    data-bs-toggle="tab" data-bs-target="#url" type="button" role="tab"
                                    aria-controls="url" aria-selected="true">
                                    <i class="an an-link"></i>
                                    @lang('tools.urlName')
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if ($type == 2) active @endif" id="vcard-tab"
                                    data-bs-toggle="tab" data-bs-target="#vcard" type="button" role="tab"
                                    aria-controls="vcard" aria-selected="false">
                                    <i class="an an-vcard"></i>
                                    @lang('tools.vcard')
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if ($type == 3) active @endif" id="text-tab"
                                    data-bs-toggle="tab" data-bs-target="#text" type="button" role="tab"
                                    aria-controls="text" aria-selected="false">
                                    <i class="an an-text"></i>
                                    @lang('tools.text')
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if ($type == 4) active @endif"
                                    id="emailbox-tab" data-bs-toggle="tab" data-bs-target="#emailbox" type="button"
                                    role="tab" aria-controls="emailbox" aria-selected="false">
                                    <i class="an an-email"></i>
                                    @lang('tools.email')
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if ($type == 5) active @endif" id="sms-tab"
                                    data-bs-toggle="tab" data-bs-target="#sms" type="button" role="tab"
                                    aria-controls="sms" aria-selected="false">
                                    <i class="an an-sms"></i>
                                    @lang('tools.sms')
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade @if ($type == 0 || $type == 1) active show @endif"
                                id="url" role="tabpanel" aria-labelledby="url-tab">
                                <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <x-input-label>@lang('tools.enterURL')</x-input-label>
                                                <x-text-input type="url" class="form-control" name="url"
                                                    id="url" required value="{{ $results['url'] ?? old('url') }}"
                                                    :error="$errors->has('url')" />
                                                <x-input-error :messages="$errors->get('url')" class="mt-2" />
                                            </div>
                                        </div>
                                        <input type="hidden" value="1" name="type" required />
                                        <x-qr-code-setting :results="$results ?? null" :imagick="$imagick" />
                                        <div class="col-md-12 text-end mt-4">
                                            <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                                                @lang('tools.generateQr')
                                            </x-button>
                                        </div>
                                    </div>
                                </x-form>
                            </div>
                            <div class="tab-pane fade @if ($type == 2) active show @endif"
                                id="vcard" role="tabpanel" aria-labelledby="vcard-tab">
                                <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <x-input-label>@lang('tools.fullName')</x-input-label>
                                                <x-text-input type="text" class="form-control" name="full_name"
                                                    id="full_name" required
                                                    value="{{ $results['full_name'] ?? old('full_name') }}"
                                                    :error="$errors->has('full_name')" />
                                                <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <x-input-label>@lang('tools.email')</x-input-label>
                                                <x-text-input type="email" class="form-control" name="email"
                                                    id="email" required
                                                    value="{{ $results['email'] ?? old('email') }}"
                                                    :error="$errors->has('email')" />
                                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <x-input-label>@lang('tools.contact')</x-input-label>
                                                <x-text-input type="text" class="form-control" name="contact"
                                                    id="contact" required
                                                    value="{{ $results['contact'] ?? old('contact') }}"
                                                    :error="$errors->has('contact')" />
                                                <x-input-error :messages="$errors->get('contact')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <x-input-label>@lang('tools.address')</x-input-label>
                                                <x-textarea-input type="text" rows="6" class="form-control"
                                                    name="address" id="address" required :error="$errors->has('address')">
                                                    {{ $results['address'] ?? old('address') }}
                                                </x-textarea-input>
                                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                            </div>
                                        </div>
                                        <input type="hidden" value="2" name="type" required />
                                        <x-qr-code-setting :results="$results ?? null" :imagick="$imagick" />
                                        <div class="col-md-12 text-end mt-4">
                                            <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                                                @lang('tools.generateQr')
                                            </x-button>
                                        </div>
                                    </div>
                                </x-form>
                            </div>
                            <div class="tab-pane fade @if ($type == 3) active show @endif"
                                id="text" role="tabpanel" aria-labelledby="text-tab">
                                <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <x-input-label>@lang('tools.text')</x-input-label>
                                                <x-textarea-input type="text" rows="12" class="form-control"
                                                    name="text" id="text" required :error="$errors->has('text')">
                                                    {{ $results['text'] ?? old('text') }}
                                                </x-textarea-input>
                                                <x-input-error :messages="$errors->get('text')" class="mt-2" />
                                            </div>
                                        </div>
                                        <input type="hidden" value="3" name="type" required />
                                        <x-qr-code-setting :results="$results ?? null" :imagick="$imagick" />
                                        <div class="col-md-12 text-end mt-4">
                                            <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                                                @lang('tools.generateQr')
                                            </x-button>
                                        </div>
                                    </div>
                                </x-form>
                            </div>
                            <div class="tab-pane fade @if ($type == 4) active show @endif"
                                id="emailbox" role="tabpanel" aria-labelledby="emailbox-tab">
                                <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <x-input-label>@lang('tools.email')</x-input-label>
                                                <x-text-input type="email" class="form-control" name="email"
                                                    id="emailid" required
                                                    value="{{ $results['email'] ?? old('email') }}"
                                                    :error="$errors->has('email')" />
                                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <x-input-label>@lang('tools.subject')</x-input-label>
                                                <x-text-input type="subject" class="form-control" name="subject"
                                                    id="subject" required
                                                    value="{{ $results['subject'] ?? old('subject') }}"
                                                    :error="$errors->has('subject')" />
                                                <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <x-input-label>@lang('tools.message')</x-input-label>
                                                <x-textarea-input type="text" rows="6" class="form-control"
                                                    name="email_message" id="email_message" required
                                                    :error="$errors->has('email_message')">
                                                    {{ $results['email_message'] ?? old('email_message') }}
                                                </x-textarea-input>
                                                <x-input-error :messages="$errors->get('email_message')" class="mt-2" />
                                            </div>
                                        </div>
                                        <input type="hidden" value="4" name="type" required />
                                        <x-qr-code-setting :results="$results ?? null" :imagick="$imagick" />
                                        <div class="col-md-12 text-end mt-4">
                                            <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                                                @lang('tools.generateQr')
                                            </x-button>
                                        </div>
                                    </div>
                                </x-form>
                            </div>
                            <div class="tab-pane fade @if ($type == 5) active show @endif"
                                id="sms" role="tabpanel" aria-labelledby="sms-tab">
                                <x-form method="post" :route="route('tool.handle', $tool->slug)" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <x-input-label>@lang('tools.contact')</x-input-label>
                                                <x-text-input type="text" class="form-control" name="number"
                                                    id="number" required
                                                    value="{{ $results['number'] ?? old('number') }}"
                                                    :error="$errors->has('number')" />
                                                <x-input-error :messages="$errors->get('number')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <x-input-label>@lang('tools.message')</x-input-label>
                                                <x-textarea-input type="text" rows="6" class="form-control"
                                                    name="sms_message" id="sms_message" required :error="$errors->has('sms_message')">
                                                    {{ $results['sms_message'] ?? old('sms_message') }}
                                                </x-textarea-input>
                                                <x-input-error :messages="$errors->get('sms_message')" class="mt-2" />
                                            </div>
                                        </div>
                                        <input type="hidden" value="5" name="type" required />
                                        <x-qr-code-setting :results="$results ?? null" :imagick="$imagick" />
                                        <div class="col-md-12 text-end mt-4">
                                            <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                                                @lang('tools.generateQr')
                                            </x-button>
                                        </div>
                                    </div>
                                </x-form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-tool-wrapper>
    @if (isset($results) && isset($results['qrFormat']))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')" :sub-title="__('tools.downloadFormatQR', ['format' => $results['qrFormat']])">
                <div class="result mt-4">
                    <div class="row">
                        <div class="col-md-12 svg-wrap" id="qr-svg-wrapper">
                            @if ($imagick)
                                @if ($results['qrFormat'] == 'svg' || $results['qrFormat'] == 'eps')
                                    {!! $results['qrCode'] !!}
                                    <x-textarea-input class="d-none" id="qrDownload">
                                        {{ $results['qrCode'] }}
                                    </x-textarea-input>
                                @elseif($results['qrFormat'] == 'png')
                                    {!! "<img id=\"qrDownload\" src=\"data:image/{$results['qrFormat']};base64, " .
                                        base64_encode($results['qrPreview']) .
                                        "\">" !!}
                                @endif
                            @else
                                {!! $results['qrCode'] !!}
                                <canvas id="canvas" height="{{ $results['size'] }}"
                                    width="{{ $results['size'] }}" class="d-none"></canvas>
                            @endif
                        </div>
                        <div class="col-md-12 mb-3 mt-3">
                            <x-download-form-button type="button" :text="__('tools.downloadQr')" id="downloadQr" />
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
        </div>
        <x-ad-slot :advertisement="get_advert_model('below-result')" />
    @endif
    <x-tool-content :tool="$tool" />
    @if (isset($results) && isset($results['qrFormat']))
        @push('page_scripts')
            <script>
                const APP = function() {
                    const colorElem = document.getElementById("color_type");
                    const formatElem = document.querySelectorAll(".qr-format");
                    const downloadBtn = document.getElementById('downloadQr')
                    const colorInput = function() {
                            if (colorElem) {
                                if (colorElem.value != 0) {
                                    document.getElementById("color_sec_div").style.display = "block";
                                } else {
                                    document.getElementById("color_sec_div").style.display = "none";
                                }
                            }
                        },
                        attachEvents = function() {
                            colorElem.addEventListener('change', () => {
                                colorInput()
                            })
                            if (downloadBtn) {
                                downloadBtn.addEventListener('click', e => {
                                    e.preventDefault();
                                    downloadQR();
                                })
                            }
                            if (formatElem.length > 0) {
                                formatElem.forEach(selectField => {
                                    showDownload(selectField)
                                    selectField.addEventListener('change', e => {
                                        showDownload(e.target)
                                    })
                                });
                            }
                        },
                        showDownload = function(e) {
                            let upload = e.parentNode.parentNode.parentNode.querySelector(
                                '.imageUpload');
                            if (e.value == 'png') {
                                upload?.classList.remove('d-none')
                            } else {
                                upload?.classList.add('d-none')
                            }
                        },
                        downloadQR = function() {
                            @if (!$imagick)
                                let canvas = document.querySelector('#canvas');
                                let ctx = canvas.getContext('2d');
                                let data = (new XMLSerializer()).serializeToString(document.querySelector(
                                    '#qr-svg-wrapper > svg'));
                                let DOMURL = window.URL || window.webkitURL || window;

                                let img = new Image();
                                let svgBlob = new Blob([data], {
                                    type: 'image/svg+xml;charset=utf-8'
                                });
                                let url = DOMURL.createObjectURL(svgBlob);

                                img.onload = function() {
                                    ctx.drawImage(img, 0, 0);
                                    DOMURL.revokeObjectURL(url);
                                    var imgURI = canvas.toDataURL('image/png').replace('image/png', 'image/octet-stream');
                                    ArtisanApp.downloadFromUrl(imgURI, '{{ $tool->slug }}.png');
                                };
                                img.src = url;
                            @else
                                @if ($results['qrFormat'] != 'png')
                                    const imgURI = document.querySelector('#qrDownload').value;
                                    ArtisanApp.downloadAsTxt(imgURI, {
                                        isElement: false,
                                        filename: '{{ $tool->slug }}.{{ $results['qrFormat'] }}',
                                        fileMime: '{{ $results['mime'] }}'
                                    });
                                @else
                                    const imgURI = document.querySelector('#qrDownload').src;
                                    ArtisanApp.downloadFromUrl(imgURI, '{{ $tool->slug }}.png');
                                @endif
                            @endif
                        };

                    return {
                        init: function() {
                            attachEvents()
                        }
                    };
                }();

                document.addEventListener("DOMContentLoaded", function(event) {
                    APP.init();
                });
            </script>
        @endpush
    @endif
</x-application-tools-wrapper>
