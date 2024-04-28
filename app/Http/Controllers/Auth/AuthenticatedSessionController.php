<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Traits\GetUserTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthenticatedSessionController extends Controller
{

    use GetUserTrait;
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        if ($request->authenticate()) {

            $request->session()->regenerate();

            $user = Auth::user();

            $token = $user->createToken('token')->plainTextToken;

            $cookie = cookie('jwt', $token);
            $user = $this->user();
            return response([
                'status' => 'good',
                'token' => $token,
                'user' => $user
            ])->withCookie($cookie);
        } else {
            return response([
                'status' => "bed",
                "message" => "invalide information"
            ]);
        }

    }

    public function getUser():User
    {
        return $this->user();
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        $cookie = Cookie::forget('jwt');

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response([
            'status' => 'good'
        ])->withCookie($cookie);
    }
}
