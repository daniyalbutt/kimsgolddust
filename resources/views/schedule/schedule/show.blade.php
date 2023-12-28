@extends('layouts.app')

@section('content')
    <div class="container-fluid bg-white mt-5">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box card">
                <div class="card-body">
                    <h3 class="box-title pull-left">Schedule {{ $schedule->id }}</h3>
                    
                        <a class="btn btn-success pull-right" href="{{ url('/schedule/schedule') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> Back</a>
                    
                    <div class="clearfix"></div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                            <tr>
                                <th>ID</th>
                                <td>{{ $schedule->id }}</td>
                            </tr>
                            <tr><th> Date </th><td> {{ $schedule->date }} </td></tr><tr><th> Location </th><td> {{ $schedule->location }} </td></tr><tr><th> Name </th><td> {{ $schedule->name }} </td></tr>
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

