<?php

class UserController extends BaseController {


	/**
     * Users settings page
     *
     * @return View
     */
    public function getIndex()
    {
    	return "hello";
    }

    /**
     * Displays the login form
     *
     */
    public function getLogin()
    {
    	$user = Auth::user();
        if(!empty($user->UserId)){
            return Redirect::to('/');
        }

       return View::make('users.login');
    }


    /**
     * Attempt to login
     */
	public function postLogin()
    {
    	$input = array(
            'UEmail'    => Input::get( 'email' ), // May be the username too
            'UPassword'    => Input::get( 'password' ), // May be the username too
        );
    	

    	$user = User::findByCredentials($input);
        //$user = $this->user->findByCredentials($input);
    	//return $user;

    	if($user == null){
    		//return "Invalid";
            $err_msg = 'Incorrect username, email or password.';
            //
            return Redirect::to('user/login')
                ->withInput(Input::except('password'))
                ->with( 'error', $err_msg );
    	}
    	else{
    		Auth::login($user);
    		//return "Welcome: ".Auth::user()->UFirstName;
            return Redirect::intended('/');
    	}
    	
   	}

    /**
     * Log the user out of the application.
     *
     */
    public function getLogout()
    {
        Auth::logout();
        Session::flush();
        return Redirect::to('/');
    }


}