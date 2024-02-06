<x-app-layout>
    @php
        $firstPatch = false;
    @endphp
    <div class="row">
        <div class="col-md-12">
            <ul class="list-group">
                @forelse ($patches as $patch)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="patch-detail">
                                <div class="fw-bold">
                                    {{ $patch['message'] }}
                                </div>
                                <span class="small text-muted">
                                    Released: {{ $patch['date'] }} - for v{{ $patch['required'] }}
                                </span>
                            </div>
                            <div class="patch-action">
                                @if (is_array($applied) && !in_array($patch['id'], $applied) && !$firstPatch)
                                    @php
                                        $firstPatch = true;
                                    @endphp
                                    <form method="post"
                                        action="{{ route('system.patches.apply', ['id' => $patch['id']]) }}">
                                        @csrf
                                        <button class="btn btn-sm rounded-pill btn-primary text-white" type="submit">
                                            @lang('admin.applyPatch')
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm rounded-pill btn-disabled" type="button" disabled>
                                        @lang('admin.applyPatch')
                                    </button>
                                @endif
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item">
                        @lang('common.noRecordsFund')
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</x-app-layout>
