<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GitHookController extends Controller
{
    private $isAction = false;

    public function gitAction(Request $request)
    {
        $secretKey = $request -> input('secret_key');

        if ($secretKey !== env('SECRET_KEY')) {
            return response()->json(['message' => 'Неверный ключ. Убедитесь в правильности ключа и повторите попытку.'], 403);
        }

        if ($this -> isAction)
            return response()->json(['message' => 'Идёт выполнение операции. Повторите попытку позже.'], 429);

        $this -> isAction = true;

        $this -> createLog($request);

        try {
            $this -> changeCode();
            return response()->json(['message' => 'Изменение кода прошло успешно.'], 200);
        }
        
        catch (\Exception $e) {
            return response()->json(['message' => 'Произошла ошибка при изменении кода.'], 500);
        }
        
        $this -> isAction = false;
    }

    private function createLog(Request $request) {
        $newLog = [
            'date' => now(),
            'ip' => ip(),
        ];
        Log::info('Изменение кода...', $newLog);
    }

    private function changeCode() {
        $gitCommands = [
            'git checkout main',
            'git fetch --all',
            'git reset --hard origin/main',
            'git pull origin main',
        ];

        $descripList = [
            'Переключение на главную ветку',
            'Получение сведений о текущих изменениях',
            'Отмена всех изменений',
            'Обновление проекта с Git до последней версии'
        ];

        foreach ($gitCommands as $key => $stage) {
            Log::info("Идёт выполнение команды: $stage", ['description' => $descripList[$key]]);
            $result = shell_exec($command);
            Log::info($result);
        }
    }
}
