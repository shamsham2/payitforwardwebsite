<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Authorization' );



/*
everything below here can be deleted / remade as your own endpoints
temporarily you could remove ->middleware('loggedIn'); middleware that does the token checking and run it without token , then add this in later 
*/





//** START test routes */
Route::get('/', function () {
    return 'try changing the url to have https, and not http :]';
});


Route::get('/test', function () {
    return 'test route is working';
});


Route::get('/health-check', 'adminEndpoints@healthCheck');
//** END test routes */


/** START Admin endpoints */
Route::get('/kiosk-admin-login', function(){ return view('login'); } );
Route::post('/kiosk-admin-login-do', 'adminEndpoints@doLogin' );
Route::get('/kiosk-admin-logout-do', 'adminEndpoints@doLogout' )->middleware('loggedIn');
Route::get('/kiosk-admin', function(){ return view('kiosk_admin'); } )->middleware('loggedIn');

Route::get('/admin-data-links', 'adminEndpoints@getAdminDataLinks')->middleware('apiToken');

Route::get('/get-pdf', 'adminEndpoints@getPdf')->middleware('apiToken');
Route::get('/download-csv', 'adminEndpoints@downloadCsv')->middleware('apiToken');
/** END Admin endpoints */



// MediaFact endpoints
Route::get('/contexts', "facts@getContexts")->middleware('crossOrigin');
Route::get('/facts/{context_id}', "facts@getFactsOfContext")->middleware('crossOrigin');


Route::get('/admin/contexts', "facts@getContexts")->middleware('crossOrigin');//->middleware('apiToken');
Route::get('/admin/facts/{context_id}', "facts@getFactsOfContext")->middleware('crossOrigin');//->middleware('apiToken');

Route::post('/admin/create-fact', "facts@createFact")->middleware('crossOrigin')->middleware('apiToken');
Route::post('/admin/update-fact', "facts@updatefact")->middleware('crossOrigin')->middleware('apiToken');
Route::post('/admin/delete-fact', "facts@deleteFact")->middleware('crossOrigin')->middleware('apiToken');
