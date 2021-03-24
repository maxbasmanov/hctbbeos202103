<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Host;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
		$transactions = Transaction::orderBy('id', 'desc')
    		->simplePaginate(20);

		return view('index', compact('transactions'));
    }

    public function view(Transaction $transaction)
    {
        return view('view', compact('transaction'));
    }

    public function search(Request $request)
    {
        $transactions = Transaction::whereHas('clients', function ($query) use ($request) {
				$query->where('name', 'like', '%'.$request->search.'%');
			})
            ->oRwhereHas('wallets', function ($query) use ($request) {
    			$query->where('user_id', 'like', '%'.$request->search.'%');
    		})
            ->orWhere('transaction_id', $request->search)
    		->orderBy('id', 'desc')
    		->simplePaginate(20);

        return view('index', compact('transactions'));
    }
}
