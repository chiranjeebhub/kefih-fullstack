		
		@if(session()->has('message.level'))
			<div class="alert-dismissible fade show  alert_message alert alert-{{ session('message.level') }}"   role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
				<!--<strong>{{ ucfirst(session('message.level')) }} ,</strong>-->
				<?php if(session('message.level')=='danger'){?>
					<?php print_r(session('message.content'));?>
				<?php }else{?>
					{!! session('message.content') !!}
				<?php } ?>
			</div>
		@endif