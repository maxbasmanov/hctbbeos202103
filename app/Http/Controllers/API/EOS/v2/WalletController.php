<?php

namespace App\Http\Controllers\API\EOS\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\EOS\v2\WalletRepository;
use App\Http\Requests\API\EOS\v2\WalletBalanceRequest;
use App\Http\Requests\API\EOS\v2\WalletStoreRequest;
use App\Http\Requests\API\EOS\v2\WalletUpdateRequest;

class WalletController extends Controller
{
	public function store(WalletStoreRequest $request)
	{
		$resp = WalletRepository::update($request);

		return $this->sendResponse($resp);
	}

	public function update(WalletUpdateRequest $request)
	{
		$resp = WalletRepository::update($request);

		return $this->sendResponse($resp);
	}

	public function balance(WalletBalanceRequest $request)
	{
		$resp = WalletRepository::balance($request);

		return $this->sendResponse($resp);
	}
}
