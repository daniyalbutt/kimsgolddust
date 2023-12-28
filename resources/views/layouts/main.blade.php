@php
$currenturl = url()->full();
$redirections = DB::table('redirections')->where('url', $currenturl)->first();
if($redirections != null){
    header("Location: " . $redirections->redirect_url , true, 302);
    exit();
}
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="@yield('description')">
        <meta name="keywords" content="@yield('keywords')"/>
		<meta name="csrf-token" content="{{ csrf_token() }}">
        <meta property="og:locale" content="en_US" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="@yield('og_title')" />
        <meta property="og:description" content="@yield('og_description')" />
        <meta property="og:url" content="{{url()->current()}}" />
        <meta property="og:site_name" content="{{config('services.website.name')}}" />
        @yield('additional_seo')
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="{{asset(!empty($favicon->img_path)?$favicon->img_path:'')}}">
        <title>@yield('title')</title>
        {!! App\Http\Traits\HelperTrait::returnFlag(1980) !!}
        <!-- ============================================================== -->
        <!-- All CSS LINKS IN BELOW FILE -->
        <!-- ============================================================== -->
        @include('layouts.front.css')
        @yield('css')
        <style>
            .myaccount-tab-menu.nav a {
                display: block;
                font-size: 16px;
                align-items: center;
                width: 100%;
                font-weight: bold;
                color: black;
                background: linear-gradient(90deg, rgba(171,119,49,1) 0%, rgba(243,218,135,1) 50%, rgba(171,119,49,1) 100%);
                margin-bottom: 15px;
                font-family: 'Cinzel', serif;
                border-radius: 10px;
                padding: 16px 25px;
            }

            .myaccount-tab-menu.nav a i {
                padding-right: 10px;
            }

            .section-heading h2 {
                font-family: 'Cinzel', serif;
                font-weight: bold;
                margin-bottom: 15px;
            }

            main.my-cart {
                margin: 60px 0px 50px;
            }

            .myaccount-tab-menu.nav .active, .myaccount-tab-menu.nav a:hover {
                color: white;
                background: linear-gradient(0deg, rgba(2,10,18,1) 0%, rgba(17,96,181,1) 100%);
            }

            .account-details-form label.required {
                width: 100%;
                font-weight: 500;
                font-size: 18px;
            }
            .account-details-form legend {
                font-family: 'Cinzel', serif;
                font-weight: bold;
                margin-bottom: 15px;
                font-size: 2rem;
            }
            .editable {
                position: relative;
            }
            .editable-wrapper {
                position: absolute;
                right: 0px;
                top: -50px;
            }

            .editable-wrapper a {
                background-color: #17a2b8;
                border-radius: 50px;
                width: 35px;
                height: 35px;
                display: inline-block;
                text-align: center;
                line-height: 35px;
                color: white;
                margin-left: 10px;
                font-size: 16px;
            }
            .editable-wrapper a.edit{
                background-color: #007bff;
            }
            @if(App\Http\Traits\HelperTrait::returnFlag(1978) != null)
            .header_middle:before{
                background: url({{ asset(App\Http\Traits\HelperTrait::returnFlag(1978))  }});
            }
            @endif
            @if(App\Http\Traits\HelperTrait::returnFlag(1979) != null)
            .header_middle:after{
                background: url({{ asset(App\Http\Traits\HelperTrait::returnFlag(1979))  }});
            }
            @endif
        </style> 
    </head>
    <body class="responsive">
      
    
        @include('layouts/front.header')
		
	

		
        @yield('content')

        
        @include('layouts/front.footer')
        <!-- ============================================================== -->
        <!-- All SCRIPTS ANS JS LINKS IN BELOW FILE -->
        <!-- ============================================================== -->
        @include('layouts/front.scripts')
        @yield('js')

    </body>
</html>