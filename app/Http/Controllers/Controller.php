<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	/**
     * Return response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($resp)
    {
		if (isset($resp->message)) {
            return response()->json([
    			'message' => $resp->message,
    		], $resp->code);
		} elseif (isset($resp->data)) {
            return response()->json($resp->data, $resp->code);
        } else {
            return response()->json([
    			'status' => 'ok',
    		], $resp->code);

        }
    }
}
