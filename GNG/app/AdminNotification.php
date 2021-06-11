<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdminNotification extends Authenticatable
{
	protected $table = 'tbl_admin_notification';
	public $primaryKey = 'id';
}
?>