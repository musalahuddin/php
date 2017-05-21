<?php
class AdminClient extends Eloquent{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'View_AdminClients';

	protected $primaryKey = 'ClientId';
}