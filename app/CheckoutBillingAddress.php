<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Config;

class CheckoutBillingAddress extends Model
{

    protected $table = 'customer_billing_address';
    protected $dates = ['created_at', 'updated_at'];


    public static function getshippingAddress($customer_id)
    {
     
        $data = CheckoutBillingAddress::select(
            'customer_billing_address.id',
            'customer_billing_address.customer_id',
            'customer_billing_address.shipping_name',
            'customer_billing_address.shipping_mobile',
            'customer_billing_address.shipping_email',
            'customer_billing_address.shipping_address',
            'customer_billing_address.shipping_address1',
            'customer_billing_address.shipping_address2',
            'customer_billing_address.shipping_pincode',
            'customer_billing_address.shipping_address_type',
            'customer_billing_address.shipping_address_default',
            'customer_billing_address.shipping_state',
            'customer_billing_address.shipping_city'
        )
//->join('states','customer_billing_address.shipping_state','states.id')
//->join('cities','customer_billing_address.shipping_city','cities.id')
            ->where('customer_billing_address.customer_id', $customer_id)
            ->where('customer_billing_address.isdeleted', 0)
            ->get()->toarray();
        return $data;
    }

    public static function getshippingAddressOfCustomer($address_id, $customer_id)
    {
        $data = CheckoutBillingAddress::select(
            'customer_billing_address.id',
            'customer_billing_address.customer_id',
            'customer_billing_address.shipping_name',
            'customer_billing_address.shipping_last_name',
            'customer_billing_address.shipping_mobile',
            'customer_billing_address.shipping_email',
            'customer_billing_address.shipping_address',
            'customer_billing_address.shipping_address1',
            'customer_billing_address.shipping_address2',
            'customer_billing_address.shipping_pincode',
            'customer_billing_address.shipping_address_type',
            'customer_billing_address.shipping_address_default',
            'states.name as shipping_state',
            'cities.name as shipping_city'
        )
            ->leftjoin('states', 'customer_billing_address.shipping_state', 'states.name')
            ->leftjoin('cities', 'customer_billing_address.shipping_city', 'cities.name')
            ->where('customer_billing_address.customer_id', $customer_id)
            ->where('customer_billing_address.id', $address_id)
            ->where('customer_billing_address.isdeleted', 0)
            ->first();
        return $data;
    }

    public static function getDeafultAddress($customer_id)
    {
        $data = CheckoutBillingAddress::select(
            'customer_billing_address.id',
            'customer_billing_address.customer_id',
            'customer_billing_address.shipping_name',
            'customer_billing_address.shipping_last_name',
            'customer_billing_address.shipping_mobile',
            'customer_billing_address.shipping_email',
            'customer_billing_address.shipping_address',
            'customer_billing_address.shipping_address1',
            'customer_billing_address.shipping_address2',
            'customer_billing_address.shipping_pincode',
            'customer_billing_address.shipping_address_type',
            'customer_billing_address.shipping_address_default',
            'states.name as shipping_state',
            'cities.name as shipping_city'
        )
            ->leftjoin('states', 'customer_billing_address.shipping_state', 'states.name')
            ->leftjoin('cities', 'customer_billing_address.shipping_city', 'cities.name')
            ->where('customer_billing_address.customer_id', $customer_id)
            ->where('customer_billing_address.shipping_address_default', 1)
            ->where('customer_billing_address.isdeleted', 0)
            ->first();
        return $data;
    }


}
