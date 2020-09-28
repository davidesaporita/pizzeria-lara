<?php

namespace App\Http\Controllers\Auth;

use Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    public function redirectTo()
    {
        switch(Auth::user()->role_id) {
            case 1:  
                $this->redirectTo = '/merchant'; 
                return $this->redirectTo; 
                break;

            case 2:  
                $this->redirectTo = '/customer';   
                return $this->redirectTo; 
                break;

            default: 
                return $this->redirectTo;
        }
    }

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
        // Se è già presente un merchant non è possibile creare un utente con lo stesso role_id
        $merchant_rule = User::where('role_id', 1)->count() > 0 ? 'min:2' : 'min:1';

        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'role_id' => ['required', 'integer', 'max:2', $merchant_rule],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {       
        return User::create([
            'first_name' => ucwords(strtolower($data['first_name'])),
            'last_name' => ucwords(strtolower($data['last_name'])),
            'role_id' => $data['role_id'],
            'email' => strtolower($data['email']),
            'password' => Hash::make($data['password']),
        ]);
    }
}
