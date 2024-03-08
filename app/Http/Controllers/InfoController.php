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

    public function Infoclient(request $resuest)
    {
        $ip = $resuest -> ip();
        $useragent = $resuest -> header('User-Agent');
        return response() -> json(['ip' => $ip, 'useragent' => $useragent]);
    }

    public function Infodatabase()
    {
        $infoDatabase = config('databace.connections.' . config('database.default'));
        return response() -> json(['databaseinfo' => $infoDatabase]);
    }
}
