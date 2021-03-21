@extends('layouts.app')
@section('content')
<div class="list-group">
    <div class="list-group-item list-group-item-light">
        <div class="row">
            <div class="col-6">
                {{ __('Log details') }}
            </div>
            <div class="col-6 text-right">
                <a href="{{ URL::previous() }}">{{ __('Back') }}</a>
            </div>
        </div>
    </div>
    <div class="list-group-item">
        <div class="row">
            <div class="col-3 col-sm-2 col-md-2">
                {{ __('Id') }}
            </div>
            <div class="col-9 col-sm-10 col-md-10 text-wrap">
                {{ $log->id }}
            </div>
        </div>
    </div>
    <div class="list-group-item">
        <div class="row">
            <div class="col-3 col-sm-2 col-md-2">
                {{ __('Method') }}
            </div>
            <div class="col-9 col-sm-10 col-md-10">
                {{ ($log->method) ? 'POST' : 'GET' }}
            </div>
        </div>
    </div>
	<div class="list-group-item">
        <div class="row">
            <div class="col-3 col-sm-2 col-md-2">
                {{ __('Status') }}
            </div>
            <div class="col-9 col-sm-10 col-md-10">
                {{ $log->status }}
            </div>
        </div>
    </div>
    <div class="list-group-item">
        <div class="row">
            <div class="col-3 col-sm-2 col-md-2">
                {{ __('URL') }}
            </div>
            <div class="col-9 col-sm-10 col-md-10 text-wrap">
                {{ $log->url }}
            </div>
        </div>
    </div>
	<div class="list-group-item">
        <div class="row">
            <div class="col-3 col-sm-2 col-md-2">
                {{ __('Client ID') }}
            </div>
            <div class="col-9 col-sm-10 col-md-10 text-wrap">
                {{ $log->client_id }}
            </div>
        </div>
    </div>
	<div class="list-group-item">
        <div class="row">
            <div class="col-3 col-sm-2 col-md-2">
                {{ __('IP') }}
            </div>
            <div class="col-9 col-sm-10 col-md-10 text-wrap">
                {{ $log->ip }}
            </div>
        </div>
    </div>
    <div class="list-group-item">
        <div class="row">
            <div class="col-3 col-sm-2 col-md-2">
                {{ __('Date') }}
            </div>
            <div class="col-9 col-sm-10 col-md-10 text-wrap">
                {{ $log->created_at }}
            </div>
        </div>
    </div>
    <div class="list-group-item">
        <div class="row">
            <div class="col-3 col-sm-2 col-md-2 text-wrap">
                {{ __('Request') }}
            </div>
            <div class="col-9 col-sm-10 col-md-10 text-wrap"><pre>{{ $log->request }}</pre></div>
        </div>
    </div>
	<div class="list-group-item">
        <div class="row">
            <div class="col-3 col-sm-2 col-md-2 text-wrap">
                {{ __('Response') }}
            </div>
            <div class="col-9 col-sm-10 col-md-10 text-wrap"><pre>{{ $log->response }}</pre></div>
        </div>
    </div>
</div>
@endsection
