<ul class="artisan-nav list-inline list">
    @foreach ($items as $item)
        @php
            $originalItem = $item;

            $listItemClass = null;
            $linkAttributes = null;
            $styles = null;
            $icon = null;

            // Background Color or Color
            if (isset($options->color) && $options->color == true) {
                $styles = 'color:' . $item->color;
            }
            if (isset($options->background) && $options->background == true) {
                $styles = 'background-color:' . $item->color;
            }

            // With child Attributes
            if (isset($originalItem->child) && !$originalItem->child->isEmpty()) {
                if ($item->active) {
                    $linkAttributes = 'class="nav-link dropdown-toggle active" data-bs-toggle="dropdown"';
                } else {
                    $linkAttributes = 'class="nav-link dropdown-toggle" data-bs-toggle="dropdown"';
                }
                $listItemClass = 'nav-item dropdown';
            } else {
                $listItemClass = 'nav-item';

                if ($item->active) {
                    $linkAttributes = 'class="nav-link active"';
                } else {
                    $linkAttributes = 'class="nav-link"';
                }

                if (isset($innerLoop)) {
                    $listItemClass = '';
                    if ($item->active) {
                        $linkAttributes = 'class="dropdown-item active"';
                    } else {
                        $linkAttributes = 'class="dropdown-item"';
                    }
                }
            }

            // Set Icon
            if (isset($options->icon) && $options->icon == true && !empty($item->icon_class)) {
                if (Str::contains($item->icon_class, '<svg')) {
                    $icon = "{$item->icon_class}";
                } else {
                    $icon = '<i class="' . $item->icon_class . '"></i>';
                }
            }
        @endphp
        <li class="{{ $listItemClass }}">
            <a href="{{ $item->href }}" target="{{ $item->target }}" style="{{ $styles }}" {!! $linkAttributes ?? '' !!}>
                {!! $icon !!}
                {{ $item->label }}
            </a>
            @if (isset($originalItem->child) && !$originalItem->child->isEmpty())
                @include('menu.list', [
                    'items' => $originalItem->child,
                    'options' => $options,
                    'innerLoop' => true,
                ])
            @endif
        </li>
    @endforeach
</ul>
