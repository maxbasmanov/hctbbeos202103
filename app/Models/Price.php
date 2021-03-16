<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
	use SoftDeletes;

	public function clients()
	{
		return $this->belongsTo(Client::class, 'client_id');
	}

	public function events()
	{
		return $this->belongsTo(Event::class, 'event_id', 'event_id')->where('group_id', $this->group_id);
	}

	public function groups()
	{
		return $this->belongsTo(Group::class, 'group_id');
	}
}
