<?php

namespace App\Http\Controllers\Auth;
use Auth;
use Socialite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    // protected $redirectTo = '/dashboard';
    protected function authenticated(Request $request) {
        $type = \Auth::user()->acc_type;
        if ($type == '1') {
            return redirect('personal/dashboard');
        } 
        if($type == '2') {
            return redirect('dashboard');
        } 
        if($type == null || $type == 0) {
            return redirect('login');
        }
    }

    /**
     * Include status as credential.
     *
     */
    protected function credentials(\Illuminate\Http\Request $request) 
    {
        return ['email' => $request->{$this->username()}, 'password' => $request->password, 'status' => 1];
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Auth logout user.
     *
     */
    public function logout() 
    {
        Auth::logout();
        return redirect('login'); 
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }
        // only allow people with @company.com to login
        if(explode("@", $user->email)[1] !== env('GOOGLE_ALLOWEDDOMAIN')){
            return redirect()->to('/');
        }
        // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();
        if($existingUser){
            // log them in
            auth()->login($existingUser, true);
        } else {
            // create a new user
            $newUser                  = new User;
            $newUser->name            = $user->name;
            $newUser->email           = $user->email;
            $newUser->role_id         = 5;
            $newUser->acc_type        = 1;
            $newUser->status          = 1;
            $newUser->save();
            auth()->login($newUser, true);
        }
        return redirect()->to('/dashboard');
    }

}
