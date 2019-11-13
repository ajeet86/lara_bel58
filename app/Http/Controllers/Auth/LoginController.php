<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Socialite;
class LoginController extends Controller {
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
    protected $redirectTo = '/edit_profile';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the login form.
     * 
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm() {
        session(['link' => url()->previous()]);
        return view('auth.login', [
            'title' => 'User Login',
            'loginRoute' => 'login',
            'forgotPasswordRoute' => 'password.request',
        ]);
    }

    protected function authenticated(Request $request, $user) {
        //return redirect(session('link'));
        return redirect('edit_profile');
    }

    public function logout() {
        Auth::logout();
        return redirect('/')->with('status', 'User has been logged out!');
    }
	
	
	 /**

    * Handle Social login request

    *

    * @return response

    */

   public function socialLogin($social)

   {

       return Socialite::driver($social)->redirect();

   }

   /**

    * Obtain the user information from Social Logged in.

    * @param $social

    * @return Response

    */

   public function handleProviderCallback($social)

   {
	   

       $userSocial = Socialite::driver($social)->user();
	 
	    // echo '<pre>';
// print_r($userSocial->getEmail());
// echo '</pre>';die;
       $user = User::where(['email' => $userSocial->getEmail()])->first();

       if($user){

           Auth::login($user);

           return redirect()->action('HomeController@index');

       }else{

	   return view('/welcome');
          // return view('auth.register',['name' => $userSocial->getName(), 'email' => $userSocial->getEmail()]);

       }

   }

}
