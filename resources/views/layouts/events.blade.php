@if (isset($errors) && count($errors) or Session::has('error'))
	<div class="events alert-danger" role="alert">
		<div class="container small">
			@foreach ($errors->all() as $error)
				{!! $error !!}
			@endforeach
			{!! Session::get('error') !!}
		</div>
	</div>
@elseif (Session::has('success'))
	<div class="events alert-success" role="alert">
		<div class="container">
			{!! Session::get('success') !!}
		</div>
	</div>
@endif

<script>
$(function () {
	$('.events').click(function(){
		$(this).hide();
	});
});
</script>
