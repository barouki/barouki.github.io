<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
	use HasApiTokens;
	protected $table = 'tbl_admin';
	public $primaryKey = 'id';
	public $timestamps = true;
	public $incrementing = false;
}
