@props(['color' => '#ffffff'])


<div class="dropdown h-100">
    <span class="input-group-text rounded-0 border-0 d-block h-100" type="button" data-coreui-toggle="dropdown" data-coreui-auto-close="outside" aria-haspopup="true" aria-expanded="false" style="background-color:{{$color}}">
    </span>
    <div class="dropdown-menu p-0">
        <hex-color-picker color="{{ $color }}"></hex-color-picker>
    </div>
</div>
