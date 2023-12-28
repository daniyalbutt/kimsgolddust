@extends('layouts.app')

@push('before-css')
    <link rel="stylesheet" href="{{asset('assets/css/datatables.min.css')}}">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    
</head>
<body>
@endpush

@section('content')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">Banner Management</h3>
        <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Home</li>
                    <li class="breadcrumb-item active">Banner Management</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12">
        <div class="btn-group float-md-right">
            <a class="btn btn-info mb-1" href="{{ url('admin/banner/create') }}">Add Slide</a>
        </div>
    </div>
</div>
@if(Session::has('flash_message'))
<div class="alert alert-success">
    {{ Session::get('flash_message') }}
</div>
@endif

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Banner Info</h4>
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
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Banner Image</th>
                                        <th>Banner Right</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($banner as $item)
                                    <tr>
                                        <td>{{ $item->order_id }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>
                                            <img src="{{ asset($item->image) }}" width="200" />
                                        </td>
                                        <td>
                                            @if($item->right_image != null)
                                            <img src="{{ asset($item->right_image) }}" width="100" />
                                            @endif
                                        </td>
                                        <td>
                                            <input data-id="{{$item->id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $item->status ? 'checked' : '' }}>                                            
                                        </td>
                                        <td>
                                            <a href="{{ url('/admin/banner/' . $item->id . '/edit') }}"
                                           title="Edit Language">
                                                <button type="button" class="btn btn-sm btn-icon btn-info"><i class="la la-info"></i></button>
                                            </a>
                                            <a href="{{ route('banner.delete', $item->id) }}"
                                               title="View Banner">
                                                <button type="button" class="btn btn-sm btn-icon btn-danger"><i class="la la-trash"></i></button>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Banner Image</th>
                                        <th>Banner Right</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@push('js')
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<!-- Toastr -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
$(document).ready(function() {
			toastr.options = {
				'closeButton': true,
				'debug': false,
				'newestOnTop': false,
				'progressBar': false,
				'positionClass': 'toast-top-right',
				'preventDuplicates': false,
				'showDuration': '1000',
				'hideDuration': '1000',
				'timeOut': '5000',
				'extendedTimeOut': '1000',
				'showEasing': 'swing',
				'hideEasing': 'linear',
				'showMethod': 'fadeIn',
				'hideMethod': 'fadeOut',
			}
		});

    
</script>
<script>
  $(function() {
    $('.toggle-class').change(function() {
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var banner_id = $(this).data('id'); 
         
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{route("bannerChangestatus")}}',
            data: {'status': status, 'banner_id': banner_id},
            success: function(data){
              console.log(data.success)
              toastr.success(data.success);
            }
        });
    })
  })
</script>


<script src="{{asset('assets/js/datatables.min.js')}}"></script>
<script>
    $(document).ready(function(){
        var originalIndex;
        var table = $(".zero-configuration").DataTable({
                        "order": [
                            [0, 'asc']
                        ],
                        rowReorder: true
                    });
        table.on('pre-row-reorder', function (e,node,index ) {
            originalIndex = node.index;
        });
        
        table.on('row-reordered', function ( e, diff, edit ) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var formData = {
                new_data: diff[0].newData,
                old_data: diff[0].oldData,
            };
            console.log(formData);
            $.ajax({
                dataType: 'json',
                type:'POST',
                url: "{{ route('banner.order') }}",
                data:formData,
                success:function(data) {
                    console.log(data);
                }
            });
        });
  
    });
</script>
@endpush