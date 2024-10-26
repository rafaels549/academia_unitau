<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\FileService;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function login(Request $request)
    {
    
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
      
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function getUsers() {
        $authenticatedUserId = auth()->id();
        $users = User::where('id', '!=', $authenticatedUserId)->get(); 
        return response()->json(['users' => UserResource::collection($users)]);
    }

    public function addUser(Request $request) {



        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'ra' => ['required',  'min:8', 'max:8', 'unique:'.User::class],
            'role' => ['required', 'in:admin,user'],
            'curso' => ['nullable', 'string', 'max:255'],
            'periodo' => ['nullable', 'integer'],

        ]);



            User::create([
            'name' => $request->name,
            'email' => $request->email,
            'ra' => $request->ra,
            'curso' => $request->curso,
            'periodo' => $request->periodo,
            'password' => bcrypt("password"),
            'is_admin' => $request->role === 'admin' ? 1 : 0,
            'is_blocked' => 0
        ]);



        return response()->noContent();

    }
  /**
     * Store a newly created resource in storage.
     */
    public function updateUserImage(Request $request)
    {
        $request->validate(['image' => 'required|mimes:png,jpg,jpeg']);

        if($request->height == '' || $request->width == '' || $request->top == '' || $request->left == '') {
            return response()->json(['error' => 'The dimensions are incomplete'], 400);
        }

        try {
        $user = (new FileService)->updateImage(auth()->user(), $request);
        $user->save();
        return response()->json(['success' => 'OK'], 200);
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function makeUser($id) {

        $user = User::findOrFail($id);
        $user->update([
            'is_admin' => 0
         ]);

    }

    public function makeAdmin($id) {
        $user = User::findOrFail($id);
   $user->update([
      'is_admin' => 1
   ]);
}

   public function block($id) {

    $user = User::findOrFail($id);
    $user->update([
        'is_blocked' => 1
     ]);

}

public function unblock($id) {
    $user = User::findOrFail($id);
$user->update([
  'is_blocked' => 0
]);



    }



}
