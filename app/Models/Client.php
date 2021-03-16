<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
	protected $table = 'oauth_clients';

	protected $casts = [
        'id' => 'string',
    ];

	public function groups()
	{
		return $this->belongsTo(Group::class, 'group_id');
	}
}
