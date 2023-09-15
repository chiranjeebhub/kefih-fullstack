
	<script >
$(document).ready(function () {

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$(".vendor_resend_button").click(function () {
		$.ajax({
			type: 'POST',
			async: false,
			url: "{{ route('vendor_resend_otp') }}",
			data: {},
			success: function (data) {
				response = JSON.parse(data);
				$('.vendor_return_message').append(response.MSG);
				setTimeout(function () {
					$('.vendor_return_message').html('')
				}, 3000);
			}
		});
	});
	$(".vendor_phone_resend_button").click(function () {
		$.ajax({
			type: 'POST',
			async: false,
			url: "{{ route('vendor_phone_resend_otp') }}",
			data: {},
			success: function (data) {
				response = JSON.parse(data);
				$('.vendor_phone_return_message').append(response.MSG);
				setTimeout(function () {
					$('.vendor_phone_return_message').html('')
				}, 3000);
			}
		});
	});
});



</script>