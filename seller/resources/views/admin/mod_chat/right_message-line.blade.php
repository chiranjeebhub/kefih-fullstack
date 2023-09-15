
    <div class="direct-chat-msg msg_container right mb-30" data-message-id="{{ $message->id }}">
		  <div class="clearfix mb-15">
			<span class="direct-chat-name pull-right">You</span>
		  </div>
		  <div class="direct-chat-text">
			<p>{!! $message->content !!}</p>
			<p class="direct-chat-timestamp"><time datetime="{{ date('Y',strtotime($message->created_at)) }}">{{ date('H:i',strtotime($message->created_at)) }}</time></p>
		  </div>
		  <!-- /.direct-chat-text -->
	</div>

