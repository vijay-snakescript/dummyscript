<div class="modal fade" id="selectPermission" tabindex="-1" aria-labelledby="selectPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectPermissionModalLabel">@lang('admin.selectPermissions')</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="accordion" id="accordionExample">
                    @foreach ($permissions as $key => $permission)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="{{ $key }}">
                                <button class="accordion-button collapsed text-uppercase" type="button"
                                    data-coreui-toggle="collapse" data-coreui-target="#collapse_{{ $key }}"
                                    aria-expanded="false"
                                    aria-controls="collapse_{{ $key }}">{{ $key }}</button>
                            </h2>
                            <div class="accordion-collapse collapse" id="collapse_{{ $key }}"
                                aria-labelledby="{{ $key }}" data-coreui-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row" id="parent_per_div{{ $key }}">
                                        @foreach ($permission as $perm)
                                            <div class="col-md-4" id="perdiv{{ $perm->id }}">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input permission-checkbox" type="checkbox"
                                                        data-parentdiv="parent_per_div{{ $key }}"
                                                        data-permissiondiv="perdiv{{ $perm->id }}"
                                                        data-cid="{{ $perm->id }}" data-name="{{ $perm->name }}"
                                                        name="permissions[{{ $perm->id }}]"
                                                        value="{{ $perm->id }}">
                                                    <label class="form-check-label"
                                                        for="guest">{{ $perm->name }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
