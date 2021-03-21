@extends('layouts.app')

@section('content')

<div class="list-group">

    <div class="list-group-item list-group-item-light">

        <div class="row">

            <div class="col-6">

                {{ __('Transaction details') }}

            </div>

            <div class="col-6 text-right">

                <a href="{{ URL::previous() }}">{{ __('Back') }}</a>

            </div>

        </div>

    </div>

    <div class="list-group-item">

        <div class="row">

            <div class="col-3 col-sm-2 col-md-2">

                {{ __('Hash') }}

            </div>

            <div class="col-9 col-sm-10 col-md-10 text-wrap">

                {{ $transaction->transaction_id }}

            </div>

        </div>

    </div>

    <div class="list-group-item">

        <div class="row">

            <div class="col-3 col-sm-2 col-md-2">

                {{ __('Status') }}

            </div>

            <div class="col-9 col-sm-10 col-md-10">

                {{ $transaction->status }}

            </div>

        </div>

    </div>

	<div class="list-group-item">

		<div class="row">

			<div class="col-3 col-sm-2 col-md-2">

				{{ __('Group') }}

			</div>

			<div class="col-9 col-sm-10 col-md-10 text-wrap">

				{{ $transaction->group_id ?? '-' }}

			</div>

		</div>

	</div>

    <div class="list-group-item">

        <div class="row">

            <div class="col-3 col-sm-2 col-md-2">

                {{ __('Client') }}

            </div>

            <div class="col-9 col-sm-10 col-md-10 text-wrap">

                {{ $transaction->clients->name ?? '-' }}

            </div>

        </div>

    </div>

    <div class="list-group-item">

        <div class="row">

            <div class="col-3 col-sm-2 col-md-2">

                {{ __('To') }}

            </div>

            <div class="col-9 col-sm-10 col-md-10 text-wrap">

                {{ $transaction->wallets->wallet ?? $transaction->wallets->name ?? '-' }}

            </div>

        </div>

    </div>

    <div class="list-group-item">

        <div class="row">

            <div class="col-3 col-sm-2 col-md-2 text-wrap">

                {{ __('Sum') }}

            </div>

            <div class="col-9 col-sm-10 col-md-10 text-wrap">

                {{ $transaction->amount }} <small>{{ $transaction->blockchains->token ?? '-' }}</small>

            </div>

        </div>

    </div>

    <div class="list-group-item">

        <div class="row">

            <div class="col-3 col-sm-2 col-md-2">

                {{ __('Event') }}

            </div>

            <div class="col-9 col-sm-10 col-md-10 text-wrap">

                {{ $transaction->events->name ?? '-' }}

            </div>

        </div>

    </div>

    <div class="list-group-item">

        <div class="row">

            <div class="col-3 col-sm-2 col-md-2">

                {{ __('Data') }}

            </div>

            <div class="col-9 col-sm-10 col-md-10 text-wrap">

                {{ $transaction->event_detail }}

            </div>

        </div>

    </div>

	<div class="list-group-item">

        <div class="row">

            <div class="col-3 col-sm-2 col-md-2">

                {{ __('Comment') }}

            </div>

            <div class="col-9 col-sm-10 col-md-10 text-wrap">

                {{ $transaction->exceptions->message ?? $transaction->comment }}

            </div>

        </div>

    </div>

    <div class="list-group-item">

        <div class="row">

            <div class="col-3 col-sm-2 col-md-2">

                {{ __('Date') }}

            </div>

            <div class="col-9 col-sm-10 col-md-10 text-wrap">

                {{ $transaction->created_at }}

            </div>

        </div>

    </div>

</div>

@endsection
