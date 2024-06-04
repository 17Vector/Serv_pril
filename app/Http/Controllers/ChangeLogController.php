<?php

namespace App\Http\Controllers;

use App\DTO\ChangeLogCollectionDTO;
use App\Models\ChangeLog;
use Illuminate\Http\Request;

class ChangeLogController extends Controller
{
    public function getListLogs()
    {
        $logs = ChangeLog::all();
        return response()->json(new ChangeLogCollectionDTO($logs));
    }

    public function getUserLogs($id)
    {
        $table = 'App\Models\User';
        return getLogs($table, $id);
    }

    public function getRoleLogs($id)
    {
        $table = 'App\Models\Role';
        return getLogs($table, $id);
    }

    public function getPermissionLogs($id)
    {
        $table = 'App\Models\Permission';
        return getLogs($table, $id);
    }

    public function restoreLog($id)
    {
        $log = ChangeLog::findOrFail($id);

        $tableClass = $log->table;
        $table_id = $log->table_id;
        $old_value = $log->old_value;

        $table = $tableClass::withTrashed()->find($table_id);

        if (!$table) {
            $table = new $tableClass();
            $table->fill($old_value);
            $table->save();
        }
        else {
            if ($table->trashed()) {
                $table->restore();
            }
            $table->update($old_value);
        }

        return response()->json($table);
    }
}

function getLogs($table, $id) {
    $logs = ChangeLog::where('table', $table)->where('table_id', $id)->get();
    return response()->json(new ChangeLogCollectionDTO($logs));
}
