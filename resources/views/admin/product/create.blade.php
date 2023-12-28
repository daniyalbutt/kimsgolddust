@extends('layouts.app')
@push('before-css')
    <link rel="stylesheet" href="{{ asset('plugins/vendors/dropify/dist/css/dropify.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.17.8/tagify.min.css"
        integrity="sha512-Ft73YZFLhxI8baaoTdSPN8jKRPhYu441A8pqlqf/CvGkUOaLCLm59ZWMdls8lMBPjs1OZ31Vt3cmZsdBa3EnMw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
            <h3 class="content-header-title mb-0 d-inline-block">Product</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">Home</li>
                        <li class="breadcrumb-item active">Product</li>
                        <li class="breadcrumb-item active">Create New Product</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12">
            <div class="btn-group float-md-right">
                <a class="btn btn-info mb-1" href="{{ url('/admin/product') }}">Back</a>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="basic-form-layouts">
            <div class="row ">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="basic-layout-form">Product Info</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                    <li><a data-action="close"><i class="ft-x"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                {!! Form::open(['enctype' => 'multipart/form-data', 'url' => '/admin/product', 'files' => true]) !!}

                                @include ('admin.product.form')

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="basic-layout-colored-form-control">Information</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                    <li><a data-action="close"><i class="ft-x"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="card-text">
                                    @if ($errors->any())
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li class="alert alert-danger">
                                                    {{ $error }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    @if (Session::has('message'))
                                        <ul>
                                            <li class="alert alert-success">
                                                {{ Session::get('message') }}
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"
        integrity="sha512-foIijUdV0fR0Zew7vmw98E6mOWd9gkGWQBWaoA1EOFAx+pY+N8FmmtIYAVj64R98KeD2wzZh1aHK0JSpKmRH8w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('plugins/vendors/dropify/dist/js/dropify.min.js') }}"></script>
    <script>
        $(function() {
            $('.dropify').dropify();
        });
        ! function(e, t, r) {
            "use strict";
            r(".repeater-default").repeater({
                show: function() {
                    console.log(this);
                    r(this).slideDown();
                    $(this).find('.select2-container').remove();
                    $(this).find('.select2').select2();
                    $(this).find('.select2-container').css('width', '100%');
                    $(this).find('.dropify').dropify();
                },
                hide: function(e) {
                    confirm("Are you sure you want to remove this item?") && r(this).slideUp(e)
                }
            })
        }(window, document, jQuery);

        function getval(sel) {
            var globelsel = sel;
            let value = sel.value;

            // alert(value);

            $.ajax({
                url: "{{ route('get-attributes') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    value: value
                },
                success: function(response) {
                    $(globelsel).parent().parent().find('.value').html('');
                    if (response.status) {
                        var html = '';
                        for (var i = 0; i < response.message.length; i++) {
                            html += '<option value="' + response.message[i].value + '">' + response.message[i]
                                .value + '</option>';
                        }
                        $(globelsel).parent().parent().find('.value').html(html);
                    } else {

                    }
                },
            });
        }
    </script>
    <script>
        $('#discount').change(function() {
            if ($(this).is(":checked")) {
                $('#discountdiv').slideDown();
            } else {
                $('#discountdiv').slideUp()
                $('#discount_get_qty').val('');
                $('#discount_buy_qty').val('');


            }
        });
    </script>
    <script>
        $('#discount').change(function() {
            if ($(this).is(":checked")) {
                $('#discountdiv').slideDown()
            } else {
                $('#discountdiv').slideUp()

            }
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.17.8/tagify.js"
        integrity="sha512-ICM3GOdxksUeCV9rCO42NxZdKfFKGGESi6/3YRqMfyFs1TSqSNukzxiHCpZzpAFeQ7IZbJ8rgAzqOOsgtgGAaw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var input = document.querySelector('input[name=tags]');
        $.ajax({
            url: "{{ route('product.tags') }}",
            type: "GET",
            success: function(response) {
                tagify = new Tagify(input, {
            		suggestionsMinChars : 1,
                    whitelist : response.data,
                    dropdown : {
                        enabled   : 1,
                        maxItems  : 5
                    }
                });
            },
        });
        // new Tagify(input)
    </script>
      <script>
      
    
    // Get the #text element
    const textArea = document.querySelector('#seo_desc');
    
    // Get the #character-count element
    const characterCount = document.querySelector('#character-count-desc');
    
    // Get the Title element
    const textTitle = document.querySelector('#seo_title');
    // Get the #character-count element
    const characterCountTitle = document.querySelector('#character-count-title');
    
    //
    // Functions
    //
    
    /**
     * Get the number of characters inside a form field
     * @param {HTMLInputElement|HTMLTextAreaElement} field The form field
     * @returns {Number} The character count
     */
    function getCharacterCount (field) {
      return field.value.length;
    }
    
    /**
     * Handle input events
     */
    function handleInput () {
      characterCount.textContent = getCharacterCount(this);
    }
    
    function handleInputTitle () {
      characterCountTitle.textContent = getCharacterCount(this);
    }
    //
    // Inits & Event Listeners
    //
    
    // Handle input events
    textArea.addEventListener('input', handleInput);
    
    textTitle.addEventListener('input', handleInputTitle);
  </script>
@endpush
