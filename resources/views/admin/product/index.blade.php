@extends('layouts.app')

@push('before-css')
    <link href="{{asset('plugins/components/datatables/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet"
          type="text/css"/>
    
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"  />-->
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    
    <style>
input.form-control.date {
    margin-top: 6px;
    width: 85%;
    padding: 2px;
    font-size: 12px;
}        
    </style>

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
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12">
        <div class="btn-group float-md-right">
            <a class="btn btn-info mb-1" href="{{ url('admin/product/create') }}">Add Product</a>
        </div>
    </div>
</div>

<section id="configuration">
    <div class="row">
        <div class="col-12">
            @if(Session::has('message'))
            <ul style="list-style: none;padding: 0;">
                <li class="alert alert-success">
                    {{ Session::get('message') }}
                </li>
            </ul>
            @endif
            
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Filter By Category</h4>
                </div>
                <div class="col-md-12 d-flex mb-5">
                    <div class="col-md-10 ">
                    <form class="d-flex" method="get" action="{{url('admin/product')}}">  
                    @csrf
                        <div class="col-md-5">    
                            <select name="catId[]" id="" class="form-control select2" >
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            </select>
                        </div>
                       
                        <div class="col-md-3">    
                            <select name="stock" id="" class="form-control" >
                                <option>Select </option>
                                <option value="2" {{($stock == 2 )?'selected':''}}>All</option>
                                <option value="1" {{($stock == 1 )?'selected':''}}>In Stock</option>
                                <option value="0" {{($stock == 0 )?'selected':''}}>Out Of Stock</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex justify-content-end">
                            <button class="btn btn-primary">Search</button>
                        </div>
                    </form>
                    </div>
                    <div class="col-md-2 d-flex justify-content-end">
                        <button class="btn btn-danger">Refresh</button>
                    </div>
                </div>
            </div>


            
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Product Info</h4>
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
                        <div class="">
                            <table class="table table-striped table-bordered zero-configuration" id="myTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Title</th>
                                        <th>Sale Price</th>
                                        <th>Regular Price</th>
                                        <th>Available for Back Order</th>
                                        <th>Stock</th>
                                        <th>Product Category</th>
                                        <th>Featured</th>
                                        <th>Sale Product</th>
                                        <th>New Product</th>
                                        <th>Product Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product as $item)    
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td class="text-dark weight-600"> {!! \Illuminate\Support\Str::limit($item->product_title, 50, $end='...') !!}
                                        </td>
                                        <td>${{ $item->price }}</td>
                                        <td>${{ $item->regular_price }}</td>
                                        
                                        <td>
                                            <input data-id="{{$item->id}}" class="toggle-class backOrder" type="checkbox" data-onstyle="success" data-offstyle="danger" data-name="newProduct" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $item->back_order ? 'checked' : '' }}>
                                        </td>
                                        
                                        <td>@if($item->stock > 0)<p class="text-success">In stock</p>@else<p class="text-danger">Out of Stock</p>@endif</td>
                                        <td>
                                            @foreach($item->category_list as $key => $value)
                                            <button class="btn btn-sm btn-secondary">
                                                {{ $value->getparent() }} {{ $value->name }}
                                            </button>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($item->is_featured_home == 0)
                                            <button class="btn btn-danger btn-sm">NO</button>
                                            @else
                                            <button class="btn btn-info btn-sm">YES</button>
                                            @endif

                                        </td>
                                        <td>
                                            <input data-id="{{$item->id}}" class="toggle-class statusChange" type="checkbox" data-onstyle="success" data-offstyle="danger" data-name="saleProduct"  data-toggle="toggle" data-on="Active" data-off="InActive" {{ $item->sales ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <input data-id="{{$item->id}}" class="toggle-class dateProduct" type="checkbox" data-onstyle="success" data-offstyle="danger" data-name="newProduct" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $item->new_product ? 'checked' : '' }}>
                                            <input class="form-control date" name="newPrdDate" type="date" value="{{$item->new_product_date}}"/>
                                        </td>
                                        <td><img src="{{asset($item->image)}}" alt="" title="" width="150"></td>
                                        <td>
                                            <a href="{{ url('/admin/product/' . $item->id . '/edit') }}">
                                                <button class="btn btn-primary btn-sm">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"> </i> Edit
                                                </button>
                                            </a>
                                            <a href="{{ route('product.delete', $item->id) }}" onclick='return confirm("Confirm delete?")'>
                                                <button class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach  
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Title</th>
                                        <th>Sale Price</th>
                                        <th>Regular Price</th>
                                        <th>Available for Back Order</th>
                                        <th>Stock</th>
                                        <th>Product Category</th>
                                        <th>Featured</th>
                                        <th>Sale Product</th>
                                        <th>New Product</th>
                                        <th>Product Image</th>
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

@push('js')<!-- ============================================================== -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" ></script>-->
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script>
  $(function() {
    $('.statusChange').change(function() {
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var user_id = $(this).data('id'); 
        
            $.ajax({
                type: "GET",
                dataType: "json",
                url: '{{route("changestatus")}}',
                data: {'status': status, 'user_id': user_id},
                success: function(data){
                  console.log(data.success)
                }
            });
        
    })
  })
</script>
<script>
  $(function() {
    $('.dateProduct').change(function() {
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var user_id = $(this).data('id'); 
        var expiryDate = $(this).parent().next().val();
        
            $.ajax({
                type: "GET",
                dataType: "json",
                url: '{{route("changeNewDate")}}',
                data: {'status': status, 'user_id': user_id,'expiryDate':expiryDate},
                success: function(data){
                  console.log(data.success)
                }
            });
        
    })
  })
</script>

<script>
  $(function() {
    $('.backOrder').change(function() {
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var prd_id = $(this).data('id'); 
        
            $.ajax({
                type: "GET",
                dataType: "json",
                url: '{{route("changeBackOrder")}}',
                data: {'status': status, 'prd_id': prd_id},
                success: function(data){
                  console.log(data.success)
                }
            });
        
    })
  })
</script>

<script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>

<script>
    $(function () {
        var table = $('#myTable').DataTable({order:[[0,"desc"]]});
    });
</script>
@endpush