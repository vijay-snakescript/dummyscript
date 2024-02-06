 <div class="container-fluid bg-light dark-mode-light-bg">
     <div class="container">
         <div class="image-converter-upload-wrap">
             <div class="image-compressor container">
                 <x-form method="post" :route="route('front.index.action')" enctype="multipart/form-data">
                     <x-upload-wrapper max-files="{{ $tool->no_file_tool }}" max-size="{{ $tool->fs_tool }}"
                         accept=".png,.jpeg,.jpg" input-name="images[]" :file-title="__('tools.dropImageHereTitle')" :file-label="__('tools.compressImageDesc')">
                         <div class="d-none text-center process-button">
                             <x-button type="submit" class="btn btn-outline-primary">
                                 @lang('tools.compressImage')
                             </x-button>
                         </div>
                     </x-upload-wrapper>
                 </x-form>
             </div>
         </div>
         @if (isset($results))
             <div class="tool-results-wrapper pb-4">
                 <x-ad-slot :advertisement="get_advert_model('above-result')" />
                 <x-page-wrapper :title="__('common.result')" class="mb-0">
                     <div class="row">
                         <div class="col-md-12">
                             <div class="progress" style="height: 3px;">
                                 <div id="conversion-progress" class="progress-bar bg-success" role="progressbar"
                                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                             </div>
                         </div>
                         <div class="col-md-12">
                             <table class="table table-style">
                                 <thead>
                                     <tr>
                                         <th width="75">#</th>
                                         <th>@lang('common.fileName')</th>
                                         <th width="200">@lang('common.size')</th>
                                         <th width="75"></th>
                                         <th width="150"></th>
                                     </tr>
                                 </thead>
                                 <tbody id="processing-files">

                                 </tbody>
                             </table>
                         </div>
                         <div class="col-md-12 text-end">
                             <x-form class="d-none download-all-btn d-inline-block" metho="post" :route="route('tool.postAction', ['tool' => $tool->slug, 'action' => 'download-all'])">
                                 <input type="hidden" name="process_id" value="{{ $results['process_id'] }}">
                                 <x-download-form-button :text="__('tools.downloadAll')" />
                             </x-form>
                             <x-reload-button :link="route('front.index')" />
                         </div>
                     </div>
                 </x-page-wrapper>
             </div>
             <x-ad-slot :advertisement="get_advert_model('below-result')" />
         @endif
     </div>
 </div>
