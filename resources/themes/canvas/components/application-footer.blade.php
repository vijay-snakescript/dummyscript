@if (setting('footer_copyright_bar', 1) == 1 || setting('footer_widgets', 1) == 1)
    <footer class="footer">
        @if (setting('footer_widgets', 1) == 1)
            <div class="contant">
                <div class="container">
                    <div class="row">
                        @for ($i = 1; $i <= setting('footer_widget_columns', 4); $i++)
                            <div class="{{ bsColumns(setting('footer_widget_columns', 4)) }}">
                                @if (!Widget::group("footer-{$i}")->isEmpty())
                                    @widgetGroup("footer-{$i}")
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        @endif
        @if (setting('footer_copyright_bar', 1) == 1)
            <div class="copyright">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12{{ setting('footer_center_copyright', 1) == 1 ? ' text-center' : '' }}">
                            {!! sanitize_html(
                                setting(
                                    '_footer_copyright',
                                    '© 2022 DotArtisan, LLC. All rights reserved. Powered By: <a href="https://dotartisan.com">DotArtisan, LLC</a>',
                                ),
                            ) !!}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </footer>
@endif
