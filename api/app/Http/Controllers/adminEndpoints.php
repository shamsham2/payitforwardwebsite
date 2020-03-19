<?php

namespace App\Http\Controllers;

date_default_timezone_set('Europe/London');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class adminEndpoints extends Controller
{
   
    //**************************************************************** */
    public function healthCheck(Request $request){

        $check = DB::table('config')
        ->get();

        if(count($check)>0){return 'success';}else{ return 'fail';}
         
    }
    //**************************************************************** */

    //**************************************************************** */
    public function doLogin(Request $request){
        $api_username = config('services.api.username');
        $api_password = config('services.api.password');
        $username = $request->get('username');
        $password = $request->get('password');

        $user = DB::table('users')
        ->where('username', $username)
        ->where('password', $password)
        ->get();

        if(($username == $api_username  && $password == $api_password) || count($user) > 0 ){
            $token = Str::random(200);
            DB::table('user_sessions')
            ->insert(
                ['username' => $username, 
                'token' => $token,
                'created_at'=> Carbon::now(),
                'expires_at'=> Carbon::now()->addHours(12)
                ]
            );

            DB::table('users')
            ->where('id', $user[0]->id)
            ->update(
                [
                'current_token' => $token,
                'token_set_time'=> Carbon::now(),
                'token_expires_time'=> Carbon::now()->addHours(24)
                ]
            );
            
            return collect(["status" => 'success', "token" => $token]);
        }else{
            return collect(["status" => 'fail', "token" => 0]);
        }

        return DB::table('resource_links')->get();
    }
    //**************************************************************** */

    //**************************************************************** */
    public function doLogout(Request $request){

        $token = $request->get('token');

        DB::table('user_sessions')
            ->where('token',$token)
            ->update(
                ['expires_at'=> Carbon::now()]
            );

        return redirect('kiosk-admin-login'); 
         
    }
    //**************************************************************** */

    //**************************************************************** */
    public function getAdminDataLinks(Request $request){
        return DB::table('resource_links')->orderBy('id', 'desc')->limit(500)->get();
    }
    //**************************************************************** */

    //**************************************************************** */
    public function getAdminDataLinkHistory(Request $request){
        return DB::table('link_history')->orderBy('id', 'desc')->limit(500)->get();
    }
    //**************************************************************** */

    //**************************************************************** */
    public function getAdminDataConfig(Request $request){
        return DB::table('config')->get();
    }
    //**************************************************************** */

    //**************************************************************** */
    public function getAdminDataGlobalLinks(Request $request){
        return DB::table('global_allowed_links')->limit(1000)->get();
    }
    //**************************************************************** */

    //**************************************************************** */
    public function getAdminDataBlockedResources(Request $request){
        return DB::table('blocked_resources')->limit(1000)->get();
    }
    //**************************************************************** */

    //**************************************************************** */
    public function getAdminDataFrameBlockedResources(Request $request){
        return DB::table('resource_links')
        ->leftJoin('unframed_resources', function ($join) {
            $join->on('unframed_resources.link', 'like', 'resource_links.target_domain')
                ->where('unframed_resources.allow_by_domain', true);           
        })
        ->where('resource_links.x_frame_options', 'like',  '%url%' )->limit(1000)->get();
    }
    //**************************************************************** */

    //**************************************************************** */
    public function getAdminDataUnframedResources(Request $request){
        return DB::table('unframed_resources')->limit(1000)->get();
    }
    //**************************************************************** */

    //**************************************************************** */
    public function getAdminDataBrokenLinks(Request $request){
        return DB::table('resource_links')
        ->where('reported_problem', true)
        ->limit(500)
        ->get();
    }
    //**************************************************************** */
    
    //**************************************************************** */
    public function getAdminDataIframeBlocked(Request $request){
        return DB::table('resource_links')
        ->where('reported_problem', true)
        ->limit(500)
        ->get();
    }
    //**************************************************************** */

    //**************************************************************** */
    public function getAdminDataStats(Request $request){

        $month = DB::table('link_history')
            ->where('date_time', '>',  Carbon::now()->startOfMonth() )
            ->orderBy('id', 'desc')
            ->get();

        $week = DB::table('link_history')
            ->where('date_time', '>',  Carbon::now()->startOfWeek() )
            ->orderBy('id', 'desc')
            ->get();

        $today = DB::table('link_history')
            ->where('date_time', '>',  Carbon::now()->startOfDay() )
            ->orderBy('id', 'desc')
            ->get();

        $frame_blocked = DB::table('resource_links')
        ->where('x_frame_options', 'like',  '%url%' )
        ->orderBy('id', 'desc')
        ->get();

        $user_reported = DB::table('resource_links')
        ->where('reported_problem',   true )
        ->orderBy('id', 'desc')
        ->get();

        $hidden = DB::table('resource_links')
        ->where('is_displayable',   false )
        ->orderBy('id', 'desc')
        ->get();    

        $saved = DB::table('resource_links')->count();
       
       
        $stats = collect(["month"=>count($month),
        "week"=>count($week),
        "today"=>count($today), 
        "savedLinks" => $saved, 
        "frame_blocked"=>count($frame_blocked),
        "user_reported"=>count($user_reported),
        "hidden"=>count($hidden),
         ]);
       
        return $stats;

    }
    //**************************************************************** */

    //**************************************************************** */
    public function getAdminDataConfigUpdate(Request $request){
        
        $data = $request->get('data');
        //validate data 

        foreach($data as $record){
            DB::table('config')
            ->where('id',$record['id'])
            ->update([
                "name"=>$record['name'],
                "value"=>$record['value']
            ]);
        }

        return 1;

    }
    //**************************************************************** */

    //**************************************************************** */
    public function searchForLink(Request $request){
    
        $target_url = $request->get('target_url');
       
       //echo urlencode(strtolower($target_url));

        $links = DB::table('resource_links')
        ->where('target_url', 'like', '%'.$target_url.'%')
        ->get();
        
        if(count($links)>0){
            return collect(["status" => 'success', "data" => $links[0], "message" => 'that worked']);
            return $links;
        }else{ 
            return collect(["status" => 'fail', "data" => [], "message" => 'that did not work']);
        }

    }
    //**************************************************************** */

    //**************************************************************** */
    public function addLink(Request $request){
    
        $target_url = $request->get('target_url');
        $dependancy_url = $request->get('dependancy_url');
        //validate data 

        //echo urlencode(strtolower($target_url));

        $links = DB::table('resource_links')
        ->where('target_url', $target_url)
        ->get();
        
        
        if(count($links)){
           
            DB::table('resource_links')
            ->where('target_url', $target_url)
            ->update([
                "dependancies_manual"=>json_encode(collect(json_decode($links[0]->dependancies_manual))->push($dependancy_url)) ,
                "updated_at"=>'1900-01-01 00:00:00' // means that link will expire and will be reresolved
            ]);

            $this->removeReportBrokenPageFlag($target_url);  

            return collect(["status" => 'success', "data" => [], "message" => 'that worked']);

        }else{ 

            return collect(["status" => 'fail', "data" => [], "message" => 'that did not work']);

        }
        

    }
    //**************************************************************** */

    //**************************************************************** */
    public function addGlobalLink(Request $request){
        
        $link = $request->get('link');
        $domain = $this->stripToDomainOnly($link);


        $global = DB::table('global_allowed_links')
        ->where('link', $link)
        ->get();
        
        
        if(count($global) < 1){
        
            DB::table('global_allowed_links')
            ->insert([
                "link"=>$link ,
                "domain"=>$domain,
                "created_at"=>Carbon::now()
            ]);

            return collect(["status" => 'success', "data" => [], "message" => 'the link was added']);

        }else{ 

            return collect(["status" => 'fail', "data" => [], "message" => 'this link is already present']);

        }
        

    }
    //**************************************************************** */

    //**************************************************************** */
    public function removeGlobalLink(Request $request){

        $link = $request->get('link');

        $global = DB::table('global_allowed_links')
        ->where('link', $link)
        ->get();
        
        
        if(count($global) > 0){
            
            DB::table('global_allowed_links')
            ->where('link', $link)
            ->delete();

            return collect(["status" => 'success', "data" => [], "message" => 'the link was removed']);

        }else{ 

            return collect(["status" => 'fail', "data" => [], "message" => 'could not find link to remove it']);

        }
        

    }
    //**************************************************************** */

    //**************************************************************** */
    public function addBlockedlResource(Request $request){
    
        $link = $request->get('link');
        $notes = $request->get('notes')? $request->get('notes') : '';
        $block_by_domain = $request->get('block_by_domain');


        $blocked = DB::table('blocked_resources')
        ->where('link', $link)
        ->get();
        
        
        if(count($blocked) < 1){
           
            DB::table('blocked_resources')
            ->insert([
                "link"=>$link ,
                "notes"=>$notes,
                "block_by_domain"=>$block_by_domain,
                "created_at"=>Carbon::now()
            ]);
 
            return collect(["status" => 'success', "data" => [], "message" => 'the link was added']);

        }else{ 

            return collect(["status" => 'fail', "data" => [], "message" => 'this link is already present']);

        }
        

    }
    //**************************************************************** */

    //**************************************************************** */
    public function removeBlockedResource(Request $request){

        $link = $request->get('link');

        $global = DB::table('blocked_resources')
        ->where('link', $link)
        ->get();
        
        
        if(count($global) > 0){
            
            DB::table('blocked_resources')
            ->where('link', $link)
            ->delete();
    
            return collect(["status" => 'success', "data" => [], "message" => 'the link was removed']);

        }else{ 

            return collect(["status" => 'fail', "data" => [], "message" => 'could not find link to remove it']);

        }
        

    }
    //**************************************************************** */

    //**************************************************************** */
    public function addUnframedResource(Request $request){
    
        $link = $request->get('link');
        $notes = $request->get('notes')? $request->get('notes') : '';
        $allow_by_domain = $request->get('allow_by_domain');


        $blocked = DB::table('unframed_resources')
        ->where('link', $link)
        ->get();
        
        
        if(count($blocked) < 1){
           
            DB::table('unframed_resources')
            ->insert([
                "link"=>$link ,
                "notes"=>$notes,
                "allow_by_domain"=>$allow_by_domain,
                "created_at"=>Carbon::now()
            ]);
 
            return collect(["status" => 'success', "data" => [], "message" => 'the link was added']);

        }else{ 

            return collect(["status" => 'fail', "data" => [], "message" => 'this link is already present']);

        }
        

    }
    //**************************************************************** */

    //**************************************************************** */
    public function removeUnframedResource(Request $request){

        $link = $request->get('link');

        $global = DB::table('unframed_resources')
        ->where('link', $link)
        ->get();
        
        
        if(count($global) > 0){
            
            DB::table('unframed_resources')
            ->where('link', $link)
            ->delete();
    
            return collect(["status" => 'success', "data" => [], "message" => 'the link was removed']);

        }else{ 

            return collect(["status" => 'fail', "data" => [], "message" => 'could not find link to remove it']);

        }
        

    }
    //**************************************************************** */

    //**************************************************************** */
    public function setIsDisplayable(Request $request){


        $is_displayable = $request->get('is_displayable');
        $target_url = $request->get('target_url');
        
        $links = DB::table('resource_links')
        ->where('target_url', $target_url)
        ->get();
        
        
        if(count($links)){
            
            DB::table('resource_links')
            ->where('target_url', $target_url)
            ->update([
                "is_displayable"=> $is_displayable
            ]);

            return collect(["status" => 'success', "data" => [], "message" => 'that worked']);

        }else{ 

            return collect(["status" => 'fail', "data" => [], "message" => 'that did not work']);

        }
        
    }
    //**************************************************************** */  

    //**************************************************************** */
    public function setReportedProblem(Request $request){


        $value = $request->get('value');
        $target_url = $request->get('target_url');
        
        $links = DB::table('resource_links')
        ->where('target_url', $target_url)
        ->get();
        
        
        if(count($links)){
            
            DB::table('resource_links')
            ->where('target_url', $target_url)
            ->update([
                "reported_problem"=> $value
            ]);

            return collect(["status" => 'success', "data" => [], "message" => 'that worked']);

        }else{ 

            return collect(["status" => 'fail', "data" => [], "message" => 'that did not work']);

        }
        
    }
    //**************************************************************** */  

    //**************************************************************** */
    public function setLinkNotes(Request $request){


        $notes = $request->get('notes');
        $target_url = $request->get('target_url');
        
        $links = DB::table('resource_links')
        ->where('target_url', $target_url)
        ->get();
        
        
        if(count($links)){
            
            DB::table('resource_links')
            ->where('target_url', $target_url)
            ->update([
                "notes"=> $notes
            ]);

            return collect(["status" => 'success', "data" => [], "message" => 'that worked']);

        }else{ 

            return collect(["status" => 'fail', "data" => [], "message" => 'that did not work']);

        }
        
    }
    //**************************************************************** */  
    
    //**************************************************************** */
    public function reportBrokenPage(Request $request){
        $target_url = $request->get('target_url');

        $link = DB::table('resource_links')
        ->where('target_url', $target_url)
        ->first();
        
        if($link){
            if($link->reported_problem){return 'thank you for reporting this page';}
            else{
                $link = DB::table('resource_links')
                ->where('target_url', $target_url)
                ->update([
                    'reported_problem' => true
                ]); 
            }
        }

        return "thank you for reporting this page";
    }
    //**************************************************************** */  

    //**************************************************************** */
    public function removeReportBrokenPageFlag($target_url){

        $link = DB::table('resource_links')
        ->where('target_url', $target_url)
        ->first();
        
        if($link){
            if(!$link->reported_problem){ return false; }
            else{
                $link = DB::table('resource_links')
                ->where('target_url', $target_url)
                ->update([
                    'reported_problem' => false
                ]); 
            }
        }

        return true;
    }
    //**************************************************************** */  

    //**************************************************************** */
    public function clearOldData(){

        $data = DB::table('link_history')
        ->where('date_time','<', Carbon::now()->subMonths(4) )
        ->delete();
        
        $data = DB::table('user_sessions')
        ->where('created_at','<', Carbon::now()->subMonths(4) )
        ->delete();


        return true;

    }
    //**************************************************************** */ 

    //**************************************************************** */ 
    public function getPdf(Request $request) {
        $filename = $request->get('file_name');
        //return var_dump( storage_path('uploads/'.$filename) );

       // return Storage::disk('local')->get('/uploads/'.$filename);
       return Storage::download('/uploads/'.$filename);

        //return Storage::get('/uploads/'.$filename);
    }
    //**************************************************************** */ 

    //**************************************************************** */ 
    public function downloadCsv(Request $request) {
        
        $report_name = $request->get('report_name');

        if($report_name == 'all_links'){
            $data = DB::table('resource_links')->get();
            return  $this->makeCsv('resource_links', $data, 'all_links_'.date("Y-m-d_H-i-s").'.csv');
        }

        if($report_name == 'history_month'){
            $data = DB::table('link_history')
            ->where('date_time', '>',  Carbon::now()->startOfMonth() )
            ->orderBy('id', 'desc')
            ->get();
            return  $this->makeCsv('link_history', $data, 'user_link_visit_history_month_'.date("Y-m-d_H-i-s").'.csv');
        }

        if($report_name == 'frame_blocked'){
            $data = DB::table('resource_links')
            ->where('x_frame_options', 'like', '%url%' )
            ->orderBy('id', 'desc')
            ->get();
            return  $this->makeCsv('resource_links', $data, 'frame_blocked_links_'.date("Y-m-d_H-i-s").'.csv');
        }

        if($report_name == 'user_reported'){
            $data = DB::table('resource_links')
            ->where('reported_problem', true )
            ->orderBy('id', 'desc')
            ->get();
            return  $this->makeCsv('resource_links', $data, 'user_reported_links_'.date("Y-m-d_H-i-s").'.csv');
        }


        if($report_name == 'hidden_from_user'){
            $data = DB::table('resource_links')
            ->where('is_displayable', false )
            ->orderBy('id', 'desc')
            ->get();
            return  $this->makeCsv('resource_links', $data, 'links_hidden_from_user'.date("Y-m-d_H-i-s").'.csv');
        }


        if($report_name == 'blocked_resources'){
            $data = DB::table('blocked_resources')
            ->orderBy('id', 'desc')
            ->get();
            return  $this->makeCsv('blocked_resources', $data, 'blocked_resources_'.date("Y-m-d_H-i-s").'.csv');
        }
        
    }
    //**************************************************************** */ 

    //*********************************************  
    public function makeCsv($table_name, $results, $filename){
        
        $fieldnames = Schema::getColumnListing($table_name);

        $data = array();
        $temparray = array();
        
        $inner=0;
        foreach($fieldnames as $cell){
            $temparray[$inner] = $cell;
            $inner ++ ;             
        }    
        $data[0] = $temparray;   
     
      
        $outer = 1;
        foreach($results as $row){
            $inner = 0;
            $temparray = array();
               foreach($row as $cell){
                  $temparray[$inner] = $cell;
                  $inner ++ ;             
                }
             $data[$outer] = $temparray; 
             $outer ++;                  
        }
     
     
       $this->array_to_csv_download($data, $filename, ","); 
    }
    //*********************************************
    
    //*********************************************   
    public function array_to_csv_download($array, $filename , $delimiter) {
        // open raw memory as file so no temp files needed, you might run out of memory though
        $f = fopen('php://memory', 'w'); 
        // loop over the input array
        foreach ($array as $line) { 
            // generate csv lines from the inner arrays
            fputcsv($f, $line, $delimiter); 
        }
        // reset the file pointer to the start of the file
        fseek($f, 0);
        // tell the browser it's going to be a csv file
        header('Content-Type: application/csv');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachment; filename="'.$filename.'";');
        // make php send the generated csv lines to the browser
        fpassthru($f);
    }
    //*********************************************


    //****  helpers  *****/


    //**************************************************** */
    public function stripToDomainOnly($url){
        $domain = parse_url($url, PHP_URL_HOST);
        $domain = str_replace('www.','',$domain);
        preg_match('#[^\.]+[\.]{1}[^\.]+$#', $domain , $matches); 
        if(count($matches) >0){$domain = $matches[0]; }
        return $domain;
    }
    //**************************************************** */




}
