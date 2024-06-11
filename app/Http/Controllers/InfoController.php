<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function Infoserver()
    {
        $phpinfo = phpversion();
        return response() -> json(['phpinfo' => $phpinfo]);
    }

    public function Infoclient(Request $resuest)
    {
        $ip = $resuest -> ip();
        $useragent = $resuest -> header('User-Agent');
        return response() -> json(['useragent' => $useragent,'ip' => $ip]);
    }

    public function Infodatabase()
    {
        $infoDatabase = config('database.connections.' . config('database.default'));
        return response() -> json(['databaseinfo' => $infoDatabase]);
    }
}
