<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\Api\LoginUserRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class Authcontroller extends Controller
{
    use ApiResponses;
   public function login(LoginUserRequest $request)
 {
    $validated = $request->validated();

    if (!Auth::attempt($request->only('email', 'password'))) {
        return $this->error('Invalid credentials', 401);
    }

    $user = User::firstWhere('email', $validated['email'])->first();

    return $this->ok(
      'Authentication',
      [
        'token' => $user->createToken(
          'API token for ' . $user->email,
          ['*'],
          now()->addMonth())->plainTextToken,
      ]
      );
 }

   public function logout(Request $request)
  {
      $request->user()->currentAccessToken()->delete();

      return response()->json(['message' => 'Logged out successfully']);
  }
}
