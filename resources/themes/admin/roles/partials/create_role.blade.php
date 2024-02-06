<div class="modal fade" id="addRole" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true"
    data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoleModalLabel">@lang('admin.createRole')</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('roles.partials.role_form')
            </div>
        </div>
    </div>
</div>
