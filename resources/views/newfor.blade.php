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
<section class="inner_banner">
  	<div class="container">
    	<div class="innerBanner_content" data-aos="fade-down" data-aos-duration="1200">
      		<h1>{{ $page->name }}</h1>
      		<p>Home / {{ $page->name }}</p>
    	</div>
  	</div>
</section>

@endsection
@section('css')
<style>

</style>
@endsection

@section('js')
<script type="text/javascript"></script>
@endsection