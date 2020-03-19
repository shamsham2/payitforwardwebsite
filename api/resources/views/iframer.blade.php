<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<head>
    <meta charset="utf-8">
    <title>WalkIns</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="api-root-url" content="{{$api_url}}" />
    
    <meta 
    http-equiv="Content-Security-Policy" 
    content="  
        default-src {{$api_url}} 
        default-src *.{{$target_domain}} 
        default-src {{$target_domain}} 
        default-src {{$target_url}}  
        {{$allowed_links}}  
        
        @if($allowed_links_manual) 
        {{$allowed_links_manual}} 
        @endif  

        @if($allowed_links_redirect) 
        {{$allowed_links_redirect}} 
        @endif  
        
        @if($global_links) 
        {{$global_links}} 
        @endif 
        default-src  'unsafe-inline'
        default-src 'self'
     "
    >


    
    <!-- <script type = "text/javascript" src = "js/stacktrace.js-2.0.0/dist/stacktrace.min.js" ></script> -->
    <script type = "text/javascript" src = "js/jquery.js" ></script> 
    <script type = "text/javascript" src = "js/custom.js" ></script>
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="js/semantic/dist/semantic.min.css">
    <link rel="stylesheet" href="css/custom.css">

  
</head>


<body style="margin:0px;">
 
    <div id="api_root_url" data="{{$api_url}}">


     <!--    
    <iframe   id="iframe_id" width="100%" height="1000px" src="{{$target_url}}"  target="_self"  sandbox="allow-forms allow-scripts  allow-same-origin  allow-pointer-lock"  frameborder="0"  ></iframe>   
    -->
    <!-- things not being used  == scrolling="no"  == sandbox: allow-top-navigation allow-popups -->



    <div class="under_iframe" >
        <div style="color:#a578d4; font-size:22px;" >Loading Page</div>
    </div>





    <iframe  onload="frameLoaded()" id="iframe_id" src="{{$target_url}}" frameborder="0"  height="100%" width="100%" target="_self"  sandbox="allow-forms allow-scripts  allow-same-origin  allow-pointer-lock"   ></iframe>
  


    <div class="report_broken_outer" > 
        <div class="float_block_right" >  <div class="ui button basic white" style="color:white;" onclick="reportBrokenPage()" >report page not loading correctly</div>   </div>    
    </div>



</body>
</html>
