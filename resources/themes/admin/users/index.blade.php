<x-app-layout>
    <x-manage-filters :search="true" :search-route="route('admin.users')">
        <button type="button" class="btn btn-outline-primary" data-coreui-toggle="modal" data-coreui-target="#createUser">
            @lang('common.createNew')
        </button>
    </x-manage-filters>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">@lang('admin.manageUsers')</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-hover table-artisan mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">@lang('admin.name')</th>
                                        <th scope="col">@lang('admin.email')</th>
                                        <th scope="col">@lang('admin.status')</th>
                                        <th scope="col">@lang('admin.role')</th>
                                        <th scope="col">@lang('admin.addedDate')</th>
                                        <th class="text-center" scope="col">@lang('admin.actions')</th>
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
                                                @foreach ($user->roles->pluck('name') as $rnames)
                                                    <span class="badge me-1 bg-primary">{{ $rnames }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                {{ $user->created_at->format(setting('datetime_format', 'd-m-Y H:i:s')) }}
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <a id="dropdownMenuButton" type="button"
                                                        data-coreui-toggle="dropdown" aria-expanded="false">
                                                        <i class="lni lni-more"></i></a>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li>
                                                            <a class="dropdown-item edit-user"
                                                                data-url="{{ route('admin.users.edit') }}"
                                                                data-id="{{ $user->id }}" href="#"
                                                                data-coreui-toggle="modal"
                                                                data-coreui-target="#editUser">@lang('admin.edit')</a>
                                                        </li>
                                                        <li>
                                                            @if ($user->status == 0)
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.users.status.change', ['id' => $user->id, 'status' => 1]) }}">Activate</a>
                                                            @else
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.users.status.change', ['id' => $user->id, 'status' => 0]) }}">Deactivate</a>
                                                            @endif
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.users.destroy', $user) }}"
                                                                method="POST">
                                                                @method('DELETE')
                                                                @csrf
                                                                <button
                                                                    class="dropdown-item text-danger warning-delete frm-submit"
                                                                    role="button" data-coreui-toggle="tooltip"
                                                                    data-placement="right"
                                                                    title="@lang('common.delete')">@lang('common.sendToTrash')</button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.users.delete', $user) }}"
                                                                method="POST">
                                                                @method('DELETE')
                                                                @csrf
                                                                <button
                                                                    class="dropdown-item text-danger warning-delete frm-submit"
                                                                    role="button" data-coreui-toggle="tooltip"
                                                                    data-placement="right"
                                                                    title="@lang('common.deleteParmantently')">@lang('common.deleteParmantently')</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if ($users->hasPages())
                    <div class="card-footer">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('users.partials.create_user')
    @include('users.partials.edit_user')
    @section('footer_scripts')
        <script>
            document.querySelectorAll('.edit-user').forEach(element => element.addEventListener('click', event => {
                event.preventDefault();
                document.getElementById('edit-modal').innerHTML = "<h6>Loading...</h6>";
                var data = element.dataset;
                axios.post(data.url, {
                        id: data.id,
                    })
                    .then(function(response) {
                        console.log(response.data)
                        if (response.data.success == 1) {
                            document.getElementById('edit-modal').innerHTML = response.data.view;
                        } else {
                            document.getElementById('edit-modal').innerHTML = "<h4>Something Went Wrong</h4>";
                        }
                    })
                    .catch(function(err) {
                        console.log(err)
                        document.getElementById('edit-modal').innerHTML = "<h4>Something Went Wrong</h4>";
                    });

            }));
        </script>
    @endsection
</x-app-layout>
