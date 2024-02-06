<x-app-layout>
    <div class="mb-5">
        @if ($menus->count() != 0)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row row-cols-lg-auto g-3 align-items-center">
                        <div class="col-auto">
                            <label class="form-label" for="select-menu">@lang('admin.selectMenuEdit')</label>
                        </div>
                        <div class="col-auto">
                            <select class="form-control" name="menu" id="select-menu">
                                <option value="">@lang('common.selectOne')</option>
                                @foreach ($menus as $item)
                                    <option value="{{ $item->id }}"
                                        @if ($menu && $menu->id == $item->id) selected @endif>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-secondary btn-sm menu-select" type="submit">
                                @lang('common.select')
                            </button>
                        </div>
                        @if ($menu)
                            <div class="col-auto">
                                <span>
                                    @lang('common.orSmall')
                                    <a href="{{ route('admin.menus') }}">
                                        @lang('admin.createNewMenu')
                                    </a>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <h3 class="h6 bold">@lang('admin.addMenuItems')</h3>
                <div class="menu-builder" id="menuSections">
                    @foreach ($sections as $section)
                        <form action="{{ $menu ? route('admin.menus.add-items', $menu) : '' }}" method="post">
                            @csrf
                            <div class="card mb-0 rounded-0 border-bottom-0">
                                <div class="card-header rounded-0{{ $loop->first ? '' : ' collapsed' }}"
                                    data-coreui-toggle="collapse" data-coreui-target="#{{ $section->key }}"
                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                    aria-controls="{{ $section->key }}">
                                    <h6 class="mb-0">{{ $section->name }}</h6>
                                </div>
                                <div id="{{ $section->key }}" class="{{ $loop->first ? 'show collapse' : 'collapse' }}"
                                    aria-labelledby="{{ $section->key }}" data-coreui-parent="#menuSections">
                                    <div class="card-body rounded-0 overflow-auto" style="max-height:200px;">
                                        @if ($section->hasItems())
                                            @foreach ($section->items as $key => $item)
                                                <div class="form-check">
                                                    <input class="form-check-input" name="items[]" type="checkbox"
                                                        value="{{ $key }}" id="{{ $key }}"
                                                        @if (!$menu) disabled @endif>
                                                    <label class="form-check-label" for="{{ $key }}">
                                                        {{ $item->label }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="card-text">
                                                @lang('admin.noItems')
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-footer rounded-0 text-end">
                                        <input type="hidden" name="source" value="{{ $section->key }}">
                                        <button type="submit" class="btn btn-primary btn-sm"
                                            @if (!$menu) disabled @endif>@lang('admin.addItems')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endforeach
                    <div class="card mb-0 rounded-0">
                        <form action="{{ $menu ? route('admin.menus.add-items', $menu) : '' }}"
                            class="frmMenuItems customItem" method="post">
                            @csrf
                            <div class="card-header" data-coreui-toggle="collapse"
                                data-coreui-target="#placeholder-custom" aria-expanded="false"
                                aria-controls="placeholder-custom">
                                <h6 class="mb-0">@lang('admin.customLink')</h6>
                            </div>
                            <div class="collapse" data-parent="#menuSections" id="placeholder-custom">
                                <div class="card-body overflow-auto" style="max-height:230px;">
                                    <div class="form-group mb-3" id="customlinkdiv">
                                        <label class="form-label" for="custom-menu-item-url">@lang('common.url')</label>
                                        <input class="form-control required" id="custom-menu-item-url" name="url"
                                            type="text" value="http://" placeholder="@lang('common.url')"
                                            @if (!$menu) disabled @endif>
                                    </div>
                                    <div class="form-group" id="customnamediv">
                                        <label class="form-label" for="custom-menu-item-name">@lang('admin.itemTitle')</label>
                                        <input class="form-control required" id="custom-menu-item-name" name="link_text"
                                            type="text" placeholder="@lang('admin.itemTitle')"
                                            @if (!$menu) disabled @endif>
                                    </div>
                                </div>
                                <div class="card-footer form-actions text-end">
                                    <input type="hidden" name="source" value="custom">
                                    <button type="submit" class="btn btn-sm btn-primary addCustomBtn addItemsBtn"
                                        @if (!$menu) disabled @endif>@lang('admin.addItem')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-sm-12">
                <h3 class="h6 bold">@lang('admin.menuStructure')</h3>
                <form action="{{ $menu ? route('admin.menus.update', $menu) : route('admin.menus.create') }}"
                    method="post">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <div class="row row-cols-lg-auto g-3 align-items-center">
                                <div class="col-12">
                                    <label class="form-label mb-0" for="menu_name">@lang('admin.menuName')</label>
                                </div>
                                <div class="col-12">
                                    <input name="name" value="{{ $menu ? $menu->name : '' }}"
                                        class="form-control form-control-sm" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                {{ $menu ? __('admin.addMenuItemHelp') : __('admin.createmenuHelp') }}
                            </p>
                            <div class="sortable-menu dd">
                                @if ($menu)
                                    <x-application-menu-builder :items="$menu->parent_items" />
                                @endif
                            </div>
                        </div>
                        <div class="card-footer footer-sticky">
                            @if ($menu)
                                <a class="text-danger deleteMenu small" role="button">
                                    @lang('admin.deleteMenu')
                                </a>
                                <input type="hidden" name="id" value="{{ $menu->id }}">
                            @endif
                            <button class="btn btn-primary btn-sm float-end" type="submit">
                                {{ $menu ? __('admin.saveMenu') : __('admin.createMenu') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if ($menu)
        <form id="deleteFrm" method="post" action="{{ route('admin.menus.destroy', $menu) }}">
            @method('DELETE')
            @csrf
        </form>
    @endif
    @section('footer_scripts')
        <script src="https://code.jquery.com/jquery-2.2.4.min.js"
            integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/nestable2@1.6.0/jquery.nestable.min.js" crossorigin="anonymous"></script>
        <script>
            const ARTISAN_APP = function() {
                const changeOrder = function() {
                        const $m_nestable = $('.dd').nestable({
                            expandBtnHTML: '',
                            collapseBtnHTML: '',
                            maxDepth: 3,
                        });
                        $m_nestable.on('change', function(e) {
                            const items = $('.dd').nestable('serialize')
                            formatMenu(items, null)
                        });
                    },
                    formatMenu = function(items, parent) {
                        items.forEach(function(item, key) {
                            $(`#menu_order_${item.id}`).val(key);
                            $(`#menu_parent_${item.id}`).val(parent?.id);
                            if (item.children) {
                                formatMenu(item.children, item)
                            }
                        });
                    };
                const customLink = function() {
                    function isValidCustomLink() {
                        const url = document.getElementById("custom-menu-item-url"),
                            name = document.getElementById("custom-menu-item-name");
                        var d = url.value,
                            e = name.value;
                        "http://" == d ? url.classList.add("is-invalid") : url.classList.remove(
                            "is-invalid")
                        "" == e ? name.classList.add("is-invalid") : name.classList.remove(
                            "is-invalid")

                        return "http://" == d || '' == e
                    }
                    document.querySelectorAll('#custom-menu-item-url, #custom-menu-item-name').forEach(element => {
                        element.addEventListener('keyup',
                            (event) => {
                                isValidCustomLink()
                            });
                    });
                    document.querySelector('.addCustomBtn').addEventListener('click', (e) => {
                        if (isValidCustomLink()) {
                            e.preventDefault();
                        }
                    });
                };
                const selectMenu = function(e) {
                    document.querySelector('.menu-select').addEventListener('click', function() {
                        const id = document.querySelector('select[name="menu"]').value
                        let route = '{{ route('admin.menus', ['menu' => ':id']) }}'
                        route = route.replace(':id', id)
                        if (id) {
                            window.location.href = route
                        }
                    })
                };
                @if ($menu)
                    const deleteMenu = function(e) {
                        document.querySelector('.deleteMenu').addEventListener("click", function() {
                            document.getElementById('deleteFrm').submit()
                        })
                    };
                    const deleteMenuItem = function(e) {
                        document.querySelectorAll('.delete-menu-item').forEach((element) => {
                            element.addEventListener('click', (e) => {
                                const id = e.target.getAttribute('data-menu-id')
                                var $url =
                                    '{{ route('admin.menus.item.destroy', ['menu' => $menu->id, 'item' => ':id']) }}';
                                $url = $url.replace(':id', id)
                                axios.delete($url)
                                    .then(res => {
                                        if (res.data)
                                            document.querySelector('[data-id="' + id + '"]')
                                            .remove()
                                    }).catch(err => {
                                        console.log(err.response.data.msg)
                                    });
                            })
                        });
                    };
                @endif

                return {
                    init: function() {
                        selectMenu();
                        customLink();
                        @if ($menu)
                            deleteMenu()
                            deleteMenuItem()
                            changeOrder()
                        @endif
                    }
                }
            }();
            document.addEventListener("DOMContentLoaded", function(event) {
                ARTISAN_APP.init();
            });
        </script>
    @endsection
</x-app-layout>
