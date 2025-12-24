<?php
namespace App\Http\Controllers\Api;


use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validateData =$request->validate([
            'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                 'phoneno' => 'required|numeric|digits:10',
                'email' => 'required|string|email|max:255|unique:users,email',
                    'password' => 'required|string|min:8|confirmed',
                
        ]);
     
     //  $data = $validateData->validated();
        
$user = User::create([
        'firstname' => $validateData['firstname'],
        'lastname' => $validateData['lastname'],
        'phoneno' => $validateData['phoneno'],
        'gender' => $request['gender'],
        'email' => $validateData['email'],
        'password' => bcrypt($validateData['password']),
    ]);

$defaultRole = Role::where('name', 'customer')->first();
    
  if ($defaultRole) {
        $user->roles()->attach($defaultRole->id);
    }
       // $role = Role::where('name', 'customer')->first();
   // $user->roles()->attach($role->id);
    //    $user->save();
        
        $token=auth('api')->login($user);
        return $this->respondWithToken($token);
    }
    
   public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (!$token = auth('api')->attempt($credentials)) {
        return response()->json(['message' => 'Invalid Credentials'], 401);
    }

    $user = auth('api')->user();

    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth('api')->factory()->getTTL() * 60,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name'), // returns array of role names
        ]
    ]);
}


    public function me()
    {
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
    