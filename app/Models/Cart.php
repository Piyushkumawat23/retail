<?php

namespace App\Models;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = [];
    protected $fillable = ['address_id','price','gifted','gift_from','gift_recipient','gift_message','tax','shipping_cost','discount','product_referral_code','coupon_code','coupon_applied','quantity','user_id','temp_user_id','owner_id','product_id','variation','salesperson_id','is_out_stock_item'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
