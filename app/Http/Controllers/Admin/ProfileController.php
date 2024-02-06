<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Hash;
use Session;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PragmaRX\Google2FAQRCode\Google2FA;
use App\Http\Requests\Admin\ProfileRequest;

class ProfileController extends Controller
{
    public function __construct()
    {
        if (!Session::get('twofa_key')) {
            $google2fa = new Google2FA();
            Session::put('twofa_key', $google2fa->generateSecretKey());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::User();

        return view('users.profile.index', compact('user'));
    }

    public function password()
    {
        $user = Auth::User();

        return view('users.profile.password', compact('user'));
    }

    /**
     */
    public function update(ProfileRequest $request)
    {
        $data = $request->input();
        $user = User::find($request->id);

        if (isset($data['name'])) {
            $data_arry = [
                'name' => $data['name'],
                'username' => $data['username'],
                'about' => $data['about'],
                'email' => $data['email'],
                'picture' => 'default.png',
            ];
        }

        if ($request->password != null || $request->password != "") {
            $data_arry['password'] = Hash::make($data['password']);
        }
        $user->update($data_arry);

        return redirect()->route('admin.profile')->withSuccess(__('admin.profileUpdated'));
    }

    /**
     * Show 2fa form to user.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function twofactorauth()
    {
        $user = Auth::User();
        $google2fa = new Google2FA();
        $secret = Session::get('twofa_key');
        $qr_image = $google2fa->getQRCodeInline(
            config('app.name'),
            Auth::user()->email,
            $secret
        );

        return view('users.profile.mfa', compact('qr_image', 'secret', 'user'));
    }


    public function twofactorUpdate(Request $request)
    {
        $user = Auth::user();
        $twofa_key = Session::get('twofa_key');
        $code = $request->input('code');
        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($twofa_key, $code);

        if ($valid) {
            $user->google2fa_secret = $twofa_key;
            $user->save();

            return redirect()->back()->with('success', __('profile.twofaEnabledSuccessfully'));
        }

        return redirect()->back()->with('error', __('profile.invalidCode'));
    }

    public function twofactorDisable(Request $request)
    {
        $user = Auth::user();
        $user->google2fa_secret = null;
        $user->save();

        return redirect()->back()->with('success', __('profile.twofaDisabledSuccessfully'));
    }

    /**
     * @param  $key
     * @return mixed
     */
    private function getInlineUrl($key)
    {
        $user = Auth::user();
        return Google2FA::getQRCodeInline(
            __('admin.twoFAApp', ['name' => $user->name]),
            $user->email,
            $key
        );
    }

    public function authenticate(Request $request)
    {
        return redirect()->route('admin.dashboard');
    }
}
