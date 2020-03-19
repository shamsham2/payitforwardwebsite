<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Admin</title>
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	
    <link rel="stylesheet" href="js/semantic/dist/semantic.min.css">
    <link rel="stylesheet" href="css/app.css">
	<script src="js/jquery.js"></script> 
    <script src="js/semantic/dist/semantic.min.js"></script> 
	<meta name="viewport" content="width=device-width, maximum-scale=1, user-scalable=no" />
  
</head>
<body>






<div class="header_container">
  	
	  <div class="" style="float:left; padding:20px; width: 100%; font-size:large; color:white;">ADMIN</div>
	  
	  <div class="logo-login" >
			
	  </div>
	  
	</div>



  	<div class="login_container">

		<div class="ui segment" >

			<form class="ui form">
				<div class="field">
					<label>username</label>
					<input type="text" id="username" placeholder="username">
				</div>
				<div class="field">
					<label>password</label>
					<input type="password" id="password" placeholder="password">
				</div>
			</form>
			</br>
			<button class="ui button" onclick="submit()" >Sign In</button>

		</div>

  	</div>



<script>

function submit(){
	var username = $('#username').val();
	var password = $('#password').val();

	console.log('submitting');

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$.post("kiosk-admin-login-do",
		{
			username: username, password:password
		},
		function(data, status){
			if(data.status == 'fail'){alert('incorrect credentials');}
			if(data.status == 'success'){ window.location = "/kiosk-admin?token="+data.token; }
		});

}

</script>

</body>
</html>
