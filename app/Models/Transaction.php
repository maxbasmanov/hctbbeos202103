<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];

	protected $casts = [
		'tokens' => 'float',
	];

	public function clients()
	{
		return $this->belongsTo(Client::class, 'client_id');
	}

	public function wallets()
	{
		return $this->belongsTo(Wallet::class, 'wallet_id');
	}

	public function events()
	{
		return $this->belongsTo(Event::class, 'event_type', 'event_id')->where('events.group_id', $this->group_id);
	}

	public function exceptions()
	{
		return $this->belongsTo(Exception::class, 'status', 'code');
	}
}
