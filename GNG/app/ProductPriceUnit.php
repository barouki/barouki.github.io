<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ProductPriceUnit extends Authenticatable
{
    // protected $connection = 'tenant';
	protected $table = 'tbl_product_price_unit';
	public $primaryKey = 'id';
	
	public static function get_random_string($field_code='price_unit_id')
	{
        $random_unique  =  sprintf('%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));

        $ProductPriceUnit = ProductPriceUnit::where('price_unit_id', '=', $random_unique)->first();
        if ($ProductPriceUnit != null) {
            $this->get_random_string();
        }
        return $random_unique;
    }
}
?>