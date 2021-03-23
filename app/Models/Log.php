<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
	public $timestamps = false;

	private function pretty($value)
	{
		return json_encode(json_decode(gzuncompress(base64_decode($value))), JSON_PRETTY_PRINT);
	}

	public function getRequestAttribute($value)
	{
		return $this->pretty($value);
	}

	public function getResponseAttribute($value)
	{
		return $this->pretty($value);
	}
}
