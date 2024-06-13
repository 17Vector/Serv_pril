<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\LogRequest;
use App\Models\ChangeLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use \Barryvdh\DomPDF\Facade\Pdf;

class GenerateAndSendReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-and-send-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a report to admins';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Подготовка отчёта, начало сбора данных...');
        
        $startTime = Carbon::now();
        $maxTimeCreating = env('MAXIMUM_TIME_TO_CREATE_A_REPORT', 30);

        $collectionInterval = env('REPORT_COLLECTION_INTERVAL', 4);
        $reportStartTime = Carbon::now()->subHours($collectionInterval);

        $this -> checkGenerationTime($startTime, $maxTimeCreating);

        $method = $this->getRating(LogRequest::class, 'controller_action', $reportStartTime);

        $this -> checkGenerationTime($startTime, $maxTimeCreating);

        $entity = $this->getRating(ChangeLog::class, 'table', $reportStartTime);

        $this -> checkGenerationTime($startTime, $maxTimeCreating);

        $userRequest = $this->getRating(LogRequest::class, 'user_id', $reportStartTime);

        $this -> checkGenerationTime($startTime, $maxTimeCreating);

        $userChanges = $this->getRating(ChangeLog::class, 'user_id', $reportStartTime);

        Log::info($userChanges);
        
        $userPermissions = User::select('users.id', 'users.username', DB::raw('count(*) as total_permissions'))
            ->join('user_and_roles', 'users.id', '=', 'user_and_roles.user_id')
            ->join('roles', 'user_and_roles.role_id', '=', 'roles.id')
            ->join('role_and_permissions', 'roles.id', '=', 'role_and_permissions.role_id')
            ->groupBy('users.id', 'users.username')
            ->orderByDesc('total_permissions')
            ->get();

        $this -> checkGenerationTime($startTime, $maxTimeCreating);

        $userAuth = LogRequest::select('user_id', DB::raw('count(*) as total'))
            ->where('created_at', '>=', $reportStartTime)
            ->where('controller_action', 'login')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->get();

        $this -> checkGenerationTime($startTime, $maxTimeCreating);
        
        Log::info('Завершение сбора данных. Начало формирование файла...');

        $reportData = [
            'method' => $method,
            'entity' => $entity,
            'userRequest' => $userRequest,
            'userAuth' => $userAuth,
            'userPermissions' => $userPermissions,
            'userChanges' => $userChanges,
            'reportType' => 'Analytical',
            'reportStartTime' => $reportStartTime,
            'reportGeneratedAt' => Carbon::now(),
        ];

        $pdf = Pdf::loadView('report', $reportData);
        $pdfPath = 'reports/report_' . Carbon::now()->format('Y_m_d_H_i_s') . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        $this->sendReports(Storage::path($pdfPath));
        Storage::delete($pdfPath);

        Log::info('Отчёт сформирован и отправлен на администраторам по email');
    }

    private function sendReports($pdfPath)
    {
            
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

            $adminEmails = User::select('users.email')
                ->join('user_and_roles', 'users.id', '=', 'user_and_roles.user_id')
                ->where('user_and_roles.role_id', 1)
                ->pluck('email');

            foreach ($adminEmails as $email) {
                $user = User::where('email', $email)->first();
                if ($user) {
                    $mail->addAddress($email);

                    $mail->isHTML(false);
                    $mail->Subject = 'Report';
                    $mail->Body = 'Hi, ' . $user->username . '! The report is attached to this message. Have a nice day!';
                    $mail->addAttachment($pdfPath);
                    $mail->send();
                    $mail->clearAddresses();
                }
            }
        } catch (Exception $e) {
            Log::info('Произошла ошибка при отправке сообщения');
        }
    }

    private function getRating($model, $field, $reportStartTime) {

        return $model::select($field, DB::raw('count(*) as total'))
            ->where('created_at', '>=', $reportStartTime)
            ->groupBy($field)
            ->orderByDesc('total')
            ->get();
    }

    private function checkGenerationTime($startTime, $maxTimeCreating) {

        if(Carbon::now()->diffInMinutes($startTime) > $maxTimeCreating) {
            Log::warning('Время для обработки отчёта истекло.');
            return true;
        }
        return false;
    }
}
