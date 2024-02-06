@foreach ($users as $user)
    <div class="col-md-6 col-sm-12">
        <div class="list-group">
            <label class="list-group-item mb-1 d-flex list-group-item-action justify-content-between">
                <div class="d-flex align-items-center w-75">
                    <div class="d-flex flex-column text-truncate">{{ $user->name }}
                        <small class="text-muted text-truncate">{{ $user->email }}</small>
                    </div>
                </div>
                <input class="form-check-input" type="checkbox" name="users[]" value="{{ $user->id }}">
            </label>
        </div>
    </div>
@endforeach
