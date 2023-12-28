@extends('layouts.main')
@section('title', 'Checkout')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" integrity="sha512-wJgJNTBBkLit7ymC6vvzM1EcSWeM9mmOu+1USHaRBbHkm6W9EgM0HY27+UtUaprntaYQJF75rc8gjxllKs5OIQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
  .payment-accordion img {
    display: inline-block;
    margin-left: 10px;
    background-color: white;
  }
  form#order-place .form-control {
    border-width: 1px;
    border-color: rgb(150, 163, 218);
    border-style: solid;
    border-radius: 8px;
    background-color: transparent;
    height: 54px;
    padding-left: 15px;
    font-family: HelveticaNeueLTStd-Lts;
    color: black;
  }
  form#order-place textarea.form-control {
      height: auto !important;
  }

  .checkoutPage {
      padding: 50px 0px;
  }
  .checkoutPage .section-heading h3{
      margin-bottom: 30px;
  }
  .YouOrder {
      background-color: #c91d22;
      color: white;
      padding: 25px;
      padding-bottom: 2px;
      min-height: 300px;
      border-radius: 3px;
      margin-bottom: 20px;
  }
  .amount-wrapper {
      padding-top: 12px;
      border-top: 2px solid white;
      text-align: left;
      margin-top: 90px;
  }

  .amount-wrapper h2 {
      font-size: 20px;
      display: flex;
      justify-content: space-between;
  }
  .amount-wrapper h3 {
      display: FLEX;
      justify-content: SPACE-BETWEEN;
      font-size: 22px;
      border-top: 2px solid white;
      padding-top: 10px;
      margin-top: 14px;
  }
  .checkoutPage span.invalid-feedback strong {
      color: #721c24;
      background-color: #f8d7da;
      border-color: #f5c6cb;
      display: block;
      width: 100%;
      font-size: 15px;
      padding: 5px 15px;
      border-radius: 6px;
  }
  .payment-accordion .btn-link {
    display: block;
    width: 100%;
    text-align: left;
    padding: 10px 19px;
    color: black;
  }

  .payment-accordion .card-header {
      padding: 0px !important;
  }
  .payment-accordion .card-header:first-child{
    border-radius: 0px;
  }
  .payment-accordion .card{
    border-radius: 0px;
  }
  .form-group.hide {
    display: none;
  }
  .StripeElement {
    box-sizing: border-box;
    height: 40px;
    padding: 10px 12px;
    border: 1px solid transparent;
    border-radius: 4px;
    background-color: white;
    box-shadow: 0 1px 3px 0 #e6ebf1;
    -webkit-transition: box-shadow 150ms ease;
    transition: box-shadow 150ms ease;
    border-width: 1px;
    border-color: rgb(150, 163, 218);
    border-style: solid;
    margin-bottom: 10px;
  }

  .StripeElement--focus {
    box-shadow: 0 1px 3px 0 #cfd7df;
  }

  .StripeElement--invalid {
    border-color: #fa755a;
  }

  .StripeElement--webkit-autofill {
    background-color: #fefde5 !important;
  }
  div#card-errors {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
    display: block;
    width: 100%;
    font-family: HelveticaNeueLTStd-Lts;
    font-size: 15px;
    padding: 5px 15px;
    border-radius: 6px;
    display: none;
    margin-bottom: 10px;
  }
  div#shipping_info {
      display: none;
  }
    #shipping_info .select2-container {
        width: 75% !important;
    }
    .shop_more {
        background-color: #105aaa;
        padding: 10px 30px;
        border-radius: 8px;
        color: white;
        font-family: 'Cinzel', serif;
        font-weight: bold;
        margin-bottom: 20px;
    }
</style>
@endsection
@section('content')

<section class="inner_banner">
    <div class="container">
        <div class="innerBanner_content">
            <h1>Checkout</h1>
            <p>Home / Checkout</p>
        </div>
    </div>
</section>

<section class="checkout_sec">
    <div class="container">
        <form action="{{route('order.place')}}" method="POST" id="order-place">
            @csrf
            <input type="hidden" name="payment_status" value="" />
            <input type="hidden" name="payment_id" value="" />
            <input type="hidden" name="payer_id" value="" />
            <input type="hidden" name="order_total" value="" />
            <input type="hidden" name="shipping_tax" value="" />
            <input type="hidden" name="payment_method" value="paypal" />
            <div class="row">
                <div class="col-md-12">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-12 col-lg-8 mb-4">
                    @if(!Auth::check())
                        <a href="{{ url('signin') }}" target="_blank" class="runningBtn gold_btn">Existing user? Sign In</a>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="checkoutField_box mb-4">
                                <label for="fname">First Name *</label>
                                <input type="text" name="first_name" id="fname" class="checkout_field" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="checkoutField_box mb-4">
                                <label for="lname">Last Name *</label>
                                <input type="text" name="last_name" id="lname" class="checkout_field" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkoutField_box mb-4">
                                <label>Email Address *</label>
                                <input type="email" name="email" class="checkout_field" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkoutField_box mb-4">
                                <label for="company_name">Company Name (optional)</label>
                                <input type="text" name="company_name" id="company_name" class="checkout_field">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkoutField_box mb-4">
                                <label for="country">Country *</label>
                                <select id="country" name="country" class="checkout_field select2" required>
                                    @foreach($countries as $key => $value)
                                    <option value="{{ $value->name }}" {{ $value->sortname == 'US' ? 'selected' : ' '}}>{{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkoutField_box mb-4">
                                <label for="address">Address *</label>
                                <div class="field_box">
                                    <input type="text" name="address_line_1" id="street_address" class="checkout_field mb-4" placeholder="Street Address" required>
                                    <input type="text" name="address_line_2" id="address" class="checkout_field" placeholder="Apartment, Suite, Unite, etc (Optional)">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkoutField_box mb-4">
                                <label>Town / City *</label>
                                <input type="text" name="city" class="checkout_field" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkoutField_box mb-4">
                                <label>Country / States *</label>
                                <input type="text" name="state" class="checkout_field" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkoutField_box mb-4">
                                <label>Postcode / Zip *</label>
                                <input type="text" name="zip_code" class="checkout_field" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkoutField_box mb-4">
                                <label>Phone *</label>
                                <input type="tel" name="phone_no" class="checkout_field" required>
                            </div>
                        </div>
                    </div>
                    @if(!Auth::check())
                    <div class="checkBox_item mb-2">
                        <input type="checkbox" name="create_account" id="create_account">
                        <label for="create_account">Create An Account?</label>
                    </div>
                    <div class="checkBox_item mb-4">
                        <input type="checkbox" name="vip_insider" id="vip_insider" checked>
                        <label for="vip_insider">Become a KIM'S GOLD DUST VIP Insider!</label>
                    </div>
                    <p class="paragraph mb-4">Create an account by entering the information below. If you are a returning customer please login at the top</p>
                    <div class="checkoutField_box mb-4">
                        <label>Account Password *</label>
                        <input id="password" type="password" name="password" class="checkout_field">
                    </div>
                    @endif
                    <div class="checkBox_item mb-4">
                        <input type="checkbox" name="different_address" id="different_address">
                        <label for="different_address">Ship to a different address?</label>
                    </div>
                    <div class="shipping_info" id="shipping_info">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="checkoutField_box mb-4">
                                    <label for="shipping_fname">First Name *</label>
                                    <input type="text" name="shipping_first_name" id="shipping_fname" class="checkout_field">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="checkoutField_box mb-4">
                                    <label for="shipping_lname">Last Name *</label>
                                    <input type="text" name="shipping_last_name" id="shipping_lname" class="checkout_field" >
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="checkoutField_box mb-4">
                                    <label for="shipping_company_name">Company Name</label>
                                    <input type="text" name="shipping_company_name" id="shipping_company_name" class="checkout_field">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="checkoutField_box mb-4">
                                    <label for="shipping_country">Country *</label>
                                    <select id="shipping_country" name="shipping_country" class="checkout_field select2">
                                        @foreach($countries as $key => $value)
                                        <option value="{{ $value->name }}" {{ $value->sortname == 'US' ? 'selected' : ' '}}>{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="checkoutField_box mb-4">
                                    <label for="shipping_address">Address *</label>
                                    <div class="field_box">
                                        <input type="text" name="shipping_address_line_1" id="shipping_street_address" class="checkout_field mb-4" placeholder="Street Address">
                                        <input type="text" name="shipping_address_line_2" id="shipping_address" class="checkout_field" placeholder="Apartment, Suite, Unite, etc (Optional)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="checkoutField_box mb-4">
                                    <label>Town / City *</label>
                                    <input type="text" name="shipping_city" class="checkout_field">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="checkoutField_box mb-4">
                                    <label>Country / States</label>
                                    <input type="text" name="shipping_shipping_state" class="checkout_field">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="checkoutField_box mb-4">
                                    <label>Postcode / Zip *</label>
                                    <input type="text" name="shipping_zip_code" class="checkout_field">
                                </div>
                            </div>
                        </div>
                        <!--<div class="checkoutField_box mb-4">-->
                        <!--    <label>Phone *</label>-->
                        <!--    <input type="tel" name="shipping_phone_no" class="checkout_field">-->
                        <!--</div>-->
                    </div>
                    <div class="form-group checkoutField_box">
                        <label>Order notes (optional)</label>
                        <textarea class="checkout_field" id="comment" name="order_notes" placeholder="Notes about your order, e.g. special notes for delivery." rows="5"></textarea>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-4 mb-4">
                    @if($remaining_price != null)
                    <div class="shop_more">
                    @if($remaining_price->type == 0)
                        <p>Shop More (${{ $remaining_price->checkout_max_price - $subtotal }}) to avail ${{ $remaining_price->price }} discount</p>
                    @else
                        <p>Shop More (${{ $remaining_price->checkout_max_price - $subtotal }}) to avail {{ $remaining_price->price }}% discount</p>
                    @endif
                    </div>
                    @endif
                    @if(count($getcheckoutbonus_price) == 0)
                    <p class="paragraph mb-4">Enter your coupon code if you have one</p>
                    <div class="coupan_codeBox">
                        <input type="text" class="discount_code checkout_field" name="discount_code" placeholder="Coupon Code"  value="{{ Session::has('discount') ? Session::get('discount')['code'] : '' }}">
                        <button type="button" class="gold_btn" id="apply_discount_code">Apply</button>
                    </div>
                    @endif
                    <div id="error-discount"></div>
                    <div class="order_summary">
                        <div class="summary_heading mb-4 text-center">
                            <p class="black_heading28">Your Order</p>
                        </div>
                        <div class="order_innerHeading border_bottom_light">
                            <p>Product</p>
                            <p>Total</p>
                        </div>
                        @php
                        $subtotal = 0;
                        $addon_total = 0;
                        $variation = 0;
                        @endphp
                        @foreach($cart as $key=>$value)
                        <div class="orderSummary_item border_bottom_light">
                            <p>{{ $value['name'] }} x {{ $value['qty'] }} x 1</p>
                            <span>
                                @if($value['discountPrice'] != 0)
                                    ${{ ($value['baseprice'] == 0 ? $value['variation_price'] : ($value['discountPrice'] )) * $value['qty'] }}  <strong>({{ ($value['discountType'] == 2 ? '-' . $value['discountPercentage'] . '%' : ' ') }} Off)</strong>
                                @else
                                    ${{ ($value['baseprice'] == 0 ? $value['variation_price'] : ($value['baseprice'] )) * $value['qty'] }}
                                @endif
                            </span>
                        </div>
                        @php
                            if($value['discountPrice'] != 0){
                            
                                $subtotal += ($value['baseprice'] == 0 ? $value['variation_price'] : ($value['discountPrice'] )) * $value['qty'];
                            }else{
                                $subtotal += ($value['baseprice'] == 0 ? $value['variation_price'] : ($value['baseprice'] )) * $value['qty'];
                            }
                        @endphp
                        @endforeach
                        <div class="orderSummary_item border_bottom_light" style="border-top: 2px solid #000;">
                            <h4>Cart Subtotal</h4>
                            <span>${{$subtotal}}</span>
                            <input type="hidden" name="tax-cart-price" id="tax-cart-price" value="{{$subtotal}}">
                        </div>
                        <div class="orderSummary_item border_bottom_light">
                            <h4>Discount Coupon Price</h4>
                            <h3>-$<span id="display-dicount">{{ Session::has('discount') ? Session::get('discount')['price'] : '00.00' }}</span></h3>
                        </div>
                        <div class="orderSummary_item border_bottom_light">
                            <h4>Tax</h4>
                            <h3>+$<span id="display-tax">00.00</span></h3>
                        </div>
                        @if(count($getcheckoutbonus_price) != 0)
                        <div class="orderSummary_item border_bottom_light">
                            <h4>Avail Discount ({{ $getcheckoutbonus_price['discount_price'] }}{{ ($getcheckoutbonus_price['type'] == 0 ? '$' : '%') }})</h4>
                            <h3 id="avail_discount">-${{ $getcheckoutbonus_price['price'] }}</h3>
                        </div>
                        @endif
                        <div class="orderSummary_item border_bottom_light">
                            <h4>Shipping and Handling</h4>
                            <h4 id="shipping_amount">$0</h4>
                        </div>
                        <div class="orderSummary_item border_bottom_dark">
                            <p>ORDER TOTAL</p>
                            @php
                            $final_total = $subtotal - (Session::has('discount') ? Session::get('discount')['price'] : 00.00);
                            if(count($getcheckoutbonus_price) != 0){
                                $final_total = $final_total - $getcheckoutbonus_price['price'];
                            }
                            @endphp
                            <input type="hidden" class="totalPrice" value="{{ $final_total }}">
                            <span>$<span id="order_total_show">{{ $final_total }}</span></span>
                        </div>
                    </div>
                    <div class="shipping-box" id="shipping-box">

                    </div>  
                    <div class="radio_point mb-3">
                        <div id="paypal-button-container"></div>
                    </div>
                    <!-- <div class="checkout_button text-center">
                        <button type="submit" class="gold_btn">Place Order</button>
                    </div> -->
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js" integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://www.paypal.com/sdk/js?client-id={{env('PAYPAL_CLIENT_ID')}}&components=buttons&enable-funding=paylater,credit,card"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
  $(document).ready(function() {
    $('.select2').select2();
  });
</script> 
<script>

    var shipping_amount = 0;

    var paypalActions;
    paypal.Buttons({
        style: {
            label: 'checkout',
            size:  'responsive',  
            shape: 'rect',    
            color: 'gold'   
        },
        env: "{{env('PAYPAL_PRODUCTION')}}", //production
        createOrder: function(data, actions) {
            // Set up the transaction
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: parseFloat('{{((float)$final_total)}}') + shipping_amount + state_tax,
                    }
                }]
            });
        },
        // onClick: function(data, actions) {
        //     if (checkEmptyFileds() == 1) {
        //         $.toast({
        //             heading: 'Alert!',
        //             position: 'bottom-right',
        //             text: 'Please fill the required fields before proceeding to pay',
        //             loaderBg: '#ff6849',
        //             icon: 'error',
        //             hideAfter: 5000,
        //             stack: 6
        //         });

        //         return actions.reject();
        //     } else {
        //         return actions.resolve();

        //     }

        // },

        onApprove(data, actions) {
         return actions.order.capture()
                    .then(function (details) {
                        if(details['status'] == "COMPLETED")
                        {
                            toastr.success('Payment Authorized! Thank You For Booking')
                            $('#order-place').submit()
                        }
                        else{
                            toastr.error('Something went wrong');
                        }

                    });
            
            // $('input[name="payment_status"]').val('Completed');
            // $('input[name="payment_id"]').val(data.orderID);
            // $('input[name="payer_id"]').val(data.payerID);
            // $('input[name="order_total"]').val(parseFloat('{{((float)$final_total)}}') + parseFloat(shipping_amount));
            // $('input[name="shipping_tax"]').val(shipping_amount);
            // $('input[name="payment_method"]').val('paypal');
            // $('#order-place').submit();
        }
    }).render('#paypal-button-container');
    
    $('#different_address').click(function(){
        if($('#different_address').is(":checked")){
            $('#shipping_info').show();
        }else{
            $('#shipping_info').hide();
        }
    });
    function checkEmptyFileds() {
        var errorCount = 0;
        $('form#order-place').find('.checkout_field').each(function() {
            if ($(this).prop('required')) {
                if (!$(this).val()) {
                    $(this).addClass('checkout_field_required');
                    errorCount = 1;
                }
            }
        });
        if($('#different_address').is(":checked")){
            $('#shipping_info').find('.checkout_field').each(function() {
                if (!$(this).val()) {
                    $(this).addClass('checkout_field_required');
                    errorCount = 1;
                }
            });
        }
        if ($('#create_account').is(":checked")) {
            if ($('#password').val() == '') {
                $('#password').addClass('checkout_field_required');
                errorCount = 1;
            }
        }

        if($('input[name="shipping"]').length != 0){
          var shipping = $('input[name="shipping"]');
          var ship_array = []; 
          for(var i = 0; i < shipping.length; i++){
              if(shipping[i].checked){
                ship_array.push(1);
              }else{
                ship_array.push(0);
              }
          }
          if($.inArray(1, ship_array) >= 0) {

          } else {
            $('#shipping-box').addClass('required');
            errorCount = 1;
          }
        }
        return errorCount;
    }
      
    
      var stripe = Stripe('{{config("services.stripe.key")}}');
      var elements = stripe.elements();
      var style = {
        base: {
          color: '#32325d',
          lineHeight: '18px',
          fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
          fontSmoothing: 'antialiased',
          fontSize: '16px',
            '::placeholder': {
            color: '#aab7c4'
          }
        },
        invalid: {
          color: '#fa755a',
          iconColor: '#fa755a'
        }
      };
      var card = elements.create('card', {style: style});

  if($('#card-element').length != 0){
    card.mount('#card-element');
    card.addEventListener('change', function(event) {
      var displayError = document.getElementById('card-errors');
      if (event.error) {
        $(displayError).show();
        displayError.textContent = event.error.message;
      } else {
        $(displayError).hide();
        displayError.textContent = '';
      }
    });
  }

  var form = document.getElementById('order-place');

  $('#stripe-submit').click(function(){
    stripe.createToken(card).then(function(result) {
      var errorCount = checkEmptyFileds();
      if ((result.error) || (errorCount == 1)) {
        // Inform the user if there was an error.
        if(result.error){
          var errorElement = document.getElementById('card-errors');
          $(errorElement).show();
          errorElement.textContent = result.error.message;
        }else{
          $.toast({
            heading: 'Alert!',
            position: 'bottom-right',
            text:  'Please fill the required fields before proceeding to pay',
            loaderBg: '#ff6849',
            icon: 'error',
            hideAfter: 5000,
            stack: 6
          });
        }
      } else {
        // Send the token to your server.
        stripeTokenHandler(result.token);
      }
    });
  });

  function stripeTokenHandler(token) {
    // Insert the token ID into the form so it gets submitted to the server
    var form = document.getElementById('order-place');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);
    form.submit();
  }

  $(document).ready(function(){
    $('#country').change();
  });

  $(document).on('click', "#apply_discount_code", function(e){
    if($("input[name=discount_code]").val() != ''){
      var data = $("input[name=discount_code]").val();
    alert(data)
      $.ajax({
            url: "{{ route('discount') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                code: data,
            },
            success: function(response) {
                if (response.status) {
                  $('#error-discount').html('<div class="mt-2 alert alert-success">'+response.message+'</div>');
                  $('#display-dicount').text(response.data.price);
                  console.log($('#order_total_show').text(response.data.after_discount_price));
                } else {
                  $('#error-discount').html('<div class="mt-2 alert alert-danger">'+response.message+'</div>');
                }
            },
        });
    }else{
      $("input[name=discount_code]").parent().addClass('required');
    }
  });
    var state_tax = 0;
    
    $('input[name=state]').on('blur', function() {
        var countryVal = $('#country').val();
        var final_total = "{{((float)$final_total)}}";
        // alert(final_total)
        $.ajax({
            url: "{{ route('check.tax') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                state: $(this).val()
            },
            success: function(response){
                if(response.status && response.data.country_name == countryVal){
                    var total_price = $('#tax-cart-price').val();
                    var percentage = response.data.percentage;
                    var tax_price = (percentage/100) * total_price;
                    console.log(tax_price);
                    state_tax = tax_price;
                    console.log($('#order_total_show').text(parseFloat($('#order_total_show').text()) + state_tax));
                    $('.totalPrice').val(parseFloat($('.totalPrice').val()) + tax_price)
                    $('#display-tax').text(parseFloat(tax_price.toFixed(2)));
                }else{
                    state_tax = 0;
                    $('.totalPrice').val(parseFloat(final_total));
                    console.log($('#order_total_show').text(parseFloat(final_total)));
                    $('#display-tax').text('00.00');
                }
            }
        });
    });

  $('#country').on('change', function() {
    var country = $(this).val();
    var totalPrice = $('.totalPrice').val();
    var final_total = "{{((float)$final_total)}}";
    var state = $('input[name=state]').val();
    $.ajax({
            url: "{{ route('check.tax') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                state: state
            },
            success: function(response){
                if(response.status && response.data.country_name == country){
                    var total_price = $('#tax-cart-price').val();
                    var percentage = response.data.percentage;
                    var tax_price = (percentage/100) * total_price;
                    state_tax = tax_price;
                    console.log($('#order_total_show').text(parseFloat($('#order_total_show').text()) + state_tax));
                    $('.totalPrice').val(parseFloat($('.totalPrice').val()) + tax_price)
                    $('#display-tax').text(parseFloat(tax_price.toFixed(2)));
                }else{
                    state_tax = 0;
                    $('.totalPrice').val(parseFloat(final_total));
                    console.log($('#order_total_show').text(parseFloat(final_total)));
                    $('#display-tax').text('00.00');
                }
            }
    });
    
    $.ajax({
      url: "{{ route('shippingTax') }}",
      type: "POST",
      data: {
        "_token": "{{ csrf_token() }}",
        country: country,
        totalPrice: totalPrice,
      },
      success: function(response) {
        if (response.status) {
          var shippingTax = response.shippingTax;
          var final_total = "{{((float)$final_total)}}";
          console.log($('#order_total_show').text(final_total));
          if(shippingTax.length != 0){
            $('#shipping-box').html('<div class="summary_heading text-center">\
                            <p class="black_heading28">Shipping Fee</p>\
                        </div>');
          }else{
            $('#shipping_amount').text('');
            $('#shipping-box').html('');
          }

          if(shippingTax.length == 0){
            $('#shipping_amount').text('Free Shipping');
          }
          for(var i = 0; i < shippingTax.length; i++){
            var checked = '';
            if(shippingTax.length == 1){
              checked = 'checked';
              console.log($('#order_total_show').text(parseFloat(final_total) + parseFloat(shippingTax[i].row_cost)));
              $('#shipping_amount').html('+$' + shippingTax[i].row_cost);
            }
            $('#shipping-box').append('<div class="orderSummary_item border_bottom_light">\
                        <h4><input class="form-check-input" type="radio" name="shipping" id="exampleRadios'+shippingTax[i].id+'" value="'+shippingTax[i].zone_name+'" onclick="shippingCheck(this)" ' + checked + '>\
                        <label class="form-check-label" for="exampleRadios'+shippingTax[i].id+'">'+shippingTax[i].zone_name+ '</label></h4>\
                        <h3>+$<span class="ship-row-cost">'+shippingTax[i].row_cost+'</span></h3>\
                      </div>');
          }
        }else{

        }
      },
    });
  });

  function shippingCheck(a){
    var final_total = "{{((float)$final_total)}}";
    var shipPrice = $(a).parent().next().find('span').text();
    console.log($('#order_total_show').text((parseFloat(final_total) + parseFloat(shipPrice) + state_tax).toFixed(2)));
    $('#shipping_amount').html('+$' + shipPrice);
    shipping_amount = parseFloat(shipPrice);
  }
</script>
@endsection