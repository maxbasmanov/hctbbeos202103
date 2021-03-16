<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
	public function inviteds()
	{
		return $this->belongsTo(Wallet::class, 'invited_id', 'user_id');
	}

	public function referrals()
	{
		return $this->belongsTo(Wallet::class, 'referral_id', 'user_id');
	}

	public function groups()
	{
		return $this->belongsTo(Group::class, 'group_id');
	}
}
