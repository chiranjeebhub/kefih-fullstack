<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Redliips</title>

</head>

<body>

	<div style="width:550px; border:solid 1px #999; margin-top:20px;">
	   	<table cellpadding="0" cellspacing="0" style="width:100%; font-family:Arial, Helvetica, sans-serif; font-size:13px; font-weight:normal; line-height:18px;">
        	<tr>
            	<td colspan="2" style="border-bottom:solid 1px #999;">
            	    <a href="{{url('/')}}"><img width="200px;" alt="Redlips" src="{{ asset('public/fronted/images/logo.jpg') }}"></a>
                </td>
            </tr>
            <tr>
                <td>
                    {!!$data['message']!!}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;">
                    <p>Thank you <br />Warm Regards</p> <p>Team <br /><strong>Redliips</strong></p>
                    <p style="text-transform:uppercase;">This is a self Generated Mail. Please Do Note Reply To it </p>
                </td>
            </tr>
        </table> 
    </div>

</body>
</html>
