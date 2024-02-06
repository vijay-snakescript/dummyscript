<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">@lang('widgets.admin.newUsers')</h6>
    </div>
    <div class="card-body card-height">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">@lang('admin.name')</th>
                    <th scope="col">@lang('admin.email')</th>
                    <th scope="col">@lang('admin.status')</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->status == 1)
                                <span class="badge me-1 bg-success">@lang('common.active')</span>
                            @else
                                <span class="badge me-1 bg-danger">@lang('common.inactive')</span>
                            @endif
                        </td>
                        <td>
                            {{ $user->created_at->diffForHumans() }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
