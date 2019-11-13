<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use DB;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/edit_profile';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:50'],
            'surname' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:60', 'unique:users'],
            'dob' => ['required', 'date'],
            'country' => ['required'],
            'phone' => ['required', 'numeric'],
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:8'],
        ]);
		
    }
    
    protected function showRegistrationForm() {
        $countries = DB::table('countries')->orderBy('country_name', 'ASC')->get();
        return view('auth.register', compact('countries'));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // echo '<pre>';
        // print_r($data);die;
        // echo '</pre>';
        return User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'dob' => Carbon::parse($data['dob']),
            'email' => $data['email'],
            'country' => $data['country'],
            'phone' => $data['phone'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
