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

<section class="testimonial_sec">
	<div class="container">
		{!! $page->content !!}
		<div class="testimonialSlider_wrap">
			<div class="testimonials_slider_wrapper row">
				@foreach($testimonials as $key => $value)
    			<div class="item col-lg-4">
    				<div class="review_card review_card_inner">
                        <div class="review_scroller">
        					<div>
        						<span><i class="fa-solid fa-quote-left"></i></span>
        						{!! $value->comments !!}
        					</div>
                        </div>
    					<div class="review_card_footer">
    						<div class="client_image">
    							<img src="{{ asset($value->image) }}" alt="client" class="img-fluid">
    						</div>
    						<div class="client_review">
    							<p>{{ $value->name }}</p>
    							<div class="client_rating">
    								@for($i = 0; $i < $value->stars; $i++)
	    							<i class="fa-sharp fa-solid fa-star"></i>
	    							@endfor
    							</div>
    						</div>
    					</div>
    				</div>
    			</div>
    			@endforeach
			</div>
		</div>
	</div>
</section>


<!--<section class="ourStory_sec3 pt-0">-->
<!--    <div class="container">-->
<!--        <div class="storySec3_content text-center">-->
<!--			<div class="container_two">-->
<!--			    {!! $section[0]->value !!}-->
<!--			</div>-->
<!--		</div>-->
<!--    </div>-->
<!--</section>-->


@endsection
@section('css')
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
@endsection

@section('js')
<script type="text/javascript"></script>
@endsection