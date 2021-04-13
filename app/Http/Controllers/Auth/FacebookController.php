<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    use ApiResponser;

    /**
     * Get google login url
     *
     * @return JsonResponse
     */
    public function facebookLoginUrl(): JsonResponse
    {
        return response()->json([
            'url' => Socialite::driver('facebook')->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function loginCallback(): JsonResponse
    {
        $facebookUser = Socialite::driver('facebook')->stateless()->user();
        $user = null;

        DB::transaction(function () use ($facebookUser, &$user) {
            $socialAccount = SocialAccount::query()->firstOrNew(
                ['social_id' => $facebookUser->getId(), 'social_provider' => 'facebook'],
                ['social_name' => $facebookUser->getName()]
            );

            if (!($user = $socialAccount->user)) {
                $user = User::query()->create([
                    'email' => $facebookUser->getEmail(),
                    'name' => $facebookUser->getName(),
                ]);
                $socialAccount->fill(['user_id' => $user->id])->save();
            }
        });

        if (is_null($user)) return $this->error(500, "Database error");

        return $this->success([
            'user' => $user,
            'google_user' => $facebookUser,
            'token' => 'Bearer ' . $user->createToken('facebook user')->plainTextToken
        ]);
    }

}
