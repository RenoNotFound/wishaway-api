<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Traits\ApiResponser;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponser;

    public function register(Request $request): JsonResponse
    {
        try {
            $attr = $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6'
            ]);

            $user = User::create([
                'name' => $attr['name'],
                'password' => bcrypt($attr['password']),
                'email' => $attr['email']
            ]);

            return $this->success([
                'token' => $user->createToken('API Token')->plainTextToken
            ]);

        } catch (ValidationException $e) {
            return $this->error(400, $e->getMessage());
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return $this->error(409, "Email already used");
            }
            return $this->error(500, "Could not register. Server Error.");
        } catch (\Exception $e) {
            return $this->error(500, 'sup');
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $attr = $request->validate([
                'email' => 'required|string|email|',
                'password' => 'required|string|min:6'
            ]);

            if (!Auth::attempt($attr)) {
                return $this->error(401, 'Credentials not match');
            }

            return $this->success([
                'token' => auth()->user()->createToken('API Token')->plainTextToken
            ]);
        } catch (ValidationException $e) {
            return $this->error(400, $e->getMessage());
        } catch (QueryException $e) {
            return $this->error(500, "Database error");
        } catch (\Exception $e) {
            return $this->error(500, $e->getMessage());
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }
}
