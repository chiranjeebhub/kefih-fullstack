<?php if(Auth::guard('vendor')->check()){?>
@if($message->message_type == 'Vendor')
    @include('admin/mod_chat/right_message-line')
@else
	@include('admin/mod_chat/left_message-line')
@endif
<?php }else{?>
@if($message->message_type == 'Admin')
    @include('admin/mod_chat/right_message-line')
@else
	@include('admin/mod_chat/left_message-line')
@endif
<?php } ?>