<?php

class AdminController extends BaseController {


	/**
     * Users settings page
     *
     * @return View
     */
    public function getHome()
    {
    	/*
        list($user,$redirect) = $this->user->checkAuthAndRedirect('user');
        if($redirect){return $redirect;}

        // Show the page
        return View::make('site/user/index', compact('user'));
        */
       
        //return "Welcome: ".Auth::user()->UFirstName;
        //
        $title = "Account-Overview";

        $av_admin = Session::get('av_admin');

        $accounts = Session::get('accounts');



        return View::make('admin.home',compact('title','av_admin','accounts'));
       
      // return Redirect::to('user/login');
    }


}