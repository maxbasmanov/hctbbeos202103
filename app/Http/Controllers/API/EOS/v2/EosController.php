<?php

namespace App\Http\Controllers\API\EOS\v2;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Log;

class EosController extends Controller
{
	private static function expiration()
	{
		return date("Y-m-d\TH:i:s", time() - 10800 + 12400);
	}

	private static function abi_json_to_bin($object)
	{
		$url = config('custom.eos.cleos').'v1/chain/abi_json_to_bin';

		$result = self::make_request('POST', $url, $object);

		return $result;
	}

	private static function get_info()
	{
		$url = config('custom.eos.cleos').'v1/chain/get_info';

		$result = self::make_request('GET', $url, false);

		return $result;
	}

	private static function get_block($get_info)
	{
		$url = config('custom.eos.cleos').'v1/chain/get_block';

		$object = ['block_num_or_id' => $get_info->head_block_num];

		$result = self::make_request('POST', $url, $object);

		return $result;
	}

	private static function wallet_unlock($wallet, $private)
	{
		$url = config('custom.eos.keosd').'v1/wallet/unlock';

		$object = [$wallet, $private];

		$result = self::make_request('POST', $url, $object);

		return $result;
	}

	private static function wallet_lock($wallet)
	{
		$url = config('custom.eos.keosd').'v1/wallet/lock';

		$result = self::make_request('POST', $url, $wallet);

		return $result;
	}

	public static function account_balance($account)
	{
		$url = config('custom.eos.cleos').'v1/chain/get_currency_balance';

		$object = [
			"account" => $account,
			"code" => config('custom.eos.token_contract'),
			"symbol" => null,
		];

		return self::make_request('POST', $url, $object);
	}

	private static function get_required_keys($abi_json_to_bin, $get_block)
	{
		$url = config('custom.eos.cleos').'v1/chain/get_required_keys';

		$object = [
		    "available_keys" => explode(',', config('custom.eos.keys')),
		    "transaction" => [
		        "actions" => [
		            [
		                "account" => "eosio.token",
		                "authorization" => [
		                    [
		                        "actor" => config('custom.eos.wallet'),
		                        "permission" => "active",
		                    ]
		                ],
		                "data" => $abi_json_to_bin->binargs,
		                "name" => "transfer",
		            ]
		        ],
		        "context_free_actions" => [],
		        "context_free_data" => [],
		        "delay_sec" => 0,
		        "expiration" => self::expiration(),
		        "max_cpu_usage_ms" => 0,
		        "max_net_usage_words" => 0,
		        "ref_block_num" => $get_block->block_num,
		        "ref_block_prefix" => $get_block->ref_block_prefix,
		        "signatures" => [],
		    ]
		];

		$result = self::make_request('POST', $url, $object);

		return $result;

	}

	public static function sign_transaction($object)
	{
		$url = config('custom.eos.keosd').'v1/wallet/sign_transaction';

		self::wallet_unlock(config('custom.eos.wallet'), config('custom.eos.password'));

		$result = self::make_request('POST', $url, $object);

		self::wallet_lock(config('custom.eos.wallet'));

		return $result;
	}

	private static function push_transaction($object)
	{
		$url = config('custom.eos.cleos').'v1/chain/push_transaction';

		$result = self::make_request('POST', $url, $object);

		return $result;
	}

	private static function send_transaction($object)
	{
		$url = config('custom.eos.cleos').'v1/chain/send_transaction';

		$result = self::make_request('POST', $url, $object);

		return $result;
	}

	public static function do_transaction($from, $to, $quantity, $memo)
	{
		$get_info = self::get_info();

		if (isset($get_info->chain_id, $get_info->head_block_num)) {

			$get_block = self::get_block($get_info);

			if (isset($get_block->block_num, $get_block->ref_block_prefix)) {

				$object = [
					'code' => config('custom.eos.token_contract'),
					'action' => 'transfer',
					'args' => [
						'from' => $from,
						'to' => $to,
						'quantity' => $quantity . config('custom.eos.token_name'),
						'memo' => $memo,
					],
				];

				$abi_json_to_bin = self::abi_json_to_bin($object);

				if (isset($abi_json_to_bin->binargs)) {

					$get_required_keys = self::get_required_keys($abi_json_to_bin, $get_block);

					$object = [
						[
							"ref_block_num" => $get_block->block_num,
						    "ref_block_prefix" => $get_block->ref_block_prefix,
						    "expiration" => self::expiration(),
							"max_net_usage_words" => 0,
							"max_cpu_usage_ms" => 0,
							"delay_sec" => 0,
						    "actions" => [
								[
							        "account" => config('custom.eos.token_contract'),
							        "name" => "transfer",
							        "authorization" => [
										[
								            "actor" => config('custom.eos.wallet'),
								            "permission" => "active",
										]
							        ],
							        "data" => $abi_json_to_bin->binargs,
								],
						    ],
							"transaction_extensions" => [],
						    "signatures" => [],
							"context_free_data" => [],
							"context_free_actions" => [],
						],
						$get_required_keys->required_keys,
						$get_info->chain_id,
					];

					$sign_transaction = self::sign_transaction($object);

					if (isset($sign_transaction->signatures)) {

						$object = [
							"compression" => "none",
							"transaction" => [
							    "expiration" => self::expiration(),
							    "ref_block_num" => $get_block->block_num,
							    "ref_block_prefix" => $get_block->ref_block_prefix,
							    "context_free_actions" => [],
							    "actions" => [
									[
							            "account" => config('custom.eos.token_contract'),
							            "name" => "transfer",
							            "authorization" => [
											[
							                    "actor" => config('custom.eos.wallet'),
							                    "permission" => "active",
							                ],
							            ],
							            "data" => $abi_json_to_bin->binargs,
							        ],
							    ],
								"transaction_extensions" => []
							],
							"signatures" => $sign_transaction->signatures,
						];

						return self::push_transaction($object);

					} else return $sign_transaction;

		   		} else return $abi_json_to_bin;

			} else return $get_block;

		} else return $get_info;
	}

	public static function project_finallize($data)
	{
		$get_info = self::get_info();

		if (isset($get_info->chain_id, $get_info->head_block_num)) {

			$get_block = self::get_block($get_info);

			if (isset($get_block->block_num, $get_block->ref_block_prefix)) {

				$object = [
					"code" => config('custom.eos.project_contract'),
					"action" => "finadd",
					"args" => $data,
				];

				$abi_json_to_bin = self::abi_json_to_bin($object);

				if (isset($abi_json_to_bin->binargs)) {

					$get_required_keys = self::get_required_keys($abi_json_to_bin, $get_block);

					$object = [
						[
							"ref_block_num" => $get_block->block_num,
						    "ref_block_prefix" => $get_block->ref_block_prefix,
						    "expiration" => self::expiration(),
							"max_net_usage_words" => 0,
							"max_cpu_usage_ms" => 0,
							"delay_sec" => 0,
						    "actions" => [
								[
							        "account" => config('custom.eos.project_contract'),
							        "name" => "finadd",
							        "authorization" => [
										[
								            "actor" => config('custom.eos.wallet'),
								            "permission" => "active",
										]
							        ],
							        "data" => $abi_json_to_bin->binargs,
								],
						    ],
							"transaction_extensions" => [],
						    "signatures" => [],
							"context_free_data" => [],
							"context_free_actions" => [],
						],
						$get_required_keys->required_keys,
						$get_info->chain_id,
					];

					$sign_transaction = self::sign_transaction($object);

					if (isset($sign_transaction->signatures)) {

						$object = [
							"compression" => "none",
							"transaction" => [
							    "expiration" => self::expiration(),
							    "ref_block_num" => $get_block->block_num,
							    "ref_block_prefix" => $get_block->ref_block_prefix,
							    "context_free_actions" => [],
							    "actions" => [
									[
							            "account" => config('custom.eos.project_contract'),
							            "name" => "finadd",
							            "authorization" => [
											[
							                    "actor" => config('custom.eos.wallet'),
							                    "permission" => "active",
							                ],
							            ],
							            "data" => $abi_json_to_bin->binargs,
							        ],
							    ],
								"transaction_extensions" => []
							],
							"signatures" => $sign_transaction->signatures,
						];

						return self::send_transaction($object);

					} else return $sign_transaction;

		   		} else return $abi_json_to_bin;

			} else return $get_block;

		} else return $get_info;
	}

	public static function get_account($account)
	{
		$url = config('custom.eos.cleos').'v1/chain/get_account';

		$object = ['account_name' => $account];

		$result = self::make_request('POST', $url, $object);

		return $result;
	}

	private static function log_request($url, $method, $info, $object, $body, $response)
	{
		$url = parse_url($url);

		if (@$url['path'] == '/v1/wallet/unlock') {
			if ($object != false) {
				$object['1'] = '**********';
			}
		}

		$log = new Log;
		$log->host = request()->getHttpHost();
		$log->group_id = request()->group_id ?? 0;
		$log->client_id = request()->header('PHP_AUTH_USER') ?? request()->client_id ?? 0;
		$log->method = ($method == 'POST') ? 1 : 0;
		$log->status = @$info['http_code'];
		$log->url = @$url['path'];
		$log->ip = '127.0.0.1';
		$log->request = base64_encode(gzcompress((($object != false)
			? json_encode($object)
			: $body), 9));
		$log->response = base64_encode(gzcompress($response, 9));
		$log->created_at = Carbon::now();
		$log->save();
	}

	private static function make_request($method, $url, $object = false, $body = false)
	{
		$header = [
    		'Content-Type: application/json',
    		'Accept: application/json'
		];

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

		if ($object != false) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($object));
			curl_setopt($ch, CURLOPT_POST, 1);
		} elseif ($body != false) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
			curl_setopt($ch, CURLOPT_POST, 1);
		} else {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		}

		//curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		$info = curl_getinfo($ch);

		self::log_request($url, $method, $info, $object, $body, $response);

		curl_close($ch);

		return json_last_error() ? false : json_decode($response);
	}
}
