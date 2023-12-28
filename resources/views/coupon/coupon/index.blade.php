@extends('layouts.app')

@push('before-css')
    <link rel="stylesheet" href="{{asset('assets/css/datatables.min.css')}}">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-8 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">Coupon</h3>
        <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Home</li>
                    <li class="breadcrumb-item active">Coupon</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-4 col-12">
        <div class="btn-group float-md-right">
            <a class="btn btn-info mb-1" href="{{ url('/coupon/coupon/create') }}">Add Coupon</a>
        </div>
    </div>
</div>

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Coupon Info</h4>
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
                                        <th>Code / Checkout Maximum Price</th>
                                        <th>Type</th>
                                        <th>Price</th>
                                        <th>Expired Date</th>
                                        <th>Visible On</th>
                                         <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($coupon as $item)
                                    <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        @if($item->is_coupons == 0)
                                        {{ $item->code }}
                                        @else
                                        {{ $item->checkout_max_price }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->type == 0)
                                        <button class="btn btn-sm btn-secondary">Fixed</button>
                                        @else
                                        <button class="btn btn-sm btn-info">Percentage</button>
                                        @endif
                                    </td>
                                    <td>{{ $item->type == 0 ? '$' : '%' }}{{ $item->price }}</td>
                                    <td>{{ $item->expire }}</td>
                                    <td>
                                        @if($item->is_coupons == 0)
                                        <button class="btn btn-sm btn-info">Discount Coupon</button>
                                        @else
                                        <button class="btn btn-sm btn-primary">Checkout Avail Offer</button>
                                        @endif
                                        
                                    </td>
                                    <td>
                                         <input data-id="{{$item->id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $item->status ? 'checked' : '' }}>
                                    </td>
                                    <td>

                                        
                                            <a href="{{ url('/coupon/coupon/' . $item->id . '/edit') }}"
                                               title="Edit Coupon">
                                                <button class="btn btn-primary btn-sm">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"> </i> Edit
                                                </button>
                                            </a>
                                        

                                        
                                            {!! Form::open([
                                       'method'=>'DELETE',
                                       'url' => ['/coupon/coupon', $item->id],
                                       'style' => 'display:inline'
                                   ]) !!}
                                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'title' => 'Delete Coupon',
                                                    'onclick'=>'return confirm("Confirm delete?")'
                                            )) !!}
                                        
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Price</th>
                                        <th>Expired Date</th>
                                        <th>Visible On</th>
                                        <th>Status</th>
                                        <th>Actions</th>
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

@endsection



@push('js')
    <script src="{{asset('assets/js/datatables.min.js')}}"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script>
      $(function() {
        $('.toggle-class').change(function() {
            var status = $(this).prop('checked') == true ? 1 : 0; 
            var coupon_id = $(this).data('id'); 
             
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{route('changeCouponStatus')}}",
                data: {'status': status, 'coupon_id': coupon_id},
                success: function(data){
                  console.log(data.success)
                  $.toast({
                        heading: 'Success!',
                        position: 'top-center',
                        text: data.success,
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3000,
                        stack: 6
                    });
                }
            });
        })
      })
    </script>
    <script>
        $(document).ready(function(){
            $(".zero-configuration").DataTable({
                "order": [
                    [0, 'desc']
                ],
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            @if(\Session::has('message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('message')}}',
                loaderBg: '#ff6849',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
            @endif
        })
    </script>
@endpush