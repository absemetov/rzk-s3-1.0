<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['id', 'sort_id', 'catalog_id', 'currency_id', 'brand_id', 'unit_id', 'warehouse_id', 'instock', 'name', 'description', 'purchase_price', 'price', 'balance', 'image_url', 'often_buy'];

    public $incrementing = false;
    
    /*
     * Get Product photos
     */
     
    public function photos()
    {
        return $this->hasMany('App\Photo');
    }
}
