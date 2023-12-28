@extends('layouts.main')
@section('title', 'Cart')
@section('css')
<style>
.prdTitle{
    gap: 15px;
    padding: 15px 0px 0 10px;
}
a.add_saveforlater {
    text-decoration: none;
    color: black;
}

a.add_saveforlater:hover {
    color: #e2b020;
}
#ywsfl_container_list {
    width: 100%;
    min-height: 100px;
    max-height: 100%;
}
#ywsfl_container_list #row-11359 {
    border-top: 1px solid #ccc;
    clear: left;
    margin-bottom: 20px;
    height: 100px;
}
.row .delete_col {
    position: relative;
    top: 50%;
}
.row .delete_col a {
    display: block;
    border: 1px solid #ccc;
    color: #ccc;
    width: 20px;
    height: 20px;
    text-align: center;
    font-size: 14px;
    line-height: 20px;
}
#ywsfl_container_list .row .delete_col, #ywsfl_container_list .row .sub_container_product {
    float: left;
}
.sub_container_product {
    margin-left: 35px;
}
.row .sub_container_product .product_name .display_name {
    font-weight: bold;
}
.row .display_product_status .savelist-in-stock {
    color: green;
}
.mainHead h3 {
    font-size: 25px;
    font-weight: 400;
    font-family: 'coolvetica', sans-serif;
    text-transform: capitalize;
    color: #000;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
}
</style>
@endsection
@section('content')

<section class="inner_banner">
    <div class="container">
        <div class="innerBanner_content">
            <h1>Your Cart</h1>
            <p>Home / Your Cart</p>
        </div>
    </div>
</section>
<form method="post" action="{{ route('update_cart') }}" id="update-cart">
	{{ csrf_field() }}
	<section class="cart_sec1">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					@if(Session::has('message'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
					@endif
				</div>
			</div>
		</div>	
	    <div class="container">
	        <table class="table table-responsive table-striped">
	            <thead>
	                <tr>
	                    <th scope="col"> </th>
	                    <th scope="col">Products</th>
	                    <th scope="col">Size</th>
	                    <th scope="col">Color</th>
	                    <th scope="col">Price</th>
	                    <th colspan="1" scope="col">Quantity</th>
	                    <th scope="col">Total</th>
	                </tr>
	            </thead>
	            <tbody>
	            	@php
	            	@endphp
	            	<?php $subtotal  = 0; $addon_total = 0; $total_variation = 0;?>
	            	@foreach($cart as $key => $value)
	            	@php
	            	$var_id = 0;
	            	@endphp
	            	@if($value['variation'] != null)
	            	@foreach($value['variation'] as $key=>$values)
	            	@php
	            	$var_id = $key;
	            	@endphp
					@endforeach 
					@endif
					@php
					if($value['variation'] == null){
						$image = asset($value['image']);
					}else{
						$image = $value['variation'][$var_id]['image'];
						if($image == null){
							$image = asset($value['image']);
						}else{
							$image = asset($value['variation'][$var_id]['image']);
						}
					}
					@endphp
	                <tr>
	                    <td>
	                        <div class="cart_item ">
	                            <div class="cartProduct_image">
	                                <img src="{{ $image }}" alt="" class="img-fluid">
	                                <span>
	                                	<a href="{{ route('remove_cart',['id' => $value['cart_id']]) }}">
	                                		<i class="fa-solid fa-xmark"></i>
	                                	</a>
	                                </span>
	                            </div>
	                       </div>
	                    </td>
	                    <td >
                            <div class="text-center prdTitle">
	                            <p class="cartProduct_name">
	                                <a target="_blank" href="{{ route('shopDetail', ['id' => $value['id'], 'slug' => str_slug($value['name'] , '-')]) }}">{{ $value['name'] }}</a>
	                            </p>
	                        </div>    
	                        
	                        <div class="saveforlater_button prdTitle text-center">
                                <a href="{{route('customer.save.product',['id'=>$value['id'],'cartId'=>$value['cart_id']])}}" rel="nofollow" class="add_saveforlater" title="Save for Later">Save for Later</a>
                            </div>
	                    </td>
	                    <td class="table_smallBox">
	                    	@php
	                    	$color = null;
	                    	@endphp
	                    	@foreach($value['variation'] as $key=>$values)
	                    	@if($values['attribute'] == 'COLOR')
	                    	@php
	                    	$color = $values['attribute_val'];
	                    	@endphp
	                    	@else
	                    	{{$values['attribute_val']}}
	                    	@endif
							@endforeach 
	                    </td>
	                    <td class="table_smallBox">
	                    	{{ $color }}
	                    </td>
	                    <td class="table_smallBox"> 
	                    @if($value['discountPrice'] != 0)
	                    ${{ $value['baseprice'] == 0 ? $value['variation_price'] : $value['discountPrice']  }}
	                    @else
	                    ${{ $value['baseprice'] == 0 ? $value['variation_price'] : $value['baseprice']  }}
	                    @endif
	                    
	                        @if($value['discountPrice'] != 0)
	                        - <del>${{ $value['baseprice'] == 0 ? $value['variation_price'] : $value['baseprice'] }}</del> {{ ( $value['discountType'] != 2 ? '$'.$value['baseprice'] - $value['discountPrice'] : ' ' ) }} <br>( {{ ($value['discountType'] == 2 ? '-' . $value['discountPercentage'] . '%' : ' ') }} Off) 
	                        @endif
	                    </td>
	                    <td class="table_smallBox" colspan="1">
	                        <div class="plus-minus">
	                            <span class="minus">
	                                <i class="fa-solid fa-square-minus"></i>
	                            </span>
	                            <input type="text" class="count" name="qty[]" value="{{ $value['qty'] }}" id="quantity-select">
	                            <span class="plus">
	                                <i class="fa-solid fa-square-plus"></i>
	                            </span>
	                        </div>
	                    </td>
	                    <td class="table_smallBox"> 
    	                    @if($value['discountPrice'] != 0)
    	                    ${{ $value['baseprice'] == 0 ? $value['variation_price'] * $value['qty'] : ($value['discountPrice'] ) * $value['qty'] }}
    	                    @else
    	                    ${{ $value['baseprice'] == 0 ? $value['variation_price'] * $value['qty'] : ($value['baseprice'] ) * $value['qty'] }}
    	                    @endif
	                    </td>
	                </tr>
	                <input type="hidden" name="product_id[]" id="" value="<?php echo $value['cart_id']; ?>">
	                @php
	                    if($value['discountPrice'] != 0){
						    $subtotal += ($value['baseprice'] == 0 ? $value['variation_price'] : ($value['discountPrice']) ) * $value['qty'];
	                    }else{
	                        $subtotal += ($value['baseprice'] == 0 ? $value['variation_price'] : ($value['baseprice']) ) * $value['qty'];
	                    }
					@endphp
	                @endforeach
	            </tbody>
	        </table>
	    </div>
	</section>
	<section class="cart_sec2">
	    <div class="container">
	        <div class="row">
	            <div class="col-12 col-md-4 col-lg-5 mb-4">
	                <div class="leftCol">
	                    <div class="discount_code_form">
	                        <input type="text" class="discount_code" name="discount_code" placeholder="Discount Code" value="{{ Session::has('discount') ? Session::get('discount')['code'] : '' }}">
	                        <button type="button" class="gold_btn" id="apply_discount_code">Apply</button>
	                    </div>
	                    <div id="error-discount"></div>
	                </div>
	                @if($savePrd != null)
	                @if(count($savePrd)>0))
                    <section class="saveForlater">
                        <div class="mainHead mb-2">
                            <h3>Save For Later<h3 />
                        </div> 
                        
                        @foreach($savePrd as $key => $item)
                        <div id="ywsfl_container_list">
                            <div id="row-11359" data-row-id="11359">
                                <div class="delete_col">
                                    <a href="{{route('remove.save.product',['id'=>$item['prd_id']])}}" class="remove_from_savelist" data-product-id="11359" title="Remove this product">×</a>
                                </div>
                                <div class="sub_container_product">
                                    <div class="product_name">
                                        <p class="display_name">{{$item['prd_name']}}</p>
                                        <p class="display_price">
                                        <span class="woocommerce-Price-amount amount">
                                            <bdi><span class="woocommerce-Price-currencySymbol">{!!$item['price']!!} </bdi>
                                        </span>
                                        </p> 
                                        @php $stock = $item['stock']; @endphp 
                                        <div class="d-flex justify-content-between">
                                            <p class="display_product_status"> @if(count($stock)>0) <span class="savelist-in-stock">In Stock</span> @else <span class="savelist-in-stock">Out Of Stock</span> @endif </p>
                                            <p class="text-danger"><a href="{{route('savePrdCart',['id'=>$item['prd_id']])}}"><i class="fa fa-shopping-cart"></i><span class="mr-1">Add To Cart</span></a></p>
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <hr />
                        @endforeach
                    </section>
                    @endif
                    @endif
	            </div>
	            <div class="col-12 col-md-8 col-lg-7">
	                <div class="rightCol">
	                    <div class="multi_buttons">
	                    	<button class="gold_btn" type="submit">Update Cart</button>
	                        <a href="{{ route('home') }}" class="gold_btn">Continue Shopping</a>
	                    </div>
	                    <div class="orderSummary">
	                        <div class="subtotal">
	                            <p>Subtotal</p>
	                            <span>${{$subtotal}}</span>
	                        </div>
	                        <div class="order_shipping">
	                            <div class="shipping_list">
	                                <p>
	                                    <i class="fa-solid fa-circle-dot"></i>
	                                    <span>Tax</span>
	                                </p>
	                                <span>+$00.00</span>
	                            </div>
	                            <div class="shipping_list">
	                                <p>
	                                    <i class="fa-solid fa-circle-dot"></i>
	                                    <span>Discount Price </span>
	                                </p>
	                                <span>-$<span id="display-dicount">{{ Session::has('discount') ? Session::get('discount')['price'] : '00.00' }}</span></span>
	                            </div>
	                        </div>
	                        <div class="order_total">
	                            <p>Total</p>
	                            <span>$<span id="order_total_show">{{$subtotal - (Session::has('discount') ? Session::get('discount')['price'] : 00.00) }}</span></span>
	                        </div>
	                        <div class="checkout_btn">
	                            <a href="{{ url('checkout') }}" class="gold_btn w-100">Proceed to Checkout</a>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</section>
</form>
@endsection

@section('js')

<script type="text/javascript">

	
 $(document).on('click', ".updateCart", function(e){

	 $('#type').val($(this).attr('data-attr'));
	 $('#update-cart').submit();
		
 });
 
 $(document).on('keydown keyup', ".qtystyle", function(e) {
	if ( $(this).val() <= 1 ) {
		e.preventDefault();
		$(this).val( 1 );	
	}

});


</script>

<script>

	function validate(evt) {
	  var theEvent = evt || window.event;

	  // Handle paste
	  if (theEvent.type === 'paste') {
		  key = event.clipboardData.getData('text/plain');
	  } else {
	  // Handle key press
		  var key = theEvent.keyCode || theEvent.which;
		  key = String.fromCharCode(key);
	  }
	  var regex = /[0-9]|\./;
	  if( !regex.test(key) ) {
		theEvent.returnValue = false;
		if(theEvent.preventDefault) theEvent.preventDefault();
	  }
	}

	$(document).on('click', "#apply_discount_code", function(e){
		if($("input[name=discount_code]").val() != ''){
			var data = $("input[name=discount_code]").val();
			$.ajax({
		        url: "{{ route('discount') }}",
		        type: "POST",
		        data: {
		            "_token": "{{ csrf_token() }}",
		            code: data,
		        },
		        success: function(response) {
		        	console.log(response);

		            if (response.status) {
		            	$('#error-discount').html('<div class="mt-2 alert alert-success">'+response.message+'</div>');
		            	$('#display-dicount').text(response.data.price);
		            	$('#order_total_show').text(response.data.after_discount_price);
		            } else {
		            	$('#error-discount').html('<div class="mt-2 alert alert-danger">'+response.message+'</div>');
		            }
		        },
		    });
		}else{
			$("input[name=discount_code]").parent().addClass('required');
		}
	});  
	
	
	$('input.qtystyle').on('input',function(e){
		// alert('Changed!')
		// alert($(this).val());
		// alert($(this).attr('data-attr-stock'));
		
		if( parseInt($(this).val()) >   parseInt($(this).attr('data-attr-stock')) ) {
			$(this).val(parseInt($(this).attr('data-attr-stock')));
			generateNotification('danger','please select only available '+parseInt($(this).attr('data-attr-stock'))+' items in stock');
		}
		
	});

</script>

<script>
function myFunction() {
  alert("Please Calculate Shipping First!");
}
</script>

@endsection

