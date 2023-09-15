    <div class="shopping-cart" style="display: none;">
            <div class="shopping-cart-header">
              <i class="fa fa-shopping-cart cart-icon"></i><span class="badge">3</span>
              <div class="shopping-cart-total">
              <span class="lighter-text">Total:</span>
              <span class="main-color-text"><i class="fa fa-rupee"></i> 2,229.97</span>
              </div>
            </div> 
            <!--end shopping-cart-header -->
		<ul class="shopping-cart-items">
		  <li>
			<img src="{{ asset('public/fronted/images/cart-item1.jpg') }}" alt="item1" />
			<span class="item-name">Sony DSC-RX100M III</span>
			<span class="item-price"><i class="fa fa-rupee"></i> 849.99</span>
			<span class="item-quantity">Quantity: 01</span>
			<span class="item-remove"><a href="#"><i class="fa fa-trash"></i></a></span>
		  </li>

		  <li>
			<img src="{{ asset('public/fronted/images/cart-item2.jpg') }}" alt="item1" />
			<span class="item-name">KS Automatic Mechanic...</span>
			<span class="item-price"><i class="fa fa-rupee"></i> 1,249.99</span>
			<span class="item-quantity">Quantity: 01</span>
			<span class="item-remove"><a href="#"><i class="fa fa-trash"></i></a></span>
		  </li>

		  <li>
			<img src="{{ asset('public/fronted/images/cart-item3.jpg') }}" alt="item1" />
			<span class="item-name">Kindle, 6" Glare-Free To...</span>
			<span class="item-price"><i class="fa fa-rupee"></i> 129.99</span>
			<span class="item-quantity">Quantity: 01</span>
			<span class="item-remove"><a href="#"><i class="fa fa-trash"></i></a></span>
		  </li>
		</ul>
		<div class="previewCartAction">			
			<div class="previewCartAction-checkout">
				<a href="{!! url('/checkout'); !!}" class="button">Checkout</a>
			</div>
			<div class="previewCartAction-viewCart">
				<a href="{!! url('/cart'); !!}" class="button1">View Cart</a>
			</div>
		</div>
	</div> 
          