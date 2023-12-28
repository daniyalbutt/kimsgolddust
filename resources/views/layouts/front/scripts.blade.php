<script src="{{ asset('js/bootstrap.min.js') }}"></script> 
<script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('js/stellarnav.min.js') }}"></script>
<script src="{{ asset('js/jquery.fancybox.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/aos.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.0/bootstrap-slider.min.js"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script>
    $(document).ready(function() {
        if(localStorage.getItem('popState') != 'shown'){
            $('.video-warpper').addClass('shown');
            let vid = document.getElementById("myVideo");
            vid.muted = true;
            document.getElementById('myVideo').addEventListener('ended',myHandler,false);
            localStorage.setItem('popState','shown')
        }
    });
    
    function myHandler(e){
        $('.video-warpper').removeClass('shown');
    }
</script>
<script>
  AOS.init();
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>

$('.megaMenu').on('mouseover mouseout focus', function () {
    $(this).find('#mega_menu').toggleClass('active');
    $(this).siblings().find('#mega_menu.active').removeClass('active');

});

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Front Scripts -->

<script>
@if (Session::has('message'))

    <script>
        $(document).ready(function() {

        toastr.{{ Session::get('message') }}
        ('{{ Session::get('message') }}');

        });
    </script>

@endif    
</script>

<script>

	function editableContent(){
		$('.editable').each(function(){
			$(this).append('<div class="editable-wrapper"><a href="javascript:" class="edit" title="Edit" onclick="editContent(this)"><i class="far fa-edit"></i></a><a href="javascript:" class="update" title="Update" onclick="updateContent(this)"><i class="far fa-share-square"></i></a></div>');
		});
	}

	function editContent(a){
		$(a).closest('.editable').attr('contenteditable', true);;
		$(a).closest('.editable-wrapper').attr('contenteditable', false);
		$(a).closest('.editable').focus();
	}

	function updateContent(a){
		var editableDiv = $(a).closest('.editable');
		var id = $(editableDiv).attr('data-id');
		var keyword = $(editableDiv).attr('data-name');
		var htmlcontent = $(editableDiv).clone(true);
		$(htmlcontent).find('.editable-wrapper').remove();
		sendData(id, keyword, $(htmlcontent).html());
	}

	function sendData(id, keyword, htmlContent){
		console.log(id);
		console.log(keyword);
		console.log(htmlContent);
		$.ajax({
	        url: "update-content",
	        type: "POST",
	        data: {
	            "_token": "{{ csrf_token() }}",
	            id: id,
	            keyword: keyword,
	            htmlContent:htmlContent,
	        },
	        success: function(response) {
	            if (response.status) {
	            	toastr.success(response.message);
	            } else {
	                toastr.success(response.error);
	            }
	        },
	    });
	}

</script>

<script type="text/javascript">

$('#newForm').on('submit',function(e){
  $('#newsresult').html('');
    e.preventDefault();

    let email = $('#newemail').val();

    $.ajax({
      url: "newsletter-submit",
      type:"POST",
      data:{
        "_token": "{{ csrf_token() }}",
        newsletter_email:email
      },
      success:function(response){
        if(response.status){
          $('#newsresult').html("<div class='alert alert-success'>" + response.message + "</div>");
        }
        else{
          $('#newsresult').html("<div class='alert alert-danger'>" + response.message + "</div>");
        }
      },
     });
    });
  </script>


<script type="text/javascript">

$('#contactform').on('submit',function(e){
  //alert('hogaya');
  $('#contactformsresult').html('');
    e.preventDefault();

    $.ajax({
      url: "{{ route('contactUsSubmit')}}",
      type:"POST",
      data: $("#contactform").serialize(),

      success:function(response){
        if(response.status){
          document.getElementById("contactform").reset();
          $('#contactformsresult').html("<div class='alert alert-success'>" + response.message + "</div>");
        }
        else{
          $('#contactformsresult').html("<div class='alert alert-danger'>" + response.message + "</div>");
        }
      },
     });
    });

</script>

@if (!Auth::guest())
@if(Auth::user()->isAdmin())
<script>editableContent();</script>
@endif
@endif

@if(Session::has('message'))
<script type="text/javascript">
    toastr.success("{{ Session::get('message') }}");
</script>
@endif

<script>
    
    $(document).ready(function(){
        if($('.product_img').length != 0){
            $('.product_img').owlCarousel({
                loop:true,
                margin:10,
                nav:false,
                dots:false,
                autoplay:true,
                autoplayTimeout:5000,
                autoplayHoverPause:false,
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:1
                    },
                    1000:{
                        items:1
                    }
                }
            });
        }
        $('.quick-view').click(function(e){
            var link = $(this).data('link');
            $('#product_view').modal('show');
            $('#product_view').addClass('loading');
            $('.quick-attribute-wrapper').removeClass('has-quick-attribute');
            $('#product_view .product_img').html('');
            var product_id = $(this).data('id');
            e.preventDefault();
            $.ajax({
                url: "{{ route('getProductDetails')}}",
                type:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    product_id:product_id
                },
                success:function(response){
                    if(response.status){
                        var product = response.product;
                        $('.quick-attribute-wrapper').html('');
                        $('#product_view .product_content h4').text(product.product_title);
                        $('#product_view .product_description').html(response.short_description);
                        $('#product_view .product_view-button').html('<a href="'+link+'">View Details</a>');
                        $('#product_view .cost').html(response.total_price);
                        $('#quick_sku span').text(product.sku);
                        $('#quick_tags span').text(product.tags);
                        $('#quick_category span').text(response.cat_list.toString());
                        $('#product_view .product_img').append('<div class="item"><img src="{{ URL::asset('') }}/'+product.image+'" class="img-responsive"></div>');
                        if(response.images.length != 0){
                            for(var i = 0; i < response.images.length; i++){
                                $('#product_view .product_img').append('<div class="item"><img src="{{ URL::asset('') }}/'+response.images[i].image+'" class="img-responsive"></div>');
                            }
                        }
                        var total_images = response.images.length;
                        if(response.attribute_array.length != 0){
                            var attribute = response.attribute_array;
                            var html = '';
                            $.each(attribute, function(index, value) {
                                html += '<div class="form-group">';
                                html += '<label for="'+index+'">'+index+'</label>';
                                html += '<select class="form-control" name="variation['+index+']" id="'+index+'" onchange="imageSlider(this)" required>';
                                html += '<option data-stock="" data-select="0" value="">Choose an option</option>';
                                for(var i = 0; i < value.length; i++){
                                    total_images++;
                                    html += '<option data-select="'+total_images+'" data-stock="'+value[i]['qty']+'" data-price="'+value[i]['price']+'" data-regular_price="'+value[i]['regular_price']+'" data-image="'+value[i]['image']+'" value="'+value[i]['value']+'">'+ value[i]['value'] +'</option>';
                                    if(value[i]['image'] != null){
                                        $('#product_view .product_img').append('<div class="item"><img src="{{ URL::asset('') }}/'+value[i]['image']+'" class="img-responsive"></div>');
                                    }
                                }
                                html += '<select>';
                                html += '</div>';
                            });
                            $('.quick-attribute-wrapper').addClass('has-quick-attribute');
                            $('.quick-attribute-wrapper').html(html);
                        }
                        $('.product_img').trigger('destroy.owl.carousel');
                        $('.product_img').owlCarousel({
                            loop:false,
                            margin:10,
                            nav:false,
                            dots:true,
                            autoplay:false,
                            responsive:{
                                0:{
                                    items:1
                                },
                                600:{
                                    items:1
                                },
                                1000:{
                                    items:1
                                }
                            }
                        });
                    }
                    console.log(response);
                    // $('#product_view').modal('toggle');
                },complete: function (data) {
                    $('#product_view').removeClass('loading');
                }
            });
        });
    
        
        $('#product_view button.close.btn').click(function(){
            $('#product_view').modal('toggle');
        })
    });
    
    function imageSlider(a){
        var stock = $(a).find(":selected").attr('data-stock');
        var image = $(a).find(":selected").attr('data-image');
        var select = $(a).find(":selected").attr('data-select');
        var price = $(a).find(":selected").attr('data-price');
        var regular_price = $(a).find(":selected").attr('data-regular_price');
        var actual_price = 0;
        if(price == 0){
          actual_price = regular_price;
        }else{
          actual_price = price;
        }
        if($(a).val() != ''){
            $('#product_view .cost').text('$'+actual_price);
        }
        if(image != ''){
            $('#product_view .product_img').trigger('to.owl.carousel', select);
        }
        if(stock != ''){
            $('.stock-info').html('<p><i class="fa-solid fa-circle-check"></i> '+ stock +' in stock</p>');
        }
    }
    (function(){
        $("#cart").hover(function(){
            $(".shopping-cart").fadeIn( "fast");
        }, function(){
            $(".shopping-cart").fadeIn( "fast");
        });
        $('.shopping-cart').hover(function(){
            $(".shopping-cart").fadeIn( "fast");
        }, function(){
            $(".shopping-cart").fadeOut( "fast");
        });
        // $("#cart").on("click", function() {
        //     $(".shopping-cart").fadeToggle( "fast");
        // });
    })();
</script>