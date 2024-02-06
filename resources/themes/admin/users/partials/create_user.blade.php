<div class="modal fade" id="createUser" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true"
    data-coreui-backdrop="static" data-coreui-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">@lang('admin.createUser')</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('users.partials.user_form')
            </div>
        </div>
    </div>
</div>
