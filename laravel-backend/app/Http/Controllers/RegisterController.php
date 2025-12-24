<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
class RegisterController extends Controller
{
        public function showForm()
        {
            return view('register');
        }

    public function register(Request $request)
    {
        //registerform validation
        $validatedData = $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                 'phoneno' => 'required|numeric|digits:10',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required',
            ]);
       
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phoneno' => $request->phoneno,
            'gender' => $request->gender,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //  Attach default role (customer)
    $role = Role::where('name', 'customer')->first();
    $user->roles()->attach($role->id);

     // Log the user in
          Auth::login($user);

          //redirect after login
      // return redirect()->route('welcome')->with('success', 'Registration successful!');
       return redirect()->route('customer.dashboard');
    }

     public function showLoginForm()
    {
        return view('login');
    }

    //login function
    public function login(Request $request)
    {
        $logindata = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
            
          if (Auth::attempt($logindata)) {
            $request->session()->regenerate();
               $user = Auth::user();
               
                $role = $user->roles()->first()->name ?? null;
                
                if ($role === 'admin') {
                    
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'vendor') {
            return redirect()->route('vendor.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
          
        }
        
        //message display when invalid user login data
        return back()->withErrors([
            'email' => 'Invalid login user.',
        ])->withInput()->with('error', 'Login failed!');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();

        return redirect('login'); // Redirect to login or home
    }
}




