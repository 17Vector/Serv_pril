<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\UserAndRole;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthСontroller extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $registDTO = $request->getDTO();

        $user = new User([
            'username' => $registDTO->username,
            'email' => $registDTO->email,
            'password' => $registDTO->password,
            'birthday' => $registDTO->birthday,
        ]);

        $user->save();

        $role_id = Role::where('name', 'User')->first()->id;

        $userAndRole = new UserAndRole([
            'user_id' => $user -> id,
            'role_id' => $role_id,
        ]);
        $userAndRole -> save();

        return response()->json(['Экземпляр ресурса созданного пользователя' => $user], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $authDTO = $request->getDTO();

        $data = [
            'username' => $authDTO->username,
            'password' => $authDTO->password
        ];

        if (Auth::attempt($data)) {

            $user = Auth::user();
            $user->tokens()->where('expires_at', '<', now())->delete();

            $activeTokenQuant = $user->tokens->count();
            $maxActiveTokens = env('MAX_USER_ACTIVE_TOKEN', 5);
            $token_Lifetime = env('TOKEN_LIFETIME', 3);

            if ($activeTokenQuant < $maxActiveTokens) {
                $token = $user->createToken($authDTO->username . '_token', ['*'], 
                                                now()->addMinutes($token_Lifetime))->plainTextToken;
                return response()->json(['Токен пользователя' => $token], 200);
            }

            return response()->json(['error' => 'Достигнуто максимальное количество активных токенов']);
        } 
            return response()->json(['error' => 'Ошибка аутентификации. Пожалуйста, проверьте свои учетные данные.'], 401);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json(['Информация о пользователе' => $user]);
    }

    public function out(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();
        return response()->json(['Отозван токен пользователя']);
    }

    public function tokens(): JsonResponse
    {
        return response()->json([Auth::user()->tokens->pluck('token')]);
    }

    public function out_all(): JsonResponse
    {
        Auth::user()->tokens->each(function($token) {
            $token->delete();
        });
        return response()->json(['Отозваны действующие токены пользователя'], 200);
    }
}