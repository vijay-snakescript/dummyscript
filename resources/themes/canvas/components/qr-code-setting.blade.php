@props([
    'results' => null,
    'imagick' => false,
])
<div class="col-md-6">
    <div class="form-group mb-3">
        <x-input-label>@lang('tools.imageSize')</x-input-label>
        <select class="form-control form-select @if ($errors->has('size')) is-invalid @endif" name="size"
            required id="size">
            @for ($i = 50; $i <= 1000; $i += 25)
                <option value="{{ $i }}" @if ($results && $results['size'] == $i) selected @endif>
                    {{ $i . 'x' . $i }}
                </option>
            @endfor
        </select>
        <x-input-error :messages="$errors->get('size')" />
    </div>
</div>
<div class="col-md-6">
    <div class="form-group mb-3">
        <x-input-label>@lang('tools.padding')</x-input-label>
        <select class="form-control form-select @if ($errors->has('size')) is-invalid @endif" name="padding"
            required id="padding">
            @for ($i = 0; $i <= 10; $i++)
                <option value="{{ $i }}" @if ($results && $results['padding'] == $i) selected @endif>
                    {{ $i }}
                </option>
            @endfor
        </select>
        <x-input-error :messages="$errors->get('size')" />
    </div>
</div>
<div class="col-md-6">
    <div class="form-group mb-3">
        <x-input-label>@lang('tools.errorCorrection')</x-input-label>
        <select class="form-control form-select @if ($errors->has('correction')) is-invalid @endif" name="correction"
            required id="correction">
            <option value="L" @if ($results && $results['correction'] == 'L') selected @endif>@lang('tools.low')</option>
            <option value="M" @if ($results && $results['correction'] == 'M') selected @endif>@lang('tools.medium')</option>
            <option value="Q" @if ($results && $results['correction'] == 'Q') selected @endif>@lang('tools.quartile')</option>
            <option value="H" @if ($results && $results['correction'] == 'H') selected @endif>@lang('tools.high')</option>
        </select>
        <x-input-error :messages="$errors->get('correction')" />
    </div>
</div>
<div class="col-md-6">
    <div class="form-group mb-3">
        <x-input-label>@lang('tools.style')</x-input-label>
        <select class="form-control form-select @if ($errors->has('style')) is-invalid @endif" name="style"
            required id="style">
            <option value="square" @if ($results && $results['style'] == 'square') selected @endif>@lang('tools.square')</option>
            <option value="dot" @if ($results && $results['style'] == 'dot') selected @endif>@lang('tools.dot')</option>
            <option value="round" @if ($results && $results['style'] == 'round') selected @endif>@lang('tools.circleRound')</option>
        </select>
        <x-input-error :messages="$errors->get('style')" />
    </div>
</div>
<div class="col-md-6">
    <div class="form-group mb-3">
        <x-input-label>@lang('tools.eyeStyle')</x-input-label>
        <select class="form-control form-select @if ($errors->has('eye')) is-invalid @endif" name="eye"
            required id="eye">
            <option value="square" @if ($results && $results['eye'] == 'square') selected @endif>@lang('tools.square')</option>
            <option value="circle" @if ($results && $results['eye'] == 'circle') selected @endif>@lang('tools.circle')</option>
        </select>
        <x-input-error :messages="$errors->get('eye')" />
    </div>
</div>
@if ($imagick)
    <div class="col-md-6">
        <div class="form-group mb-3">
            <x-input-label>@lang('tools.format')</x-input-label>
            <select class="form-control form-select qr-format @if ($errors->has('format')) is-invalid @endif"
                name="format" required id="format">
                <option value="svg" @if ($results && $results['format'] == 'svg') selected @endif>SVG</option>
                <option value="eps" @if ($results && $results['format'] == 'eps') selected @endif>EPS</option>
                <option value="png" @if ($results && $results['format'] == 'png') selected @endif>PNG</option>
            </select>
            <x-input-error :messages="$errors->get('format')" />
        </div>
    </div>
    <div class="col-md-6 d-none imageUpload">
        <div class="form-group mb-3">
            <x-input-label>@lang('tools.addLogoImage')</x-input-label>
            <div class="file-input">
                <x-input-file-button accept=".png" file-id="qr-logo"
                    class="btn btn-outline-primary d-block upload-btn" />
            </div>
        </div>
    </div>
@endif
<div class="col-md-6">
    <div class="form-group mb-3">
        <x-input-label>@lang('tools.colorType')</x-input-label>
        <select class="form-control form-select @if ($errors->has('color_type')) is-invalid @endif" name="color_type"
            required id="color_type">
            <option value="0" @if ($results && $results['color_type'] == '0') selected @endif>@lang('tools.defaultSingle')</option>
            <option value="vertical" @if ($results && $results['color_type'] == 'vertical') selected @endif>@lang('tools.verticalGradient')</option>
            <option value="horizontal" @if ($results && $results['color_type'] == 'horizontal') selected @endif>@lang('tools.horizontalGradient')</option>
            <option value="diagonal" @if ($results && $results['color_type'] == 'diagonal') selected @endif>@lang('tools.diagonalGradient')</option>
            <option value="inverse_diagonal" @if ($results && $results['color_type'] == 'inverse_diagonal') selected @endif>@lang('tools.inverseDiagonalGradient')
            </option>
            <option value="radial" @if ($results && $results['color_type'] == 'radial') selected @endif>@lang('tools.radialGradient')</option>
        </select>
        <x-input-error :messages="$errors->get('color_type')" />
    </div>
</div>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2">
            <div class="form-group mb-3">
                <x-input-label>@lang('tools.backgroundColor')</x-input-label>
                <x-text-input type="color" name="background_color" required
                    class="form-control-color p-0 text-end diraction-end"
                    value="{{ $results['background_color'] ?? '#ffffff' }}" :error="$errors->has('background_color')" />
                <x-input-error :messages="$errors->get('background_color')" />
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group mb-3">
                <x-input-label>@lang('tools.color')</x-input-label>
                <x-text-input type="color" name="color" required
                    class="form-control-color p-0 text-end diraction-end" value="{{ $results['color'] ?? '#000000' }}"
                    :error="$errors->has('color_sec')" />
                <x-input-error :messages="$errors->get('color')" />
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group mb-3" id="color_sec_div"
                @if (!$results || $results['color_type'] == '0') style="display:none" @endif>
                <x-input-label>@lang('tools.colorSec')</x-input-label>
                <x-text-input type="color" name="color_sec" required
                    class="form-control-color p-0 text-end diraction-end"
                    value="{{ $results['color_sec'] ?? '#ffffff' }}" :error="$errors->has('color_sec')" />
                <x-input-error :messages="$errors->get('color_sec')" />
            </div>
        </div>
    </div>
    @if (!empty($results['logo']))
        <input type="hidden" name="logo" value="{{ $results['logo'] }}">
    @endif
</div>
