
	<div class="direct-chat-msg msg_container mb-30" data-message-id="{{ $message->id }}">
		  <div class="clearfix mb-15">
			<span class="direct-chat-name">{{ $message->message_type }}</span>
			<span class="direct-chat-timestamp pull-right">{{ date('M d, Y',strtotime($message->created_at)) }}</span>
		  </div>
		  <!-- /.direct-chat-info -->
		  <img class="direct-chat-img avatar" src="{{ url('public/images/user-avatar.png') }}" alt="message user image">
		  <!-- /.direct-chat-img -->
		  <div class="direct-chat-text">
			<p>{!! $message->content !!}</p>
			<p class="direct-chat-timestamp"><time datetime="{{ date('Y',strtotime($message->created_at)) }}">{{ date('H:i',strtotime($message->created_at)) }}</time></p>
		  </div>		  
	</div>
	
