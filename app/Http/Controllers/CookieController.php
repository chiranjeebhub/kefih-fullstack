<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;
use App\Http\Requests;
//use Cookie;
use App\Cart;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class CookieController extends Controller {

  /* below code a set a cookie in browser */
   
   /*public function setCookie($prd_id=''){
       
      //$cookie = Cookie::forever('name', 'value');
	  $response = new Response('Hello World');
      $response->withCookie(cookie('name', $prd_id));
      return $response;
   }
   
   public function setCookie(Request $request){
	  $response = new Response('Hello World');
      $response->withCookie(cookie('name1', 'Anything'));
      return $response;
   }*/
  
  /* below code a get a cookie in browser */
   /*public function getCookie(Request $request){
     $val = Cookie::get('name');
    
      echo $val;
   }*/
   
   public function mapProductWebApp(Request $request){
	     $cust_id=auth()->guard('customer')->user()->id;
	   $carts_data=Cart::where('cart.user_id',$cust_id)
                                 ->delete();
	       
            
	        
	    if($cookie_data=@$_COOKIE["productsInCart"]){
	             $whole_products=array();
            $cookie_data=json_decode($cookie_data);
          foreach($cookie_data as $key=>$products){
            $productadded=Cart::select(
                        'cart.prd_id',
                        'cart.size_id',
                        'cart.color_id'
                        )
                     ->join('products','cart.prd_id','products.id')
        	        ->where('cart.user_id',$cust_id)
        	        ->where('cart.prd_id',$products->product_id)
        	        ->where('cart.size_id',$products->size_id)
        	        ->where('cart.color_id',$products->color_id)
        	         ->where('cart.w_size_id',$products->w_size_id)
                    ->where('products.status',1)
                    ->where('products.isdeleted',0)
        	        ->first();
        	  if(!$productadded){
        	      $inserted_data=array(
                                    'user_id'=>$cust_id,
                                    'prd_id'=>$products->product_id,
                                    'size_id'=>$products->size_id,
                                    'color_id'=>$products->color_id,
                                    "w_size_id"=>$products->w_size_id,
                                    'qty'=>$products->qty
        	                        );
        	     array_push($whole_products,$inserted_data);                     
        	  }
        }
            if(sizeof($whole_products)>0){
            Cart::insert($whole_products);
            }    
	 }
	    
	 
	}
	
	public function manageAppWebUserCart($method,$data){
            //   $method=>0 for add
            //   $method=>1 for Update
            //   $method=>2 for Deleted
           
            $cust_id=auth()->guard('customer')->user()->id;
       switch($method){
            case 0:
                $productadded=Cart::select(
                        'cart.prd_id',
                        'cart.size_id',
                        'cart.color_id'
                        )
                     ->join('products','cart.prd_id','products.id')
        	        ->where('cart.user_id',$cust_id)
        	        ->where('cart.prd_id',$data['product_id'])
        	        ->where('cart.size_id',$data['size_id'])
        	        ->where('cart.color_id',$data['color_id'])
        	         ->where('cart.w_size_id',$data['w_size_id'])
                    ->where('products.status',1)
                    ->where('products.isdeleted',0)
        	        ->first();
        	        if(!$productadded){
                        $insert_data=array(
                        "user_id"=>$cust_id,
                        "prd_id"=>$data['product_id'],
                        "size_id"=>$data['size_id'],
                        "color_id"=>$data['color_id'],
                        "qty"=>$data['qty']
                        );
                      Cart::insert($insert_data);
        	        }
               
                
            break;
            
            case 1:
                
                $update_data=array(
                        "qty"=>$data['qty']
                    );
                    
                 Cart::where('user_id',$cust_id)
                    ->where('prd_id',$data['product_id'])
                    ->where('size_id',$data['size_id'])
                    ->where('color_id',$data['color_id'])
                      ->where('cart.w_size_id',$data['w_size_id'])
                     ->update($update_data);
            break;
            
            
            case 2:
                 Cart::where('user_id',$cust_id)
                    ->where('prd_id',$data[0])
                    ->where('size_id',$data[1])
                    ->where('color_id',$data[2])
                    ->delete();
            break;
           
       }
            
   }
   
   public function increaseQtyOfProduct($prd_id){
              $minutes=(86400 * 30);
	 if($cookie_data=@$_COOKIE["productsInCart"]){
	     
	 	$cookie_data=json_decode($cookie_data);
        $whole_products=array();
      
                $ex=0;
                $k=0;

        foreach($cookie_data as $key=>$products){
               
            if(($products->product_id==$prd_id['product_id']) && ($products->size_id==$prd_id['size_id']) && ($products->color_id==$prd_id['color_id']) && ($products->w_size_id==$prd_id['w_size_id']) ){
            $cookie_data[$key]->qty=$prd_id['qty'];
            $ex=1;
            $k=$key;
            }
                
        }
        if($ex==1){
             if(Auth::guard('customer')->check())
                    {
                    $this->manageAppWebUserCart(1,$prd_id);
                    }
              $cookie_data[$k]->qty=$prd_id['qty'];
                $mode=2;
        }
                  
            $json = json_encode($cookie_data);
            
            setcookie('productsInCart', $json, time() + ($minutes));
            return $mode;
	 }    
      }
   public function setCookie($prd_id){
       
     $minutes=60;
	 if ($cookie_data = Cookie::get('recent_views_products')) {
		
		$cookie_data=json_decode($cookie_data);
		if(!is_array($cookie_data))
		{
			//$data=[];
			$data = array();
			$data[] = $cookie_data;
		}else{
			$data = $cookie_data;
		}
		array_push($data, $prd_id);
	 } else {
		$data   = $prd_id;
	 }
	 
	// to store
	$json = json_encode($data); // convert to string

	 Cookie::queue('recent_views_products', $json, $minutes);
	 //Cookie::queue(Cookie::make('name', $data, $minutes));
	// Cookie::make('name', $data, $minutes); 
   }
   
   
   
   public function getCookie(){
     $val = Cookie::get('recent_views_products');
    
      return $val;
   }
   
   public function deleteCookie(){
     Cookie::forget('name');
   }
   
    public function getCartCookie( ){
		return @$_COOKIE["name1"];
   }
   
//   public function remove_cokkie_cart( ){
       
//         Cookie::forget('productsInCart');
//         $products_in_cart[]=array();
//             $json = json_encode($products_in_cart); 
//             setcookie('productsInCart', $json, time() + (0));
		 
//   }
   
   public function remove_cokkie_cart( ){
       setcookie("productsInCart", "", 1);
       unset($_COOKIE['productsInCart']);
        // $products_in_cart[]=array();
        //     $json = json_encode($products_in_cart); 
        //     setcookie('productsInCart', $json, time() + (0));
		 
   }
    public function getcustomCartCookie( ){
		return @$_COOKIE["productsInCart"];
   }
   
   
   public function deleteCartItem($prd_data){
         $minutes=(86400 * 30);
       $dt=$prd_data['prd_id'];
       $cart_data=explode('-',$dt);
       
            $cookie_data=@$_COOKIE["productsInCart"];
            $cookie_data=json_decode($cookie_data);
           
            $k=0;
            
             foreach($cookie_data as $key=>$products){
               if(($products->product_id==$cart_data[0]) && ($products->size_id==$cart_data[1]) && ($products->color_id==$cart_data[2]) && ($products->qty==$cart_data[3]) ){
                 $k=$key;
            }
                
        }
       
        if(Auth::guard('customer')->check())
                {
                $this->manageAppWebUserCart(2,$cart_data);
                }
                    unset($cookie_data[$k]);
                    $cookie_data= array_values((array)$cookie_data);
                    $json = json_encode($cookie_data);
                
                setcookie('productsInCart', $json, time() + ($minutes));
      
       
   }
   
   
    public function setcustomCartCookie($prd_id){
           
        $minutes=(86400 * 30);
	 if($cookie_data=@$_COOKIE["productsInCart"]){
	     
	 	$cookie_data=json_decode($cookie_data);
        $whole_products=array();
      
                $ex=0;
                $k=0;

        foreach($cookie_data as $key=>$products){
               
            if(($products->product_id==$prd_id['product_id']) && ($products->size_id==$prd_id['size_id']) && ($products->color_id==$prd_id['color_id']) && ($products->w_size_id==$prd_id['w_size_id'])  ){
            $cookie_data[$key]->qty=$prd_id['qty'];
            $ex=1;
            $k=$key;
            }
                
        }
        
        if($ex==1){
              $cookie_data[$key]->qty=$prd_id['qty'];
                $mode=2;
        } else{
            array_push($cookie_data,$prd_id);
            $mode=1;
        }
                  
                $json = json_encode($cookie_data);
            
                setcookie('productsInCart', $json, time() + ($minutes));
                return $mode;
	 } else {
	      
            $products_in_cart[]=$prd_id;
            $json = json_encode($products_in_cart); 
            setcookie('productsInCart', $json, time() + ($minutes));
            return 1;
           
	 }
    }
 


}
	
