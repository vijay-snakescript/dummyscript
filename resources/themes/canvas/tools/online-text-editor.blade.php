<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
            <x-ad-slot :advertisement="get_advert_model('above-form')" />
            <x-form class="no-app-loader" method="post" :route="route('tool.handle', $tool->slug)">
                <div class="row mt-4 mb-4">
                    <div class="col-md-12 mt-2 mb-3">
                        <div class="form-group mh-300">
                            <x-textarea-input type="text" name="string" class="editor" rows="8" :placeholder="__('common.someText')"
                                id="textarea">
                                {{ old('string') }}
                            </x-textarea-input>
                            <x-input-error :messages="$errors->get('string')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-md-12 text-end">
                        <x-download-form-button type="submit" :text="__('common.download')" />
                    </div>
                </div>
                <x-ad-slot :advertisement="get_advert_model('below-form')" />
            </x-form>
    </x-tool-wrapper>
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
        <script>
            document.querySelectorAll('.editor').forEach(elem => {
                ClassicEditor.create(elem, {
                        simpleUpload: {
                            uploadUrl: '{{ route('uploader.upload') }}',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            }
                        },
                    })
                    .then(editor => {})
                    .catch(error => {
                        console.log('error', error);
                    });
            });
        </script>
    @endpush
</x-application-tools-wrapper>
