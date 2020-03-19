<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>WalkIns Kiosk</title>
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	
    <link rel="stylesheet" href="js/semantic/dist/semantic.min.css">
    <link rel="stylesheet" href="css/app.css">
	<script src="js/jquery.js"></script> 
    <script src="js/semantic/dist/semantic.min.js"></script> 
  
</head>
<body>






<div class="header_container">
  	
	  <div class="" style="float:left; padding:20px; width: 100%; font-size:large; color:white;">{{$error_code}}</div>
	  
	  <div class="logo-login" >
			<img src="css/walkins logo.svg" alt=""> 
	  </div>
	  
	</div>



  	<div class="login_container">

		<div class="ui segment" >

            <div class="ui header center aligned " >
              {{$message}}
            </div>   

		</div>

  	</div>





</body>
</html>
