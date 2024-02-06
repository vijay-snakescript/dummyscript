@if (Breadcrumbs::exists())
    <nav class="breadcrumbs" aria-label="breadcrumb">
        <div class="container">
            {{ Breadcrumbs::render() }}
        </div>
    </nav>
@endif
