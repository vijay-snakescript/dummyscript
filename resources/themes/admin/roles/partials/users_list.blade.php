<div class="modal fade" id="userList" tabindex="-1" aria-labelledby="userListModalLabel" aria-hidden="true"
    data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userListModalLabel">@lang('admin.selectUsers')</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <x-search-input :search="true" :role="$role" class="flex-row-reverse" />
                    </div>
                </div>
            </div>
            <form id="processRole" action="{{ route('admin.role.action') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="role_id" value="{{ $role->id }}">
                    <input type="hidden" name="action" value="1">
                    <div class="row" id="userView"></div>
                </div>
                <div class="modal-footer text-end">
                    <button type="submit" class="btn btn-primary">@lang('common.save')</button>
                </div>
            </form>
        </div>
    </div>
</div>
