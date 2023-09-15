@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')  


  <!-- Main content -->
    <section class="content">

      <div class="row">
			
        <div class="col-lg-3 col-md-12">
			<div class="box">
				<!--<div class="box-header with-border p-0 pt-10">
					<div class="form-element lookup">
						<input class="form-control p-20 w-p100" type="text" placeholder="Search Contact">
					</div>
				</div>-->
				<div class="box-body p-0">
				  <div id="chat-contact" class="media-list media-list-hover media-list-divided ">
					<?php for($i=0; $i<count($customer_list); $i++){?>
					<div class="media media-single">
					  <!--<a href="#">
						<img class="avatar avatar-xl" src="../../../images/avatar/2.jpg" alt="...">
					  </a>-->

					  <div class="media-body">
						<h6><a href="#"><?php echo ucwords($customer_list[$i]['name']);?></a></h6>
						<a href="javascript:void(0);" class="chat-toggle" data-id="{{ $customer_list[$i]['id'] }}" data-user="{{ $customer_list[$i]['name'] }}">Open chat</a>
						<!--<small class="text-green">Online</small>-->
					  </div>
					</div>
					<?php } ?>
					
				  </div>
				</div>
            </div>
          
        </div>
        <!-- /.col -->
        <div class="col-lg-9 col-md-12">
          <div class="box direct-chat">
            <div class="box-header with-border bg-lighter">
              <h3 class="box-title">Chat Message</h3>
            </div>
			
			<div class="col-lg-12 col-md-12">
				<div id="chat-overlay" class="row"></div>
			</div>
			
			<div id="chat_box" class="chat_box pull-right" style="display: none">
				<!-- /.box-header -->
				<div class="box-body">
					<div class="panel-heading">
						<h3 class="panel-title"><span class="glyphicon glyphicon-comment"></span> Chat with <i class="chat-user"></i> </h3>
						<span class="glyphicon glyphicon-remove pull-right close-chat"></span>
					</div>
				  <!-- Conversations are loaded here -->
				  <div id="chat-app" class="direct-chat-messages chat-app">
					
					  
				  </div>
				  <!--/.direct-chat-messages-->
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
				  <!--<form action="#" method="post">-->
					<div class="input-group">
						<input type="hidden" name="name" id="name" value="Admin" maxlength="15" />
						<input type="text" name="message" id="message" placeholder="Type Message ..." class="form-control">
						  <div class="input-group-addon">
							<div class="align-self-end gap-items">
							  <span class="publisher-btn file-group">
								<i class="fa fa-paperclip file-browser"></i>
								<input type="file">
							  </span>
							  <!--<span class="publisher-btn" ><i class="fa fa-smile-o"></i></span>-->
							  <span class="publisher-btn" id="send-message" data-to-user=""><i class="fa fa-paper-plane"></i></span>
							</div>
						  </div>
					</div>
					<input type="hidden" id="to_user_id" value="" />
					
				  <!--</form>-->
				</div>
			</div>
            <!-- /.box-footer-->
			
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
	
<?php 
$colors = array('#007AFF','#FF7000','#FF7000','#15E25F','#CFC700','#CFC700','#CF1100','#CF00BE','#F00');
$color_pick = array_rand($colors);
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script language="javascript" type="text/javascript">  
	var msgBox = $('#chat-app');
	var wsUri = "ws://162.144.95.174:9000/testing/server.php"; 	
	websocket = new WebSocket(wsUri); 
	
	websocket.onopen = function(ev) { 
		msgBox.append('<div class="system_msg" style="color:#bbbbbb">Welcome to my "Sunny Chat Server"!</div>'); 
	}
	
	websocket.onmessage = function(ev) { 
		var response 		= JSON.parse(ev.data); 
		var res_type 		= response.type; 
		var user_message 	= response.message; 
		var user_name 		= response.name; 
		var user_color 		= response.color; 
		var time			= response.time; 
		var year 			= response.year; 
		var date 			= response.date; 

		switch(res_type){
			case 'usermsg':
				msgBox.append('<div class="direct-chat-msg right mb-30"><div class="clearfix mb-15"><span class="direct-chat-name pull-right" style="color:' + user_color + '">' + user_name + '</span><span class="direct-chat-timestamp pull-right">'+date+'</span></div><div class="direct-chat-text"><p>' + user_message + '</p><p class="direct-chat-timestamp"><time datetime="'+year+'">'+time+'</time></p></div></div>');
				break;
			case 'system':
				/*msgBox.append('<div style="color:#bbbbbb">' + user_message + '</div>');*/
				break;
		}
		msgBox[0].scrollTop = msgBox[0].scrollHeight; 

	};
	
	websocket.onerror	= function(ev){ msgBox.append('<div class="system_error">Error Occurred - ' + ev.data + '</div>'); }; 
	websocket.onclose 	= function(ev){ msgBox.append('<div class="system_msg">Connection Closed</div>'); }; 

	$('#send-message').click(function(){
		send_message();
	});
	
	$( "#message" ).on( "keydown", function( event ) {
	  if(event.which==13){
		  send_message();
	  }
	});
	
	function send_message(){
		var message_input = $('#message'); 
		var name_input = $('#name'); 
		
		if(message_input.val() == ""){ 
			alert("Enter your Name please!");
			return;
		}
		if(message_input.val() == ""){ 
			alert("Enter Some message Please!");
			return;
		}

		var sws_url="http://aptechbangalore.com/test/admin/";
		var dataString = 'message='+message_input.val()+'&to_user=5';

		$.ajax({
			type:"POST",
			url:sws_url+"send",
			data:dataString,
			success:function(data){
				
			},
			error:function(data){
				
			}
		});
		
		var msg = {
			message: message_input.val(),
			name: name_input.val(),
			color : '<?php echo $colors[$color_pick]; ?>'
		};
		
		websocket.send(JSON.stringify(msg));	
		message_input.val(''); 
	}
	
	
$(function () {
	
	// on close chat close the chat box but don't remove it from the dom
   $(".close-chat").on("click", function (e) {
       $(this).parents("div.chat-opened").removeClass("chat-opened").slideUp("fast");
   });

    // on click on any chat btn render the chat box
   $(".chat-toggle").on("click", function (e) {
       e.preventDefault();

       let ele = $(this);
       let user_id = ele.attr("data-id");
       let username = ele.attr("data-user");

       cloneChatBox(user_id, username, function () {
           let chatBox = $("#chat_box_" + user_id);

           if(!chatBox.hasClass("chat-opened")) {
               chatBox.addClass("chat-opened").slideDown("fast");
               loadLatestMessages(chatBox, user_id);

               chatBox.find(".chat-app").animate({scrollTop: chatBox.find(".chat-app").offset().top + chatBox.find(".chat-app").outerHeight(true)}, 800, 'swing');
           }
       });
   });
   
   
 });
 
function loaderHtml() {
    return '<i class="glyphicon glyphicon-refresh loader"></i>';
}

function cloneChatBox(user_id, username, callback)
{
    if($("#chat_box_" + user_id).length == 0) {

        let cloned = $("#chat_box").clone(true);
        // change cloned box id
        cloned.attr("id", "chat_box_" + user_id);
        cloned.find(".chat-user").text(username);
        cloned.find(".publisher-btn").attr("data-to-user", user_id);
        cloned.find("#to_user_id").val(user_id);
        $("#chat-overlay").append(cloned);
    }

    callback();
}

function loadLatestMessages(container, user_id)
{
    let chat_area = container.find(".chat-app");

    chat_area.html("");
	
	var sws_url="http://aptechbangalore.com/test/admin/";

    $.ajax({
        url: sws_url + "load-latest-messages",
        data: {user_id: user_id, _token: $("meta[name='csrf-token']").attr("content")},
        method: "GET",
        dataType: "json",
        beforeSend: function () {
            if(chat_area.find(".loader").length  == 0) {
                chat_area.html(loaderHtml());
            }
        },
        success: function (response) { alert(response);
            if(response.state == 1) {
                response.messages.map(function (val, index) {
                    $(val).appendTo(chat_area);
                });
            }
        },
        complete: function () {
            chat_area.find(".loader").remove();
        }
    });
}

</script>



@endsection
    

  
  

    
