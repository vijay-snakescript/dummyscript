<?php

namespace App\Http\Controllers\Auth;

use Setting;
use App\Models\Role;
use App\Models\User;
use App\Models\Social;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthnticationController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'front.index';

    public function __construct()
    {
        Session::put('redirectTo', URL::previous());
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectTo()
    {
        return Session::get('redirectTo') ? Session::get('redirectTo') : $this->redirectTo;
    }

    /**
     * Gets the social redirect.
     *
     * @param string $provider The provider
     *
     * @return Redirect
     */
    public function getSocialRedirect($provider)
    {
        $providerKey = Config::get('services.' . $provider);

        if (empty($providerKey['client_id'])) {
            \App::abort(404, __('socials.noProvider'));
        }

        $socialite = Socialite::driver($provider);

        if ($provider === 'facebook') {
            $fields = ['name', 'email'];
            $scopes = ['email'];
            $gender = (bool) Setting::get('gender_permission', 1);
            $agegroup = (bool) Setting::get('agegroup_permission', 1);
            if ($gender) {
                $fields[] = 'gender';
                $scopes[] = 'user_gender';
            }

            if ($agegroup) {
                $fields[] = 'age_range';
                $scopes[] = 'user_age_range';
            }

            $socialite->fields($fields)->scopes($scopes);
        }

        return $socialite->redirect();
    }

    /**
     * Gets the social handle.
     *
     * @param string $provider The provider
     *
     * @return Redirect
     */
    public function getSocialHandle($provider, Request $request)
    {
        if ($request->get('denied') != '') {
            return redirect()->to('login')
                ->withErrors(__('socials.denied'));
        }

        $socialUserObject = false;
        try {
            $socialite = Socialite::driver($provider);
            if ($provider === 'facebook') {
                $fields = ['name', 'email'];
                $gender = (bool) Setting::get('gender_permission', 1);
                $agegroup = (bool) Setting::get('agegroup_permission', 1);
                if ($gender) {
                    $fields[] = 'gender';
                }

                if ($agegroup) {
                    $fields[] = 'age_range';
                }

                $socialite->fields($fields);
            }

            //check nostate for stateless request
            if ($request->get('noState')) {
                $socialite->stateless();
            }

            //check if request is via token or code
            if ($token = $request->get('access_token')) {
                $socialUserObject = $socialite->userFromToken($token);
            } else {
                $socialUserObject = $socialite->user();
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($request->wantsJson()) {
                return response()->json(['action' => __('socials.errorOccurred'), 'message' => $e->getMessage()]);
            }

            return redirect()->to('login')
                ->withErrors(__('socials.errorOccurred'));
        }

        if ($socialUserObject) {
            $socialUser = null;
            $message = false;
            $email = $socialUserObject->email ?? false;
            if (!$email) {
                $email = 'missing_' . $socialUserObject->id . '@example.org';
            }
            // Check if email is already registered
            $user = User::where('email', '=', $email)->first();

            if (empty($user)) {
                $fullname = explode(' ', $socialUserObject->name);
                if (count($fullname) == 1) {
                    $fullname[1] = '';
                }

                $username = $socialUserObject->nickname;
                if ($username == null) {
                    foreach ($fullname as $name) {
                        $username .= $name;
                    }
                }

                // Check to make sure username does not already exist in DB before recording
                $username = $this->manageUserRepository->checkUserName($username, $email);
                $gender = isset($socialUserObject->user['gender']) ? ucfirst($socialUserObject->user['gender']) : '';
                // $age_range = isset($socialUserObject->user['age_range']['min'])? $socialUserObject->user['age_range']['min'] : '';
                $user = User::create(
                    [
                        'name'                 => $socialUserObject->name,
                        'username'             => $username,
                        'email'                => $email,
                        'password'             => Hash::make(Str::random(40)),
                        'token'                => $token,
                        'status'               => '1',
                        'activated'            => true,
                    ]
                );

                $user->activated = true;
                $user->email_verified_at = $user->freshTimestamp();
                $user->save();

                $default_role = Setting::get('public_user_role', false);
                if ($default_role) {
                    $role = Role::find($default_role);
                    $user->assignRole($role);
                }

                $message = __('socials.registerSuccess');
            }

            $sameSocialId = Social::where('social_id', '=', $socialUserObject->id)
                ->where('provider', '=', $provider)
                ->first();

            if (empty($sameSocialId)) {
                $sameSocialId = new Social();
            }

            $sameSocialId->social_id = $socialUserObject->id;
            $sameSocialId->provider = $provider;
            $user->social()->save($sameSocialId);

            // Twitter User Object details: https://developer.twitter.com/en/docs/tweets/data-dictionary/overview/user-object
            if ($sameSocialId->provider == 'twitter') {
                $user->twitter = $socialUserObject->nickname;
            }
            $user->save();

            auth()->login($user, true);

            if ($request->wantsJson()) {
                return response()->json(['user' => $user]);
            } else {
                if ($message) {
                    return redirect()->to($this->redirectTo())->with('success', $message);
                }

                return redirect()->to($this->redirectTo());
            }
        } else {
            if ($request->wantsJson()) {
                return response()->json(['action' => __('socials.errorOccurred')]);
            }

            return redirect()->to('login')
                ->withErrors(__('socials.errorOccurred'));
        }
    }
}
