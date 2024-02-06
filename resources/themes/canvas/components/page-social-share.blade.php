@props(['url', 'title', 'elementClasses' => 'mb-3', 'style' => 'style2'])
<div class="d-flex align-items-center flex-column flex-md-row justify-content-between {{ $elementClasses }}">
    <div class="fw-bold me-2">
        <h3 class="mb-0">@lang('profile.shareOnSocialMedia')</h3>
    </div>
    <ul class="nav nav-social mt-3 mt-md-0 flex-nowrap {{ $style }}">
        <li>
            <a title="@lang('profile.shareToBrand', ['brand' => 'Facebook'])" data-bs-toggle="tooltip" data-placement="top"
                class="btn-social py-2 btn btn-sm social-share btn-facebook"
                href="https://www.facebook.com/share.php?u={{ urlencode($url) }}&amp;quote={{ urlencode($title) }}"
                rel="nofollow noreferrer noopener">
                <i class="an an-2x an-facebook"></i>
            </a>
        </li>
        <li>
            <a title="@lang('profile.shareToBrand', ['brand' => 'Twitter'])" data-bs-toggle="tooltip" data-placement="top"
                class="btn-social py-2 btn btn-sm social-share btn-twitter"
                href="https://twitter.com/intent/tweet?text={{ urlencode($title) }} {{ urlencode($url) }}"
                rel="nofollow noreferrer noopener">
                <i class="an an-2x an-twitter"></i>
            </a>
        </li>
        <li>
            <a title="@lang('profile.shareToBrand', ['brand' => 'Pinterest'])" data-bs-toggle="tooltip" data-placement="top"
                class="btn-social py-2 btn btn-sm social-share btn-pinterest"
                href="https://pinterest.com/pin/create/link/?url={{ urlencode($url) }}">
                <i class="an an-2x an-pinterest"></i>
            </a>
        </li>
        <li>
            <a title="@lang('profile.shareToBrand', ['brand' => 'Reddit'])" data-bs-toggle="tooltip" data-placement="top"
                class="btn-social py-2 btn btn-sm social-share btn-reddit"
                href="https://www.reddit.com/submit?url={{ urlencode($url) }}&amp;title={{ urlencode($title) }}">
                <i class="an an-2x an-reddit-alien"></i>
            </a>
        </li>
        <li class="nav-item d-none">
            <a title="@lang('profile.shareToBrand', ['brand' => 'WhatsApp'])" data-bs-toggle="tooltip" data-placement="top" target="_top"
                class="btn-social py-2 btn btn-sm btn-whatsapp"
                href="whatsapp://send?text={{ urlencode($title) }}%20%0A{{ urlencode($url) }}">
                <i class="an an-2x an-whatsapp"></i>
            </a>
        </li>
        <li>
            <a title="@lang('profile.emailThisPage')" data-bs-toggle="tooltip" data-placement="top"
                class="btn-social py-2 btn btn-sm btn-email"
                href="mailto:?body={{ __('profile.shareToEmailText', ['title' => urlencode($title), 'url' => urlencode($url)]) }}"
                rel="nofollow noreferrer noopener">
                <i class="an an-2x an-email"></i>
            </a>
        </li>
    </ul>
</div>
@push('page_scripts')
    <script>
        const SocialApp = function() {
            const popupSize = {
                width: 780,
                height: 550
            };
            const initEvents = function() {
                if (((navigator.userAgent.match(/Android|iPhone/i) && !navigator.userAgent.match(/iPod|iPad/i)) ?
                        true : false)) {
                    document.querySelector(".btn-whatsapp").parentNode.classList.remove('d-none');
                }
                document.querySelectorAll('.social-share').forEach(element => {
                    element.addEventListener('click', e => {
                        e.preventDefault()
                        var verticalPos = Math.floor((window.innerWidth - popupSize.width) / 2),
                            horisontalPos = Math.floor((window.innerHeight - popupSize.height) / 2),
                            url = element.href;

                        var popup = window.open(url, 'social',
                            'width=' + popupSize.width + ',height=' + popupSize.height +
                            ',left=' + verticalPos + ',top=' + horisontalPos +
                            ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');
                        if (popup) {
                            popup.focus();
                        } else {
                            var win = window.open(url, '_blank');
                            win.focus();
                        }
                    });
                });
            }

            return {
                init: function() {
                    initEvents()
                }
            }
        }()

        document.addEventListener("DOMContentLoaded", function(event) {
            SocialApp.init();
        });
    </script>
@endpush
