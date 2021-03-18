<?php

namespace App\Http\Middleware;

use App\Models\Client;
use App\Models\User;
use App\Models\Event;
use Lcobucci\JWT\Parser;
use Laravel\Passport\ClientRepository;
use Closure;

class Hosts
{
	private $error = false;

    public function handle($request, Closure $next)
    {
		foreach ($request->getClientIps() as $ip) {
			if ($request->bearerToken()) {
				$oauth = (new Parser())
					->parse($request->bearerToken())
					->getClaim('aud');

				$oauth = (new ClientRepository())->findActive($oauth);

				$client = Client::whereHas('groups', function($q) use ($ip){
                        $q->where('ip', $ip);
                        $q->where('revoked', 0);
                    })
                    ->where('id', $oauth->id)
	                ->where('revoked', 0)
					->first();

				$this->check_oauth($client, $oauth, $request);
			}

			if (!empty($client) && !empty($request)) {
				$this->check_headers($request);
				$this->check_method($request);
				$this->check_primary($client, $request);
				$this->check_event($client, $request);
			} else {
				$this->error = true;
				$this->message = 'Unauthenticated.';
			}

            if ($this->error == true) {
				return response()->json([
					'message' => $this->message ?? 'Unauthenticated.',
				], 401);

            } else return $next($request);
        }
    }

	private function check_headers($request)
	{
		if ($request->headers->get('Content-Type') != 'application/json' or
			$request->headers->get('Accept') != 'application/json') {
			$this->error = true;
			$this->message = 'Content-Type and Accept must be application/json.';
		}
	}

	private function check_method($request)
	{
		if ($request->isMethod('get')) {
			$this->error = true;
			$this->message = 'Method must be POST.';
		}
	}

	private function check_oauth($client, $oauth, $request)
	{
		if (!$client or	empty($client)) {
			$this->error = true;
			$this->message = 'Unauthenticated.';
		} else {
			$request->merge([
				'group_id' => $client->group_id,
				'client_id' => $client->id,
			]);

			if ($request->client_id != $oauth->id) {
				$this->error = true;
				$this->message = 'Authorized client id not same as request client id.';
			}
		}
	}

	private function check_primary($client, $request)
	{
		if (($request->is('referrals/store') && $client->primary == 0)) {
			$this->error = true;
			$this->message = 'This request must be from primary client.';
		} else return false;
	}

	private function check_event($client, $request)
	{
		if ($request->is('transactions/store') && !empty($request->event_type)) {
			$event = Event::where('group_id', $client->group_id)->find($request->event_type);

			if (isset($event) && !empty($event) && (
					($event->primary == 0 && $client->primary == 1) or
					($event->primary == 1 && $client->primary == 0)
				)) {

				$this->error = true;
				$this->message = 'Primary event type must be from primary client and vice versa or event type not found.';
			}
		}
	}
}
