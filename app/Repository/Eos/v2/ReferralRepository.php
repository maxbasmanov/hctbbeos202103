<?php

namespace App\Repository\EOS\v2;

use App\Repository\EOS\v2\WalletRepository;
use App\Repository\EOS\v2\TransactionRepository;
use App\Http\Controllers\API\EOS\v2\EosController;
use App\Models\Referral;
use App\Models\Wallet;

class ReferralRepository
{
	public static function store($request)
	{
		if (isset($request->student_id, $request->referral_id) &&
			$request->student_id != $request->referral_id) {

			if (Referral::where('group_id', $request->group_id)
				->where('invited_id', $request->student_id)
				->where('referral_id', $request->referral_id)
				->count() == 0) {

				$invited = Wallet::where('user_id', $request->student_id)
					->where('group_id', $request->group_id)
					->where('status', 1)
					->first();

				$referral = Wallet::where('user_id', $request->referral_id)
					->where('group_id', $request->group_id)
					->where('status', 1)
					->first();

				$parentInvited = Referral::where('group_id', $request->group_id)
					->where('referral_id', $request->student_id)
					->first();

				$referral = new Referral;
				$referral->group_id = $request->group_id;
				$referral->invited_id = $request->student_id;
				$referral->referral_id = $request->referral_id;
				$referral->referral_type = $request->referral_type ?? null;
				$referral->referral_hash = $request->referral_hashkey ?? null;
				$referral->event_type_verbose = $request->event_type_verbose ?? null;
				$referral->save();

				// What if event_type will be not 5?
				$request->merge(['event_type' => 5]);

				$price = PriceRepository::get($request);

				if (isset($price) and !empty($price) and $price != null) {
					$eventDetail = [
						'referral_type' => $request->referral_type ?? null,
						'referral_id' => $referral->user_id,
						'event_type' => $request->event_type ?? null,
						'event_type_verbose' => $request->event_type_verbose ?? null,
						'referral_hashkey' => $request->referral_hashkey ?? null,
					];

					$data['uid'] = $request->uid ?? null;
					$data['course'] = null;
					$data['eventType'] = $request->event_type ?? null;
					$data['eventDetail'] = json_encode($eventDetail);
					$data['org'] = $request->org ?? null;

					if (isset($invited->wallet) && isset($price->amount)) {
						$transaction = EosController::do_transaction(
							config('custom.eos.wallet'),
							$invited->wallet,
							$price->amount,
							config('custom.eos.wallet'));


						if (isset($transaction->transaction_id)) {
							$data['hash'] = $transaction->transaction_id;
							$status = 1;
						} else {
							$status = 0;
						}

						TransactionRepository::save($data, $request, $invited, $price->amount, $status);
					}

					if (isset($parentInvited) && !empty($parentInvited)) {
						$parentInvited = Wallet::where('group_id', $request->group_id)
							->where('user_id', $parentInvited->invited_id)
							->where('status', 1)
							->first();

						if (isset($parentInvited) && !empty($parentInvited)) {
							$eventDetail = [
								'referral_type' => $request->referral_type ?? null,
								'referral_id' => $parentInvited->user_id,
								'event_type' => $request->event_type ?? null,
								'event_type_verbose' => $request->event_type_verbose ?? null,
								'referral_hashkey' => $request->referral_hashkey ?? null,
							];

							$data['uid'] = $request->uid ?? null;
							$data['course'] = null;
							$data['eventType'] = $request->event_type ?? null;
							$data['eventDetail'] = json_encode($eventDetail);
							$data['org'] = $request->org ?? null;

							$transaction = EosController::do_transaction(
								config('custom.eos.wallet'),
								$parentInvited->wallet,
								$price->amount,
								config('custom.eos.wallet'));

							if (isset($transaction->transaction_id)) {
								$data['hash'] = $transaction->transaction_id;
								$status = 1;
							} else {
								$status = 0;
							}

							TransactionRepository::save($data, $request, $parentInvited, $price->referral, $status);
						}
					}
				}
			}
		}

		return (object) [
			'code' => 200,
		];
	}
}
