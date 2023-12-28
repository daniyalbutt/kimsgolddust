@extends('layouts.app')
@push('before-css')
  <link rel="stylesheet" href="{{asset('plugins/vendors/dropify/dist/css/dropify.min.css')}}">
@endpush
@section('content')

<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">Edit Banner</h3>
        <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item" href="{{url('admin/dashboard')}}">Home</li>
                    <li class="breadcrumb-item"><a href="{{url('admin/banner')}}">Banner Management</a></li>
                    <li class="breadcrumb-item active">Edit Banner</li>
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
                      <h4 class="card-title" id="basic-layout-form">Edit Banner Info</h4>
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
                          <form class="form" enctype="multipart/form-data" method="post" action="{{route('admin.banner.update', $banner->id)}}">
                              @csrf
                              @method('PATCH')
                              <div class="form-body">
                                  <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="title">Title</label>
                                                <input class="form-control" name="title" type="text" id="title" value="{{$banner->title}}">
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="summary-ckeditor">Description</label>
                                                <textarea name="description" id="summary-ckeditor" cols="30" rows="10" class="form-control" required>{{$banner->description}}</textarea>
                                            </div>
                                        </div> -->
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="image">Banner Image</label>
                                                <div class="upload-photo">
                                                    <input type="file" name="image" id="image" class="dropify" {{ ($banner->image != '') ? "data-default-file =".asset($banner->image) : ''}} />
                                                    <div class="form-group mt-1 alert alert-info">
                                                        <label style="color: white;">Add Alter Text of Upper Image</label>
                                                        <input type="text" name="image_alter_tag" class="form-control" value="{{ $banner->image_alter_tag }}">
                                                    </div>
                                                </div>
                                              </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="right_image">Banner Right Image</label>
                                                <div class="upload-photo">
                                                    <input type="file" name="right_image" id="right_image" class="dropify" {{ ($banner->right_image != '') ? "data-default-file =".asset($banner->right_image) : ''}} />
                                                     <div class="form-group mt-1 alert alert-info">
                                                        <label style="color: white;">Add Alter Text of Upper Image</label>
                                                        <input type="text" name="right_banner_alter_tag" class="form-control" value="{{ $banner->right_banner_alter_tag }}">
                                                    </div>
                                                </div>
                                              </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="front_image">Image</label>
                                                <div class="upload-photo">
                                                    <input type="file" name="front_image" id="front_image" class="dropify" {{ ($banner->front_image != '') ? "data-default-file =".asset($banner->front_image) : ''}} />
                                                    <div class="form-group mt-1 alert alert-info">
                                                        <label style="color: white;">Add Alter Text of Upper Image</label>
                                                        <input type="text" name="front_image_alter_tag" class="form-control" value="{{ $banner->front_image_alter_tag }}">
                                                    </div>
                                                </div>
                                              </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="shop_link">Shop Link</label>
                                                <input class="form-control" name="shop_link" value="{{$banner->shop_link}}" type="text" id="shop_link">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="additonal_class">Additonal Class</label>
                                                <input class="form-control" name="additonal_class" type="text" id="additonal_class" value="{{$banner->additonal_class}}">
                                            </div>
                                        </div>
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
@push('js')
  <script src="{{asset('plugins/vendors/dropify/dist/js/dropify.min.js')}}"></script>
  <script>
      $(function() {
          $('.dropify').dropify();
      });
  </script>
@endpush
@endsection
