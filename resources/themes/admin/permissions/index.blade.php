<x-app-layout>
    <div class="row">
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('admin.permissions.store') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="title">@lang('admin.title')</label>
                            <input class="form-control" id="title" type="text" placeholder="" required
                                name="title">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">@lang('admin.description')</label>
                            <textarea class="form-control" id="description" name="description" type="text"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">@lang('admin.name')</label>
                            <input class="form-control" id="name" type="text" placeholder="" required
                                name="name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="group">@lang('admin.group')</label>
                            <select class="form-control" name="group" required disabled>
                                <option id="0">Select Group</option>
                                @foreach ($permissions as $key => $permission)
                                    <option id="{{ $key }}">{{ $key }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">@lang('common.save')</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="accordion" id="accordionExample">
                                @foreach ($permissions as $key => $permission)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="{{ $key }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-coreui-toggle="collapse"
                                                data-coreui-target="#collapse_{{ $key }}" aria-expanded="false"
                                                aria-controls="collapse_{{ $key }}">{{ $key }}</button>
                                        </h2>
                                        <div class="accordion-collapse collapse" id="collapse_{{ $key }}"
                                            aria-labelledby="{{ $key }}"
                                            data-coreui-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">#</th>
                                                                    <th scope="col">@lang('admin.name')</th>
                                                                    <th scope="col">@lang('admin.group')</th>
                                                                    <th scope="col">@lang('admin.addedDate')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($permission as $perm)
                                                                    <tr>
                                                                        <th scope="row">{{ $loop->iteration }}</th>
                                                                        <td>{{ $perm->name }}</td>
                                                                        <td>{{ $perm->group }}</td>
                                                                        <td>{{ $perm->created_at }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
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
        </div>
    </div>
</x-app-layout>
