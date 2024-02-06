<x-app-layout>
    <div class="row">
        <div class="tab-content rounded-bottom">
            <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-601">
                <ul class="nav nav-pills nav-fill">
                    <li class="nav-item"><a class="nav-link @if ($type == 1) active @endif"
                            aria-current="page" href="{{ route('admin.advertisements.create', ['type'=>'1']) }}"><i class="lni lni-text-align-center"></i>
                            @lang('admin.textType')</a></li>
                    <li class="nav-item"><a class="nav-link @if ($type == 2) active @endif"
                            href="{{ route('admin.advertisements.create', ['type'=>'2']) }}"><i class="lni lni-image"></i> @lang('admin.imageType')</a>
                    </li>
                    <li class="nav-item"><a class="nav-link @if ($type == 3) active @endif"
                            href="{{ route('admin.advertisements.create', ['type'=>'3']) }}"><i class="lni lni-code"></i> @lang('admin.codeType')</a></li>
                </ul>
            </div>
        </div>
        <x-ads-form :type=$type />
    </div>
</x-app-layout>
