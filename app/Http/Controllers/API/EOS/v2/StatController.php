<?php

namespace App\Http\Controllers\API\EOS\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\EOS\v2\StatRepository;
use App\Http\Requests\API\EOS\v2\StatRequest;

class StatController extends Controller
{
    public function index(StatRequest $request)
    {
        $resp = StatRepository::index($request);

        return $this->sendResponse($resp);
    }
}
