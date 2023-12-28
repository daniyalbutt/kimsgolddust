@extends('layouts.app')

@section('content')
    <div class="container-fluid bg-white mt-5">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box card">
                <div class="card-body">
                    <h3 class="box-title pull-left">Discount {{ $discount->id }}</h3>
                    
                        <a class="btn btn-success pull-right" href="{{ url('/discount/discount') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> Back</a>
                    
                    <div class="clearfix"></div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                            <tr>
                                <th>ID</th>
                                <td>{{ $discount->id }}</td>
                            </tr>
                            <tr><th> Cat Id </th><td> {{ $discount->cat_id }} </td></tr><tr><th> Date Range </th><td> {{ $discount->date_range }} </td></tr><tr><th> Discount Price </th><td> {{ $discount->discount_price }} </td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.admin.footer')
</div>
@endsection

