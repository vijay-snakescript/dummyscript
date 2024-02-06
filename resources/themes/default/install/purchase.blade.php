@extends('install.layout')

@section('content')
    <h2>2. Verify Purchase</h2>
    <hr>
    @include ('install.messages')

        <div class="box">
            <div class="configure-form">
                Verification done. Nulled by <a style="color:red;" href="https://weadown.com" target="_blank">weadown.com</a>.
            </div>
        </div>


        <div class="content-buttons mt-3 text-end">
            <a href="{{ ($requirement->satisfied() && $verifyPurchase->satisfied()) ? route('installconfig.get') : route('verify.redirect') }}" class="btn btn-primary rounded-pill px-5 text-white btn-lg">
                {{ ($requirement->satisfied() && $verifyPurchase->satisfied()) ? 'Continue' : 'Signin with Envato' }}
            </a>
        </div>
    </form>
@endsection

@push('scripts')
    <script>

    </script>
@endpush
