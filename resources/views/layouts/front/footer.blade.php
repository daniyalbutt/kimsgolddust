<footer>
	<div class="container">
		<div class="footer_top">
			<p>{{ App\Http\Traits\HelperTrait::returnFlag(1971) }}</p>
			<span>{{ App\Http\Traits\HelperTrait::returnFlag(1972) }}</span>
			<form class="email_bar" id="newForm">
				<input type="email" name="email" placeholder="Enter Your Email Address.." id="newemail">
				<button type="submit" class="gold_btn">Submit</button>
				<div id="newsresult"></div>
			</form>
		</div>
		<div class="footer_middle">
			<div class="f_leftCol">
				<a href="{{ route('home') }}">
				    @php
				    $footer_logo = \DB::table('imagetable')->where('table_name', 'footer_logo')->first();
				    @endphp
					<img src="{{ asset('images/footer-Gif.gif') }}" alt="logo" class="img-fluid">
				</a>
			</div>
			<div class="f_rightCol">
				<div class="f_col f_col1">
					<h5 class="f_listHeading">Quick Links</h5>
					<ul class="f_listing">
						<li>
							<a href="{{ route('schedule') }}">Show Schedule</a>
						</li>
						<li>
							<a href="{{ route('testimonial') }}">Testimonials</a>
						</li>
						<li>
							<a href="{{ route('firing.process') }}">The Firing Process</a>
						</li>
						<!--<li>-->
						<!--	<a href="{{ route('helpful.tips') }}">Helpful Tips</a>-->
						<!--</li>-->
						<li>
							<a href="{{ route('jewelry.care') }}">Jewelry Care</a>
						</li>
						<li>
							<a href="{{ route('faq') }}">FAQ</a>
						</li>
					</ul>
				</div>
				<div class="f_col f_col2">
					<h5 class="f_listHeading">Shop</h5>
					<ul class="f_listing">
						@php
						$foot_link = DB::table('categories')->where('parent', 0)->where('id', '!=', 1)->where('status', 1)->get();
						@endphp
						@foreach($foot_link as $key => $value)
						<li>
							<a href="{{ route('categoryDetail', ['id' => $value->id, 'slug' => str_slug($value->name , '-')]) }}">{{ $value->name }}</a>
						</li>
						@endforeach
					</ul>
				</div>
				<div class="f_col f_col3">
					<h5 class="f_listHeading">{{ App\Http\Traits\HelperTrait::returnFlag(1974) }}</h5>
					<img src="{{ asset('images/paymentCart.PNG') }}" alt="" class="img-fluid">
				</div>
				<div class="f_col f_col4">
					<h5 class="f_listHeading">Contact Us</h5>
					<a href="tel:{{ App\Http\Traits\HelperTrait::returnFlag(59) }}" class="mb-3">
						<span><i class="fa-sharp fa-solid fa-phone fa-rotate-90"></i></span>
						PH: {{ App\Http\Traits\HelperTrait::returnFlag(59) }}
					</a>
					<a href="mailto:{{ App\Http\Traits\HelperTrait::returnFlag(218) }}">
						<span><i class="fa-solid fa-envelope"></i></span>
						{{ App\Http\Traits\HelperTrait::returnFlag(218) }}
					</a>
				</div>
				<div class="f_col f_col5">
					<div class="f5_leftCol">
						<img src="{{ asset('images/f_bottom_icon.png') }}" alt="" class="img-fluid">
						<div class="f5_content">
							<h5>{{ App\Http\Traits\HelperTrait::returnFlag(1975) }}</h5>
							<p>{!! App\Http\Traits\HelperTrait::returnFlag(1976) !!}</span></p>
						</div>
					</div>
					<div class="f5_rightCol">
						@if(App\Http\Traits\HelperTrait::returnFlag(682) != null)
						<a href="{{ App\Http\Traits\HelperTrait::returnFlag(682) }}" target="_blank">
							<img src="{{ asset('images/like.png') }}" alt="" class="img-fluid">
						</a>
						@endif
						@if(App\Http\Traits\HelperTrait::returnFlag(1962) != null)
						<a href="{{ App\Http\Traits\HelperTrait::returnFlag(1962) }}" target="_blank">
							<img src="{{ asset('images/love.png') }}" alt="" class="img-fluid">
						</a>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="copyright_bar">
		<p>{{ App\Http\Traits\HelperTrait::returnFlag(499) }}</p>
	</div>
</footer>


<div class="modal fade product_view" id="product_view">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="loader">
                    <img src="{{ asset('images/loader.gif') }}">
                </div>
                <div class="row main-row">
                    <div class="col-md-6">
                        <div class="product_img owl-carousel owl-theme">
                            
                        </div>
                    </div>
                    <div class="col-md-6 product_content">
                        <h4></h4>
                        <div class="product_description">
                            
                        </div>
                        <div class="product_view-button">
                            
                        </div>
                        <h3 class="cost"></h3>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="quick-attribute-wrapper">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="quick-option">
                                    <p id="quick_sku">SKU: <span></span></p>
                                    <p id="quick_category">CATEGORIES : <span></span></p>
                                    <p id="quick_tags">TAGS: <span></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="btn-ground">
                            <button type="button" class="btn gold_btn">Add To Cart </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="video-warpper">
    <video controls autoplay muted id="myVideo">
        <source src="{{ asset('video/Alice-vfx.mp4') }}" type="video/mp4">
        <source src="{{ asset('video/Alice-vfx.mp4') }}" type="video/ogg">
        Your browser does not support the video tag.
    </video>
</div>