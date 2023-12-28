@extends('layouts.app')

@push('before-css')
    <link rel="stylesheet" href="{{asset('plugins/vendors/dropify/dist/css/dropify.min.css')}}">
@endpush

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">Global</h3>
        <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Home</li>
                    <li class="breadcrumb-item active">Global</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content-body">
  <section id="basic-form-layouts">
      <div class="row match-height">
          <div class="col-md-7">
              <div class="card">
                  <div class="card-header">
                      <h4 class="card-title" id="basic-layout-form">Global Info</h4>
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
                          <form class="form" enctype="multipart/form-data" method="post" action="{{route('config_settings_update')}}">
                              @csrf
                              <div class="form-body">
                                    <div class="row">
                                        <?php 
                                            $_getConfig = DB::table('m_flag')->where('is_active','1')->where('is_config','1')->get();
                                        ?>
                                        @foreach($_getConfig as $_Config)
                                            @if($_Config->type='1')
                                            @if($_Config->has_image == 0)
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="{{$_Config->flag_type}}">{{$_Config->flag_show_text}}</label>
                                                    <input id="{{$_Config->flag_type}}" class="form-control" name="{{$_Config->flag_type}}" type="text" value="{{$_Config->flag_value}}">
                                                </div>
                                            </div>
                                            @elseif($_Config->has_image == 1)
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="{{$_Config->flag_type}}">{{$_Config->flag_show_text}}</label>
                                                    <input class="form-control dropify" name="{{$_Config->flag_type}}" type="file" id="image" {{ ($_Config->flag_value != '') ? "data-default-file = ".asset($_Config->flag_value) : ''}} {{ ($_Config->flag_value == '') ? "" : ''}} value="{{$_Config->flag_value}}">
                                                </div>
                                            </div>
                                            @else
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="{{$_Config->flag_type}}">{{$_Config->flag_show_text}}</label>
                                                    <textarea rows="15" id="{{$_Config->flag_type}}" class="form-control" name="{{$_Config->flag_type}}">{{$_Config->flag_value}}</textarea>
                                                </div>
                                            </div>
                                            @endif
                                            @else
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="{{$_Config->flag_type}}">{{$_Config->flag_show_text}}</label>
                                                    <input id="{{$_Config->flag_type}}" class="form-control" name="{{$_Config->flag_type}}" type="text" value="{{$_Config->flag_value}}">
                                                </div>
                                            </div>
                                            @endif
                                        @endforeach
                                  </div>
                              </div>
                              <div class="form-actions text-right pb-0">
                                  <button type="submit" class="btn btn-primary">
                                  <i class="la la-check-square-o"></i> Update
                                  </button>
                              </div>
                          </form>
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
                              @if(Session::has('message'))
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
    <script src="{{asset('plugins/vendors/dropify/dist/js/dropify.min.js')}}"></script>
    <script>
      $(function() {
          $('.dropify').dropify();
      });
    </script>
@endpush