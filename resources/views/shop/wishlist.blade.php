@extends('layouts.main')
@section('title', 'Wishlist')
@section('css')
<style>

</style>
@endsection
@section('content')

<section class="inner_banner">
    <div class="container">
        <div class="innerBanner_content">
            <h1>Your Wishlist</h1>
            <p>Home / Your Wishlist</p>
        </div>
    </div>
</section>

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
		@if(count($wishlist) > 0)
	    <div class="container">
	        <table class="table table-responsive table-striped">
	            <thead>
	                <tr>
	                    <th colspan="1" scope="col">Products</th>
	                    <th scope="col">Price</th>
	                    <th scope="col">Stock Status</th>
	                    <th scope="col"></th>
	                </tr>
	            </thead>
	            <tbody>
	            @foreach($wishlist as $key=>$value)
	            
	            <?php
                    $prod = DB::table('products')->where('id',$value->product_id)->first();
                    $prod_image = App\Product::where('id', $value->product_id)->first();
                ?>


    	                <tr>
    	                    <form method="post" action="{{ route('save_cart') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="product_id" id="product_id" value="{{ $prod->id }}">  
                            <input type="hidden" max="10000" min="1" class="count" name="quantity" value="1" id="quantity-select" step="1" >
    	                    <td colspan="1">
    	                        <div class="cart_item">
    	                            <div class="cartProduct_image">
    	                                <img src="{{ asset($prod_image->image) }}" alt="" class="img-fluid">
    	                                <span>
    	                                	<a href="{{route('wishlist.add',['id' => $prod->id])}}">
    	                                		<i class="fa-solid fa-xmark"></i>
    	                                	</a>
    	                                </span>
    	                            </div>
    	                            <p class="cartProduct_name"><a target="_blank" href="{{ route('shopDetail', ['id' => $prod->id, 'slug' => str_slug($prod->product_title , '-')]) }}">{{ $prod->product_title }}</a></p>
    	                        </div>
    	                    </td>
    	                   
    	                    
    	                    <td class="table_smallBox">{{$prod->actual_price }}</td>
    	                    <td class="table_smallBox" colspan="1">
    	                        {{ $prod->stock}} in stock
    	                    </td>
    	                    <td class="table_smallBox"><button class="gold_btn" type="submit">Add To Cart</button></td>
	                        </form>
    	                </tr>

	                 @endforeach
	               
	                
	               
	            </tbody>
	        </table>
	    </div>
	    @else
	    <div class="container">
	        <h3 class="text-center">No Produsts In Wishlist!</h3>
	    </div>
	    @endif
	</section>



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


	

</script>

<script>
function myFunction() {
  alert("Please Calculate Shipping First!");
}
</script>

@endsection

