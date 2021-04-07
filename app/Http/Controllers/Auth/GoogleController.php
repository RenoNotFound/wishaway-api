<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use App\Traits\ApiResponser;

class GoogleController extends Controller
{
    use ApiResponser;

    /**
     * Get google login url
     *
     * @return JsonResponse
     */
    public function googleLoginUrl(): JsonResponse
    {
        return response()->json([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function loginCallback(): JsonResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = null;

        DB::transaction(function () use ($googleUser, &$user) {
            $socialAccount = SocialAccount::query()->firstOrNew(
                ['social_id' => $googleUser->getId(), 'social_provider' => 'google'],
                ['social_name' => $googleUser->getName()]
            );

            if (!($user = $socialAccount->user)) {
                $user = User::query()->create([
                    'email' => $googleUser->getEmail(),
                    'name' => $googleUser->getName(),
                ]);
                $socialAccount->fill(['user_id' => $user->id])->save();
            }
        });

        if (is_null($user)) return $this->error(500, "Database error");

        return $this->success([
            'user' => $user,
            'google_user' => $googleUser,
            'token' => 'Bearer ' . $user->createToken('google user')->plainTextToken
        ]);
    }
}
