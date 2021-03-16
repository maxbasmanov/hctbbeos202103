<?php

namespace App\Repository\EOS\v2;

use App\Http\Controllers\API\EOS\v2\EosController;
use App\Models\Transaction;
use App\Models\Wallet;

class WalletRepository
{
    public static function all($request)
    {
        $wallets = Wallet::where('group_id', $request->group_id);

        if ($request->student_id) {
            $wallets->where('user_id', $request->student_id);
        }

        return $wallets->get();
    }

    public static function store($request)
	{
        $eos = EosController::wallet_store($request);

        if (isset($eos->transaction_id)
            && isset($eos->processed->receipt->status)
            && $eos->processed->receipt->status == 'executed') {

            if (isset($eos->processed->action_traces)) {
                foreach ($eos->processed->action_traces as $action) {
                    if ($action->except != null && $action->error_code != null) {
                        return (object) [
                            'code' => $action->error_code ?? 500,
                            'message' => $action->except ?? 'Transaction unknown error...',
                        ];

                        break;
                    }
                }
            }

            $wallet = Wallet::where('user_id', $request->student_id)
                ->where('group_id', $request->group_id)
                ->where('blockchain_id', $request->blockchain_id)
                ->where('status', 1)
                ->first();

            $wallet = $wallet ?? new Wallet;
			$wallet->user_id = $request->student_id;
			$wallet->wallet = $request->wallet;
			$wallet->blockchain_id = $request->blockchain_id;
			$wallet->client_id = $request->client_id;
			$wallet->group_id = $request->group_id;
			$wallet->status = 1;
			$wallet->save();

            return (object) [
                'code' => 200,
            ];
        } else {
            if (isset($eos->error->code, $eos->error->name)
                && $eos->error->code == 3050001
                && $eos->error->name == 'account_name_exists_exception') {

                $code = 422;
            }

            return (object) [
                'code' => $code ?? $eos->code ?? 500,
                'message' => $eos->error->details[0]->message ?? 'Transaction unknown error...',
            ];
        }
    }

    public static function update($request)
    {
        $eos = EosController::get_account($request->wallet);

        if (isset($eos->code)) {
            return (object) [
                'code' => $eos->code ?? 500,
                'message' => $eos->error->details[0]->message ?? 'Transaction unknown error...',
            ];
        } else {
            Wallet::where('user_id', $request->student_id)
    			->where('group_id', $request->group_id)
    			->where('blockchain_id', $request->blockchain_id)
    			->update(['status' => 0]);

    		$wallet = Wallet::where('user_id', $request->student_id)
    			->where('wallet', $request->wallet)
    			->where('group_id', $request->group_id)
    			->where('blockchain_id', $request->blockchain_id)
    			->orderBy('id', 'desc')
    			->first();

    		if (!empty($wallet)) {
    			$wallet->status = 1;
    			$wallet->save();
    		} else {
    			$wallet = new Wallet;
    			$wallet->user_id = $request->student_id;
    			$wallet->wallet = $request->wallet;
    			$wallet->blockchain_id = $request->blockchain_id;
    			$wallet->client_id = $request->client_id;
                $wallet->group_id = $request->group_id;
    			$wallet->status = 1;
    			$wallet->save();
    		}

            return (object) [
                'code' => 200,
            ];
        }
    }

    public static function balance($request)
    {
        $balance = ['balance' => '0.0000'];

        $wallet = Wallet::where('user_id', $request->student_id)
			->where('group_id', $request->group_id)
			->where('blockchain_id', $request->blockchain_id)
			->where('status', 1)
			->first();

		if ($wallet) {
			$eos = EosController::account_balance($wallet->wallet);

			if (isset($eos->code)) {
                return (object) [
                    'code' => $eos->code ?? 500,
                    'message' => $eos->error->details[0]->message ?? 'Transaction unknown error...',
                ];
			} else {
                $balance = ['balance' => $eos[0] ?? '0.0000'];

                return (object) [
                    'code' => 200,
                    'data' => $balance,
                ];
            }
		} else {
            return (object) [
                'code' => 200,
                'data' => $balance,
            ];
		}
    }
}
