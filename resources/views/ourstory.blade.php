@extends('layouts.main')
@section('title', $page->seo_title)
@section('description', $page->seo_description)
@section('keywords', $page->seo_keyword)


@if($page->og_title !="")
@section('og_title',$page->og_title)
@else
@section('og_title',$page->seo_title)
@endif
@if($page->og_description !="")
@section('og_description',$page->og_description)
@else
@section('og_description',$page->seo_description)
@endif


@section('additional_seo')
{!! $page->additional_seo !!}
@endsection


@section('content')
<!--<section class="inner_banner" style="background: {{ $page->banner_color  }}">-->
<!--  	<div class="container">-->
<!--    	<div class="innerBanner_content" data-aos="fade-down" data-aos-duration="1200">-->
<!--      		<h1>{{ $page->name }}</h1>-->
<!--      		<p>Home / {{ $page->name }}</p>-->
<!--    	</div>-->
<!--  	</div>-->
<!--</section>-->

<section class="ourStory_sec2">
	<div class="container">
		<div class="container_two">
			<div class="story_content mb-4">
				{!! $page->content !!}
			</div>
		</div>
	</div>
</section>

<section class="ourStory_sec2">
	<div class="container">
		<!--<div class="container_two">-->
		<div class="row">
			<!--<div class="storySlider_wrap">-->
			    
				<div class="col-md-9 col-12 ">
				    <div class="swiper-container slider">
				        
				    
				    <div class="swiper-wrapper">
				    	@foreach($stories as $key => $value)
				        <div class="swiper-slide">
				        	<img src="{{ asset($value->image) }}" class="img-fluid" alt="">
				        	<div class="story_slideBar">{{ $value->left_text }}</div>
				        </div>
				        @endforeach
				    </div>
				    <div class="swiper-button-next"></div>
				    <div class="swiper-button-prev"></div>
				    </div>
				</div>
				<div class="col-md-3 col-12">
				    <div class="swiper-container slider-thumbnail">
				    <div class="swiper-wrapper ">
				    	@foreach($stories as $key => $value)
				        <div class="swiper-slide ">
				        	<img src="{{ asset($value->image) }}" class="img-fluid" alt="">
				        	<p class="slide_thumb_text">{{ $value->right_text }}</p>
				        </div>
				        @endforeach
				    </div>
				    </div>
				</div>
			<!--</div>-->
		</div>
	</div>
</section>

<!--<section class="ourStory_sec3">-->
<!--	<div class="container">-->
<!--		<div class="storySec3_image">-->
<!--			<img src="{{ asset($page->image) }}" alt="" class="img-fluid">-->
<!--		</div>-->
		<!--<div class="storySec3_content text-center">-->
		<!--	<div class="container_two">-->
		<!--		{!! $section[0]->value !!}-->
		<!--	</div>-->
		<!--</div>-->
<!--	</div>-->
<!--</section>-->

<section class="index_sec6">
	<div class="container">
		<div class="row">
		    <div class="col-md-12">
		        <h5>{{ $section[1]->value }}</h5>
		    </div>
			@foreach($productvideos as $key => $value)
			<div class="col-12 col-md-4 col-lg-4 mb-4">
				<div class="singleProduct">
					<video autoplay muted loop>
					  <source src="{{ asset($value->video) }}" type="video/mp4">
					</video>
				</div>
			</div>
			@endforeach
		</div>
	</div>
</section>

@endsection
@section('css')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/6.8.4/swiper-bundle.min.css">
<style>
	@if($page->left_image != null)
	.inner_banner:before{
        background: url({{ asset($page->left_image) }});
    }
    @endif
    @if($page->right_image != null)
    .inner_banner:after{
        background: url({{ asset($page->right_image) }});
    }
    @endif
</style>
<style>
.slider-thumbnail img {
    margin-top: 17px;
}
.slider-thumbnail{
    left:12px;
}

</style>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/6.8.4/swiper-bundle.min.js"></script>
<script>
// Image Gallery Thumbnail js

var sliderThumbnail = new Swiper('.slider-thumbnail', {
  direction: 'vertical',
  mousewheelControl: true,
  slidesPerView: 4,
  freeMode: true,
  watchSlidesVisibility: true,
  watchSlidesProgress: true,
    breakpoints:{
        320:{
            direction: 'horizontal', 
             slidesPerView: 2,
        },
        480:{
             direction: 'horizontal',
              slidesPerView: 2,
        },
        
        768: {
          direction: 'vertical',
          slidesPerView: 4,
        },
        992: {
           direction: 'vertical',
           slidesPerView: 4,
        },
  }
});

// Image Gallery Main js

var slider = new Swiper('.slider', {
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  thumbs: {
    swiper: sliderThumbnail
  },
  autoplay: 
    {
      delay: 2000,
    },
    loop: true,
});
</script>
@endsection