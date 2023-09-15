
<div class="table sellettbl ordertabl">
    <div class="theader">
        <div class="table_header" style="width:50%;">Product</div>
        <div class="table_header">Seller</div>
        <div class="table_header">Price</div>
        <div class="table_header" style="width: 18%;"> </div>
    </div>
@foreach($datas as $product)
    <div class="table_row">
        <div class="table_small">
            <div class="table_cell">Product</div>
            <div class="table_cell">{{$product->name}}</div>

        </div>
        <div class="table_small">
          <div class="table_cell">Seller</div>
          <div class="table_cell">{{$product->seller_name}}</div>
        </div>
        <div class="table_small">
          <div class="table_cell">Price</div>
            <div class="table_cell"><div>
                @if ($product->price!='' && $product->price!=0)
                    <del><span><i class="fa fa-inr"></i>{{$product->price}}</span></del>
                <i class="fa fa-inr"></i><em id="prd_price_{{$product->id}}">{{$product->spcl_price}}</em>
                @else <i class="fa fa-inr"></i><span id="prd_price_{{$product->id}}">{{$product->spcl_price}}</span>
                @endif </div>
            </div>
        </div>
        <div class="table_small">
          <div class="table_cell">Order Id</div>
            <div class="table_cell">
                <input type="hidden" name="qty" min="1" max="5" value="1" id="prd_qty_{{$product->id}}">
                    <span id="back_response_msg_{{$product->id}}"></span>
                    <button type="submit" value="submit"
                    class="btn-danger btn add-to-cart-btn addTocart hideCartButtton"
                    prd_page='0'
                    url="{{$product::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}"
                    prd_index='{{$product->id}}' 
                    prd_id='{{$product->id}}'
                    prd_type="{{$product->product_type}}"
                    size_require="{!!App\Products::Issize_requires($product->id)!!}"
                    color_require="{!!App\Products::Iscolorrequires($product->id)!!}"
                    size_id="{!!$product::getFirstattrId('Sizes',$product->id)!!}"
                    color_id="{!!$product::getFirstattrId('Colors',$product->id)!!}"
                    ><i class="fa fa-shopping-cart"></i> Add to cart</button>
            </div>
        </div>

    </div>
     @endforeach
									  									  
</div>

        @include('fronted.includes.addtocartscript')
      


