<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function findOrCreate($user)
    {
        $authUser = User::where('email',$user->email)->first();
        if ($authUser)
        {
            return $authUser;
        }
        return User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            'password'    => bcrypt(rand()),
            'facebook_id' => $user->id
        ]);
            // $user = new User;
            // $user->name = $userSocial->name;
            // $user->email = $userSocial->email;
            // $user->password = bcrypt(rand());
            // $user->save();
        
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }
    public function handleFacebookCallback()
    {
        $userSocial = Socialite::driver('facebook')->user();
        $authUser = $this->findOrCreate($userSocial);
    
        Auth::login($authUser);
        return "user saved";
    }
}
