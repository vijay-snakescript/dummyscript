@props(['items', 'menuId' => Str::random(4)])
<ol class="dd-list accordion" id="sortable-{{ $menuId }}">
    @foreach ($items as $index => $item)
        <li class="dd-item" data-id="{{ $item->id }}">
            <div class="card mb-1">
                <div class="card-header d-flex" id="cardHeader-{{ $menuId }}">
                    <p class="mb-0 w-100 dd-handle">
                        {{ $item->label }}
                    </p>
                    <span class="collapse-handle collapsed ml-auto" data-coreui-toggle="collapse"
                        data-coreui-target="#menu-item-{{ $item->id }}" aria-expanded="true"
                        aria-controls="menu-item-{{ $item->id }}">
                        <i class="lni lni-chevron-down"></i>
                    </span>
                </div>
                <div id="menu-item-{{ $item->id }}" class="collapse"
                    aria-labelledby="cardHeader-{{ $menuId }}" data-parent="#sortable-{{ $menuId }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label class="form-label"
                                        for="item-title-{{ $item->id }}">@lang('admin.itemTitle')</label>
                                    <input type="text" class="form-control" id="item-title-{{ $item->id }}"
                                        name="items[{{ $item->id }}][label]" placeholder="@lang('common.title')"
                                        value="{{ $item->label }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label class="form-label"
                                        for="item-is-route-{{ $item->id }}">@lang('admin.linkType')</label>
                                    <select id="item-is-route-{{ $item->id }}" class="form-control route_type"
                                        name="items[{{ $item->id }}][is_route]">
                                        <option value="0" @if (!$item->is_route) selected @endif>
                                            @lang('common.url')</option>
                                        <option value="1" @if ($item->is_route) selected @endif>
                                            @lang('common.route')</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label class="form-label" for="item-link-{{ $item->id }}">
                                        <span data-conditional-name="items[{{ $item->id }}][is_route]"
                                            data-conditional-value="0">
                                            @lang('admin.itemURL')
                                        </span>
                                    </label>
                                    <label class="form-label" for="item-link-{{ $item->id }}">
                                        <span data-conditional-name="items[{{ $item->id }}][is_route]"
                                            data-conditional-value="1">
                                            @lang('admin.itemRoute')
                                        </span>
                                    </label>
                                    <input type="text" class="form-control" name="items[{{ $item->id }}][link]"
                                        placeholder="" id="item-link-{{ $item->id }}" value="{{ $item->link }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label class="form-label"
                                        for="item-parameters-{{ $item->id }}">@lang('admin.routeParameter')</label>
                                    <textarea rows="3" class="form-control" id="item-parameters-{{ $item->id }}"
                                        name="items[{{ $item->id }}][parameters]"
                                        placeholder="{{ json_encode(['key' => 'value'], JSON_PRETTY_PRINT) }}"
                                        data-conditional-name="items[{{ $item->id }}][is_route]" data-conditional-value="1">{{ json_encode($item->parameters) }}</textarea>
                                </div>
                                <div class="form-group mb-2">
                                    <button type="button" class="btn btn-sm btn-primary" data-coreui-toggle="collapse"
                                        href="#advance-setting-{{ $item->id }}" role="button"
                                        aria-expanded="false"
                                        aria-controls="advance-setting-{{ $item->id }}">@lang('common.advance')</button>
                                </div>
                                <div class="item-advance-controlls collapse" id="advance-setting-{{ $item->id }}">
                                    <div class="form-group mb-2">
                                        <label class="form-label"
                                            for="item-class-{{ $item->id }}">@lang('admin.customClass')</label>
                                        <input type="text" class="form-control" id="item-class-{{ $item->id }}"
                                            name="items[{{ $item->id }}][class]" placeholder="@lang('admin.customClassHelp')"
                                            value="{{ $item->class }}">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="form-label"
                                            for="item-icon-{{ $item->id }}">@lang('admin.iconHelp')</label>
                                        <input type="text" class="form-control" id="item-icon-{{ $item->id }}"
                                            name="items[{{ $item->id }}][icon]" placeholder="@lang('admin.iconPlaceholder')"
                                            value="{{ $item->icon }}">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="form-label"
                                            for="item-target-{{ $item->id }}">@lang('admin.openIn')</label>
                                        <select id="item-target-{{ $item->id }}" class="form-control"
                                            name="items[{{ $item->id }}][target]">
                                            <option value="_self"@if ($item->target == '_self') selected @endif>
                                                @lang('admin.openSame')</option>
                                            <option value="_blank"@if ($item->target == '_blank') selected @endif>
                                                @lang('admin.openNew')</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="items[{{ $item->id }}][id]"
                                    value="{{ $item->id }}">
                                <input type="hidden" id="menu_order_{{ $item->id }}"
                                    name="items[{{ $item->id }}][sort]" value="{{ $item->sort }}">
                                <input type="hidden" id="menu_parent_{{ $item->id }}"
                                    name="items[{{ $item->id }}][parent]" value="{{ $item->parent }}">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn text-danger btn-link delete-menu-item"
                            data-menu-id="{{ $item->id }}"
                            data-menu-parent-id={{ $item->parent_id }}>@lang('common.delete')</button>
                    </div>
                </div>
            </div>

            @if (!$item->child->isEmpty())
                <x-application-menu-builder :items="$item->child" />
            @endif
        </li>
    @endforeach
</ol>
