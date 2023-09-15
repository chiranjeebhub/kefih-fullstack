	<footer class="main-footer">
		@php
		$url = url()->current();
	
		if($url == "https://b2cdomain.in/bta/seller"){
			echo "<a href='https://b2cdomain.in/bta/seller/vendor_register/MA==' target='_blank' style='float:left;margin-left:2%;'>CREATE YOUR SELLER ACCOUNT</a>";
		}
		@endphp
	© {{date('Y')}} <a href="javascript:void(0)">Kefih</a>. All Rights Reserved.
	</footer>