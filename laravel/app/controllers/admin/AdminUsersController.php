<?php

class AdminUsersController extends BaseController {


    public function getUsers($client_id, $dealer_id){
        
        $title = "User-Administration";

        $av_admin = Session::get('av_admin');

        $uri = '';

        return View::make('admin.users.index',compact('title','av_admin','uri','client_id','dealer_id'));
    }

    public function getUser($client_id, $dealer_id, $user_id){
        
        $title = "User-Administration";

        $av_admin = Session::get('av_admin');

        $uri = '/'.$user_id;

        return View::make('admin.users.index',compact('title','av_admin','uri','client_id','dealer_id'));
    }


    public function getData($client_id, $dealer_id){

    	//return 'hello';

        $args = func_get_args();
        if(!empty($args[2])){
            $users = AdminUser::where('ClientId','=',$client_id)
            ->where('DealerId','=',$dealer_id)
            ->where('UserId','=',$args[2])
            ->select(array('UserId','UFirstName','ULastName','UEmail','TotalSessions','UserTypeName'));
        }
        else{
            $users = AdminUser::where('ClientId','=',$client_id)
            ->where('DealerId','=',$dealer_id)
            ->select(array('UserId','UFirstName','ULastName','UEmail','TotalSessions','UserTypeName'));
        }   
        
        return Datatables::of($users)
        ->make();
        
    }


}