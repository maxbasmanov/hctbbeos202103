<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
	public $timestamps = false;

	public function getRequestAttribute($value)
	{
		return json_encode(json_decode(gzuncompress(base64_decode($value))), JSON_PRETTY_PRINT);
	}

	public function getResponseAttribute($value)
	{
		return json_encode(json_decode(gzuncompress(base64_decode($value))), JSON_PRETTY_PRINT);
	}
}
