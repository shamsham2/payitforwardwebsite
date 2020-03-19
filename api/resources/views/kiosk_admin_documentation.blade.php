<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Documentation</title>

    <link rel="stylesheet" href="js/semantic/dist/semantic.min.css">
    <link rel="stylesheet" href="css/app.css">
    <script type = "text/javascript" src = "js/app.js" ></script> 
    <script src="js/semantic/dist/semantic.min.js"></script>
  
</head>
<body>

<div id='app'>
<div  api-url="http://test-api.librarykiosks.is.ed.ac.uk" ></div>

@verbatim



	<div class="header_container">
		
		<div class="" style="float:left; padding:20px; width: 100%; font-size:large; color:white;">Documentation</div>
		
		<div class="logo" >
			<img src="css/walkins logo.svg" alt=""> 
			<!-- <div class="ui basic grey button" style="float:right; margin-top:12px; color:white;" v-on:click="logOut()">Sign Out</div> -->
		</div>
		
	</div>


<div class="main_container">
<div class="ui segment" style="padding:0px;">
	



@endverbatim



<embed src="{{ url('/get-pdf?file_name=documentation.pdf') }}" width="100%" height="1000px" zoom="100%" type="application/pdf">












</div>
</div>


<script>


</script>
</body>
</html>
