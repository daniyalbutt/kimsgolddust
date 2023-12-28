// NAVIGATION
initializeStellarNav(1, '.stellarnav');
function initializeStellarNav(index, element) {
    $(element).stellarNav({
        breakpoint: 991,
        position: 'left'
    });
}

// BANNER SLIDER JS
$('.banner_slider').owlCarousel({
    loop:true,
    margin:10,
    nav:false,
    dots:false,
    autoplay:true,
    autoplayTimeout:3000,
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

$('.megaMenu_slider').owlCarousel({
    loop:true,
    margin:0,
    nav:false,
    dots:false,
    autoplay:true,
    autoplayTimeout:3000,
    autoplayHoverPause:true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:1
        },
        1000:{
            items:2
        }
    }
});

// PRODUCT SLIDER JS
$('.product_slider').owlCarousel({
    loop:true,
    margin:15,
    nav:false,
    dots:false,
    autoplay:true,
    autoplayTimeout:2000,
    autoplayHoverPause:true,
    center: true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:4
        },
        1100:{
            items:5
        }
    }
});

// TESTIMONIALS SLIDER JS
$('.testimonials_slider').owlCarousel({
    loop:true,
    margin:15,
    nav:false,
    dots:true,
    autoplay:true,
    autoplayTimeout:10000,
    autoplayHoverPause:true,
    center: true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:3
        }
    }
});

// FIRING PROCESS SLIDER JS
$('.firingProcess_slider').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    dots: false,
    navText : ["<i class='fa-sharp fa-solid fa-circle-arrow-left'></i>","<i class='fa-sharp fa-solid fa-circle-arrow-right'></i>"],
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


// STORY PAGE SLIDER JS
$('.story_slider').owlCarousel({
    loop:true,
    margin:10,
    nav:false,
    dots:true,
    autoplay:true,
    autoplayTimeout:3000,
    autoplayHoverPause:true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:2
        }
    }
});



// SHOP PAGE FILTER BAR JS
$('.filter_btn').click(function(){
    $('.shop_left').toggleClass('show');
});

// PRICE RANGE BAR JS
(function($){
    $(document).ready(function(){
        $('.show-min, .show-max').keydown(function(event){
            var kc, num, rt = false;
            kc = event.keyCode;
            if(kc == 8 || ((kc > 47 && kc < 58) || (kc > 95 && kc < 106))) rt = true;
            return rt;
        });
        
        $('.input-range').each(function(){
            var value = $(this).attr('data-slider-value');
            var separator = value.indexOf(',');
            if( separator !== -1 ){
                value = value.split(',');
                value.forEach(function(item, i, arr) {
                    arr[i] = parseFloat(item);
                });
            } else {
                value = parseFloat(value);
            }
            var mySlider = $(this).slider({
                formatter: function(value) {
                    if(typeof value == 'object'){
                        $('#min').val(value[0]);
                        $('.show-min').val(value[0]);
                        $('#max').val(value[1]);
                        $('.show-max').val(value[1]);
                        $('.priceRange_field').val('$'+value[0] + ' - $' + value[1]);
                    }
                    return '$. ' + value;
                },
                min: parseFloat($(this).attr('data-slider-min')),
                max: parseFloat($(this).attr('data-slider-max')), 
                range: $(this).attr('data-slider-range'),
                value: value,
                tooltip_split: $(this).attr('data-slider-tooltip_split'),
                tooltip: $(this).attr('data-slider-tooltip')
            });
            mySlider.change({
                'oldValue' : 100,
                'newValue' : 1000
            });
        });
        
        $('.show-min').keyup(function(){
            $('#min').val($(this).val());
        });
        $('.show-max').keyup(function(){
            $('#max').val($(this).val());
        });
        
    });
})( jQuery );


//PRODUCT QUANTITY SELECT INPUT
$(document).ready(function(){
    $(document).on('click','.plus',function(){
       $(this).siblings('.count').val(parseInt($(this).siblings('.count').val()) + 1 );
       $('.prdCount').val(parseInt($('.prdCount').val()) + 1);
   });
    $(document).on('click','.minus',function(){
      $(this).siblings('.count').val(parseInt($(this).siblings('.count').val()) - 1 );
      $('.prdCount').val(parseInt($('.prdCount').val()) - 1);
      if ($(this).siblings('.count').val() == 0) {
        $(this).siblings('.count').val(1);
        }
      if($('.prdCount').val() == 0){
          $('.prdCount').val(1);
      }
    });
});

$('.prodcutDetail_image-wrapper, .prodcutDetail_image-wrapper-icon, .product_img, .featureProduct').bind('contextmenu', function(e) {
    return false;
}); 
