<x-app-layout>
    <x-manage-filters :search="true" :value="$search" :search-route="route('admin.users.trashed')">
        <button type="button" class="btn btn-outline-primary" data-coreui-toggle="modal" data-coreui-target="#createUser">
            @lang('common.createNew')
        </button>
    </x-manage-filters>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">@lang('admin.deletedUsers')</h6>
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
                                        <th scope="col">@lang('admin.deletedDate')</th>
                                        <th class="text-end" scope="col">@lang('admin.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                {{ $user->deleted_at->format(setting('datetime_format', 'd-m-Y H:i:s')) }}
                                            </td>
                                            <td class="d-flex gap-2 justify-content-end">
                                                <form action="{{ route('admin.users.restore', $user) }}" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <button class="btn btn-success btn-sm text-white" role="button"
                                                        data-coreui-toggle="tooltip" data-placement="right"
                                                        title="@lang('common.restore')">@lang('common.restore')</button>
                                                </form>
                                                <form action="{{ route('admin.users.delete', $user) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button
                                                        class="btn btn-danger btn-sm text-white warning-delete frm-submit"
                                                        role="button" data-coreui-toggle="tooltip"
                                                        data-placement="right"
                                                        title="@lang('common.deleteParmantently')">@lang('common.delete')</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="20">@lang('common.noRecordsFund')</td>
                                        </tr>
                                    @endforelse
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
