<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'ra' => ['required',  'min:8', 'max:8', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'curso' => ['required', 'string', 'max:255'],
            'periodo' => ['required', 'integer'],
            'documento' => [ 'file', 'mimes:pdf,jpg,jpeg,png,webp']
        ]);

        $documentoPath = $request->file('documento')->store('documentos', 'public');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'ra' => $request->ra,
            'password' => Hash::make($request->string('password')),
            'curso' => $request->curso,
            'periodo' => $request->periodo,
            'documento' => $documentoPath
        ]);

        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }
}
