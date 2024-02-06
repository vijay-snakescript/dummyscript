<form action="{{ isset($roleEdit) ? route('admin.roles.update', $roleEdit) : route('admin.roles.store') }}"
    method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $roleEdit->id ?? null }}">
    <div class="form-group mb-3">
        <label for="name" class="form-label">@lang('admin.name')</label>
        <input class="form-control  slug_title" id="name" name="name" value="{{ $roleEdit->name ?? null }}"
            type="text" placeholder="@lang('common.enterName')" required>
    </div>
    <div class="form-group mb-3">
        <label for="description" class="form-label">@lang('admin.description')</label>
        <textarea class="form-control  slug_title" id="description" name="description" value="" type="text">{{ $roleEdit->description ?? null }}</textarea>
    </div>
    <div class="row mb-3">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary btn-xs col-md-2" data-coreui-toggle="collapse"
                data-coreui-target=".multi-collapse" aria-expanded="false"
                aria-controls="permissionTextDiv permissionOptionDiv">
                @lang('admin.manage')
            </button>
        </div>
    </div>
    <div class="row mb-3" id="permission-div">
        <div class="col-sm-12 col-md-12">
            <div class="collapse multi-collapse show" id="permissionTextDiv">
                <h3 class="col-md-10">@lang('admin.permissions')</h3>
                @if (isset($roleEdit))
                    @foreach ($roleEdit->permissions->pluck('name') as $pnames)
                        <span class="badge me-1 bg-success">{{ $pnames }}</span>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="col-sm-12 col-md-12">
            <div class="collapse multi-collapse" id="permissionOptionDiv">
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
                                    <ul class="list-group" id="parent_per_div{{ $key }}">
                                        @foreach ($permission as $perm)
                                            <label
                                                class="list-group-item d-flex list-group-item-action justify-content-between"
                                                id="perdiv{{ $perm->id }}">
                                                <div>
                                                    <p class="mb-0">{{ $perm->title }}</p>
                                                    <p class="text-muted small mb-0">{{ $perm->description }}</p>
                                                </div>
                                                <input type="checkbox" class="form-check-input permission-checkbox"
                                                    data-parentdiv="parent_per_div{{ $key }}"
                                                    data-permissiondiv="perdiv{{ $perm->id }}"
                                                    data-cid="{{ $perm->id }}" data-name="{{ $perm->name }}"
                                                    name="permissions[{{ $perm->id }}]"
                                                    value="{{ $perm->id }}"
                                                    @if (isset($roleEdit) && $roleEdit->hasPermissionTo($perm->id)) checked @endif>
                                            </label>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="form-check form-switch mb-3">
        <input class="form-check-input" id="default" type="checkbox" @if (isset($roleEdit) && $roleEdit->default == 1) checked @endif
            name="default" value="1">
        <label class="form-check-label" for="default">@lang('admin.default')</label>
        <br><span class="text-muted small">@lang('admin.defaultHelpText')</span>
    </div>
    <div class="card-footer text-end">
        <button type="submit" class="btn btn-primary">@lang('common.save')</button>
    </div>
</form>
