		@if(session()->has('message.level'))

		<div class=" alert_message alert alert-{{ session('message.level') }}">
       {!! session('message.content') !!}
		</div>
		@endif