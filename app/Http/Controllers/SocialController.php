<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    //
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function Callback($provider)
    {
        Socialite::driver('facebook')->usingGraphVersion('v15.0');
        $userSocial =   Socialite::driver($provider)->stateless()->user();

        if (empty($userSocial->getEmail())) {
            $user_array = ['name' => $userSocial->getName(), 'username' => Str::slug($userSocial->getName(), '')];
            $user_array = json_encode($user_array);

            return redirect()->route('register', ['token' => base64_encode($user_array)]);
        }

        $users       =   User::where(['email' => $userSocial->getEmail()])->first();
        if ($users) {
            Auth::login($users);
            return redirect()->route('front.index');
        } else {
            $user = User::create([
                'name'          => $userSocial->getName(),
                'email'         => $userSocial->getEmail(),
                'username'      => Str::slug($userSocial->getName()),
                'provider_id'   => $userSocial->getId(),
                'provider'      => $provider,
                'password'      => bcrypt('test1223'),
                'status'        => 1,
                'email_verified_at'        => now(),
            ]);
            Auth::login($user);

            return redirect()->route('front.index');
        }
    }
}
