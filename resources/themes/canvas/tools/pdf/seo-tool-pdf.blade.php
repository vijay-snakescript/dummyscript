<!DOCTYPE html>
<html lang="en">

<head>
    @vite(['resources/themes/canvas/assets/sass/app.scss'])
</head>

<body>
    <main class="main-wrapper">
        <div class="contant-wrap">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <x-seo-tool-result :results="$results" :tool="$tool" :pdf="true"></x-seo-tool-result>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
