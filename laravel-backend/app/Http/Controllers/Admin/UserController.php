<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
      $search = $request->input('search');
       
         $users = User::when($search, function ($query, $search) {
        $query->where('firstname', 'like', "%{$search}%")
              ->orWhere('lastname', 'like', "%{$search}%")
              ->orWhere('phoneno', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        })->paginate(1);
$roles = Role::all();
             
        return view('admin.users.index', compact('users', 'search','roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
        $user->save();
        return redirect()->route('users.index')->with('success', 'User Created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         //registerform validation
         $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                 'phoneno' => 'required|numeric|digits:10',
                'email' => 'required|string|email|max:255',
                             
            ]);
            $user = User::findOrFail($id);
                       
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->phoneno = $request->phoneno;
            $user->gender = $request->gender;
            $user->email = $request->email;
            //  Save updates
    $user->save();

    

    // Redirect back to category list
    return redirect()->route('users.index')->with('success', 'User updated successfully!');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $user = User::findOrFail($id); 
   
         $user->delete(); //  soft delete
        return redirect()->route('users.index')
                     ->with('success', 'User moved to trash!');
    }
    // Change role (from dropdown in listing)
   public function changeRole(Request $request, $id)
{
    $user = User::findOrFail($id);

    // Replace existing role(s) with the new one
    $user->roles()->sync([$request->role_id]);

    return response()->json(['message' => 'Role updated successfully!']);
}
public function changePassword(Request $request, $id)
{
    $user = User::findOrFail($id);
    $user->password = Hash::make($request->password); // always hash
    $user->save();

    return redirect()->route('users.index')->with('success', 'Password updated successfully.');
}
}
