<?php

class AdminDealersController extends BaseController {


    public function getDealers($client_id){
        
        $title = "Account-Administration";

        $av_admin = Session::get('av_admin');

        $uri = '';

        return View::make('admin.dealers.index',compact('title','av_admin','uri','client_id'));
    }

    public function getDealer($client_id, $dealer_id){
        
        $title = "Account-Administration";

        $av_admin = Session::get('av_admin');

        $uri = '/'.$dealer_id;

        return View::make('admin.dealers.index',compact('title','av_admin','uri','client_id'));
    }


    public function getData($client_id){

    	
        $args = func_get_args();
        if(!empty($args[1])){
            $dealers = AdminDealer::where('ClientId','=',$client_id)
            ->where('DealerId','=',$args[1])
            ->select(array('ClientId','DealerId','DealerName','TotalUsers','DealerCommentsText'));
        }
        else{
            $dealers = AdminDealer::where('ClientId','=',$client_id)
            ->select(array('ClientId','DealerId','DealerName','TotalUsers','DealerCommentsText'));
        }
        
        
        return Datatables::of($dealers)
        ->edit_column('TotalUsers','<a href="{{{ URL::to(\'client/\'.$ClientId.\'/dealer/\'.$DealerId.\'/users\') }}}">{{{ $TotalUsers }}}</a>')
        ->remove_column('ClientId')
        ->make();
        
    }


}