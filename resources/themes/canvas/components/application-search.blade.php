@if (theme_option('enable_search_in_header', 0))
    <ul class="navbar-nav navbar-search">
        <li class="nav-item dropdown header-search-nav">
            <div type="button" class="nav-link fa-icon-wait nine-dots" id="navSearchBar" role="button"
                data-hide-on-body-scroll="data-hide-on-body-scroll" data-bs-auto-close="outside" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false" aria-label="@lang('common.search')">
                <i class="an an-search"></i>
            </div>
            <ul class="dropdown-menu" aria-labelledby="navSearchBar">
                <div class="search-wrap">
                    <form class="d-flex" role="search">
                        <input class="form-control" id="toolsAutocomplete" type="search"
                            placeholder="@lang('tools.searchFieldPlaceholder')" dir="ltr" spellcheck=false autocorrect="off"
                            autocomplete="off" autocapitalize="off" maxlength="2048" tabindex="1"
                            aria-label="@lang('common.search')">
                    </form>
                </div>
            </ul>
        </li>
    </ul>
@endif
