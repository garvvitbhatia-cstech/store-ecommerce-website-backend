<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
	
	protected $table = 'orders';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_id',
        'online_transaction_id',
		'user_id',
		'payment_status',
		'final_order',
		'order_type',
		'price',
		'gst',
		'shipping',
		'discount',
		'coupon_code',
		'total',
		'payment_through',
		'payment_method',
		'order_day',
		'order_month',
		'order_year',
		'customer_name',
		'customer_email',
		'customer_contact',
		'customer_address',
		'customer_pincode',
		'customer_state',
		'customer_city',
		'customer_country',
		'shipping_name',
		'shipping_email',
		'shipping_contact',
		'shipping_address',
		'shipping_country',
		'shipping_state',
		'shipping_city',
		'shipping_zipcode',
		'customer_latitude',
		'customer_longitude',
		'notes',
		'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    
	
}
