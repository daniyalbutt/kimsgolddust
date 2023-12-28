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

<section class="faq_sec1">
	<div class="container">
		<div class="faq_wrap">
			{!! $page->content !!}
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