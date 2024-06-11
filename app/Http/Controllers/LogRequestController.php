<?php

namespace App\Http\Controllers;

use App\DTO\LogRequestCollectionDTO;
use App\DTO\LogRequestDTO;
use App\Models\LogRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LogRequestController extends Controller
{
    public function getLogList(Request $request) {

        $this -> checkExpiration();

        $logs = LogRequest::query();

        

        if ($request->has('filter')) {
            
            $filters = $request->filter;

            foreach ($filters as $filter) {
                $logs->where($filter['key'], $filter['value']);
            }
        }

        if ($request->has('sortBy')) {

            $sorts = $request->sortBy;

            foreach ($sorts as $sort) {
                $logs->orderBy($sort['key'], $sort['order']);
            }
        }

        $pageQuant = $request -> input('pageQuant', 10);
        $logs = $logs -> paginate($pageQuant);

        $logs->getCollection()->transform(function ($log) {
            return [
                'url' => $log->url,
                'controller' => $log->controller,
                'controller_action' => $log->controller_action,
                'answer_status' => $log->answer_status,
                'date' => $log->date,
            ];
        });
    
        return response()->json($logs, 200);
    }

    public function specificLog($id) {

        $this -> checkExpiration();

        $log = LogRequest::findOrFail($id);
        $logDTO = new LogRequestDTO($log->toArray());
    
        return response()->json(['data' => $logDTO], 200);
    }

    public function hardDeleteLog($id) {

        $this -> checkExpiration();

        $log = LogRequest::findOrFail($id);

        $log->forceDelete();

        return response()->json(['message' => 'Лог удален успешно'], 200);
    }

    private function checkExpiration()
    {
        $expired_at = Carbon::now()->subHours(73);
        LogRequest::where('created_at', '<', $expired_at)->delete();
    }
}
