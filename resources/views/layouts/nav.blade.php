<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
	<div class="container">
		<a class="navbar-brand" href="{{ ((Auth::guest()) ? url('/') : route('admin.index')) }}">
			{{ config('app.name') }}
		</a>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="{{ route('transactions.index') }}" title="{{ __('Transactions') }}">
						{{ __('Transactions') }}
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('logs.index') }}" title="{{ __('Logs') }}">
						{{ __('Logs') }}
					</a>
				</li>
			</ul>
			<form class="form-inline my-2 my-lg-0" action="{{ route('transactions.search') }}">
				<input name="search" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">{{ __('Search') }}</button>
			</form>
		</div>
	</div>
</nav>

<script>
$(function () {
	$("#search-show").click(function() {
		$('.navbar-links').hide();
		$('.navbar-search').show();
		$("#search-hide").click(function() {
			$('.navbar-links').show();
			$('.navbar-search').hide();
			$('.navbar-search input[type=search]').val('');
		});
	});
});
</script>
