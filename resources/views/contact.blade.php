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

<section class="contactUs_sec1">
	<div class="container">
		<div class="container_two">
			<div class="row">
				<div class="col-12 col-md-5 col-lg-4 mb-4" data-aos="fade-right" data-aos-duration="1200">
					<div class="leftCol">
						<p class="black_heading mb-5">{{ $page->page_name }}</p>
						<div class="contact_info">
							<a href="tel:{{ App\Http\Traits\HelperTrait::returnFlag(59) }}"><img src="{{ asset('images/phone_icon.png') }}" alt="" class="img-fluid">{{ App\Http\Traits\HelperTrait::returnFlag(59) }}</a>
							<a href="mailto:{{ App\Http\Traits\HelperTrait::returnFlag(218) }}"><img src="{{ asset('images/envalop_icon.png') }}" alt="" class="img-fluid">{{ App\Http\Traits\HelperTrait::returnFlag(218) }}</a>
							{!! $page->content !!}
						</div>
					</div>
				</div>
				<div class="col-12 col-md-7 col-lg-8" data-aos="fade-up" data-aos-duration="1200">
					<div class="rightCol">
						<p class="black_heading mb-4">{{ $section[0]->value }}</p>
						<form action="" method="POST" id="contactform">
							@csrf
							<input type="hidden" name="form_name" value="Contact Form">
							<div class="row">
							    
								<div class="checkoutField_box col-12 col-md-6 col-lg-6 mb-4">
								    <label for="fname">Name *</label>
									<input type="text" name="name" placeholder="" class="checkout_field" required>
								</div>
								<div class="checkoutField_box col-12 col-md-6 col-lg-6 mb-4">
								    <label for="fname">Email *</label>
									<input type="email" name="email" placeholder="" class="checkout_field" required>
								</div>
								<div class="checkoutField_box col-12 col-md-6 col-lg-6 mb-4">
								    <label for="fname">Phone Number*</label>
									<input type="tel" name="number" placeholder="" class="checkout_field" required>
								</div>
								<div class="checkoutField_box col-12 col-md-6 col-lg-6 mb-4">
									<label for="fname">Street Address</label>
									<input type="text" name="address" placeholder="" class="checkout_field">
								</div>
								<div class="checkoutField_box col-12 col-md-6 col-lg-6 mb-4">
								    <label for="fname">City, State, Zip</label>
									<input type="text" name="zip" placeholder="" class="checkout_field">
								</div>
								<div class="checkoutField_box col-12 col-md-6 col-lg-6 mb-4">
									<label for="fname">Country</label>
									<input type="text" name="country" placeholder="" class="checkout_field">
								</div>
								<div class="checkoutField_box col-12 col-md-12 col-lg-12 mb-4">
								    <label for="fname">What can we do for you?</label>
									<textarea placeholder=""  class="checkout_field" rows="5" name="message"></textarea>
								</div>
								<div class="contactForm_button">
									<button type="submit" class="gold_btn">Submit</button>
								</div>
								<div class="col-md-12">
									<div id="contactformsresult"></div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


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