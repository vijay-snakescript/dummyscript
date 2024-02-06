@props(['disabled' => false, 'role'])

<div class="input-group mb-3">
    <input type="text" class="form-control" name="q" id="search-input" placeholder="Recipient's username"
        aria-label="@lang('common.search')" {{ $disabled ? 'disabled' : '' }} aria-describedby="search-with-button">
    <button class="btn btn-primary get-users" data-id="{{ $role->id }}"
        data-url="{{ route('admin.role.getUsers', $role->id) }}" type="button">
        <i class="lni lni-search-alt"></i>
    </button>
</div>
