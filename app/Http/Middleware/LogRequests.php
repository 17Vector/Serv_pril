<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LogRequest;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $newLog = new LogRequest();
        $newLog->url = $request->fullUrl();
        $newLog->http_method = $request->method();

        $newLog->controller = $request->route()->getActionName();
        $newLog->controller_action = $request->route()->getActionMethod();

        $newLog->request_body = json_encode($request->all());
        $newLog->request_header = json_encode($request->headers->all());

        $newLog->user_id = $request->user() ? $request->user()->id : null;
        $newLog->ip_user = $request->ip();
        $newLog->user_agent = $request->header('User-Agent');

        $newLog->answer_status = $response->getStatusCode();
        $newLog->answer_body = $response->getContent();
        $newLog->answer_header = json_encode($response->headers->all());

        $newLog->date = Carbon::now();
        $newLog->save();

        return $response;
    }
}
