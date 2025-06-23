<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use App\Models\User;
use App\Permissions\V1\Abilities;
use App\Traits\ApiResponses;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authcontroller extends Controller
{
    use ApiResponses;
    use AuthorizesRequests;

    public function login(LoginUserRequest $request)
    {
        $validated = $request->validated();

        if (! Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Invalid credentials', 401);
        }

        $user = User::where('email', $validated['email'])->firstOrFail();

        $abilities = Abilities::getAbilities($user);

        return $this->ok(
            'Authentication',
            [
                'token' => $user->createToken(
                    'API token for '.$user->email,
                    $abilities,
                    now()->addMonth()
                )->plainTextToken,
            ]
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
