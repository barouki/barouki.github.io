<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\SwitchDB;

class Product extends Authenticatable
{
    // protected $connection = 'tenant';
	protected $table = 'tbl_products';
	public $primaryKey = 'id';

	public static function get_random_string($field_code='product_id')
	{
        $random_unique  =  sprintf('%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));

        $product = Product::where('product_id', '=', $random_unique)->first();
        if ($product != null) {
            $this->get_random_string();
        }
        return $random_unique;
    }
}
?>