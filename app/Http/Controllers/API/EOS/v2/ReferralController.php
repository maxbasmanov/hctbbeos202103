<?php

namespace App\Http\Controllers\API\EOS\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\EOS\v2\ReferralRepository;
use App\Http\Requests\API\EOS\v2\ReferralRequest;

class ReferralController extends Controller
{
    public function store(ReferralRequest $request)
    {
        $resp = ReferralRepository::store($request);

        return $this->sendResponse($resp);
    }
}
