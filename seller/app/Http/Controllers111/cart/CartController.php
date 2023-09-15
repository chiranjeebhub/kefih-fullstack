<?php
namespace App\Http\Controllers\cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;
use App\Http\Controllers\Vendors;
use App\Helpers\CustomFormHelper;
use App\Helpers\FIleUploadingHelper;
use App\Helpers\MsgHelper;
use App\Brands;
use App\Vendor;
use App\Products;
use App\Coupon;
use App\CouponDetails;
use App\Customer;
use App\ProductAttributes;
use Illuminate\Support\Facades\Hash;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use Session;
use View;
use URL;
use App\Helpers\CommonHelper;
use Carbon\Carbon;
use App\Http\TraitLayer\PaymentTrait;
class CartController extends Controller
{

    public function validation_coupon_whencart_changes($obj, $input, $request)
    {

        switch ($obj->coupon_type)
        {

            case 0:
            case 4:
                $response = array(
                    "Error" => 0,
                    "Msg" => "Coupon Applied 1 "
                );
            break;
            case 3:
            case 7: // check date and cart amount
                $paymentDate = date('Y-m-d H:i:s');
                $paymentDate = date('Y-m-d H:i:s', strtotime($paymentDate));
                $contractDateBegin = date('Y-m-d H:i:s', strtotime($obj->started_date));
                $contractDateEnd = date('Y-m-d H:i:s', strtotime($obj->end_date));

                if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd))
                { // check date up
                    if (($input['cart_total'] >= $obj->below_cart_amt) && ($input['cart_total'] <= $obj->above_cart_amt))
                    { // check cart amount
                        $response = array(
                            "Error" => 0,
                            "Msg" => "Coupon Applied 2 "
                        );

                    }
                    else
                    {
                        $response = array(
                            "Error" => 1,
                            "Msg" => "Not valid for this  total 2 "
                        );
                    }

                }
                else
                {
                    $response = array(
                        "Error" => 1,
                        "Msg" => "Coupon code invalid  2"
                    );

                }

            break;

            case 2:
            case 6: // check date
                $paymentDate = date('Y-m-d H:i:s');
                $paymentDate = date('Y-m-d H:i:s', strtotime($paymentDate));
                $contractDateBegin = date('Y-m-d H:i:s', strtotime($obj->started_date));
                $contractDateEnd = date('Y-m-d H:i:s', strtotime($obj->end_date));

                if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd))
                {
                    $response = array(
                        "Error" => 0,
                        "Msg" => "Coupon code Applied  3"
                    );

                }
                else
                {
                    $response = array(
                        "Error" => 1,
                        "Msg" => "Coupon code invalid  4 "
                    );

                }
            break;

            case 1:
            case 5: // check  cart amount
                if (($input['cart_total'] >= $obj->below_cart_amt) && ($input['cart_total'] <= $obj->above_cart_amt))
                { // check cart amount
                    $response = array(
                        "Error" => 0,
                        "Msg" => "Coupon Applied 4"
                    );

                }
                else
                {
                    $response = array(
                        "Error" => 1,
                        "Msg" => "Not valid for this  total 4"
                    );
                }
            break;

        }
        return $response;

    }
    public function update_wishlist_count()
    {
        $wishlist_data = $this->getWishlist_item();
        $response = array(
            "size" => sizeof($wishlist_data)
        );

        echo json_encode($response);

    }
    public function getActivateCoupon($ip)
    {
        $cust_id=0;
         if(Auth::guard('customer')->check()){
                $cust_id=auth()->guard('customer')->user()->id;
         }
         
        
        $isActivate = DB::table('cart_coupon')->where('user_id', $cust_id)->first();
        return $isActivate;
    }

    public function verifyAppliedCoupon($code, $request)
    {

        $coupondata = CouponDetails::select(
            'coupons.coupon_type',
            'coupons.id',
            'coupons.coupon_type',
            'coupons.max_discount',
            'coupons.below_cart_amt',
            'coupons.above_cart_amt',
            'coupons.number_of_user',
            'coupons.uses_per_user',
            'coupons.coupon_for',
            'coupons.started_date',
            'coupons.end_date',
            'coupons.discount_value',
            'coupons.total_coupon',
            'coupon_details.coupon_code'
            )->join('coupons', 'coupons.id', 'coupon_details.coupon_id')
            ->where('coupon_details.coupon_code', $code)->where('coupon_details.coupon_used', 0)
            ->first();
        if ($coupondata)
        {
            
            if(Auth::guard('customer')->check()){
                $cust_id=auth()->guard('customer')->user()->id;
                
                $perPersonUsed=Coupon::perPersonUsedCoupon($cust_id,$coupondata);
                if($perPersonUsed['Error']==1){
                  return  $perPersonUsed;
                        die();
                }
                
           $maxCustomerUsed=Coupon::maxCustomerUsed($coupondata);
                if($maxCustomerUsed['Error']==1){
                    return  $maxCustomerUsed;
                        die();
                }
                
                if($coupondata->coupon_for==2){
                    $forNewCustomer=Coupon::forNewCustomer($cust_id);
                if($forNewCustomer['Error']==1){
                   return  $forNewCustomer;
                        die();
                }
                }
            
            }
            

            $obj = DB::table('tbl_coupon_assign')->where('fld_coupon_id', $coupondata->id)
                ->first();

            if ($obj)
            {
                $ip = $request->ip();
                switch ($obj->fld_coupon_assign_type)
                {

                    case 1: /// category wise assign
                        $categoryProductIncarts = DB::table('cart')->select('cart.*')
                            ->join('products', 'products.id', 'cart.prd_id')
                            ->join('product_categories', 'product_categories.product_id', 'products.id')
                            ->join('categories', 'product_categories.cat_id', 'categories.id')
                            ->where('user_id', $cust_id = auth()->guard('customer')
                            ->user()
                            ->id)
                            ->where('categories.id', $obj->fld_assign_type_id)
                            ->get();
                        if (sizeof($categoryProductIncarts) > 0)
                        {
                            $cart_total = 0;
                            foreach ($categoryProductIncarts as $categoryProductIncart)
                            {
                                $product_data = Products::select('price', 'spcl_price')->where('id', $categoryProductIncart->prd_id)
                                    ->first();
                                $prd_attr = DB::table('product_attributes')->where('size_id', $categoryProductIncart->size_id)
                                    ->where('color_id', $categoryProductIncart->color_id)
                                    ->where('product_id', $categoryProductIncart->prd_id)
                                    ->first();

                                $total = ($product_data->price + $prd_attr->price) * $categoryProductIncart->qty;

                                if ($product_data->spcl_price != 0 && $product_data->spcl_price != '')
                                {
                                    $total = ($product_data->spcl_price + $prd_attr->price) * $categoryProductIncart->qty;

                                }
                                $cart_total += $total;

                            }
                            $input['cart_total'] = $cart_total;
                            $response = $this->validation_coupon_whencart_changes($coupondata, $input, $request);

                        }
                        else
                        {
                            $response = array(
                                "Error" => 1,
                                "Msg" => "Coupon code invalid"
                            );

                        }
                    break;

                    case 2: // brand wise assign
                        

                        $brandProductIncarts = DB::table('cart')->select('cart.*')
                            ->join('products', 'products.id', 'cart.prd_id')
                            ->join('brands', 'products.product_brand', 'brands.id')
                            ->where('user_id', $cust_id = auth()->guard('customer')
                            ->user()
                            ->id)
                            ->where('brands.id', $obj->fld_assign_type_id)
                            ->get();

                        if (sizeof($brandProductIncarts) > 0)
                        {
                            $cart_total = 0;
                            foreach ($brandProductIncarts as $brandProductIncart)
                            {
                                $product_data = Products::select('price', 'spcl_price')->where('id', $brandProductIncart->prd_id)
                                    ->first();
                                $prd_attr = DB::table('product_attributes')->where('size_id', $brandProductIncart->size_id)
                                    ->where('color_id', $brandProductIncart->color_id)
                                    ->where('product_id', $brandProductIncart->prd_id)
                                    ->first();

                                $total = ($product_data->price + $prd_attr->price) * $brandProductIncart->qty;

                                if ($product_data->spcl_price != 0 && $product_data->spcl_price != '')
                                {
                                    $total = ($product_data->spcl_price + $prd_attr->price) * $brandProductIncart->qty;

                                }
                                $cart_total += $total;

                            }
                            $input['cart_total'] = $cart_total;
                            $response = $this->validation_coupon_whencart_changes($coupondata, $input, $request);

                        }
                        else
                        {
                            $response = array(
                                "Error" => 1,
                                "Msg" => "Coupon code invalid"
                            );

                        }
                    break;

                    case 3: // product wise assign
                        

                        if (Auth::guard('customer')->check())
                        {
                            $cust_id = auth()->guard('customer')
                                ->user()->id;
                        }

                        $prdIncart = DB::table('cart')->where('user_id', $cust_id = auth()->guard('customer')
                            ->user()
                            ->id)
                            ->where('prd_id', $obj->fld_assign_type_id)
                            ->first();
                        if ($prdIncart)
                        {
                            $product_data = Products::select('price', 'spcl_price')->where('id', $obj->fld_assign_type_id)
                                ->first();
                            $prd_attr = DB::table('product_attributes')->where('size_id', $prdIncart->size_id)
                                ->where('color_id', $prdIncart->color_id)
                                ->where('product_id', $obj->fld_assign_type_id)
                                ->first();

                            $total = ($product_data->price + $prd_attr->price) * $prdIncart->qty;

                            if ($product_data->spcl_price != 0 && $product_data->spcl_price != '')
                            {
                                $total = ($product_data->spcl_price + $prd_attr->price) * $prdIncart->qty;

                            }
                            $input['cart_total'] = $total;
                            $response = $this->validation_coupon_whencart_changes($coupondata, $input, $request);

                        }
                        else
                        {
                            $response = array(
                                "Error" => 1,
                                "Msg" => "Coupon code invalid"
                            );

                        }

                    break;

                }
            }
            else
            {
                $input['cart_total'] = $this->getCartTotal($request);

                $response = $this->validation_coupon_whencart_changes($coupondata, $input, $request);

            }

        }
        else
        {
            $response = array(
                "Error" => 1,
                "Msg" => "Coupon code invalid"
            );

        }

        return $response;
    }
    public function getCartTotal(Request $request)
    {
        $shipping_charges_details = CommonHelper::getShippingDetails();

        $ip = $request->ip();
        $isActivate = $this->getActivateCoupon($ip);
        $tax = 0;
        $discount = 0;
        $shipping_charge = 0;
        $grand_total = 0;

        $reward_points = 0;
        if (Auth::guard('customer')->check())
        {
            $cust_id = auth()->guard('customer')
                ->user()->id;
            $cart_data = self::getCart_item($request->ip() , $cust_id);

            $cust_info = Customer::where('id', $cust_id)->first();
            $reward_points = $cust_info->total_reward_points;

        }
        else
        {
            $cart_data = self::getCart_item($request->ip());
        }
        $html = '';
        $total = 0;
        foreach ($cart_data as $row)
        {
            $old_prc = $row->master_price;
            if ($row->master_spcl_price != '' && $row->master_spcl_price != 0)
            {
                $prc = $row->master_spcl_price;
            }
            else
            {
                $prc = $row->master_price;
            }

            if ($row->color_id == 0 && $row->size_id != 0)
            {

                $attr_data = DB::table('product_attributes')->where('product_id', $row->prd_id)
                    ->where('size_id', $row->size_id)
                    ->first();
                $prc += $attr_data->price;
                $old_prc += $attr_data->price;
            }
            if ($row->color_id != 0 && $row->size_id == 0)
            {
                $attr_data = DB::table('product_attributes')->where('product_id', $row->prd_id)
                    ->where('color_id', $row->color_id)
                    ->first();
                $prc += $attr_data->price;
                $old_prc += $attr_data->price;
            }
            if ($row->color_id != 0 && $row->size_id !== 0)
            {
                $attr_data = DB::table('product_attributes')->where('product_id', $row->prd_id)
                    ->where('color_id', $row->color_id)
                    ->where('size_id', $row->size_id)
                    ->first();
                $prc += $attr_data->price;
                $old_prc += $attr_data->price;
            }

            $total += $prc * $row->qty;

        }
        return $total;

    }
    public static function getDiscountvalue($type, $id, $ip, $discount)
    {
        $cust_id=0;
        if (Auth::guard('customer')->check()) { $cust_id = auth()->guard('customer') ->user()->id;
        }
        $ip = $ip;
        switch ($type)
        {

            case 1: /// category wise assign
                $categoryProductIncarts = DB::table('cart')->select('cart.*')
                    ->join('products', 'products.id', 'cart.prd_id')
                    ->join('product_categories', 'product_categories.product_id', 'products.id')
                    ->join('categories', 'product_categories.cat_id', 'categories.id')
                    ->where('user_id', $cust_id)->where('categories.id', $id)->get();
                  
                if (sizeof($categoryProductIncarts) > 0)
                {
                    $cart_total = 0;
                    foreach ($categoryProductIncarts as $categoryProductIncart)
                    {
                        $product_data = Products::select('price', 'spcl_price')->where('id', $categoryProductIncart->prd_id)
                            ->first();
                        $prd_attr = DB::table('product_attributes')->where('size_id', $categoryProductIncart->size_id)
                            ->where('color_id', $categoryProductIncart->color_id)
                            ->where('product_id', $categoryProductIncart->prd_id)
                            ->first();

                        $total = ($product_data->price + $prd_attr->price) * $categoryProductIncart->qty;

                        if ($product_data->spcl_price != 0 && $product_data->spcl_price != '')
                        {
                            $total = ($product_data->spcl_price + $prd_attr->price) * $categoryProductIncart->qty;

                        }
                        $cart_total += $total;

                    }
                    $discount = ($cart_total * $discount) / 100;
                    return $discount;
                }
                else
                {
                    return 0;
                }
            break;

            case 2: // brand wise assign
                

                $brandProductIncarts = DB::table('cart')->select('cart.*')
                    ->join('products', 'products.id', 'cart.prd_id')
                    ->join('brands', 'products.product_brand', 'brands.id')
                    ->where('user_ip', $ip)->where('brands.id', $id)->get();

                if (sizeof($brandProductIncarts) > 0)
                {
                    $cart_total = 0;
                    foreach ($brandProductIncarts as $brandProductIncart)
                    {
                        $product_data = Products::select('price', 'spcl_price')->where('id', $brandProductIncart->prd_id)
                            ->first();
                        $prd_attr = DB::table('product_attributes')->where('size_id', $brandProductIncart->size_id)
                            ->where('color_id', $brandProductIncart->color_id)
                            ->where('product_id', $brandProductIncart->prd_id)
                            ->first();

                        $total = ($product_data->price + $prd_attr->price) * $brandProductIncart->qty;

                        if ($product_data->spcl_price != 0 && $product_data->spcl_price != '')
                        {
                            $total = ($product_data->spcl_price + $prd_attr->price) * $brandProductIncart->qty;

                        }
                        $cart_total += $total;

                    }
                    $discount = ($cart_total * $discount) / 100;
                    return $discount;

                }
                else
                {
                    return 0;
                }
            break;

            case 3: // product wise assign
                

                if (Auth::guard('customer')->check())
                {
                    $cust_id = auth()->guard('customer')
                        ->user()->id;
                }

                $prdIncart = DB::table('cart')->where('user_ip', $ip)->where('prd_id', $obj->fld_assign_type_id)
                    ->first();
                if ($prdIncart)
                {
                    $product_data = Products::select('price', 'spcl_price')->where('id', $obj->fld_assign_type_id)
                        ->first();
                    $prd_attr = DB::table('product_attributes')->where('size_id', $prdIncart->size_id)
                        ->where('color_id', $prdIncart->color_id)
                        ->where('product_id', $id)->first();

                    $total = ($product_data->price + $prd_attr->price) * $prdIncart->qty;

                    if ($product_data->spcl_price != 0 && $product_data->spcl_price != '')
                    {
                        $total = ($product_data->spcl_price + $prd_attr->price) * $prdIncart->qty;

                    }
                    $discount = ($total * $discount) / 100;
                    return $discount;

                }
                else
                {
                    DB::table('cart_coupon')->where('user_ip', $ip)->delete();
                    return 0;
                }

            break;

        }
    }
    public function activateCoupon($obj, $request)
    {
 $cust_id=auth()->guard('customer')->user()->id;
        $coupondata = CouponDetails::select('tbl_coupon_assign.fld_coupon_assign_type', 'tbl_coupon_assign.fld_assign_type_id')->join('coupons', 'coupons.id', 'coupon_details.coupon_id')
            ->join('tbl_coupon_assign', 'coupons.id', 'tbl_coupon_assign.fld_coupon_id')
            ->where('coupon_details.coupon_code', $obj->coupon_code)
            ->where('coupon_details.coupon_used', 0)
            ->first();

        $isAssign = DB::table('tbl_coupon_assign')->where('fld_coupon_id', $obj->id)
            ->first();

        $type = '';
        $type_id = '';
        if ($isAssign)
        {
            $type = $coupondata->fld_coupon_assign_type;
            $type_id = $coupondata->fld_assign_type_id;
        }

        $ip = $request->ip();
        $isActivate = DB::table('cart_coupon')->where('user_id', $cust_id)->first();
        if ($isActivate)
        
        {
            
               
            DB::table('cart_coupon')->where('user_id', $cust_id)->update(['coupon_code' => $obj->coupon_code, 'discount_value' => $obj->discount_value, 'coupon_assign_type' => $type, 'coupon_assign_type_id' => $type_id]);

        }
        else
        { 
            DB::table('cart_coupon')->insert(['coupon_code' => $obj->coupon_code, 'discount_value' => $obj->discount_value, 'coupon_assign_type' => $type, 'coupon_assign_type_id' => $type_id, 'user_id' => $cust_id]);
        }

    }
    public function validation_coupon($obj, $input, $request)
    {

        switch ($obj->coupon_type)
        {

            case 0:
            case 4:
                $response = array(
                    "Error" => 0,
                    "Msg" => "Coupon Applied "
                );

                $this->activateCoupon($obj, $request);

            break;
            case 3:
            case 7: // check date and cart amount
                $paymentDate = date('Y-m-d H:i:s');
                $paymentDate = date('Y-m-d H:i:s', strtotime($paymentDate));
                $contractDateBegin = date('Y-m-d H:i:s', strtotime($obj->started_date));
                $contractDateEnd = date('Y-m-d H:i:s', strtotime($obj->end_date));

                if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd))
                { // check date
                    if (($input['cart_total'] >= $obj->below_cart_amt) && ($input['cart_total'] <= $obj->above_cart_amt))
                    { // check cart amount
                        $this->activateCoupon($obj, $request);
                        $response = array(
                            "Error" => 0,
                            "Msg" => "Coupon Applied "
                        );

                    }
                    else
                    {
                        $response = array(
                            "Error" => 1,
                            "Msg" => "Not valid for this  total "
                        );
                    }

                }
                else
                {
                    $response = array(
                        "Error" => 1,
                        "Msg" => "Coupon code invalid "
                    );

                }

            break;

            case 2:
            case 6: // check date
                

                $paymentDate = date('Y-m-d H:i:s');
                $paymentDate = date('Y-m-d H:i:s', strtotime($paymentDate));
                $contractDateBegin = date('Y-m-d H:i:s', strtotime($obj->started_date));
                $contractDateEnd = date('Y-m-d H:i:s', strtotime($obj->end_date));

                if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd))
                {
                    $this->activateCoupon($obj, $request);
                    $response = array(
                        "Error" => 0,
                        "Msg" => "Coupon code Applied "
                    );

                }
                else
                {
                    $response = array(
                        "Error" => 1,
                        "Msg" => "Coupon code invalid "
                    );

                }
            break;

            case 1:
            case 5: // check  cart amount
                if (($input['cart_total'] >= $obj->below_cart_amt) && ($input['cart_total'] <= $obj->above_cart_amt))
                { // check cart amount
                    $this->activateCoupon($obj, $request);
                    $response = array(
                        "Error" => 0,
                        "Msg" => "Coupon Applied"
                    );

                }
                else
                {
                    $response = array(
                        "Error" => 1,
                        "Msg" => "Not valid for this  total"
                    );
                }
            break;

        }

        echo json_encode($response);

    }

    public function couponAssigned($request, $obj, $coupondata)
    {
        
                $cust_id=0;
                if (Auth::guard('customer')->check())
                    {
                        $cust_id = auth()->guard('customer')
                            ->user()->id;
                    }
        $ip = $request->ip();
        switch ($obj->fld_coupon_assign_type)
        {

            case 1: /// category wise assign
                $categoryProductIncarts = DB::table('cart')->select('cart.*')
                    ->join('products', 'products.id', 'cart.prd_id')
                    ->join('product_categories', 'product_categories.product_id', 'products.id')
                    ->join('categories', 'product_categories.cat_id', 'categories.id')
                    ->where('user_id', $cust_id)->where('categories.id', $obj->fld_assign_type_id)
                    ->get();
                    
                    
                if (sizeof($categoryProductIncarts) > 0)
                {
                    $cart_total = 0;
                    foreach ($categoryProductIncarts as $categoryProductIncart)
                    {
                        $product_data = Products::select('price', 'spcl_price')->where('id', $categoryProductIncart->prd_id)
                            ->first();
                        $prd_attr = DB::table('product_attributes')->where('size_id', $categoryProductIncart->size_id)
                            ->where('color_id', $categoryProductIncart->color_id)
                            ->where('product_id', $categoryProductIncart->prd_id)
                            ->first();

                        $total = ($product_data->price + $prd_attr->price) * $categoryProductIncart->qty;

                        if ($product_data->spcl_price != 0 && $product_data->spcl_price != '')
                        {
                            $total = ($product_data->spcl_price + $prd_attr->price) * $categoryProductIncart->qty;

                        }
                        $cart_total += $total;

                    }
                    $input['cart_total'] = $cart_total;
                   
                    $this->validation_coupon($coupondata, $input, $request);
                    die();
                }
                else
                {
                    $response = array(
                        "Error" => 1,
                        "Msg" => "Coupon code invalid"
                    );
                    echo json_encode($response);
                    die();
                }
            break;

            case 2: // brand wise assign
                

                $brandProductIncarts = DB::table('cart')->select('cart.*')
                    ->join('products', 'products.id', 'cart.prd_id')
                    ->join('brands', 'products.product_brand', 'brands.id')
                    ->where('user_ip', $ip)->where('brands.id', $obj->fld_assign_type_id)
                    ->get();

                if (sizeof($brandProductIncarts) > 0)
                {
                    $cart_total = 0;
                    foreach ($brandProductIncarts as $brandProductIncart)
                    {
                        $product_data = Products::select('price', 'spcl_price')->where('id', $brandProductIncart->prd_id)
                            ->first();
                        $prd_attr = DB::table('product_attributes')->where('size_id', $brandProductIncart->size_id)
                            ->where('color_id', $brandProductIncart->color_id)
                            ->where('product_id', $brandProductIncart->prd_id)
                            ->first();

                        $total = ($product_data->price + $prd_attr->price) * $brandProductIncart->qty;

                        if ($product_data->spcl_price != 0 && $product_data->spcl_price != '')
                        {
                            $total = ($product_data->spcl_price + $prd_attr->price) * $brandProductIncart->qty;

                        }
                        $cart_total += $total;

                    }
                    $input['cart_total'] = $cart_total;
                    $this->validation_coupon($coupondata, $input, $request);
                    die();
                }
                else
                {
                    $response = array(
                        "Error" => 1,
                        "Msg" => "Coupon code invalid"
                    );
                    echo json_encode($response);
                    die();
                }
            break;

            case 3: // product wise assign
                

                if (Auth::guard('customer')->check())
                {
                    $cust_id = auth()->guard('customer')
                        ->user()->id;
                }

                $prdIncart = DB::table('cart')->where('user_ip', $ip)->where('prd_id', $obj->fld_assign_type_id)
                    ->first();
                if ($prdIncart)
                {
                    $product_data = Products::select('price', 'spcl_price')->where('id', $obj->fld_assign_type_id)
                        ->first();
                    $prd_attr = DB::table('product_attributes')->where('size_id', $prdIncart->size_id)
                        ->where('color_id', $prdIncart->color_id)
                        ->where('product_id', $obj->fld_assign_type_id)
                        ->first();

                    $total = ($product_data->price + $prd_attr->price) * $prdIncart->qty;

                    if ($product_data->spcl_price != 0 && $product_data->spcl_price != '')
                    {
                        $total = ($product_data->spcl_price + $prd_attr->price) * $prdIncart->qty;

                    }
                    $input['cart_total'] = $total;
                    $this->validation_coupon($coupondata, $input, $request);
                    die();
                }
                else
                {
                    $response = array(
                        "Error" => 1,
                        "Msg" => "Coupon code invalid"
                    );
                    echo json_encode($response);
                    die();
                }

            break;

        }
    }
   
    public function apply_coupon(Request $request)
    {
        $input = $request->all();
        $cust_id=auth()->guard('customer')->user()->id;
        
       

        $coupondata = CouponDetails::select(
                    'coupons.coupon_type',
                    'coupons.number_of_user',
                    'coupons.uses_per_user',
                    'coupons.id',
                    'coupons.coupon_type', 
                    'coupons.max_discount',
                    'coupons.below_cart_amt',
                    'coupons.above_cart_amt',
                    'coupons.coupon_for', 
                    'coupons.started_date',
                    'coupons.end_date',
                    'coupons.discount_value',
                    'coupons.total_coupon',
                    'coupon_details.coupon_code'
            )->join('coupons', 'coupons.id', 'coupon_details.coupon_id')
            ->where('coupon_details.coupon_code', $input['code'])->where('coupon_details.coupon_used', 0)
            ->first();
          
        if ($coupondata)
        {
            $perPersonUsed=Coupon::perPersonUsedCoupon($cust_id,$coupondata);
           
          
                if($perPersonUsed['Error']==1){
                   echo json_encode($perPersonUsed);
                        die();
                }
                
           $maxCustomerUsed=Coupon::maxCustomerUsed($coupondata);
           
          
           
                if($maxCustomerUsed['Error']==1){
                   echo json_encode($maxCustomerUsed);
                        die();
                }
                  
                if($coupondata->coupon_for==2){
                    $forNewCustomer=Coupon::forNewCustomer($cust_id);
                if($forNewCustomer['Error']==1){
                   echo json_encode($forNewCustomer);
                        die();
                }
                }
             
          

            $isAssign = DB::table('tbl_coupon_assign')->where('fld_coupon_id', $coupondata->id)
                ->first();
               
            if ($isAssign)
            {

                $this->couponAssigned($request, $isAssign, $coupondata);
                die();
            }
            else
            {

                $this->validation_coupon($coupondata, $input, $request);
                die();
            }

        }
        else
        {
            $response = array(
                "Error" => 1,
                "Msg" => "Coupon code invalid"
            );

        }

        echo json_encode($response);
    }


    function checkValidateLimit($couponObj){
    	$status =  false;
    	$count = DB::table('cart_coupon')->where('coupon_code', $couponObj->coupon_code)->count();
    	if($count >= $couponObj->number_of_user){
    		$status  = true;
    	}
    	return $status;
    }


    public function add_to_wishlist(Request $request)
    {
        $input = $request->all();
        $user_id = auth()->guard('customer')
            ->user()->id;

        $is_exist = DB::table('tbl_wishlist')->select('fld_wishlist_id')
            ->where('fld_product_id', '=', $input['prd_id'])->where('fld_user_id', '=', $user_id)->first();
        $inputs = array(
            'fld_product_id' => $input['prd_id'],
            'fld_user_id' => $user_id
        );
        if ($is_exist)
        {

            $method = 2;
            $res = DB::table('tbl_wishlist')->where('fld_product_id', '=', $input['prd_id'])->where('fld_user_id', '=', $user_id)->update($inputs);
        }
        else
        {
            $method = 1;
            $res = DB::table('tbl_wishlist')->insert($inputs);
        }

        if ($res)
        {
            $response = array(
                "status" => true,
                "method" => $method
            );
        }
        else
        {
            $response = array(
                "status" => false,
                "method" => $method
            );
        }
        echo json_encode($response);
    }
    public static function cart_product_delete()
    {

        $minutes = (86400 * 30);
        $cookie_data = app(\App\Http\Controllers\CookieController::class)->getcustomCartCookie();

        if ($cookie_data != '')
        {

            $cookie_data = json_decode($cookie_data);

            foreach ($cookie_data as $key => $products)
            {
                $isProductExist = DB::table('products')->select('id', 'status', 'isdeleted', 'isblocked')
                    ->where('id', $products->product_id)
                    ->first();
                if ($isProductExist)
                {

                    if ($isProductExist->status == 0 || $isProductExist->isdeleted == 1 || $isProductExist->isblocked == 1)
                    {
                        unset($cookie_data[$key]);
                    }

                    $isattrProductExist = DB::table('product_attributes')->select('id')
                        ->where('product_id', $products->product_id)
                        ->where('size_id', $products->size_id)
                        ->where('color_id', $products->color_id)
                        ->first();
                    if (!$isattrProductExist)
                    {
                        unset($cookie_data[$key]);
                    }
                }
                else
                {
                    unset($cookie_data[$key]);
                }

            }
            $cookie_data = array_values((array)$cookie_data);
            $json = json_encode($cookie_data);

            setcookie('productsInCart', $json, time() + ($minutes));

        }

    }
    public static function getCart_item($ip, $customer_id = '')
    {
        self::cart_product_delete();
        $return_data = app(\App\Http\Controllers\CookieController::class)->getcustomCartCookie();
        //   echo "<pre>";
        //  print_r($return_data);
        //  die();
        if ($return_data == '')
        {
            return array();
        }
        $cookie_data = json_decode($return_data);

        $cart_data = array();
        foreach ($cookie_data as $cookie)
        {
            $products_in_cart = Products::select('products.default_image', 'products.name', 'products.id as prd_id', 'products.price as master_price', 'products.spcl_price as master_spcl_price', 'products.delivery_days as delivery_days', 'products.shipping_charges as shipping_charges'
)->where('id', $cookie->product_id)
                ->first();
            $products_in_cart->size_id = $cookie->size_id;
            $products_in_cart->color_id = $cookie->color_id;
            $products_in_cart->w_size_id = $cookie->w_size_id;
            $products_in_cart->qty = $cookie->qty;
            array_push($cart_data, $products_in_cart);
        }
        return $cart_data;
    }
    public function getWishlist_item()
    {
        $user_id = auth()->guard('customer')
            ->user()->id;
        $wishlist_data = DB::table('tbl_wishlist')->select('tbl_wishlist.*', 'products.default_image', 'products.name', 'products.id as prd_id')
            ->join('products', 'tbl_wishlist.fld_product_id', '=', 'products.id')
            ->where('tbl_wishlist.fld_user_id', '=', $user_id)->get();

        return $wishlist_data;
    }
    public function index(Request $request)
    {

        if (Auth::guard('customer')->check())
        {
            $cust_id = auth()->guard('customer')
                ->user()->id;
            $cart_data = self::getCart_item($request->ip() , $cust_id);
        }
        else
        {
            $cart_data = self::getCart_item($request->ip());

        }
        return view('fronted.mod_cart.list', ["cart" => $cart_data]);

    }
    public function changeQtyOfCartProduct(Request $request)
    {
        $input = $request->all();

        $stock = ProductAttributes::select('qty')->where('size_id', '=', $input['size'])->where('color_id', '=', $input['color'])->where('product_id', '=', $input['prd_id'])->first();

        $quantity = $stock ? $stock->qty : 0;

        if (!array_key_exists("w_size_id", $input))
        {
            $input['w_size_id'] = 0;
        }

        $prd_in_cart = array(
            'product_id' => $input['prd_id'],
            'size_id' => $input['size'],
            'color_id' => $input['color'],
            'qty' => $input['qty'],
            'w_size_id' => $input['w_size_id']

        );

        if ($quantity >= $input['qty'])
        {

            $return = app(\App\Http\Controllers\CookieController::class)->increaseQtyOfProduct($prd_in_cart);

            $response = array(
                "error" => false
            );

        }
        else
        {
            $response = array(
                "error" => true
            );
        }
        echo json_encode($response);
    }
    public function update_cart(Request $request)
    {
        $user_logged_in = 0;
        $offerProduct = 0;
        $use_wallet = $request->wallet;
        $shipping_charges_details = CommonHelper::getShippingDetails();

        $ip = $request->ip();
        $isActivate = $this->getActivateCoupon($ip);
        $tax = 0;
        $discount = 0;
        $shipping_charge = 0;
        $grand_total = 0;
        $out_of_stock = 0;
        $reward_points = 0;
        $available_points = 0;
        if (Auth::guard('customer')->check())
        {
            $user_logged_in = 1;
            $cust_id = auth()->guard('customer')
                ->user()->id;
            $cart_data = self::getCart_item($request->ip() , $cust_id);

            $cust_info = Customer::where('id', $cust_id)->first();
            $available_points = $cust_info->total_reward_points;
        }
        else
        {
            $cart_data = self::getCart_item($request->ip());
        }
        $html = '';
        $total = 0;
        foreach ($cart_data as $row)
        {

            $stock = ProductAttributes::select('qty')->where('size_id', '=', $row->size_id)
                ->where('color_id', '=', $row->color_id)
                ->where('product_id', '=', $row->prd_id)
                ->first();
            $quantity = $stock ? $stock->qty : 0;

            if ($quantity < $row->qty)
            {

                $out_of_stock++;
            }
            $old_prc = $row->master_price;
            if ($row->master_spcl_price != '' && $row->master_spcl_price != 0)
            {
                $offerProduct++;
                $prc = $row->master_spcl_price;
            }
            else
            {
                $prc = $row->master_price;
            }

            if ($row->color_id == 0 && $row->size_id != 0)
            {

                $attr_data = DB::table('product_attributes')->where('product_id', $row->prd_id)
                    ->where('size_id', $row->size_id)
                    ->first();
                $prc += $attr_data->price;
                $old_prc += $attr_data->price;
            }
            if ($row->color_id != 0 && $row->size_id == 0)
            {
                $attr_data = DB::table('product_attributes')->where('product_id', $row->prd_id)
                    ->where('color_id', $row->color_id)
                    ->first();
                $prc += $attr_data->price;
                $old_prc += $attr_data->price;
            }
            if ($row->color_id != 0 && $row->size_id !== 0)
            {
                $attr_data = DB::table('product_attributes')->where('product_id', $row->prd_id)
                    ->where('color_id', $row->color_id)
                    ->where('size_id', $row->size_id)
                    ->first();
                $prc += $attr_data->price;
                $old_prc += $attr_data->price;
            }

            $price_html = '';
            if ($row->master_spcl_price != '' && $row->master_spcl_price != 0)
            {
                $price_html .= '<i class="fa fa-rupee"></i>' . $prc;
            }
            else
            {
                $price_html .= '<i class="fa fa-rupee"></i>' . $prc;
            }

            $total += $prc * $row->qty;
            $grand_total += $total;
            $shipping_charge += $row->shipping_charges * $row->qty;

            if ($row->color_id != 0)
            {
                $colorImage = DB::table('product_configuration_images')->where('product_id', $row->prd_id)
                    ->where('color_id', $row->color_id)
                    ->first();
                if ($colorImage)
                {
                    $url = Config::get('constants.Url.public_url') . Config::get('constants.uploads.product_images') . '/' . $colorImage->product_config_image;
                }
                else
                {
                    $url = Config::get('constants.Url.public_url') . Config::get('constants.uploads.product_images') . '/' . $row->default_image;
                }

            }
            else
            {
                $url = Config::get('constants.Url.public_url') . Config::get('constants.uploads.product_images') . '/' . $row->default_image;
            }

            $size_html = '';
            if ($row->size_id != 0)
            {
                $s = Products::getAttrName('Sizes', $row->size_id);
                $size_html = 'Size : ' . $s;
            }
            $color_html = '';
            if ($row->color_id != 0)
            {
                $s = Products::getAttrName('Colors', $row->color_id);
                $color_html = 'Color : ' . $s;
            }
            $prd_new_id = $row->prd_id . '-' . $row->size_id . '-' . $row->color_id . '-' . $row->qty;
            $html .= '<li id="cart_item_row_' . $prd_new_id . '">
			<img src="' . $url . '" alt="item1">
			<span class="item-name">' . $row->name . '</span>
			<span class="item-price">' . $price_html . ' </span>
			
			<span class="item-quantity">Quantity :  ' . $row->qty . '</span>
			
			<span class="item-remove"><a href="javascript:void(0)" class="deleteCartItem" prd_id="' . $prd_new_id . '"><i class="fa fa-trash " ></i></a></span>
		  </li>';
        }
        $ship_amount = $shipping_charges_details->cart_total;

        if ($ship_amount < $total)
        {
            $shipping_charge = 0;
        }
        else
        {
            $shipping_charge = $shipping_charges_details->shipping_charge;
        }
        if (sizeof($cart_data) == 0)
        {
            
            if (Auth::guard('customer')->check())
            {
            $cust_id = auth()->guard('customer')
            ->user()->id;
            
             DB::table('cart_coupon')
            ->where('user_id', $cust_id)->delete();
            }
            $ip = $request->ip();
           
        }

       

        $coupon_array = array();
         $coupon_apply = false;
         if (Auth::guard('customer')->check())
        {
                $cust_id = auth()->guard('customer')
                    ->user()->id;
            $coupon = DB::table('cart_coupon')->where('user_id', $cust_id)->first();
           
            if ($coupon)
        {

            $coupon_apply = true;
            $res_data = $this->verifyAppliedCoupon($coupon->coupon_code, $request);
           

            if ($res_data['Error'] == 0)
            {

                $coupondata = CouponDetails::select('coupons.max_discount')->join('coupons', 'coupons.id', 'coupon_details.coupon_id')
                    ->where('coupon_details.coupon_code', $coupon->coupon_code)
                    ->where('coupon_details.coupon_used', 0)
                    ->first();
 
                if ($coupon->coupon_assign_type != '')
                {
                   
                    $coupon_array['coupon_code'] = $coupon->coupon_code;
                    $coupon_array['discount_value'] = $coupon->discount_value;
                     
                    $discount = self::getDiscountvalue($coupon->coupon_assign_type, $coupon->coupon_assign_type_id, $ip, $coupon->discount_value);;

                    if ($coupondata->max_discount < $discount)
                    {
                        $discount = $coupondata->max_discount;
                    }
                }
                else
                {
                    $discount = ($total * $coupon->discount_value) / 100;
                    $coupon_array['coupon_code'] = $coupon->coupon_code;
                    $coupon_array['discount_value'] = $coupon->discount_value;
                    if ($coupondata->max_discount < $discount)
                    {
                        $discount = $coupondata->max_discount;
                    }
                }
                 
            }
            else
            {
                $coupon_apply = false;
                DB::table('cart_coupon')
                    ->where('user_id', $cust_id)->delete();
            }

        }
        }

       
        

        $cod_charges = 0;
        if ($request->paymentMethod == 0)
        {
            $cod_charges = $shipping_charges_details->cod_charges;
        }

        if ($offerProduct == 0)
        {
            if ($use_wallet == 1)
            {
                if ($total > 0)
                {
                    $myNumber = $total;
                    $percentToGet = 20;
                    $percentInDecimal = $percentToGet / 100;
                    $percent = $percentInDecimal * $myNumber;

                    if ($available_points > 0)
                    {
                        if ($available_points > $percent)
                        {
                            $reward_points = $percent;
                        }
                        else
                        {
                            $reward_points = $available_points;
                        }
                    }
                    else
                    {
                        $reward_points = 0;
                    }

                }

            }
            else
            {
                $reward_points = 0;
            }
        }

        $pincode_error = 1;
        if (@$_COOKIE["pincode_error"])
        {
            $pincode_error = $_COOKIE["pincode_error"];
        }
        
  
        //$gra = round($grand_total = ($total + $tax + 0 - $discount + $cod_charges - $reward_points));
        $gra = round($grand_total = ($total + $tax + 0 - $discount - $reward_points));
        
        $request->session()
            ->put('paymentDetails', array(
            "grandTotal" => ($gra < $shipping_charges_details->cart_total)?$gra+$shipping_charges_details->shipping_charge+$cod_charges:$gra+$cod_charges,
             "subTotal" => $total,
            "coupon_apply" => $coupon_apply,
            "shippingCharges" => round(($gra < $shipping_charges_details->cart_total)?$shipping_charges_details->shipping_charge:0),
            'coupon_code' => ($coupon_array) ? $coupon_array['coupon_code'] : '',
            "useWallet" => $use_wallet,
            "usePoints" => round($reward_points) ,
            "discount" => round($discount) ,
            "cod_charges" => $cod_charges,
            "tax" => $tax,
        ));

        $ship_amount = $shipping_charges_details->cart_total;

        if ($ship_amount < $total)
        {
            $shipping_charge = 0;
        }

        $response = array(
            "html" => $html,
            "size" => sizeof($cart_data) ,
            "total" => $total,
            'coupon_code' => ($coupon_array) ? $coupon_array['coupon_code'] : '',
            'coupon_percent' => ($coupon_array) ? $coupon_array['discount_value'] : '',
            'out_of_stock' => $out_of_stock,
            "pincode_error" => (@$_COOKIE["pincode_error"]) ? 1 : @$_COOKIE["pincode_error"],
            "grand_total_with_tax" => ($gra < $shipping_charges_details->cart_total)?$gra+$shipping_charges_details->shipping_charge+$cod_charges:$gra+$cod_charges,
            "tax" => $tax,
            "user_logged_in" => $user_logged_in,
            "shipping_charge" => round(($gra < $shipping_charges_details->cart_total)?$shipping_charges_details->shipping_charge:0) ,
            'cod_charges' => $cod_charges,
            "shippingamount" => $ship_amount,
            "discount" => round($discount) ,
            'offerProducts' => $offerProduct,
            'use_wallet' => $use_wallet,
            "available_points" => $available_points,
            "reward_points" => round($reward_points) ,
            "remainingPoints" => round($available_points - $reward_points) ,
            "cart_list_view" => view("fronted.mod_cart.ajax.back_response_cart_list", array(
                'cart' => $cart_data,
                'isActivate' => $coupon_array
            ))->render() ,
            "review_order" => view("fronted.mod_checkout.ajax.back_response_review_order", array(
                'cart_data' => $cart_data,

            ))->render()
        );
        echo json_encode($response);

    }

    public function add_to_savelater(Request $request)
    {
        $input = $request->all();
        $user_id = auth()->guard('customer')
            ->user()->id;

        $is_exist = DB::table('tbl_save_later')->select('fld_save_later_id')
            ->where('fld_product_id', '=', $input['prd_id'])->where('fld_user_id', '=', $user_id)->first();
        $inputs = array(
            'fld_product_id' => $input['prd_id'],
            'fld_user_id' => $user_id
        );
        if ($is_exist)
        {

            $method = 2;
            $res = DB::table('tbl_save_later')->where('fld_product_id', '=', $input['prd_id'])->where('fld_user_id', '=', $user_id)->update($inputs);
        }
        else
        {
            $method = 1;
            $res = DB::table('tbl_save_later')->insert($inputs);
        }

        if ($res)
        {
            $response = array(
                "status" => true,
                "method" => $method
            );
        }
        else
        {
            $response = array(
                "status" => false,
                "method" => $method
            );
        }
        echo json_encode($response);
    }

    public function savelater(Request $request)
    {

        if (Auth::guard('customer')->check())
        {
            $cust_id = auth()->guard('customer')
                ->user()->id;
            $cart_data = self::getCart_item($request->ip() , $cust_id);
        }
        else
        {
            $cart_data = self::getCart_item($request->ip());

        }
        return view('fronted.mod_cart.list', ["cart" => $cart_data]);

    }

}

