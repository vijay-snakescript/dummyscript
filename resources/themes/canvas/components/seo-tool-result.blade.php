 @props([
    'results' => null,
    'tool' => null,
])

 <div class="printable-container" data-bs-spy="scroll" data-bs-target="#seo-report-navbar" data-bs-offset="0"
     class="scrollspy-example" tabindex="0">
     <div class="scroll-top-margin pagebreak" id="overview">
         <x-report-wrapper :title="__('seo.overview')">
             <x-slot name="actions">
                 <p class="mb-0">
                     @lang('seo.reportGeneratedDate', ['date' => now()->format(setting('datetime_format'))])
                 </p>
             </x-slot>
             {{-- report screen and info --}}
             <div class="row">
                 <div class="col-12">
                     <div class="report-img my-4">
                         <div class="image">
                             <img class="laptop" src="/themes/default/images/mac.svg"
                                 alt="{{ $results['result']['baseUrl'] ?? '' }}">
                             <div class="screenshot">
                                 <img class="w-100" src="{{ generateScreenshot($results['result']['baseUrl']) }}">
                             </div>
                         </div>
                     </div>
                     <div class="info text-center">
                         <div class="my-2 h5">
                             {{ $results['result']['title']['string'] ?? '' }}
                         </div>
                         <div class="my-2 line-truncate line-1"><a href="{{ $results['result']['baseUrl'] ?? '#' }}"
                                 rel="nofollow" target="_blank">{{ $results['result']['baseUrl'] ?? '' }}</a>
                         </div>
                         <div class="my-2 text-break text-muted">
                             {{ $results['result']['description']['string'] ?? '' }}.
                         </div>
                     </div>
                 </div>
             </div>
             {{-- report facts --}}
             <div class="report-facts py-3">
                 <div class="row">
                     <div class="col-lg-3">
                         <div class="progress-wrap">
                             <div class="progress-value">
                                 <div>{{ $results['result']['score']['page_percentage'] }}<br>
                                     <span class="d-block mt-2">@lang('seo.outOf100')</span>
                                 </div>
                             </div>
                             <svg data-percentage="{{ $results['result']['score']['page_percentage'] }}"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="-1 -1 34 34">
                                 <circle cx="16" cy="16" r="15.9" class="circle" />
                                 <circle cx="16" cy="16" r="15.9"
                                     class="progress progress-{{ $results['result']['score']['page_percentage'] >= 75 ? 'success' : ($results['result']['score']['page_percentage'] > 60 ? 'warning' : 'danger') }}"
                                     style="stroke-dashoffset: {{ 100 - $results['result']['score']['page_percentage'] }}px;" />
                             </svg>
                         </div>
                     </div>
                     <div class="col-lg-9">
                         <div class="row">
                             <div class="col-md-6 facts" data-bs-toggle="tooltip" title="@lang('seo.loadTime')">
                                 <div class="d-flex position-relative align-items-center">
                                     <i class="an an-stopwatch an-2x an-light"></i>
                                     <span class="me-2 ps-3">@lang('seo.secondCount', ['count' => $results['result']['loadtime']])</span>
                                 </div>
                             </div>
                             <div class="col-md-6 facts" data-bs-toggle="tooltip" title="@lang('seo.pageSize')">
                                 <div class="d-flex position-relative align-items-center">
                                     <i class="an an-balance an-2x an-light"></i>
                                     <span
                                         class="me-2 ps-3">{{ formatSizeUnits($results['result']['pagesize'] ?? 0) }}</span>
                                 </div>
                             </div>
                             <div class="col-md-6 facts" data-bs-toggle="tooltip" title="@lang('seo.httpRequests')">
                                 <div class="d-flex position-relative align-items-center">
                                     <i class="an an-resources an-2x an-light"></i>
                                     <span class="me-2 ps-3">@lang('seo.resourcesCount', ['count' => $results['result']['httpRequests']['total_requests']])</span>
                                 </div>
                             </div>
                             <div class="col-md-6 facts" data-bs-toggle="tooltip" title="@lang('seo.HTTPSEncryption')">
                                 <div class="d-flex position-relative align-items-center">
                                     <i class="an an-lock an-2x an-light"></i>
                                     <span
                                         class="me-2 ps-3">{{ $results['result']['ssl']['is_valid'] == true ? 'Secured' : 'Not Secured' }}
                                     </span>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             {{-- report stats --}}
             <div class="row">
                 <div class="col-12 col-sm-6 col-lg-3">
                     <div class="issue-type mt-3">
                         <div class="text-muted small mb-2">
                             <div class="percentage mb-2">
                                 <i class="an an-triangle text-danger"></i>
                                 <span>{{ $results['result']['high_test_count']['percentage'] }}%</span>
                             </div>
                             <div class="text-truncate text-muted small mb-1">@lang('seo.highIssue', ['count' => $results['result']['high_test_count']['passed']])</div>
                         </div>
                         <div class="progress progress-small w-100">
                             <div class="progress-bar bg-danger rounded" role="progressbar"
                                 style="width: {{ $results['result']['high_test_count']['percentage'] }}%"
                                 aria-valuenow="{{ $results['result']['high_test_count']['percentage'] }}"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                         </div>
                     </div>
                 </div>
                 <div class="col-12 col-sm-6 col-lg-3">
                     <div class="issue-type mt-3">
                         <div class="text-muted small mb-2">
                             <div class="percentage mb-2">
                                 <i class="an an-square text-warning"></i>
                                 <span>{{ $results['result']['medium_test_count']['percentage'] }}%</span>
                             </div>
                             <div class="text-truncate text-muted small mb-1">@lang('seo.mediumIssues', ['count' => $results['result']['medium_test_count']['passed']])</div>
                         </div>
                         <div class="progress progress-small w-100">
                             <div class="progress-bar bg-warning rounded" role="progressbar"
                                 style="width: {{ $results['result']['medium_test_count']['percentage'] }}%"
                                 aria-valuenow="{{ $results['result']['medium_test_count']['percentage'] }}"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                         </div>
                     </div>
                 </div>
                 <div class="col-12 col-sm-6 col-lg-3">
                     <div class="issue-type mt-3">
                         <div class="text-muted small mb-2">
                             <div class="percentage mb-2">
                                 <i class="an an-circle text-info"></i>
                                 <span>{{ $results['result']['low_test_count']['percentage'] }}%</span>
                             </div>
                             <div class="text-truncate text-muted small mb-1">@lang('seo.lowIssues', ['count' => $results['result']['low_test_count']['passed']])</div>
                         </div>
                         <div class="progress progress-small w-100">
                             <div class="progress-bar bg-info rounded" role="progressbar"
                                 style="width: {{ $results['result']['low_test_count']['percentage'] }}%"
                                 aria-valuenow="{{ $results['result']['low_test_count']['percentage'] }}"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                         </div>
                     </div>
                 </div>
                 <div class="col-12 col-sm-6 col-lg-3">
                     <div class="issue-type mt-3">
                         <div class="text-muted small mb-2">
                             <div class="percentage mb-2">
                                 <i class="an an-chack text-success"></i>
                                 <span>{{ $results['result']['test_count']['percentage'] }}%</span>
                             </div>
                             <div class="text-truncate text-muted small mb-1">@lang('seo.testPassedCount', ['count' => $results['result']['test_count']['passed']])</div>
                         </div>
                         <div class="progress progress-small w-100">
                             <div class="progress-bar bg-success rounded" role="progressbar"
                                 style="width: {{ $results['result']['test_count']['percentage'] }}%"
                                 aria-valuenow="{{ $results['result']['test_count']['percentage'] }}"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                         </div>
                     </div>
                 </div>
             </div>
         </x-report-wrapper>
     </div>
     <div class="scroll-top-margin pagebreak" id="seo">
         <x-report-wrapper :title="__('seo.commonSeoIssues')">
             @if (
                 $results['result']['count_section']['seo']['high'] > 0 ||
                     $results['result']['count_section']['seo']['medium'] > 0 ||
                     $results['result']['count_section']['seo']['low'] > 0)
                 <x-slot name="actions">
                     <div class="report-badges d-flex">
                         @if ($results['result']['count_section']['seo']['high'] > 0)
                             <span class="badge badge-danger ms-1">@lang('seo.highIssue', ['count' => $results['result']['count_section']['seo']['high']])</span>
                         @endif
                         @if ($results['result']['count_section']['seo']['medium'] > 0)
                             <span class="badge badge-warning ms-1">@lang('seo.mediumIssues', ['count' => $results['result']['count_section']['seo']['medium']])</span>
                         @endif
                         @if ($results['result']['count_section']['seo']['low'] > 0)
                             <span class="badge badge-info ms-1">@lang('seo.lowIssues', ['count' => $results['result']['count_section']['seo']['low']])</span>
                         @endif
                     </div>
                 </x-slot>
             @endif
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['title']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['title']['label']" />
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.title')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 @if ($results['result']['title']['passed'])
                                     <p>
                                         {{ __('seo.perfectTitle') }}
                                     </p>
                                 @else
                                     @foreach ($results['result']['title']['error'] as $type => $error)
                                         <p>{!! __("seo.titleError{$type}", $error) !!}</p>
                                     @endforeach
                                 @endif
                                 @if ($results['result']['title']['length'] != 0)
                                     <div
                                         class="alert alert-{{ $results['result']['title']['passed'] ? 'success' : 'danger' }}">
                                         <p class="mb-0">
                                             <strong>@lang('seo.text'):</strong>
                                             {{ $results['result']['title']['string'] ?? '' }}
                                         </p>
                                         <p class="mb-0">
                                             <strong>@lang('seo.length'):</strong>
                                             {{ __('seo.numberCharacters', ['count' => $results['result']['title']['length']]) }}
                                         </p>
                                     </div>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.titleExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['description']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' .
                                                 $results['result']['tests']['description']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.metaDescription')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 @if ($results['result']['description']['passed'])
                                     <p>
                                         {{ __('seo.perfectDescription') }}
                                     </p>
                                 @else
                                     @foreach ($results['result']['description']['error'] as $type => $error)
                                         <p>{!! __("seo.descriptionError{$type}", $error) !!}</p>
                                     @endforeach
                                 @endif
                                 @if ($results['result']['description']['length'] != 0)
                                     <div
                                         class="alert alert-{{ $results['result']['description']['passed'] ? 'success' : 'danger' }}">
                                         <p class="mb-0">
                                             <strong>@lang('seo.text'):</strong>
                                             {{ $results['result']['description']['string'] ?? '' }}
                                         </p>
                                         <p class="mb-0">
                                             <strong>@lang('seo.length'):</strong>
                                             {{ __('seo.numberCharacters', ['count' => $results['result']['description']['length']]) }}
                                         </p>
                                     </div>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.metaDescrptionExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="fw-bold">@lang('seo.googleSearchPreview')</div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-2"><strong>@lang('seo.desktopVersion')</strong></p>
                                 <div class="google-search-preview desktop-version border shadow-sm px-3 py-2 mb-4">
                                     <div class="site-url line-truncate line-1">
                                         {{ $results['result']['url'] }}</div>
                                     <div class="site-title line-truncate line-1">
                                         {{ Str::limit($results['result']['title']['string'], config('artisan.seo.page_title_max')) }}
                                     </div>
                                     <div class="site-description line-truncate line-2">
                                         {{ Str::limit($results['result']['description']['string'], config('artisan.seo.meta_description_max')) }}
                                     </div>
                                 </div>
                                 <p class="mb-2"><strong>@lang('seo.mobileVersion')</strong></p>
                                 <div class="google-search-preview mobile-version border shadow-sm px-3 py-2">
                                     <div class="site-url line-truncate line-1">
                                         {{ $results['result']['url'] }}</div>
                                     <div class="site-title line-truncate line-2">
                                         {{ Str::limit($results['result']['title']['string'], config('artisan.seo.page_title_max')) }}
                                     </div>
                                     <div class="site-description line-truncate line-3">
                                         {{ Str::limit($results['result']['description']['string'], config('artisan.seo.meta_description_max')) }}
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <i class="an an-circle-down-arrow an-2x invisible"></i>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['heading']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['heading']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.headings')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 @if ($results['result']['full_page']['headers']['total'] > 0)
                                     <p>@lang('seo.hasHeadingsTag')</p>
                                     <ul class="list-group rounded-0">
                                         @foreach ($results['result']['full_page']['headers']['tags'] as $key => $header)
                                             @if ($header['count'] > 0)
                                                 <li class="list-group-item">
                                                     <div class="d-flex justify-content-between"
                                                         data-bs-toggle="collapse"
                                                         href="#multiCollapseH_{{ $key }}" role="button"
                                                         aria-expanded="false" aria-controls="multiCollapseH1">
                                                         <p class="mb-0 text-uppercase">
                                                             {{ $key }}</p>
                                                         <span
                                                             class="badge badge-primary">{{ $header['count'] ?? '0' }}</span>
                                                     </div>
                                                     <div class="collapse" class="collapse multi-collapse px-3"
                                                         id="multiCollapseH_{{ $key }}">
                                                         <hr>
                                                         <ol class="mb-0 pb-2">
                                                             @foreach ($header['headers'] as $head)
                                                                 <li class="py-1 text-break">
                                                                     {{ $head }}</li>
                                                             @endforeach
                                                         </ol>
                                                     </div>
                                                 </li>
                                             @endif
                                         @endforeach
                                     </ul>
                                 @else
                                     <p>@lang('seo.noHeadingsTag')</p>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.headingsExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['keywords']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['keywords']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.mostCommonKeywords')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p>@lang('seo.mostCommonKeywordsHelp', ['count' => count($results['result']['full_page']['keywords'])])</p>
                                 <div class="mt-1">
                                     @if (count($results['result']['full_page']['keywords']) > 0)
                                         @foreach ($results['result']['full_page']['keywords'] as $keyword => $wordCount)
                                             <span class="badge badge-success">{{ $keyword }}
                                                 ({{ $wordCount }})
                                             </span>
                                         @endforeach
                                     @endif
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.mostCommonKeywordsExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['404page']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['404page']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.404error')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {!! $results['result']['has_404']['has_notfound'] == true ? __('seo.hasFound404') : __('seo.hasNotFound404') !!}
                                 </p>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.404errorExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['images']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['images']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.imageAltText')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 @php
                                     $total = $results['result']['full_page']['images']['count'];
                                     $withAlt = $results['result']['full_page']['images']['count_alt'];
                                     $missing = $total - $withAlt;
                                 @endphp
                                 <p>
                                     {!! $missing == 0
                                         ? __('seo.imagesAltPassed', ['count' => $total, 'count_alt' => $withAlt, 'missing' => $missing])
                                         : __('seo.imagesAltMissingCount', ['count' => $total, 'count_alt' => $withAlt, 'missing' => $missing]) !!}
                                 </p>
                                 @if ($missing > 0)
                                     <ul class="list-group rounded-0 p-0 mt-3">
                                         <li class="list-group-item">
                                             <div class="d-flex justify-content-between" data-bs-toggle="collapse"
                                                 href="#imagesWithoutAltAttr" role="button" aria-expanded="false"
                                                 aria-controls="imagesWithoutAltAttr">
                                                 <p class="mb-0">@lang('seo.imageWithoutAlt')</p>
                                                 <span class="badge badge-primary">{{ $missing }}</span>
                                             </div>
                                             <div class="collapse" class="collapse multi-collapse px-3"
                                                 id="imagesWithoutAltAttr">
                                                 <hr>
                                                 <ol class="mb-0 pb-2">
                                                     @if (count($results['result']['full_page']['images']['images']) > 0)
                                                         @foreach ($results['result']['full_page']['images']['images'] as $images)
                                                             @if (empty($images['alt']))
                                                                 <li class="py-1 text-break">
                                                                     <a href="{{ $images['src'] }}" target="_blank"
                                                                         rel="noopener noreferrer">{{ $images['src'] }}</a>
                                                                 </li>
                                                             @endif
                                                         @endforeach
                                                     @endif
                                                 </ol>
                                             </div>
                                         </li>
                                     </ul>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.imageAltTextExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['links']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['links']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.inpageLinks')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p>@lang('seo.internalLinksCount', ['count' => $results['result']['full_page']['links']['internal']])</p>
                                 <ul class="list-group rounded-0 p-0 mt-3">
                                     @if ($results['result']['full_page']['links']['internal'] > 0)
                                         <li class="list-group-item">
                                             <div class="d-flex justify-content-between" data-bs-toggle="collapse"
                                                 href="#multiCollapseH_links_internal" role="button"
                                                 aria-expanded="false" aria-controls="multiCollapseH_links_internal">
                                                 <p class="mb-0">@lang('seo.internalLinks')</p>
                                                 <span
                                                     class="badge badge-primary">{{ $results['result']['full_page']['links']['internal'] ?? '0' }}</span>
                                             </div>
                                             <div class="collapse" class="collapse multi-collapse px-3"
                                                 id="multiCollapseH_links_internal">
                                                 <hr>
                                                 <ol class="mb-0 pb-2">
                                                     @if (count($results['result']['full_page']['links']['links']) > 0)
                                                         @foreach ($results['result']['full_page']['links']['links'] as $links)
                                                             @if ($links['internal'] == true)
                                                                 <li class="py-1 text-break">
                                                                     <a href="{{ $links['url'] }}" target="_blank"
                                                                         rel="noopener noreferrer">
                                                                         {{ !empty($links['content']) ? $links['content'] : $links['url'] }}
                                                                     </a>
                                                                 </li>
                                                             @endif
                                                         @endforeach
                                                     @endif
                                                 </ol>
                                             </div>
                                         </li>
                                     @endif
                                     @if ($results['result']['full_page']['links']['external'] > 0)
                                         <li class="list-group-item">
                                             <div class="d-flex justify-content-between" data-bs-toggle="collapse"
                                                 href="#multiCollapseH_links_external" role="button"
                                                 aria-expanded="false" aria-controls="multiCollapseH_links_external">
                                                 <p class="mb-0">@lang('seo.externalLinks')</p>
                                                 <span
                                                     class="badge badge-primary">{{ $results['result']['full_page']['links']['external'] ?? '0' }}</span>
                                             </div>
                                             <div class="collapse" class="collapse multi-collapse px-3"
                                                 id="multiCollapseH_links_external">
                                                 <hr>
                                                 <ol class="mb-0 pb-2">
                                                     @if (count($results['result']['full_page']['links']['links']) > 0)
                                                         @foreach ($results['result']['full_page']['links']['links'] as $links)
                                                             @if ($links['internal'] == false)
                                                                 <li class="py-1 text-break">
                                                                     <a href="{{ $links['url'] }}" target="_blank"
                                                                         rel="noopener noreferrer">
                                                                         {{ !empty($links['content']) ? $links['content'] : $links['url'] }}
                                                                     </a>
                                                                 </li>
                                                             @endif
                                                         @endforeach
                                                     @endif
                                                 </ol>
                                             </div>
                                         </li>
                                     @endif
                                 </ul>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.internalLinksExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['language']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['language']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.language')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {{ $results['result']['language'] != null ? __('seo.languageDeclared') : __('seo.languageNotDeclared') }}
                                 </p>
                                 <div class="mt-1">
                                     <code>{{ $results['result']['language'] }}</code>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.languageExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['favicon']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['favicon']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.favicon')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     @if (!empty($results['result']['favicon']))
                                         <img src="{{ $results['result']['favicon'] ?? '' }}"
                                             alt="{{ __('seo.faviconYes') }}" width="16">
                                     @endif
                                     {{ !empty($results['result']['favicon']) ? __('seo.faviconYes') : __('seo.faviconNo') }}
                                 </p>
                                 <div class="mt-1">
                                     <code>{{ $results['result']['favicon'] ?? '' }}</code>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.faviconExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['has_robots_txt']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' .
                                                 $results['result']['tests']['has_robots_txt']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.has_robots_txt')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {!! $results['result']['tests']['has_robots_txt']['status']
                                         ? __('seo.robotsTxtPassed')
                                         : __('seo.robotsTxtFailed') !!}
                                 </p>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.keywordUsagerobotsTxtExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['nofollow']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['nofollow']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.nofollow')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {!! $results['result']['tests']['nofollow']['status'] ? __('seo.nofollowPassed') : __('seo.nofollowFailed') !!}
                                 </p>
                                 @if ($results['result']['full_page']['links']['nofollow'] > 0)
                                     <ul class="list-group rounded-0 p-0 mt-3">
                                         <li class="list-group-item">
                                             <div class="d-flex justify-content-between" data-bs-toggle="collapse"
                                                 href="#multiCollapseH_nofollow" role="button" aria-expanded="false"
                                                 aria-controls="multiCollapseH_nofollow">
                                                 <p class="mb-0">@lang('seo.nofollowLinks')</p>
                                                 <span
                                                     class="badge badge-primary">{{ $results['result']['full_page']['links']['nofollow'] ?? '0' }}</span>
                                             </div>
                                             <div class="collapse" class="collapse multi-collapse px-3"
                                                 id="multiCollapseH_nofollow">
                                                 <hr>
                                                 <ol class="mb-0 pb-2">
                                                     @foreach ($results['result']['full_page']['links']['links'] as $links)
                                                         @if ($links['nofollow'] === true)
                                                             <li class="py-1 text-break">
                                                                 <a href="{{ $links['url'] }}" target="_blank"
                                                                     rel="noopener noreferrer">
                                                                     {{ !empty($links['content']) ? $links['content'] : $links['url'] }}
                                                                 </a>
                                                             </li>
                                                         @endif
                                                     @endforeach
                                                 </ol>
                                             </div>
                                         </li>
                                     </ul>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.keywordUsagenofollowExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['noindex']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['noindex']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.noindex')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {{ $results['result']['noindex'] == null ? __('seo.noindexPassed') : __('seo.noindexFailed') }}
                                 </p>
                                 <div class="mt-1">
                                     <code>{{ $results['result']['noindex'] }}</code>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.keywordUsagenoindexExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['spfRecord']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['spfRecord']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.spfRecord')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {{ $results['result']['spfRecord'] === false ? __('seo.spfRecordFailed') : __('seo.spfRecordPassed') }}
                                 </p>
                                 @if ($results['result']['spfRecord'] != false)
                                     <div class="mt-1">
                                         <code>{{ $results['result']['spfRecord']['txt'] }}</code>
                                     </div>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.multiCollapsespfRecordExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['redirects']['status'] === true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['redirects']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.redirects')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {{ $results['result']['tests']['redirects']['status'] === true ? __('seo.redirectsPassed') : __('seo.redirectsFailed') }}
                                 </p>
                                 @if (count($results['result']['redirects']) > 1)
                                     <div class="mt-1">
                                         @foreach ($results['result']['redirects'] as $redirect)
                                             @if ($loop->iteration != 1)
                                                 <span class="px-1 bg-info text-white">→</span>
                                             @endif
                                             <code>{{ $redirect['location'] }}</code>
                                         @endforeach
                                     </div>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.multiCollapseredirectsExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['friendly']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['friendly']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.friendly')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {{ $results['result']['tests']['friendly']['status'] === true ? __('seo.friendlyPassed') : __('seo.friendlyFailed') }}
                                 </p>
                                 @if (!$results['result']['tests']['friendly']['status'])
                                     <ul class="list-group rounded-0 p-0 mt-3">
                                         <li class="list-group-item">
                                             <div class="d-flex justify-content-between" data-bs-toggle="collapse"
                                                 href="#multiCollapseH_friendly" role="button" aria-expanded="false"
                                                 aria-controls="multiCollapseH_friendly">
                                                 <p class="mb-0">@lang('seo.unfriendlyUrl')</p>
                                                 <span
                                                     class="badge badge-primary">{{ $results['result']['full_page']['links']['friendly'] ?? '0' }}</span>
                                             </div>
                                             <div class="collapse" class="collapse multi-collapse px-3"
                                                 id="multiCollapseH_friendly">
                                                 <hr>
                                                 <ol class="mb-0 pb-2">
                                                     @foreach ($results['result']['full_page']['links']['links'] as $links)
                                                         @if ($links['friendly'] == false)
                                                             <li class="py-1 text-break">
                                                                 <a href="{{ $links['url'] }}" target="_blank"
                                                                     rel="noopener noreferrer">
                                                                     {{ !empty($links['content']) ? $links['content'] : $links['url'] }}
                                                                 </a>
                                                             </li>
                                                         @endif
                                                     @endforeach
                                                 </ol>
                                             </div>
                                         </li>
                                     </ul>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.multiCollapsefriendlyExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
         </x-report-wrapper>
     </div>
     <div class="mt-4 scroll-top-margin pagebreak" id="performance">
         <x-report-wrapper :title="__('seo.speedOptimizations')">
             @if (
                 $results['result']['count_section']['performance']['high'] > 0 ||
                     $results['result']['count_section']['performance']['medium'] > 0 ||
                     $results['result']['count_section']['performance']['low'] > 0)
                 <x-slot name="actions">
                     <div class="report-badges d-flex">
                         @if ($results['result']['count_section']['performance']['high'] > 0)
                             <span class="badge badge-danger ms-1">@lang('seo.highIssue', ['count' => $results['result']['count_section']['performance']['high']])</span>
                         @endif
                         @if ($results['result']['count_section']['performance']['medium'] > 0)
                             <span class="badge badge-warning ms-1">@lang('seo.mediumIssues', ['count' => $results['result']['count_section']['performance']['medium']])</span>
                         @endif
                         @if ($results['result']['count_section']['performance']['low'] > 0)
                             <span class="badge badge-info ms-1">@lang('seo.lowIssues', ['count' => $results['result']['count_section']['performance']['low']])</span>
                         @endif
                     </div>
                 </x-slot>
             @endif
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['domsize']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['domsize']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.domSize')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {!! $results['result']['domsize']['passed'] == true
                                         ? __('seo.domPassed', ['size' => $results['result']['domsize']['domsize'], 'max' => config('artisan.seo.dom_size')])
                                         : __('seo.domFailed', [
                                             'size' => $results['result']['domsize']['domsize'],
                                             'max' => config('artisan.seo.dom_size'),
                                         ]) !!}
                                 </p>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.domExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['loadtime']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['loadtime']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.loadTime')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {!! $results['result']['loadtime'] > config('artisan.seo.load_time')
                                         ? __('seo.loadtimeFailedCount', [
                                             'time' => $results['result']['loadtime'],
                                             'recommended' => config('artisan.seo.load_time'),
                                         ])
                                         : __('seo.loadtimePassedCount', [
                                             'time' => $results['result']['loadtime'],
                                             'recommended' => config('artisan.seo.load_time'),
                                         ]) !!}
                                 </p>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.loadTimeExplainer', ['recommended' => config('artisan.seo.load_time')])" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['pagesize']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['pagesize']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.pageSize')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {!! $results['result']['tests']['pagesize']['status']
                                         ? __('seo.pagesizePassedCount', [
                                             'size' => formatSizeUnits($results['result']['pagesize'] ?? 0),
                                             'max' => formatSizeUnits(config('artisan.seo.page_size')),
                                         ])
                                         : __('seo.pagesizeFailedCount', [
                                             'size' => formatSizeUnits($results['result']['pagesize'] ?? 0),
                                             'max' => formatSizeUnits(config('artisan.seo.page_size')),
                                         ]) !!}
                                 </p>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.pagesizeExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['httpRequests']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' .
                                                 $results['result']['tests']['httpRequests']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.httpRequests')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p>
                                     {!! $results['result']['tests']['httpRequests']['status']
                                         ? __('seo.httpRequestPassedCount', [
                                             'requests' => $results['result']['httpRequests']['total_requests'] ?? 0,
                                             'max' => config('artisan.seo.http_requests_limit'),
                                         ])
                                         : __('seo.httpRequestFailedCount', [
                                             'requests' => $results['result']['httpRequests']['total_requests'] ?? 0,
                                             'max' => config('artisan.seo.http_requests_limit'),
                                         ]) !!}
                                 </p>
                                 @if ($results['result']['httpRequests']['total_requests'] > 0)
                                     <ul class="list-group rounded-0 p-0 mt-3">
                                         @foreach ($results['result']['httpRequests']['requests'] as $key => $rqts)
                                             <li class="list-group-item">
                                                 <div class="d-flex justify-content-between" data-bs-toggle="collapse"
                                                     href="#multiCollapseH_links_{{ $key }}" role="button"
                                                     aria-expanded="false"
                                                     aria-controls="multiCollapseH_links_{{ $key }}">
                                                     <p class="mb-0">{{ $key }}</p>
                                                     <span
                                                         class="badge badge-primary">{{ count($rqts) ?? '0' }}</span>
                                                 </div>
                                                 <div class="collapse" class="collapse multi-collapse px-3"
                                                     id="multiCollapseH_links_{{ $key }}">
                                                     <hr>
                                                     <ol class="mb-0 pb-2">
                                                         @if (count($rqts) > 0)
                                                             @foreach ($rqts as $links)
                                                                 <li class="py-1 text-break">
                                                                     {{ $links }}</li>
                                                             @endforeach
                                                         @endif
                                                     </ol>
                                                 </div>
                                             </li>
                                         @endforeach
                                     </ul>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.httpRequestExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['imageFormats']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' .
                                                 $results['result']['tests']['imageFormats']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.imageFormats')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p>
                                     {!! $results['result']['tests']['imageFormats']['status']
                                         ? __('seo.imageFormatsPassedCount', ['count' => count($results['result']['imageFormats']) ?? 0])
                                         : __('seo.imageFormatsFailedCount', ['count' => count($results['result']['imageFormats']) ?? 0]) !!}
                                 </p>
                                 <ul class="list-group rounded-0 p-0 mt-3">
                                     <li class="list-group-item">
                                         <div class="d-flex justify-content-between" data-bs-toggle="collapse"
                                             href="#multiCollapseH_links_mages" role="button" aria-expanded="false"
                                             aria-controls="multiCollapseH_links_mages">
                                             <p class="mb-0">@lang('seo.imagesWithoutWebp')</p>
                                             <span
                                                 class="badge badge-primary">{{ count($results['result']['imageFormats']) ?? '0' }}</span>
                                         </div>
                                         <div class="collapse" class="collapse multi-collapse px-3"
                                             id="multiCollapseH_links_mages">
                                             <hr>
                                             <ol class="mb-0 pb-2">
                                                 @if (count($results['result']['imageFormats']) > 0)
                                                     @foreach ($results['result']['imageFormats'] as $mages)
                                                         <li class="py-1 text-break">
                                                             <a href="{{ $mages['url'] }}" target="_blank"
                                                                 rel="noopener noreferrer">{{ $mages['url'] }}</a>
                                                         </li>
                                                     @endforeach
                                                 @endif
                                             </ol>
                                         </div>
                                     </li>
                                 </ul>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.imageFormatExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['text_compression']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' .
                                                 $results['result']['tests']['text_compression']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.textCompression')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 @php
                                     $percentage = round(100 - ($results['result']['pagesize'] / $results['result']['contentsize']) * 100, 0);
                                     $from = formatSizeUnits($results['result']['contentsize']);
                                     $to = formatSizeUnits($results['result']['pagesize']);
                                     $compression = $results['result']['encoding'][0] ?? null;
                                     $langArray = compact('percentage', 'from', 'to', 'compression');
                                 @endphp
                                 <p class="mb-0">
                                     {!! $results['result']['tests']['text_compression']['status']
                                         ? __('seo.textCompressionPassed', $langArray)
                                         : __('seo.textCompressionFailed', $langArray) !!}
                                 </p>
                                 @if ($results['result']['tests']['text_compression']['status'])
                                     <div class="mt-1">
                                         <code>{{ $compression }}</code>
                                     </div>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.textCompressionExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['deferJs']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['deferJs']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.deferJS')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p>
                                     {!! $results['result']['tests']['deferJs']['status']
                                         ? __('seo.deferJSPassed', ['count' => count($results['result']['deferJs']) ?? 0])
                                         : __('seo.deferJSFailed', ['count' => count($results['result']['deferJs']) ?? 0]) !!}
                                 </p>
                                 @if (count($results['result']['deferJs']) > 0)
                                     <ul class="list-group rounded-0 p-0 mt-3">
                                         <li class="list-group-item">
                                             <div class="d-flex justify-content-between" data-bs-toggle="collapse"
                                                 href="#multiCollapseH_links_defer" role="button"
                                                 aria-expanded="false" aria-controls="multiCollapseH_links_defer">
                                                 <p class="mb-0">@lang('seo.deferJsText')</p>
                                                 <span
                                                     class="badge badge-primary">{{ count($results['result']['deferJs']) ?? '0' }}</span>
                                             </div>
                                             <div class="collapse" class="collapse multi-collapse px-3"
                                                 id="multiCollapseH_links_defer">
                                                 <hr>
                                                 <ol class="mb-0 pb-2">
                                                     @foreach ($results['result']['deferJs'] as $defer)
                                                         <li class="py-1 text-break">
                                                             <a href="{{ $defer }}" target="_blank"
                                                                 rel="noopener noreferrer">
                                                                 {{ $defer }}
                                                             </a>
                                                         </li>
                                                     @endforeach
                                                 </ol>
                                             </div>
                                         </li>
                                     </ul>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.deferJsExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['doctype']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['doctype']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.doctype')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {!! $results['result']['tests']['doctype']['status'] ? __('seo.doctypePassed') : __('seo.doctypeFailed') !!}
                                 </p>
                                 <div class="mt-1">
                                     <span class="badge badge-success">{{ $results['result']['doctype'] }}</span>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.keywordUsageDoctypeExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['nestedTables']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' .
                                                 $results['result']['tests']['nestedTables']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.nestedTables')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {!! $results['result']['tests']['nestedTables']['status']
                                         ? __('seo.nestedTablesPassed')
                                         : __('seo.nestedTablesFailed') !!}
                                 </p>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.keywordUsagenestedTablesExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['framesets']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['framesets']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.framesets')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {!! $results['result']['tests']['framesets']['status'] ? __('seo.framesetsPassed') : __('seo.framesetsFailed') !!}
                                 </p>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="@lang('seo.keywordUsageframesetsExplainer')" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
         </x-report-wrapper>
     </div>
     <div class="mt-4 scroll-top-margin pagebreak" id="security">
         <x-report-wrapper :title="__('seo.serverAndSecurity')">
             @if (
                 $results['result']['count_section']['security']['high'] > 0 ||
                     $results['result']['count_section']['security']['medium'] > 0 ||
                     $results['result']['count_section']['security']['low'] > 0)
                 <x-slot name="actions">
                     <div class="report-badges d-flex">
                         @if ($results['result']['count_section']['security']['high'] > 0)
                             <span class="badge badge-danger ms-1">@lang('seo.highIssue', ['count' => $results['result']['count_section']['security']['high']])</span>
                         @endif
                         @if ($results['result']['count_section']['security']['medium'] > 0)
                             <span class="badge badge-warning ms-1">@lang('seo.mediumIssues', ['count' => $results['result']['count_section']['security']['medium']])</span>
                         @endif
                         @if ($results['result']['count_section']['security']['low'] > 0)
                             <span class="badge badge-info ms-1">@lang('seo.lowIssues', ['count' => $results['result']['count_section']['security']['low']])</span>
                         @endif
                     </div>
                 </x-slot>
             @endif
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['plainEmails']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' .
                                                 $results['result']['tests']['plainEmails']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.plainEmail')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {!! $results['result']['tests']['plainEmails']['status']
                                         ? __('seo.plainEmailPassed', ['count' => count($results['result']['plainEmails']) ?? 0])
                                         : __('seo.plainEmailFailed', ['count' => count($results['result']['plainEmails']) ?? 0]) !!}
                                 </p>
                                 @if (count($results['result']['plainEmails']) > 0)
                                     <ul class="list-group rounded-0 p-0 mt-3">
                                         <li class="list-group-item">
                                             <div class="d-flex justify-content-between" data-bs-toggle="collapse"
                                                 href="#multiCollapseH_links_mail" role="button"
                                                 aria-expanded="false" aria-controls="multiCollapseH_links_mail">
                                                 <p class="mb-0">@lang('seo.plainEmail')</p>
                                                 <span
                                                     class="badge badge-primary">{{ count($results['result']['plainEmails']) ?? '0' }}</span>
                                             </div>
                                             <div class="collapse" class="collapse multi-collapse px-3"
                                                 id="multiCollapseH_links_mail">
                                                 <hr>
                                                 <ol class="mb-0 pb-2">
                                                     @foreach ($results['result']['plainEmails'] as $email)
                                                         <li class="py-1 text-break">
                                                             {{ $email }}</li>
                                                     @endforeach
                                                 </ol>
                                             </div>
                                         </li>
                                     </ul>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.plainEmailExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['httpsEncryption']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' .
                                                 $results['result']['tests']['httpsEncryption']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.httpsEncryption')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {!! $results['result']['ssl']['is_valid'] == true
                                         ? __('seo.sslTestPassed', ['issuer' => $results['result']['ssl']['issuer'] , 'expire_at' => $results['result']['ssl']['expire_at']])
                                         : __('seo.sslTestFailed') !!}
                                 </p>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.httpsEncryptionExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['mixedContent']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' .
                                                 $results['result']['tests']['mixedContent']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.mixedContent')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {{ $results['result']['mixedContent']['total_requests'] == 0 ? __('seo.mixedContentNo') : __('seo.mixedContentYes') }}
                                 </p>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.mixedContentExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['serverSignature']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' .
                                                 $results['result']['tests']['serverSignature']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.serverSignature')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {{ count($results['result']['server']) == 0 ? __('seo.serverNo') : __('seo.serverYes') }}
                                 </p>
                                 <div class="mt-1">
                                     @if (count($results['result']['server']) > 0)
                                         @foreach ($results['result']['server'] as $server)
                                             <code>{{ $server }}</code>
                                         @endforeach
                                     @endif
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.serverSigExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['coLinks']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['coLinks']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.coLinks')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {!! $results['result']['tests']['coLinks']['status']
                                         ? __('seo.coLinksPassed', ['count' => count($results['result']['unsafeCOLinks']) ?? 0])
                                         : __('seo.coLinksFailed', ['count' => count($results['result']['unsafeCOLinks']) ?? 0]) !!}
                                 </p>
                                 @if (!$results['result']['tests']['coLinks']['status'])
                                     <ul class="list-group rounded-0 p-0 mt-3">
                                         <li class="list-group-item">
                                             <div class="d-flex justify-content-between" data-bs-toggle="collapse"
                                                 href="#coLinksCollapse" role="button" aria-expanded="false"
                                                 aria-controls="coLinksCollapse">
                                                 <p class="mb-0">@lang('seo.coLinks')</p>
                                                 <span
                                                     class="badge badge-primary">{{ count($results['result']['unsafeCOLinks']) ?? '0' }}</span>
                                             </div>

                                             <div class="collapse" class="collapse multi-collapse px-3"
                                                 id="coLinksCollapse">
                                                 <hr>
                                                 <ol class="mb-0 pb-2">
                                                     @foreach ($results['result']['unsafeCOLinks'] as $links)
                                                         <li class="py-1 text-break">
                                                             <a href="{{ $links }}" target="_blank"
                                                                 rel="noopener noreferrer">{{ $links }}</a>
                                                         </li>
                                                     @endforeach
                                                 </ol>
                                             </div>
                                         </li>
                                     </ul>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.coLinksExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['http2']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['http2']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.http2')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {!! $results['result']['http2'] == true ? __('seo.http2Passed') : __('seo.http2Failed') !!}
                                 </p>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.http2Explainer') }}" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['hsts']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['hsts']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.hsts')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {!! $results['result']['hsts'] == true ? __('seo.hstsPassed') : __('seo.hstsFailed') !!}
                                 </p>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.hstsExplainer') }}" data-bs-toggle="tooltip"
                             data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
         </x-report-wrapper>
     </div>
     <div class="mt-4 scroll-top-margin pagebreak" id="advance">
         <x-report-wrapper :title="__('seo.advance')">
            @if (
                 $results['result']['count_section']['others']['high'] > 0 ||
                     $results['result']['count_section']['others']['medium'] > 0 ||
                     $results['result']['count_section']['others']['low'] > 0)
                 <x-slot name="actions">
                     <div class="report-badges d-flex">
                         @if ($results['result']['count_section']['others']['high'] > 0)
                             <span class="badge badge-danger ms-1">@lang('seo.highIssue', ['count' => $results['result']['count_section']['others']['high']])</span>
                         @endif
                         @if ($results['result']['count_section']['others']['medium'] > 0)
                             <span class="badge badge-warning ms-1">@lang('seo.mediumIssues', ['count' => $results['result']['count_section']['others']['medium']])</span>
                         @endif
                         @if ($results['result']['count_section']['others']['low'] > 0)
                             <span class="badge badge-info ms-1">@lang('seo.lowIssues', ['count' => $results['result']['count_section']['others']['low']])</span>
                         @endif
                     </div>
                 </x-slot>
             @endif
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['socialTags']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' .
                                                 $results['result']['tests']['socialTags']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.socialMediaMetaTags')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {!! $results['result']['tests']['socialTags']['status']
                                         ? __('seo.socialMediaMetaTagsPassed')
                                         : __('seo.socialMediaMetaTagsFailed') !!}
                                 </p>
                                 <ul class="list-group rounded-0 p-0 mt-3">
                                     @if (count($results['result']['structuredData']['og'] ?? []) > 0)
                                         <li class="list-group-item">
                                             <div class="d-flex justify-content-between" data-bs-toggle="collapse"
                                                 href="#multiCollapseH_links_og" role="button" aria-expanded="false"
                                                 aria-controls="multiCollapseH_links_og">
                                                 <p class="mb-0">@lang('seo.openGraph')</p>
                                                 <span
                                                     class="badge badge-primary">{{ count($results['result']['structuredData']['og'] ?? []) ?? '0' }}</span>
                                             </div>
                                             <div class="collapse" class="collapse multi-collapse px-3"
                                                 id="multiCollapseH_links_og">
                                                 <hr>
                                                 <ol class="mb-0 pb-2">
                                                     @foreach ($results['result']['structuredData']['og'] as $key => $links)
                                                         <li class="py-1 text-break">
                                                             <div class="row">
                                                                 <div class="col-sm-4">
                                                                     <strong>{{ $key }}</strong>
                                                                 </div>
                                                                 <div class="col-sm-8 line-truncate line-1">
                                                                     {{ $links }}</div>
                                                             </div>
                                                         </li>
                                                     @endforeach
                                                 </ol>
                                             </div>
                                         </li>
                                     @endif
                                     @if (count($results['result']['structuredData']['twitter'] ?? []) > 0)
                                         <li class="list-group-item">
                                             <div class="d-flex justify-content-between" data-bs-toggle="collapse"
                                                 href="#multiCollapseH_links_twitter" role="button"
                                                 aria-expanded="false" aria-controls="multiCollapseH_links_twitter">
                                                 <p class="mb-0">@lang('seo.twitter')</p>
                                                 <span
                                                     class="badge badge-primary">{{ count($results['result']['structuredData']['twitter'] ?? []) ?? '0' }}</span>
                                             </div>
                                             <div class="collapse" class="collapse multi-collapse px-3"
                                                 id="multiCollapseH_links_twitter">
                                                 <hr>
                                                 <ol class="mb-0 pb-2">
                                                     @foreach ($results['result']['structuredData']['twitter'] as $key => $links)
                                                         <li class="py-1 text-break">
                                                             <div class="row">
                                                                 <div class="col-sm-4">
                                                                     <strong>{{ $key }}</strong>
                                                                 </div>
                                                                 <div class="col-sm-8 line-truncate line-1">
                                                                     {{ $links }}</div>
                                                             </div>
                                                         </li>
                                                     @endforeach

                                                 </ol>
                                             </div>
                                         </li>
                                     @endif
                                 </ul>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.socialMediaMetaTagsExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['structuredData']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' .
                                                 $results['result']['tests']['structuredData']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.structuredData')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {!! $results['result']['tests']['structuredData']['status']
                                         ? __('seo.structuredDataPassed')
                                         : __('seo.structuredDataFailed') !!}
                                 </p>
                                 @if (count($results['result']['structuredData']['schema'] ?? []) > 0)
                                     <ul class="list-group rounded-0 p-0 mt-3">
                                         <li class="list-group-item">
                                             <div class="d-flex justify-content-between" data-bs-toggle="collapse"
                                                 href="#multiCollapseH_links_schema" role="button"
                                                 aria-expanded="false" aria-controls="multiCollapseH_links_schema">
                                                 <p class="mb-0">@lang('seo.schema')</p>
                                                 <span
                                                     class="badge badge-primary">{{ count($results['result']['structuredData']['schema'] ?? []) ?? '0' }}</span>
                                             </div>
                                             <div class="collapse" class="collapse multi-collapse px-3"
                                                 id="multiCollapseH_links_schema">
                                                 <hr>
                                                 <x-seo-og-schema :item="$results['result']['structuredData']['schema']" />
                                             </div>
                                         </li>
                                     </ul>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.structuredDataExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>

             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['viewPort']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['viewPort']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.viewPort')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {{ !empty($results['result']['viewport']) ? __('seo.hasViewPort') : __('seo.hasNotviewPort') }}
                                 </p>
                                 <div class="mt-1">
                                     <code class=" ">{{ $results['result']['viewport'] }}</code>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.viewPortExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['charset']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['charset']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.charset')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {{ $results['result']['charset'] != null ? __('seo.hasCharset') : __('seo.hasNotCharset') }}
                                 </p>
                                 <div class="mt-1">
                                     <code class=" ">{{ $results['result']['charset'] }}</code>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.charsetExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['sitemap']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['sitemap']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.sitemaps')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {{ $results['result']['tests']['sitemap']['status'] ? __('seo.hasSitemap') : __('seo.hasNotSitemap') }}
                                 </p>
                                 @if ($results['result']['tests']['sitemap']['status'])
                                     <ul class="list-group rounded-0 p-0 mt-3">
                                         <li class="list-group-item">
                                             <div class="d-flex justify-content-between" data-bs-toggle="collapse"
                                                 href="#seoReport_sitemaps" role="button" aria-expanded="false"
                                                 aria-controls="seoReport_sitemaps">
                                                 <p class="mb-0">@lang('seo.sitemaps')</p>
                                                 <span
                                                     class="badge badge-primary">{{ count($results['result']['sitemaps']['sitemaps']) ?? '0' }}</span>
                                             </div>
                                             <div class="collapse" class="collapse multi-collapse px-3"
                                                 id="seoReport_sitemaps">
                                                 <hr>
                                                 <ol class="mb-0 pb-2">
                                                     @foreach ($results['result']['sitemaps']['sitemaps'] as $sitemap)
                                                         <li class="py-1 text-break">
                                                             <a href="{{ $sitemap }}" target="_blank"
                                                                 rel="noopener noreferrer">{{ $sitemap }}</a>
                                                         </li>
                                                     @endforeach
                                                 </ol>
                                             </div>
                                         </li>
                                     </ul>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.sitemapExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['social']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['social']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.socialLinks')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p>
                                     {{ $results['result']['tests']['social']['status'] ? __('seo.hasSocial') : __('seo.hasNotSocial') }}
                                 </p>
                                 @if ($results['result']['tests']['social']['status'])
                                     <ul class="list-group rounded-0 p-0 mt-3">
                                         @foreach ($results['result']['social'] as $key => $social)
                                             <li class="list-group-item">
                                                 <div class="d-flex justify-content-between" data-bs-toggle="collapse"
                                                     href="#multiCollapseH_links_{{ $key }}"
                                                     role="button" aria-expanded="false"
                                                     aria-controls="multiCollapseH_links_{{ $key }}">
                                                     <p class="mb-0">{{ $key }}</p>
                                                     <span
                                                         class="badge badge-primary">{{ count($social) ?? '0' }}</span>
                                                 </div>
                                                 <div class="collapse" class="collapse multi-collapse px-3"
                                                     id="multiCollapseH_links_{{ $key }}">
                                                     <hr>
                                                     <ol class="mb-0 pb-2">
                                                         @if (count($social) > 0)
                                                             @foreach ($social as $links)
                                                                 <li class="py-1 text-break">
                                                                     {{ $links['url'] }}</li>
                                                             @endforeach
                                                         @endif
                                                     </ol>
                                                 </div>
                                             </li>
                                         @endforeach
                                     </ul>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.socialExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['contentlength']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' .
                                                 $results['result']['tests']['contentlength']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.contentlength')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     @lang('seo.contentlengthCount', ['count' => $results['result']['full_page']['word_count']])
                                 </p>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.contentlengthExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>

             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['inlineCss']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['inlineCss']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.inlineCss')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p>
                                     {{ count($results['result']['inlineCss']) == 0 ? __('seo.hasNotValidCss') : __('seo.hasValidCss') }}
                                 </p>
                                 @if (count($results['result']['inlineCss']) > 0)
                                     <ul class="list-group rounded-0 p-0 mt-3">
                                         <li class="list-group-item">
                                             <div class="d-flex justify-content-between" data-bs-toggle="collapse"
                                                 href="#inlineCssCollapse" role="button" aria-expanded="false"
                                                 aria-controls="inlineCssCollapse">
                                                 <p class="mb-0">@lang('seo.inlineCss')</p>
                                                 <span
                                                     class="badge badge-primary">{{ count($results['result']['inlineCss']) ?? '0' }}</span>
                                             </div>
                                             <div class="collapse" class="collapse multi-collapse px-3"
                                                 id="inlineCssCollapse">
                                                 <hr>
                                                 <ol class="mb-0 pb-2">
                                                     @foreach ($results['result']['inlineCss'] as $links)
                                                         <li class="py-1 text-break">
                                                             <code>{{ $links }}</code>
                                                         </li>
                                                     @endforeach
                                                 </ol>
                                             </div>
                                         </li>
                                     </ul>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.inlineCssExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['depHtml']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['depHtml']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.depHtml')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {!! $results['result']['tests']['depHtml']['status']
                                         ? __('seo.depHtmlPassed', ['count' => count($results['result']['depricatedtTags']['deprecatedTags']) ?? 0])
                                         : __('seo.depHtmlFailed', ['count' => count($results['result']['depricatedtTags']['deprecatedTags']) ?? 0]) !!}
                                 </p>
                                 @if ($results['result']['depricatedtTags']['total'] > 0)
                                     <div class="mt-3 border rounded px-3 py-2">
                                         @foreach ($results['result']['depricatedtTags']['deprecatedTags'] as $tag => $count)
                                             <div
                                                 class="d-flex justify-content-between{{ $loop->iteration != 1 ? ' mt-3' : '' }}">
                                                 <div class="tag-name">{{ "<{$tag}>" }}</div>
                                                 <div class="tag-count"><span
                                                         class="badge badge-primary">{{ $count }}</span>
                                                 </div>
                                             </div>
                                         @endforeach
                                     </div>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.depHtmlExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['canonical']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['canonical']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.canonical')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {!! $results['result']['tests']['canonical']['status'] ? __('seo.canonicalPassed') : __('seo.canonicalFailed') !!}
                                 </p>
                                 <div class="mt-1">
                                     <code>{{ $results['result']['canonical'] }}</code>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.keywordUsageCanonicalExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['analytics']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' . $results['result']['tests']['analytics']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.analytics')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {{ $results['result']['analytics'] != null ? __('seo.analyticsPassed') : __('seo.analyticsFailed') }}
                                 </p>
                                 <div class="mt-1">
                                     <code>{{ $results['result']['analytics'] }}</code>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.multiCollapseanalyticsExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['is_disallowed']['status'] === true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' .
                                                 $results['result']['tests']['is_disallowed']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.is_disallowed')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                 <p class="mb-0">
                                     {{ $results['result']['tests']['is_disallowed']['message'] }}
                                 </p>
                                 @if (!$results['result']['tests']['is_disallowed']['status'])
                                     <ul class="list-group rounded-0 p-0 mt-3">
                                         <li class="list-group-item">
                                             <div class="d-flex justify-content-between" data-bs-toggle="collapse"
                                                 href="#multiCollapseH_links_disallowed" role="button"
                                                 aria-expanded="false"
                                                 aria-controls="multiCollapseH_links_disallowed">
                                                 <p class="mb-0">@lang('seo.disallowedRules')</p>
                                                 <span
                                                     class="badge badge-primary">{{ count($results['result']['sitemaps']['disallow_rules']) ?? '0' }}</span>
                                             </div>

                                             <div class="collapse" class="collapse multi-collapse px-3"
                                                 id="multiCollapseH_links_disallowed">
                                                 <hr>
                                                 <ol class="mb-0 pb-2">
                                                     @foreach ($results['result']['sitemaps']['disallow_rules'] as $links)
                                                         <li class="py-1 text-break">
                                                             <a href="{{ $links }}" target="_blank"
                                                                 rel="noopener noreferrer">{{ $links }}</a>
                                                         </li>
                                                     @endforeach
                                                 </ol>
                                             </div>
                                         </li>
                                     </ul>
                                 @endif
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.multiCollapseisdisallowedExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             {{-- TODO: improve keywords  --}}
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['keywords_usage']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' .
                                                 $results['result']['tests']['keywords_usage']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.keywordUsageTest')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                <div class="table-responsive-sm">
                                    <table class="table table-bordered">
                                        <thead>
                                            <th>@lang('seo.keyword')</th>
                                            <th width="20%">@lang('seo.title')</th>
                                            <th width="20%">@lang('seo.description')</th>
                                            <th width="20%">@lang('seo.headings')</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($results['result']['tests']['keywords_usage']['data'] as $key => $keyword)
                                                <tr>
                                                    <td>
                                                        <div class="line-truncate line-1" data-bs-toggle="tooltip"
                                                            title="{{ $key }}">{{ $key }}
                                                            ({{ $keyword['count'] }})
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{!! $keyword['title']
                                                        ? '<i class="an an-chack text-success"></i>'
                                                        : '<i class="an an-times text-danger"></i>' !!}</td>
                                                    <td class="text-center">{!! $keyword['description']
                                                        ? '<i class="an an-chack text-success"></i>'
                                                        : '<i class="an an-times text-danger"></i>' !!}</td>
                                                    <td class="text-center">{!! $keyword['headers']
                                                        ? '<i class="an an-chack text-success"></i>'
                                                        : '<i class="an an-times text-danger"></i>' !!}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.keywordUsageTestExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
             <div class="border-top p-3">
                 <div class="row">
                     <div class="col">
                         <div class="row">
                             <div class="col-12 col-lg-4">
                                 <div class="d-flex align-items-center mb-lg-3">
                                     <div class="d-flex justify-content-center me-3">
                                         @if ($results['result']['tests']['keywords_usage_long']['status'] == true)
                                             <x-seo-icon-checked />
                                         @else
                                             <x-dynamic-component :component="'seo-icon-' .
                                                 $results['result']['tests']['keywords_usage_long']['label']">
                                             </x-dynamic-component>
                                         @endif
                                     </div>
                                     <div class="fw-bold">@lang('seo.keywordUsageTestLong')</div>
                                 </div>
                             </div>
                             <div class="col-12 col-lg-8">
                                <div class="table-responsive-sm">
                                    <table class="table table-bordered">
                                        <thead>
                                            <th>@lang('seo.keyword')</th>
                                            <th width="20%">@lang('seo.title')</th>
                                            <th width="20%">@lang('seo.description')</th>
                                            <th width="20%">@lang('seo.headings')</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($results['result']['tests']['keywords_usage_long']['data'] as $key => $keyword)
                                                <tr>
                                                    <td>
                                                        <div class="line-truncate line-1" data-bs-toggle="tooltip"
                                                            title="{{ $key }}">{{ $key }}
                                                            ({{ $keyword['count'] }})
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{!! $keyword['title']
                                                        ? '<i class="an an-chack text-success"></i>'
                                                        : '<i class="an an-times text-danger"></i>' !!}</td>
                                                    <td class="text-center">{!! $keyword['description']
                                                        ? '<i class="an an-chack text-success"></i>'
                                                        : '<i class="an an-times text-danger"></i>' !!}</td>
                                                    <td class="text-center">{!! $keyword['headers']
                                                        ? '<i class="an an-chack text-success"></i>'
                                                        : '<i class="an an-times text-danger"></i>' !!}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-auto d-none d-lg-block d-md-block">
                         <div class="icon-question" title="{{ __('seo.keywordUsageTestLongExplainer') }}"
                             data-bs-toggle="tooltip" data-bs-placement="left">?</div>
                     </div>
                 </div>
             </div>
         </x-report-wrapper>
     </div>
 </div>
