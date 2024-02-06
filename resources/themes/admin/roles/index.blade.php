<x-app-layout>
    <x-manage-filters :search="true" :search-route="route('admin.roles', $role ?? null)" class="flex-row-reverse">
        <button type="button" class="btn btn-outline-primary" data-coreui-toggle="modal" data-coreui-target="#addRole">
            @lang('admin.createRole')
        </button>
    </x-manage-filters>
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-justified nav-pills admin-pill-nav d-block" role="navigation">
                @foreach ($roles as $roleData)
                    <li class="nav-item align-items-center{{ $role->id == $roleData->id ? ' selected' : '' }}">
                        <a class="name w-100 align-self-center" href="{{ route('admin.roles', $roleData->id) }}">
                            <div>
                                <span class="role-name">{{ $roleData->name }}</span>
                                <div class="role-desc small text-muted">
                                    {{ $roleData->description }}
                                </div>
                            </div>
                        </a>
                        <div class="actions">
                            <div class="btn-group">
                                <button class="btn btn-link dropdown-toggle dropdown-no-caret" type="button"
                                    data-coreui-toggle="dropdown" aria-expanded="false">
                                    <i class="pull-right lni lni-cog"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item edit-role" data-url="{{ route('admin.roles.edit') }}"
                                        data-id="{{ $roleData->id }}" data-coreui-toggle="modal"
                                        data-coreui-target="#editRole">@lang('common.edit')</a>
                                    <form action="{{ route('admin.roles.destroy', $roleData->id) }}" method="POST"
                                        class="">
                                        @method('DELETE')
                                        @csrf
                                        <button class="dropdown-item  warning-delete frm-submit" role="button"
                                            data-bs-toggle="tooltip" data-placement="right"
                                            title="@lang('common.delete')">@lang('common.delete')</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-9">
            <form method="post" action="{{ route('admin.role.action') }}">
                <div class="card mb-3">
                    <div class="card-header">
                        <button type="submit" class="btn btn-primary btn-sm"
                            id="unassignBtn">@lang('admin.unassign')</button>
                        <button type="button" class="btn btn-primary btn-sm get-users" id="assignBtn"
                            data-id="{{ $role->id }}" data-url="{{ route('admin.role.getUsers', $role->id) }}"
                            data-coreui-toggle="modal" data-coreui-target="#userList">@lang('admin.assignRole')</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <input type="hidden" name="role_id" value="{{ $role->id }}" />
                            <input type="hidden" name="action" value="0" />
                            @csrf
                            <div class="col-12">
                                <table class="table table-hover table-artisan mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">
                                                <input type="checkbox" class="form-check-input checkbox_all i-checks">
                                            </th>
                                            <th scope="col">@lang('admin.name')</th>
                                            <th scope="col">@lang('admin.email')</th>
                                            <th scope="col">@lang('admin.status')</th>
                                            <th scope="col">@lang('admin.addedDate')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <th scope="row">
                                                    <input type="checkbox" class="form-check-input id_class"
                                                        name="users[]" value="{{ $user->id }}">
                                                </th>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @if ($user->status == 1)
                                                        <span class="badge me-1 bg-success">@lang('common.active')</span>
                                                    @else
                                                        <span class="badge me-1 bg-danger">@lang('common.inactive')</span>
                                                    @endif
                                                </td>
                                                <td>{{ $user->created_at->format(setting('datetime_format', 'd-m-Y H:i:s')) }}
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
            </form>
        </div>
    </div>
    @include('roles.partials.create_role')
    @include('roles.partials.edit_role')
    @include('roles.partials.users_list')
    @section('footer_scripts')
        <script>
            const APP = function() {
                const enableDisableBtn = function() {
                        var lnth = document.querySelectorAll('.id_class:checked').length

                        if (lnth > 0) {
                            document.getElementById("unassignBtn").disabled = false;
                            document.getElementById("assignBtn").disabled = true;
                        } else {
                            document.getElementById("unassignBtn").disabled = true;
                            document.getElementById("assignBtn").disabled = false;
                        }

                    },
                    checkAll = function() {
                        document.querySelectorAll('.i-checks').forEach(element => element.addEventListener('click',
                            event => {
                                var clist = document.getElementsByClassName("id_class");
                                if (element.checked) {
                                    for (var i = 0; i < clist.length; ++i) {
                                        clist[i].checked = "checked";
                                    }
                                } else {
                                    for (var i = 0; i < clist.length; ++i) {
                                        clist[i].checked = "";
                                    }
                                }
                                enableDisableBtn();
                            }));

                        document.querySelectorAll('.id_class').forEach(element => {
                            element.addEventListener('change', event => {
                                enableDisableBtn();
                            })
                        });
                    },
                    assignRoleModal = function() {
                        if (document.querySelectorAll('.get-users').length > 0) {
                            document.querySelector('.get-users').addEventListener('click',
                                event => {
                                    const element = document.querySelector('.get-users')
                                    document.getElementById('userView').innerHTML =
                                        "<h6>{{ __('common.loading') }}</h6>";
                                    var search = document.getElementById('search-input').value;
                                    var data = element.dataset;
                                    axios.post(data.url, {
                                            id: data.id,
                                            q: search,
                                        })
                                        .then(function(response) {
                                            if (response.data.success == 1) {
                                                document.getElementById('userView').innerHTML = response.data.view;
                                            } else {
                                                document.getElementById('userView').innerHTML =
                                                    "<h4>{{ __('common.somethingWentWrong') }}</h4>";
                                            }
                                        })
                                        .catch(function(err) {
                                            console.log(err)
                                            document.getElementById('userView').innerHTML =
                                                "<h4>{{ __('common.somethingWentWrong') }}</h4>";
                                        });
                                });
                        }
                    };
                const editRoleModal = function() {
                    if (document.querySelectorAll('.edit-role').length > 0) {
                        document.querySelectorAll('.edit-role').forEach(element => element.addEventListener('click',
                            event => {
                                event.preventDefault();
                                document.getElementById('edit-modal').innerHTML =
                                    "<h6>{{ __('common.loading') }}</h6>";
                                var data = element.dataset;
                                axios.post(data.url, {
                                        id: data.id,
                                    })
                                    .then(function(response) {
                                        console.log(response.data)
                                        if (response.data.success == 1) {
                                            document.getElementById('edit-modal').innerHTML = response.data
                                                .view;
                                        } else {
                                            document.getElementById('edit-modal').innerHTML =
                                                "<h4>{{ __('common.somethingWentWrong') }}</h4>";
                                        }
                                    })
                                    .catch(function(err) {
                                        console.log(err)
                                        document.getElementById('edit-modal').innerHTML =
                                            "<h4>{{ __('common.somethingWentWrong') }}</h4>";
                                    });

                            }));
                    }
                }
                return {
                    init: function() {
                        enableDisableBtn();
                        assignRoleModal();
                        editRoleModal();
                        checkAll();
                    }
                }
            }();
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endsection
</x-app-layout>
