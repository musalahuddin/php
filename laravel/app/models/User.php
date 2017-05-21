<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'UserInfo';

	protected $primaryKey = 'UserId';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');


	/**
	 * [findByCredentials description]
	 * @param  array  $credentials
	 * @return \Illuminate\Support\Collection|static
	 */
	public static function findByCredentials(array $credentials)
	{
		// First we will add each credential element to the query as a where clause.
		// Then we can execute the query and, if we found a user, return it in a
		// Eloquent User "model" that will be utilized by the Guard instances.
		
		$instance = new static;

		$query = $instance->newQuery();

		foreach ($credentials as $key => $value)
		{
			$query->where($key, $value);
		}

		return static::collectUserAccountsAndReturnUser($query);

		//return $query->first();
		//return $query->get();
	}


	/**
	 * collect all user's accounts into a Session
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @return \Illuminate\Support\Collection|static
	 */
	public static function collectUserAccountsAndReturnUser($builder){

		Session::flush();

		$users = $builder->get();

		//echo $accounts->first();


		foreach($users as $account){
			//echo "<br/>".$account->UserDealerId."<br/>";
			if($account->UserHierarchyId == '1' && ($account->UserTypeId == '1' || $account->UserTypeId == '2')){
				Session::put('av_admin', true);
			}
			Session::push('accounts',$account);
		}

		//print_r(Session::all());
		//dd(Session::all());
		
		return $users->first();
		
	}

	public function getRememberToken()
	{
		return null; // not supported
	}

	public function setRememberToken($value)
	{
		// not supported
	}

	public function getRememberTokenName()
	{
		return null; // not supported
	}

	/**
	* Overrides the method to ignore the remember token.
	*/
	public function setAttribute($key, $value)
	{
		$isRememberTokenAttribute = $key == $this->getRememberTokenName();
		if (!$isRememberTokenAttribute)
		{
			parent::setAttribute($key, $value);
		}
	}

}
