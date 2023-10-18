<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
class CheckoutShipping extends Model
{

	protected $table = 'customer_shipping_address';
    protected $dates = ['created_at', 'updated_at'];


	public static function getshippingAddress($customer_id){
        if(!$customer_id) return [];

		$data = CheckoutShipping::select(
		      'customer_shipping_address.id',
		    'customer_shipping_address.customer_id',
		    'customer_shipping_address.shipping_name',
		     'customer_shipping_address.shipping_mobile',
		    'customer_shipping_address.shipping_email',
		     'customer_shipping_address.shipping_address',
		    'customer_shipping_address.shipping_address1',
		     'customer_shipping_address.shipping_address2',
		    'customer_shipping_address.shipping_pincode',
		     'customer_shipping_address.shipping_address_type',
		    'customer_shipping_address.shipping_address_default',
		     'customer_shipping_address.shipping_state',
		    'customer_shipping_address.shipping_city'
		    )
//->join('states','customer_shipping_address.shipping_state','states.id')
//->join('cities','customer_shipping_address.shipping_city','cities.id')
->where('customer_shipping_address.customer_id', $customer_id)
->where('customer_shipping_address.isdeleted', 0)
					->get()->toarray();
		return $data;
	}

	public static function getshippingAddressOfCustomer($address_id,$customer_id){
		$data = CheckoutShipping::select(
		      'customer_shipping_address.id',
		    'customer_shipping_address.customer_id',
		    'customer_shipping_address.shipping_name',
		     'customer_shipping_address.shipping_mobile',
		    'customer_shipping_address.shipping_email',
		     'customer_shipping_address.shipping_address',
		    'customer_shipping_address.shipping_address1',
		     'customer_shipping_address.shipping_address2',
		    'customer_shipping_address.shipping_pincode',
		     'customer_shipping_address.shipping_address_type',
		    'customer_shipping_address.shipping_address_default',
		     'states.name as shipping_state',
		    'cities.name as shipping_city'
		    )
->leftjoin('states','customer_shipping_address.shipping_state','states.name')
->leftjoin('cities','customer_shipping_address.shipping_city','cities.name')
->where('customer_shipping_address.customer_id', $customer_id)
->where('customer_shipping_address.id',$address_id)
					->first();
		return $data;
	}



}
