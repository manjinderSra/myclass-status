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
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\HasApiTokens;

class SaasAdminAuthController extends Controller
{
    /**
     * Handle an incoming registration request.
     */
    public function register(Request $request)
    {
        dd($request);
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'saasAdmin', // Set the role to saasAdmin
            ]);

            event(new Registered($user));

            Auth::login($user);
            
            // Create token for API access
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success', 
                'message' => 'Registration successful',
                'token' => $token,
                'redirect' => route('saasAdmin.dashboard')
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ]);

            // Attempt to authenticate
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The provided credentials are incorrect.'
                ], 401);
            }

            // Check if the user has the saasAdmin role
            if (Auth::user()->role !== 'saasAdmin') {
                Auth::logout();
                return response()->json([
                    'status' => 'error',
                    'message' => 'This account does not have administrator access.'
                ], 403);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                'status' => 'success', 
                'message' => 'Login successful',
                'token' => $token,
                'redirect' => route('saasAdmin.dashboard')
            ]);
            
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request)
    {
        try {
            // Revoke the token that was used to authenticate the current request
            if ($request->user()) {
                $request->user()->currentAccessToken()->delete();
            }
            
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'status' => 'success', 
                'message' => 'Logged out successfully',
                'redirect' => route('saasAdmin.login')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 