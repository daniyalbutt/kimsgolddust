<html>
    <head>
        <meta charset="utf-8">
        <title>{{ $page->seo_title }}</title>
        <meta name="description" content="{{ $page->seo_description }}">
        <meta name="keywords" content="{{ $page->seo_keyword }}"/>
        {!! $page->additional_seo !!}
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" type="image/png" sizes="16x16" href="{{asset(!empty($favicon->img_path)?$favicon->img_path:'')}}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/all.css') }}">
        <link rel="stylesheet" href="{{ asset('css/aos.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/responsive.css') }}" />
        <link href="https://allfont.net/allfont.css?fonts=coolvetica-regular" rel="stylesheet" type="text/css" />
        <style>
            @if($page->left_image != null)
            .login_sec .leftCol:before{
                background-image: url({{ $page->left_image  }});
            }
            @endif
            @if($page->right_image != null)
            .login_sec .leftCol:after{
                background-image: url({{ $page->right_image  }});
            }
            @endif
        </style>    
    </head>
    <body>
        <section class="login_sec">
            <div class="leftCol" style="background: {{ $page->banner_color  }}">
                <div class="campany_logo" data-aos="fade-down" data-aos-duration="1200">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset($logo->img_path) }}" alt="" class="img-fluid">
                    </a>
                </div>
            </div>
            @if (Session::has('success'))
            <div class="alert alert-danger mt-2">
                {{ Session::get('success') }} 
            </div>
            @endif
            <div class="rightCol">
                <div class="form_wrap">
                    <div class="form_heading" data-aos="fade-down" data-aos-duration="1200">
                        <p class="black_heading40">{!! $page->name !!}</p>
                        {!! $page->content !!}
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <input type="email" class="contactField mb-4 {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="Email">

                        @if ($errors->has('email'))
                        <small class="alert alert-danger w-100 d-block p-2 mt-2">{{ $errors->first('email') }}</small>
                        @endif
                        <input type="password" class="contactField mb-4 {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password">
                        @if ($errors->has('password'))
                        <small class="alert alert-danger w-100 d-block p-2 mt-2">{{ $errors->first('password') }}</small>
                        @endif
                        <div class="forget_password">
                            <a href="{{ route('forget.password.get') }}" class="paragraph">Forgot password ?</a>
                        </div>
                        <div class="form_button">
                            <button type="submit" class="gold_btn w-100">Login</button>
                        </div>
                    </form>
                </div>
                <p class="other_text">Don't have an account? <a href="{{ route('signup') }}" class="paragraph">Signup</a>
                </p>
            </div>
        </section>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
        <script src="{{ asset('js/aos.js') }}"></script>
        <script>
            AOS.init();
        </script>
    </body>
</html>