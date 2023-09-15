<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Redliips</title>

</head>

<body style="margin: 0; background: #e0e3de;">

<table style=" width: 70%; margin: 40px auto; font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif;">        
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" style="padding: 15px; background: #fff; width:100%; margin: -5px auto;">
                    <tr bgcolor="#231f20">
						<td style="text-align: center; padding: 5px 0;">
							<a href="{{url('/')}}"><img width="200px;" alt="Redliips" src="{{ asset('public/fronted/images/logo.jpg') }}"></a>
						</td>
					</tr>
                    
                    <tr>
                        <td>
                            <table cellpadding="0" cellspacing="0" style="width: 100%; background: #fff;">
                               
                                <tr>
                                    <td style="padding: 10px 0px;">
                                        <table cellpadding="0" cellspacing="0" style="width: 100%; text-align: left; font-size: 17px; line-height: 22px; padding: 20px;">
											<tr>
												<td>
													<h1 style="margin: 10px 0 10px; font-size: 24px; letter-spacing: 1px; word-spacing: 2px; text-align: center; text-transform:uppercase;">Welcome!</h1>
												</td>
											</tr>
											<tr>
												<td>
												     {!!$data['message']!!}
													<p style="margin: 15px 0 0;">Have a Good day.</p>
													<p style="margin: 0;"><strong><a href="https://www.redliips.com/">Redliips.com</a></strong></p>
													
												</td>
											</tr>											
                                        </table>                                                                            
                                    </td>
                                </tr>								
                            </table>
                        </td>
                    </tr>					
					<tr bgcolor="#231f20">
						<td>
							<ul style="display:inline-block; width:100%; ">
								
								<li align="left" style="color: #ee1d23; font-size: 18px; float: left;  margin-right:38px;"><a style="font-size: 20px; color: #fff; text-decoration: none;" href="{{route('page_url',['about_us'])}}" target="_blank">ABOUT US</a></li>
								<li align="left" style="color: #ee1d23; font-size: 18px; float: left; "><a style="font-size: 20px; color: #fff; text-decoration: none;" href="{{route('page_url',['terms'])}}" target="_blank">TEARM & CONDITIONS</a></li>							
							</ul>							
						</td>
					</tr>
					<tr>
						<td><p style="font-size:18px; line-height:24px;">E-mail us: <a style="color:#000;" href="mailto:connect@redliips.com">connect@Redliips.com</a></p>
						<p style="text-align:center; font-size:18px; line-height:24px; margin: 0;">Follow us: <span style="vertical-align: middle; display: inline-block;
margin-left: 10px;">
							<a href="https://twitter.com/Redlipsofficial" target="_blank"><img title="Twitter" width="30px;" src="{{ asset('public/fronted/images/twitter-icon.png') }}"></a> 
							<a href="https://www.snapchat.com/add/Redlipsofficial" target="_blank"><img title="Snapchat" width="30px;" src="{{ asset('public/fronted/images/snapchat-icon.png') }}"></a> 
							<a href="https://www.facebook.com/Redlipsofficial/" target="_blank"><img title="Facebook" width="30px;" src="{{ asset('public/fronted/images/facebook-icon.png') }}"></a> 
                            <a href="https://www.instagram.com/Redlipsofficial/?hl=en" target="_blank"><img title="Instagram" width="30px;" src="{{ asset('public/fronted/images/instagram-icon.png') }}"></a></span></p>
						</td>
					</tr>					
                </table>                
            </td>               
        </tr>             
    </table>




	

</body>
</html>

