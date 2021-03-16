<?php

namespace App\Repository\Eos\v2;

use App\Repository\Eos\v2\WalletRepository;
use App\Models\Transaction;

class StatRepository
{
    public static function index($request)
	{
		$transactions = Transaction::groupBy([
				'wallet_id',
				'blockchains.id',
			])
			->selectRaw('wallets.user_id')
            ->selectRaw('SUM(transactions.amount) as tokens')
            ->selectRaw('blockchains.token as units')
            ->selectRaw('blockchains.delimiter as delimiter')
            ->selectRaw('COUNT(*) as transactions')
            ->selectRaw("'n/a' as income")
            ->selectRaw("'n/a' as offers")
            ->join('blockchains', 'blockchains.id', '=', 'transactions.blockchain_id')
			->join('wallets', 'wallets.id', '=', 'transactions.wallet_id')
            ->where('transactions.group_id', $request->group_id)
			->where('transactions.blockchain_id', $request->blockchain_id)
            ->where('transactions.status', 1);

        if ($request->course_id) {
            $transactions->where('course_id', $request->course_id);
        }

        if ($request->event_type) {
            $transactions->where('event_type', $request->event_type);
        }

        if ($request->student_id) {
			$wallets = WalletRepository::all($request);
			$transactions->whereIn('transactions.wallet_id', $wallets);
		}

		$transactions = $transactions->get();

		$data = [];

		if ($transactions->count() > 0) {
			foreach ($transactions as $transaction) {
				array_push($data, [$transaction->user_id => [$transaction]]);
			}
		}

        return (object) [
            'code' => 200,
            'data' => $data,
        ];
    }
}
