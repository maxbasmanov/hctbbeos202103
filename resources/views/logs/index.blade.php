@extends('layouts.app')
@section('content')
<div class="list-group mb-3">
	<div class="list-group-item list-group-item-light">
		<div class="row">
			<div class="col-1 text-center">
				{{ __('Id') }}
			</div>
			<div class="col-1 text-center">
				{{ __('Method') }}
			</div>
			<div class="col-1 text-center">
				{{ __('Status') }}
			</div>
			<div class="col-5">
				{{ __('URL') }}
			</div>
			<div class="col-2">
				{{ __('IP') }}
			</div>
			<div class="col-2 text-right">
				{{ __('Date') }}
			</div>
		</div>
	</div>
	@foreach($logs as $log)
	<a href="{{ route('logs.view', $log) }}" class="list-group-item list-group-item-action {{ Helper::colors($log->status) }}">
		<div class="row">
			<div class="col-1 text-center">
				{{ $log->id }}
			</div>
			<div class="col-1 text-center">
				{{ ($log->method) ? 'POST' : 'GET' }}
			</div>
			<div class="col-1 text-center">
				{{ $log->status }}
			</div>
			<div class="col-5">
				{{ $log->url }}
			</div>
			<div class="col-2">
				{{ $log->ip }}
			</div>
			<div class="col-2 small-xs text-right">
				{{ $log->created_at }}
			</div>
		</div>
	</a>
	@endforeach
</div>
<div class="row">
	<div class="col-12">
		{!! $logs->appends($_GET)->links() !!}
	</div>
</div>
@endsection
