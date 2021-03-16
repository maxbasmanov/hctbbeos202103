<?php

namespace App\Repository\Eos\v2;

use App\Models\Price;

class PriceRepository
{
    public static function get($request)
	{
        $price = Price::where('group_id', $request->group_id)
			->where('client_id', $request->client_id)
			->where('event_id', $request->event_type)
            ->where('revoked', 0)
			->first();

        return $price;
    }
}
