@if (theme_option('enable_dark_mode', 0) == 1)
    <div class="night-mode">
        <div class="switch">
            <div class="user-toggle">
                <div role="status" class="[ visually-hidden ][ js-mode-status ]"></div>
                <button class="[ toggle-button ] [ js-mode-toggle ] btn-mode">
                    <span class="theme-mode theme-mode-light" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </div>
@endif
