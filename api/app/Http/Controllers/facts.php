<?php
namespace App\Http\Controllers;

date_default_timezone_set('Europe/London');
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class facts extends Controller
{


    /**********************************************************************************************/
    public function getContexts(Request $request){  
        header("Access-Control-Allow-Origin: *");    
        return  DB::table('contexts')
            ->orderBy('title', 'asc')
            ->get();
    }
    /**********************************************************************************************/

    /**********************************************************************************************/
    public function getFactsOfContext(Request $request){  
        header("Access-Control-Allow-Origin: *");  
 
        return  DB::table('facts')
        ->where('context_id', $request->context_id)
        ->orderBy('title', 'asc')
        ->get();
    }
    /**********************************************************************************************/
    

    /**********************************************************************************************/
    public function updateFact(Request $request){  
        header("Access-Control-Allow-Origin: *");  

        $user = $this->getUserOfToken($request->token)[0];

        if($user->access_level <= 2 ){
            // means they have access to do this even if they did not create it 
            $worked =   DB::table('facts')
            ->where('id', $request->id)
            ->update([
                "title" => $request->title,
                "key_points" => $request->key_points,
                "summary" => $request->summary,
                "url" => $request->url,
                "tags" => $request->tags,
                "media_type" => $request->media_type,
                "updated_at" => Carbon::now(),
                "updated_by_id" => $this->getUserIDOfToken($request->token),
            ]);

            return collect(["status" => 'success', "message"=>"success"]);

        }else{
            // means they have access to do this even if they did not create it 
                $worked =   DB::table('facts')
                    ->where('id', $request->id)
                    ->where('updated_by_id', $user->id)
                    ->update([
                        "title" => $request->title,
                        "key_points" => $request->key_points,
                        "summary" => $request->summary,
                        "url" => $request->url,
                        "tags" => $request->tags,
                        "media_type" => $request->media_type,
                        "updated_at" => Carbon::now(),
                        "updated_by_id" => $this->getUserIDOfToken($request->token),
                    ]);

            if($worked){
                return collect(["status" => 'success', "message"=>"success"]);
            }else{
                return collect(["status" => 'fail', 'message'=>'you can only update facts you have created']);   
            }
          
        }

    }
    /**********************************************************************************************/

    /**********************************************************************************************/
    public function createFact(Request $request){  
        header("Access-Control-Allow-Origin: *");  

        $worked =  DB::table('facts')
        ->insert([
            "context_id" => $request->category_id,
            "title" => $request->title,
            "key_points" => $request->key_points,
            "summary" => $request->summary,
            "url" => $request->url,
            "tags" => $request->tags,
            "media_type" => $request->media_type,
            "updated_at" => Carbon::now(),
            "updated_by_id" => $this->getUserIDOfToken($request->token),
        ]);

        return collect(["status" => 'success']);
    }
    /**********************************************************************************************/

    /**********************************************************************************************/
    public function deleteFact(Request $request){  
        header("Access-Control-Allow-Origin: *");  
        $user = $this->getUserOfToken($request->token)[0];

        if($user->access_level <= 2 ){
            // means they have access to do this even if they did not create it 
            $worked =  DB::table('facts')
                    ->where('id', $request->id)
                    ->delete();

            return collect(["status" => 'success', "message"=>"success"]);
        }else{
            // means they have access to do this even if they did not create it 
            $worked =  DB::table('facts')
                    ->where('id', $request->id)
                    ->where('updated_by_id', $user->id)
                    ->delete();
            if($worked){
                return collect(["status" => 'success', "message"=>"success"]);
            }else{
                return collect(["status" => 'fail', 'message'=>'you can only delete facts you have created']);   
            }
          
        }

    }
    /**********************************************************************************************/


    /**********************************************************************************************/
    public function getUserIDOfToken($token){  

        $user =  DB::table('users')
        ->where('current_token', $token)
        ->get();

        if(count($user)>0){
            return $user[0]->id;
        }else{
            return 0;
        }

    }
    /**********************************************************************************************/

    /**********************************************************************************************/
    public function getUserOfToken($token){  

        $user =  DB::table('users')
        ->where('current_token', $token)
        ->get();

        if(count($user)>0){
            return $user;
        }else{
            return [];
        }

    }
    /**********************************************************************************************/


    /**********************************************************************************************/
    public function hasAccess($token){  
        $user = $this->getUserOfToken($token);
        if($user){

        }else{
            return false;
        }
    }
    /**********************************************************************************************/

}
