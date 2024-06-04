<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\UserAndRole;
use App\Models\TwoFactorModel;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

        $user = User::where('username', $authDTO->username)->first();

        $lastCode = $user->twoFactorCodes()->orderBy('created_at', 'desc')->first();
        if ($lastCode && $lastCode->created_at->diffInSeconds(now()) < 30) {
            return response()->json(['message' => 'Вы отправли слишком много запросов. Повторите попытку позже'], 429);
        }

        if (Auth::attempt($data)) {

            $this->send2FACode($user);
            return response()->json(['message' => 'Код двухфакторной авторизации отправлен на ваш email.']);
        }
            return response()->json(['error' => 'Ошибка аутентификации. Пожалуйста, проверьте свои учетные данные.'], 401);
    }

    public function tfCodeConfirm(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required',
            'code' => 'required|numeric',
        ]);

        $user = User::where('username', $request->username)->firstOrFail();
        $code = $user->twoFactorCodes()->where('code', $request->code)->first();

        if ($code && !$code->isExpired())
        {
            $code->delete();

            $user->tokens()->where('expires_at', '<', now())->delete();

            $activeTokenQuant = $user->tokens->count();
            $maxActiveTokens = env('MAX_USER_ACTIVE_TOKEN', 5);
            $token_Lifetime = env('TOKEN_LIFETIME', 3);

            if ($activeTokenQuant < $maxActiveTokens) {
                $token = $user->createToken('token')->plainTextToken;

                return response()->json(['Токен пользователя' => $token], 200);
            }
                return response()->json(['error' => 'Достигнуто максимальное количество активных токенов']);
        }
        return response()->json(['error' => 'Неверный или истекший код.'], 422);
    }

    public function resendCode(Request $request)
    {
        $request->validate([
            'username' => 'required',
        ]);

        $user = User::where('username', $request->username)->firstOrFail();

        $lastCode = $user->twoFactorCodes()->orderBy('created_at', 'desc')->first();
        if ($lastCode && $lastCode->created_at->diffInSeconds(now()) < 30) {
            return response()->json(['message' => 'Вы запрашиваете код слишком часто. Попробуйте позже.'], 429);
        }

        return $this->send2FACode($user);
    }

    private function send2FACode($user)
    {
        $user->twoFactorCodes()->delete();

        $code = TwoFactorModel::generateCode();
        $expiresAt = TwoFactorModel::generateExpiration();

        $user->twoFactorCodes()->create([
            'code' => $code,
            'expires_at' => $expiresAt,
        ]);

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.yandex.ru';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'vector.niz@yandex.ru';
            $mail->Password   = 'plavanie.live25';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('vector.niz@yandex.ru');
            $mail->addAddress($user->email);

            $mail->isHTML(false);
            $mail->Subject = 'Authentication Code';
            $mail->Body = $code;

            $mail->send();
        } catch (Exception $e) {
            return response()->json(['error' => 'Произошла ошибка при отправке сообщения!'], 500);
        }
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