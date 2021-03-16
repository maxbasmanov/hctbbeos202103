<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Log;

class LogAfterRequest
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
		$log = new Log;
		$log->host = $request->getHttpHost();
        $log->group_id = $request->group_id ?? 0;
        $log->client_id = $request->header('PHP_AUTH_USER') ?? $request->client_id;
		$log->client_id = $request->header('PHP_AUTH_USER') ?? $request->client_id;
		$log->method = $request->isMethod('post') ? 1 : 0;
		$log->status = $response->status();
		$log->url = $request->getPathInfo();
		$log->ip = $request->ip();
		$log->request = base64_encode(gzcompress(json_encode($request->all()), 9));
		$log->response = base64_encode(gzcompress(json_encode($response->original), 9));
		$log->created_at = Carbon::now();
		$log->save();
    }
}
