@extends('layouts.app')
@section('content')
<div class="list-group mb-3">
	<div class="list-group-item list-group-item-light">
		<div class="row">
			<div class="col-6">
				{{ __('Hash') }}
			</div>
			<div class="col-1">
				{{ __('Client') }}
			</div>
			<div class="col-3 text-right">
				{{ __('Sum') }}
			</div>
			<div class="col-2 text-right">
				{{ __('Date') }}
			</div>
		</div>
	</div>
	@foreach($transactions as $transaction)
	<a href="{{ route('transactions.view', $transaction) }}" class="list-group-item list-group-item-action">
		<div class="row">
			<div class="col-6 small text-truncate">
				{{ $transaction->transaction_id }}
			</div>
			<div class="col-1">
				{{ $transaction->clients->name ?? '-' }}
			</div>
			<div class="col-3 text-right text-truncate">
				{{ $transaction->amount }} <small>{{ $transaction->blockchains->token ?? '-' }}</small>
			</div>
			<div class="col-2 small text-right">
				{{ $transaction->created_at }}
			</div>
		</div>
	</a>
	@endforeach
</div>
{{ $transactions->appends($_GET)->links() }}
@endsection
