<?php

class AdminClientsController extends BaseController {


    public function getClients(){
        
        $title = "Client-Administration";

        $av_admin = Session::get('av_admin');

        $uri = '';

        return View::make('admin.clients.index',compact('title','av_admin','uri'));
    }

   
    public function getClient($client_id){
        
        $title = "Client-Administration";

        $av_admin = Session::get('av_admin');

        $uri = '/'.$client_id;

        return View::make('admin.clients.index',compact('title','av_admin','uri'));
    }

    public function getData(){

    	//return 'hello';

        $args = func_get_args();
        if(!empty($args[0])){
            $clients = AdminClient::where('ClientId','=',$args[0])->select(array('ClientId','ClientName','TotalSystems','TotalDealers','ClientCommentsText'));
        }
        else{
            $clients = AdminClient::where('ClientId','>','0')->select(array('ClientId','ClientName','TotalSystems','TotalDealers','ClientCommentsText'));
        }
        return Datatables::of($clients)
        ->edit_column('TotalSystems','<a href="#">{{{ $TotalSystems }}}</a>')
        ->edit_column('TotalDealers','<a href="{{{ URL::to(\'client/\'.$ClientId.\'/dealers\') }}}">{{{ $TotalDealers }}}</a>')
        ->make();
    }
}