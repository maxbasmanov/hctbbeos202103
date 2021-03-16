<?php

namespace App\Http\Controllers\API\EOS\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\EOS\v2\TransactionRepository;
use App\Http\Requests\API\EOS\v2\TransactionRequest;

class TransactionController extends Controller
{
	public function index(Request $request)
	{
		$resp = TransactionRepository::index($request);

		return $this->sendResponse($resp);
	}

	public function store(TransactionRequest $request)
	{
		$resp = TransactionRepository::store($request);

		return $this->sendResponse($resp);
	}
}
