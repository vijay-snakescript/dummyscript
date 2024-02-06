<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <?php $segments = ''; ?>
    @for ($i = 1; $i <= count(Request::segments()); $i++)
        <?php $segments .= '/' . Request::segment($i); ?>
        @if ($i < count(Request::segments()))
            <li class="breadcrumb-item">{{ Request::segment($i) }}</li>
        @else
            <li class="breadcrumb-item active">{{ Request::segment($i) }}</li>
        @endif
    @endfor
</ol>
