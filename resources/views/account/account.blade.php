@extends('layouts.main')
@section('title', 'Account Details')
@section('content')

<?php $segment = Request::segments(); ?>

<section class="inner_banner">
    <div class="container">
        <div class="innerBanner_content" data-aos="fade-down" data-aos-duration="1200">
            <h1>Account Details</h1>
            <p>Home / Account Details</p>
        </div>
    </div>
</section>

<main class="my-cart">
    <!-- banner start -->
    <!-- banner end -->

<!-- main content start -->

 <!-- my account wrapper start -->
    <div class="my-account-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- My Account Page Start -->
                    <div class="myaccount-page-wrapper">
                        <!-- My Account Tab Menu Start -->
                        <div class="row">
                            @include('account.sidebar')
                            <!-- My Account Tab Menu End -->
    
                            <!-- My Account Tab Content Start -->
                            <div class="col-lg-9 col-md-8">
                                <div class="tab-content" id="myaccountContent">
                                   
                                   <!-- Single Tab Content Start -->
                                    <div class="tab-pane active" id="account-info" role="tabpanel">
                                        <div class="myaccount-content">
                                            <div class="section-heading">
                                                <h2>Account Details</h2>
                                            </div>
                                            <div class="account-details-form">
                                               <form action="{{ route('update.account') }}" method="post" enctype="multipart/form-data" id="accountForm">
                                                @csrf
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="single-input-item">
                                                                <input type="text" id="last-name" name="uname" class="contactField mb-4" placeholder="Last Name" value="<?php echo Auth::user()->name; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="single-input-item">
                                                                <input type="email" id="email" placeholder="Email Address" class="contactField mb-4" name="email" value="<?php echo Auth::user()->email; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
    
    
                                                    <fieldset>
                                                        <legend>Password change</legend>
    
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <input type="password" id="new-pwd" placeholder="New Password" name="password" class="contactField mb-4">
                                                                </div>
                                                            </div>
    
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <input type="password" id="confirm-pwd" placeholder="Confirm Password" name="password_confirmation" class="contactField mb-4">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
    
                                                    <div class="single-input-item">
                                                        <button class="gold_btn" id="updateProfile">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div> <!-- Single Tab Content End -->
    
                                    
                                </div>
                            </div> <!-- My Account Tab Content End -->
                        </div>
                    </div> <!-- My Account Page End -->
                </div>
            </div>
        </div>
    </div>
    <!-- my account wrapper end -->


<!-- main content end -->   
</main>
@endsection
@section('css')
<style type="text/css">
    
</style>
@endsection
@section('js')

<script type="text/javascript">

 $(document).on('click', "#updateProfile", function(e){
        $('#accountForm').submit();
  });

</script>

@endsection