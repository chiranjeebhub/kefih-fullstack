@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')  

<style>
.close-chat{ top: 30px; position: absolute; right: 0px; font-weight: 300; }
.chat_box:nth-child(even) { border: 1px solid #cccccc1c; background: #fafafa66; margin-top:10px; margin-left:25px; }
.chat_box:nth-child(odd) { border: 1px solid #cccccc1c; background: #dde6f217; margin-top:10px; margin-left:25px; }
.chat-toggle{float:right;}
.box.direct-chat .box-header{ margin-top: 0px; background:#fff; }

.chat-app {
    height: 400px;
    overflow-y: scroll;
}

.loader {
    -webkit-animation: spin 1000ms infinite linear;
    animation: spin 1000ms infinite linear;
}
@-webkit-keyframes spin {
    0% {
        -webkit-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(359deg);
        transform: rotate(359deg);
    }
}
</style>

  <!-- Main content -->
    <section class="content">

      <div class="row">
			
        <div class="col-lg-3 col-md-12">
			<div class="box">
				<div class="box-header with-border p-0">
					<h5 style="text-align: center;">
						<?php
							if(Auth::guard('vendor')->check()){
								
							}else{
								echo 'Vendor';
							}
						?>
						
					</h5>
				</div>
				<div class="box-body p-0">
				  <div id="chat-contact" class="media-list media-list-hover media-list-divided ">
					<?php 
						
						$names = array('Admin Support','Bindu','Sushmitha');
						for($i=0; $i<count($customer_list); $i++){
							
							$msg_count1='';
							$customer_msg='';
							$customer_msg=(object)array('user_id'=>$customer_list[$i]['id']);
							$msg_count1= App\Messages::adminCountMessages($customer_msg);
							
							if(Auth::guard('vendor')->check()){
								$name=$names[rand (0,count($names)-1)];
								$na='Admin';
							}else{
								$name=$customer_list[$i]['name'];
								$na=$name;
							}
					?>
					<div class="media media-single">
					  <!--<a href="#">
						<img class="avatar avatar-xl" src="../../../images/avatar/2.jpg" alt="...">
					  </a>-->

					  <div class="media-body">
						<h6>
							<a href="#"><?php echo ucwords($na);?></a>
							<a href="javascript:void(0);" class="chat-toggle" data-id="{{ $customer_list[$i]['id'] }}" data-user="{{ $name }}"><?php echo $msg_count1;?> <span class="glyphicon glyphicon-comment"></span></a>
						</h6>
						
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
            <div class="box-header with-border">
              <h3 class="box-title">Chat Message</h3>
            </div>
			
			<div class="col-lg-12 col-md-12">
				<div id="chat-overlay" class="row"></div>
			</div>
			
			<div class="sample">
				<div id="chat_box" class="chat_box pull-right" style="display: none">
					<!-- /.box-header -->
					<div class="box-body">
						<div class="panel-heading">
							<h3 class="panel-title"><span class="glyphicon glyphicon-comment"></span> Chat with <i class="chat-user"></i> </h3>
							<span class="glyphicon glyphicon-remove pull-right close-chat" close-to-user=""></span>
						</div>
					  <!-- Conversations are loaded here -->
					  <div id="chat-app" class="direct-chat-messages chat-app">
						
						  
					  </div>
					  <!--/.direct-chat-messages-->
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
					  <div class="input-group">
							<?php if(Auth::guard('vendor')->check()){ $name= 'Vendor'; }else{ $name='Admin'; } ?>
							<input type="hidden" name="name" id="name" value="<?php echo $name;?>" maxlength="15" />
							<input type="text" name="message" id="message" placeholder="Type Message ..." class="form-control chat_input">
							  <div class="input-group-addon">
								<div class="align-self-end gap-items">
								  <!--<span class="publisher-btn file-group">
									<i class="fa fa-paperclip file-browser"></i>
									<input type="file">
								  </span>
								  <span class="publisher-btn" ><i class="fa fa-smile-o"></i></span>-->
								  <span class="publisher-btn" id="send-message" data-to-user=""><i class="fa fa-paper-plane"></i></span>
								</div>
							  </div>
						</div>
						<input type="hidden" id="to_user_id" value="" />
					</div>
				</div>
            <!-- /.box-footer-->
			</div>
			
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
	var sws_url="<?php echo URL::to('/');?>/admin/";
	var wsUri = "ws://162.144.95.174:9000/testing/server.php"; 	
	websocket = new WebSocket(wsUri); 
</script>
<script src="{{ asset('public/js/sws_chat.js') }}"></script>
<script>
function send(to_user, message, name)
{
    let chat_box = $("#chat_box_" + to_user);
    let chat_area = chat_box.find(".chat-app");
	
    $.ajax({
        url: sws_url + "send",
        data: {to_user: to_user,name: name, message: message, _token: $("meta[name='csrf-token']").attr("content")},
        method: "POST",
        dataType: "json",
        beforeSend: function () {
            if(chat_area.find(".loader").length  == 0) {
                chat_area.append(loaderHtml());
            }
        },
        success: function (response) { 
			 
			var msg = {
				sws_self:'true',
				
			};
			
			var msg1 = {
				message: $('#message').val(),
				name: $('#name').val(),
				sws_self:'false',
				color : '<?php echo $colors[$color_pick]; ?>'
			};
			
			/*websocket.send(JSON.stringify(msg));*/
			websocket.send(JSON.stringify(msg1));
        },
        complete: function () {
            chat_area.find(".loader").remove();
			chat_box.find(".chat_input").val("");
            chat_area.animate({scrollTop: $(document).height()+chat_area.outerHeight(true) }, 800, 'swing');
        }
    });
}
</script>

<!-- The core Firebase JS SDK is always required and must be listed first -->
<!--<script src="https://www.gstatic.com/firebasejs/7.5.2/firebase-app.js"></script>-->

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->

<script>
  // Your web app's Firebase configuration
  /*var firebaseConfig = {
    apiKey: "AIzaSyBNr2Yi-ydo8OKICsH6ktA5HoiOuihKRXg",
    authDomain: "upin-2e245.firebaseapp.com",
    databaseURL: "https://upin-2e245.firebaseio.com",
    projectId: "upin-2e245",
    storageBucket: "upin-2e245.appspot.com",
    messagingSenderId: "171697667236",
    appId: "1:171697667236:web:412292724f8c37a7cd7a31"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  
	  // Retrieve Firebase Messaging object.
	const messaging = firebase.messaging();
	messaging.requestPermission().then(function() {
	  console.log('Notification permission granted.');
	  // TODO(developer): Retrieve an Instance ID token for use with FCM.
	  // ...
	}).catch(function(err) {
	  console.log('Unable to get permission to notify.', err);
	});*/

</script>

@endsection
    

  
  

    
