<?php

namespace App\Repository\EOS\v2;

use App\Repository\EOS\v2\WalletRepository;
use App\Repository\EOS\v2\PriceRepository;
use App\Http\Controllers\API\EOS\v2\EosController;
use App\Models\Transaction;
use App\Models\Referral;
use App\Models\Wallet;

class TransactionRepository
{
    public static function save($data, $request, $wallet, $amount, $status = 1)
    {
        $transaction = new Transaction;
        $transaction->transaction_id = $data['hash'] ?? NULL;
        $transaction->uid = $data['uid'];
        $transaction->wallet_id = $wallet->id;
        $transaction->group_id = $request->group_id;
        $transaction->client_id = $request->client_id;
        $transaction->status = $status;
        $transaction->course_id = $data['course'];
        $transaction->event_type = $data['eventType'];
        $transaction->event_detail = $data['eventDetail'];
        $transaction->org = $data['org'];
        $transaction->amount = $amount;
		$transaction->comment = '?';

        $transaction->save();
    }

    public static function index($request)
    {
        $wallets = WalletRepository::all($request);

		$data = [];

		if ($wallets && !$wallets->isEmpty()) {
			foreach ($wallets as $wallet) {
				$transactions = Transaction::select([
						'transaction_id',
						'status',
						'created_at as date',
						'client_id as lms_id',
						'event_type',
						'event_detail',
						'course_id',
					])
					->where('wallet_id', $wallet->id)
                    ->groupBy('transactions.id')
					->get();

                $data[$wallet->wallet] = $transactions;
			}
		}

        return (object) [
            'code' => 200,
            'data' => $data,
        ];
    }

    public static function store($request)
	{
        $wallet = Wallet::where('user_id', $request->student_id)
            ->where('group_id', $request->group_id)
            ->where('status', 1)
            ->first();

        if ($wallet) {
    		$price = PriceRepository::get($request);

    		if (isset($price) and !empty($price) and $price != null) {
                $data['uid'] = $request->uid ?? null;
                $data['course'] = $request->course_id ?? null;
                $data['eventType'] = $request->event_type ?? null;
                $data['eventDetail'] = json_encode($request->event_details) ?? null;
                $data['org'] = $request->org ?? null;

                // Check Transaction limits
    			if ($price->limit != 0) {
    				if (isset($request->uid) and !empty($request->uid)) {
    					$transactions = Transaction::where('group_id', $request->group_id)
    						->where('wallet_id', $wallet->id)
    						->where('event_type', $request->event_type)
    						->where('uid', $request->uid)
    						->count();
    				} else {
    					$transactions = Transaction::where('group_id', $request->group_id)
    						->where('wallet_id', $wallet->id)
    						->where('event_type', $request->event_type)
                            ->where('course_id', $request->course_id)
    						->count();
    				}
    			}

    			if ($price->limit == 0 or (
    				$price->limit != 0 and
    				$transactions < $price->limit)) {

                    // Main transactions
                    $transaction = EosController::do_transaction(
                        config('custom.eos.wallet'),
                        $wallet->wallet,
                        $price->amount  . ' ' . request()->token,
                        config('custom.eos.wallet'));

                    if (isset($transaction->transaction_id)) {
                        $data['hash'] = $transaction->transaction_id;
                        $status = 1;
                    } else {
                        $status = 0;
                    }

                    self::save($data, $request, $wallet, $price->amount, $status);

                    // Referral Transactions
                    $referral = Referral::where('group_id', $request->group_id)
                        ->where('referral_id', $request->student_id)
                        ->first();

                    if (isset($referral) && !empty($referral)) {
                        $invited = Wallet::where('group_id', $request->group_id)
                            ->where('user_id', $referral->invited_id)
                            ->where('status', 1)
                            ->first();

                        if (isset($invited) && !empty($invited) && $price->referral != 0) {
                            $transaction = EosController::do_transaction(
                                config('custom.eos.wallet'),
                                $invited->wallet,
                                $price->referral  . ' ' . request()->token,
                                config('custom.eos.wallet'));

                            if (isset($transaction->transaction_id)) {
                                $data['hash'] = $transaction->transaction_id;
                                $status = 1;
                            } else {
                                $status = 0;
                            }

                            self::save($data, $request, $invited, $price->referral, $status);
                        }
                    }

                    return (object) [
                        'code' => 200,
                    ];
    			} else {
                    return (object) [
                        'code' => 422,
                        'message' => 'Transaction limit...',
                    ];
                }
            } else {
                return (object) [
                    'code' => 422,
                    'message' => 'Unknown event...',
                ];
            }
		} else {
            return (object) [
                'code' => 422,
                'message' => 'User has no account...',
            ];
        }
    }
}
