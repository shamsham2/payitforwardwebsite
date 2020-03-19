

$(document).ready(function(){  
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    


   /* 
    var theUrl = "http://oxfordreference.com/view/10.1093/acref/9780191826726.001.0001/acref-9780191826726";//$('#iframe_id').attr('src');
    console.log("tring to hit page with ajax === "+theUrl);

    $.ajax({
        type: "GET",
        url: theUrl,
        success: function (dt, status, request) {
            console.log(request.getAllResponseHeaders());
        },
        error: function (jqXHR, status) {console.log('cant hit page with ajax');}
    });
*/
    
 });


window.onerror = function (errorMsg, url, lineNumber, column, errorObj) {
    
    
    // $.post('//your.domain/client-logs', function () {
        console.log(
        errorMsg
            , url
            , lineNumber
            , column
            , errorObj
        );
    // });

    // Tell browser to run its own error handler as well
    return false;
};


function reportBrokenPage(){
    
    var theUrl = $('#iframe_id').attr('src');
    var apiUrl = $('meta[name="api-root-url"]').attr('content');

    $.ajax({
        url: apiUrl+'/report-broken-page',
        type: 'post',
        data: {target_url:theUrl },
        success: function( data, textStatus, jQxhr ){
            alert(data);
        },
        error: function( jqXhr, textStatus, errorThrown ){
            alert('thank you for informing us');
        }
    });


}



function frameLoaded(){
    //var html = $('#iframe_id');
   // console.log(html.contents().find('html').html() );
}

