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
<section class="inner_banner" style="background: {{ $page->banner_color  }}">
  	<div class="container">
    	<div class="innerBanner_content" data-aos="fade-down" data-aos-duration="1200">
      		<h1>{{ $page->name }}</h1>
      		<p>Home / {{ $page->name }}</p>
    	</div>
  	</div>
</section>

<section class="firingProcess_sec1">
	<div class="container">
		{!! $page->content !!}
		<div class="firingProcess_image text-center">
			<img src="{{ asset($page->image) }}" alt="" class="img-fluid">
		</div>
		{!! $section[0]->value !!}
	</div>
</section>

<section class="firingProcess_sec2">
	<div class="container">
		<div class="row">
			
			<div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-4">
				<div class="firing_video">
                    <video width="100%" height="500" controls>
                      <source src="{{asset('video/Firing-Process.mp4')}}" type="video/mp4">
                      <source src="{{asset('video/Firing-Process.mp4')}}" type="video/ogg">
                    Your browser does not support the video tag.
                    </video>
				</div>
			</div>
			
		</div>
		{!! $section[1]->value !!}
	</div>
</section>

<section class="firingProcess_sec3">
	<div class="container">
		{!! $section[2]->value !!}
		<div class="firing_sliderWrap">
			<div class="owl-carousel firingProcess_slider owl-theme">
				@foreach($sliders as $key => $value)
    			<div class="item">
    				<img src="{{ asset($value->image) }}" alt="Slider" class="img-fluid">
    				<div class="lower-section">
    				    <h4>{{ $value->title }}</h4>
    				    <p>{{ $value->content }}</p>
    				</div>
    			</div>
    			@endforeach
			</div>
		</div>
		{!! $section[3]->value !!}
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