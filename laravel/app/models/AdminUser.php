<?php
class AdminUser extends Eloquent{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'View_AdminUsers';

	protected $primaryKey = 'UserId';
}