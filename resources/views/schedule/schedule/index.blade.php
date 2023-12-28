@extends('layouts.app')

@push('before-css')
    <link rel="stylesheet" href="{{asset('assets/css/datatables.min.css')}}">
    <style>
        td.sorting_1 {cursor: all-scroll;}
    </style>
@endpush

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-8 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">Schedule</h3>
        <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Home</li>
                    <li class="breadcrumb-item active">Schedule</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-4 col-12">
        <div class="btn-group float-md-right">
            <a class="btn btn-info mb-1" href="{{ url('/schedule/schedule/create') }}">Add Schedule</a>
        </div>
    </div>
</div>

<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Schedule Info</h4>
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
                                        <th>Date</th><th>Location</th><th>Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schedule as $item)
                                    <tr>
                                    <td>{{ $item->order_id }}</td>
                                    <td>{{ $item->date }}</td><td>{{ $item->location }}</td><td>{{ $item->name }}</td>
                                    <td>

                                        
                                            <a href="{{ url('/schedule/schedule/' . $item->id . '/edit') }}"
                                               title="Edit Schedule">
                                                <button class="btn btn-primary btn-sm">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"> </i> Edit
                                                </button>
                                            </a>
                                        

                                        
                                            {!! Form::open([
                                       'method'=>'DELETE',
                                       'url' => ['/schedule/schedule', $item->id],
                                       'style' => 'display:inline'
                                   ]) !!}
                                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'title' => 'Delete Schedule',
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
                                        <th>Date</th><th>Location</th><th>Name</th>
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
<meta name="csrf-token" content="{{ csrf_token() }}" />

@endsection



@push('js')
    <script src="{{asset('assets/js/datatables.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            var originalIndex;
            
            var table = $(".zero-configuration").DataTable({
                "order": [
                    [0, 'desc']
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
                    url: "{{ route('schedule.order') }}",
                    data:formData,
                    success:function(data) {
                        console.log(data);
                    }
                });
            });
  
            // table.on('row-reordered', function (e) {
            //     console.log(e);
            //     console.log("Row-Reorder");
            // });
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