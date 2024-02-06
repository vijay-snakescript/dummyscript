<x-app-layout>
    <form id="faqFrm" action="{{ route('admin.faqs.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">@lang('admin.createFaq')</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="question" class="form-label">@lang('admin.questions')</label>
                            <input type="text" id="question"
                                class="form-control {{ $errors->has('question') ? ' is-invalid' : '' }}" name="question"
                                value="{{ old('question') }}" />
                            @if ($errors->has('question'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('question') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label for="answer__editor" class="form-label">@lang('admin.answeres')</label>
                            <textarea type="text" id="answer__editor"
                                class="form-control editor {{ $errors->has('answer') ? ' is-invalid' : '' }}" name="answer">{{ old('answer') }}</textarea>
                            @if ($errors->has('answer'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('answer') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="pricing"
                                id="forPricingPage">
                            <label class="form-check-label" for="forPricingPage">
                                @lang('admin.showOnPricing')
                            </label>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary" id="settings-btn" type="submit">@lang('common.save')</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @section('footer_scripts')
        <script src="{{ asset('themes/admin/js/ckeditor/ckeditor.js') }}"></script>
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
    @endsection
</x-app-layout>
