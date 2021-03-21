<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Log;

class LogController extends Controller
{
	public function index(Request $request)
	{
		$logs = Log::orderByDesc('id')
			->simplePaginate(20);

		return view('logs.index', compact('logs'));
	}

	public function view(Log $log)
	{
		return view('logs.view', compact('log'));
	}
}
