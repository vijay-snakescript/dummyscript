<?php

namespace App\Http\Controllers;

use Session;
use Setting;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Property;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\SendGoodbyeEmail;
use Butschster\Head\Facades\Meta;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FAQRCode\Google2FA;
use App\Http\Requests\DeleteUserAccount;


class UserController extends Controller
{
    protected $idMultiKey = '622723'; //int
    protected $seperationKey = '****';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        if (!Session::get('twofa_key')) {
            $google2fa = new Google2FA();
            Session::put('twofa_key', $google2fa->generateSecretKey());
        }
    }

    public function profile()
    {
        $user = Auth::user();

        $meta = __("static_pages.profile");
        Meta::setMeta((object) $meta);


        return view('user.profile', compact('user'));
    }

    public function plan()
    {
        $user = Auth::user();
        $subscription  = $user->subscription;
        $plan = $user->subscription->plan ?? null;
        $plan?->load('translations', 'properties');

        $properties = Property::active()->with('translations')->get();

        $meta = __("static_pages.plan");
        Meta::setMeta((object) $meta);

        return view('user.plan', compact('user', 'plan', 'properties', 'subscription'));
    }

    public function cancleSubscription()
    {
        $user = Auth::user();
        $subscription  = $user->activeSubscriptions()->where('plan_id', '!=', '0')->first();
        $subscription->status = 2;
        $subscription->save();

        return redirect()->back()->with('success', __('tools.subscriptionCanceled'));;
    }

    public function delete()
    {
        $user = Auth::user();

        $meta = __("static_pages.deleteAccount");
        Meta::setMeta((object) $meta);

        return view('user.delete-account', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\DeleteUserAccount $request
     * @param int                                  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteUserAccount $request)
    {
        $user = Auth::user();

        // Create and encrypt user account restore token
        $sepKey = $this->getSeperationKey();
        $userIdKey = $this->getIdMultiKey();
        $restoreKey = Setting::get('user_restore_key', 'sup3rS3cr3tR35t0r3K3y21');
        $encrypter = Setting::get('restore_user_enc_type', 'AES-256-ECB');
        $level1 = $user->id * $userIdKey;
        $level2 = urlencode(Str::random(4) . $sepKey . $level1);
        $level3 = base64_encode($level2);
        $level4 = openssl_encrypt($level3, $encrypter, $restoreKey);
        $level5 = base64_encode($level4);

        // Save Restore Token and Ip Address
        $user->token = $level5;
        $user->save();

        // Send Goodbye email notification
        // $this->sendGoodbyEmail($user, $user->token);

        // Soft Delete User
        $user->delete();

        // Clear out the session
        Auth::logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect()->route('login')->with('success', trans('profile.successUserAccountDeleted'));
    }

    public function userReActivate(Request $request, $token)
    {
        $sepKey = $this->getSeperationKey();
        $userIdKey = $this->getIdMultiKey();
        $restoreKey = Setting::get('user_restore_key', 'sup3rS3cr3tR35t0r3K3y21');
        $encrypter = Setting::get('restore_user_enc_type', 'AES-256-ECB');
        $level5 = base64_decode($token);
        $level4 = openssl_decrypt($level5, $encrypter, $restoreKey);
        $level3 = base64_decode($level4);
        $level2 = urldecode($level3);
        $level1[] = explode($sepKey, $level2);
        $userId = null;
        if (isset($level1[0][1])) {
            $userId = $level1[0][1] / $userIdKey;
        }
        $user = User::onlyTrashed()->where('id', $userId)->first();

        if (!$user instanceof User) {
            return redirect()->route('front.index')->withError(__('profile.errorRestoreUserTime'));
        }

        $deletedDate = $user->deleted_at;
        $currentDate = Carbon::now();
        $daysDeleted = $currentDate->diffInDays($deletedDate);
        $cutoffDays = Setting::get('restore_user_cutoff', 30);

        if ($daysDeleted >= $cutoffDays) {
            return redirect()->route('login')->withError(__('profile.errorRestoreUserTime'));
        }
        $user->token = null;
        $user->save();
        $user->restore();

        return redirect()->route('login')->with('success', trans('profile.successUserRestore', ['username' => $user->name]));
    }

    /**
     * Send GoodBye Email Function via Notify.
     *
     * @param User  $user
     * @param string $token
     *
     * @return void
     */
    public static function sendGoodbyEmail(User $user, $token)
    {
        $user->notify(new SendGoodbyeEmail($token));
    }

    public function twofactorauth()
    {
        $google2fa = new Google2FA();
        $secret = Session::get('twofa_key');
        $qr_image = $google2fa->getQRCodeInline(
            config('app.name'),
            Auth::user()->email,
            $secret
        );

        $meta = __("static_pages.2fa");
        Meta::setMeta((object) $meta);

        return view('user.twofactor', compact('qr_image', 'secret'));
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

    public function authenticate(Request $request)
    {
        return redirect()->route('front.index');
    }

    public function password()
    {
        $meta = __("static_pages.password");
        Meta::setMeta((object) $meta);
        return view('user.password');
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'min:6|required|same:password_confirmation',
            'password_confirmation' => 'required'
        ]);

        $user = Auth::user();
        $user->update([
            'password' => bcrypt($request->new_password)
        ]);

        return redirect()->back()->withSuccess(__('profile.profileUpdatedMsg'));
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => "required",
            'about' => 'nullable|max:500',
            'username' => 'required',
            'image' => 'nullable'
        ]);

        $data = [
            'name' => $request->name,
            'about' => $request->name,
            'username' => $request->username,
        ];

        $user = Auth::user();
        if ($request->file("image")) {
            $user->clearMediaCollection("avatar");
            $user->addMediaFromRequest("image")->toMediaCollection('avatar');
        }
        $user->update($data);

        return redirect()->back()->withSuccess(__('profile.profileUpdatedMsg'));
    }

    /**
     * Get User Restore ID Multiplication Key.
     *
     * @return string
     */
    public function getIdMultiKey()
    {
        return $this->idMultiKey;
    }

    /**
     * Get User Restore Seperation Key.
     *
     * @return string
     */
    public function getSeperationKey()
    {
        return $this->seperationKey;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->withResponsiveImages();
    }
}
