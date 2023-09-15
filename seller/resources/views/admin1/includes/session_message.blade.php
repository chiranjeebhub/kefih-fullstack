		@if(session()->has('message.level'))

		<div class=" alert_message alert alert-{{ session('message.level') }}">

		<strong>{{ ucfirst(session('message.level')) }} ,</strong>
		{!! session('message.content') !!}
		</div>
		@endif