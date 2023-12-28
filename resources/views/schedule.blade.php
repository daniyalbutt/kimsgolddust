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

<section class="schedule_sec1">
	<div class="container">
		<div class="container_two">
			{!! $page->content !!}
			<p class="paragraph text-center mb-5"></p>
			<div class="row">
				<div class="col-12 col-md-6 col-lg-6 mb-4">
					<div class="schedule_card">
						<img src="{{ asset($page->image) }}" alt="" class="img-fluid">
						{!! $section[0]->value !!}
					</div>
				</div>
				<div class="col-12 col-md-6 col-lg-6 mb-4">
					<div class="schedule_card">
						<img src="{{ asset($section[1]->value) }}" alt="{{ $section[1]->alter_tag }}" class="img-fluid">
						{!! $section[2]->value !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="schedule_sec2">
	<div class="container">
		<div class="row">
			@foreach($schedules as $key => $value)
			<div class="col-12 col-md-6 col-lg-4 mb-4">
				<div class="schedule_cardBox">
					<div class="schedule_date">
						@php
						$date_original = explode(" ", $value->date);
						@endphp
						@for($i = 0; $i < count($date_original); $i++)
						<span>{{ $date_original[$i] }}</span>
						@endfor
					</div>
					<div class="schedule_details">
						<span>{{ $value->location }}</span>
						<h4>{{ $value->name }}</h4>
						{!! $value->description !!}
					</div>
				</div>
			</div>
			@endforeach
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