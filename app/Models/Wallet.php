<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'user_id',
		'wallet',
		'client_id',
	];

	protected $dates = ['deleted_at'];

	public function transactions()
	{
		return $this->hasMany(Transaction::class, 'wallet_id');
	}

	public function clients()
	{
		return $this->belongsTo(Client::class, 'client_id');
	}

	public function referrals()
	{
		return $this->belongsTo(Referral::class, 'user_id', 'referral_id');
	}
}
