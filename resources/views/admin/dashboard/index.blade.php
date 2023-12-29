@extends('layouts.app')

@push('before-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chartist/0.11.4/chartist.min.css" integrity="sha512-V0+DPzYyLzIiMiWCg3nNdY+NyIiK9bED/T1xNBj08CaIUyK3sXRpB26OUCIzujMevxY9TRJFHQIxTwgzb0jVLg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Vector CSS -->
    <link href="{{asset('plugins/vendors/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet"/>
    <!-- page css -->
    <link href="{{asset('assets/css/pages/google-vector-map.css')}}" rel="stylesheet">
@endpush

@section('content')
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    
    <div class="container-fluid mt-1">
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card pull-up">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="media-body text-left">
                                    <h3 class="info mt-0">{{$orderTotal}}</h3>
                                    <h6 class="mt-0">Products Sold</h6>
                                </div>
                                <div>
                                    <i class="la la-shopping-cart info font-large-2 float-right"></i>
                                </div>
                            </div>
                            <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                <div class="progress-bar bg-gradient-x-info" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card pull-up">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="media-body text-left">
                                    <h3 class="warning mt-0">${{$orderTotalPrice}}</h3>
                                    <h6 class="mt-0">Total Income</h6>
                                </div>
                                <div>
                                    <i class="la la-pie-chart warning font-large-2 float-right"></i>
                                </div>
                            </div>
                            <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                <div class="progress-bar bg-gradient-x-warning" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card pull-up">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="media-body text-left">
                                    <h3 class="success mt-0">{{count($user)}}</h3>
                                    <h6 class="mt-0">New Customers</h6>
                                </div>
                                <div>
                                    <i class="la la-user-plus success font-large-2 float-right"></i>
                                </div>
                            </div>
                            <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                <div class="progress-bar bg-gradient-x-success" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card pull-up">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="media d-flex">
                                <div class="media-body text-left">
                                    <h3 class="danger mt-0">{{ $totalOrder }}</h3>
                                    <h6 class="mt-0">Total Orders</h6>
                                </div>
                                <div>
                                    <i class="la la-heart danger font-large-2 float-right"></i>
                                </div>
                            </div>
                            <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                <div class="progress-bar bg-gradient-x-danger" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="row match-height">
            <div class="col-xl-8 col-12" id="ecommerceChartView">
                <div class="card card-shadow">
                    <div class="card-header card-header-transparent py-20">
                        <div class="btn-group dropdown">
                            <a href="#" class="text-body dropdown-toggle blue-grey-700" data-toggle="dropdown">PRODUCTS SALES</a>
                            <div class="dropdown-menu animate" role="menu">
                                <a class="dropdown-item" href="#" role="menuitem">Sales</a>
                                <a class="dropdown-item" href="#" role="menuitem">Total sales</a>
                                <a class="dropdown-item" href="#" role="menuitem">profit</a>
                            </div>
                        </div>
                        <ul class="nav nav-pills nav-pills-rounded chart-action float-right btn-group" role="group">
                            <li class="nav-item">
                                <a class="active nav-link" data-toggle="tab" href="#scoreLineToDay">Day</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#scoreLineToWeek">Week</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#scoreLineToMonth">Month</a>
                            </li>
                        </ul>
                    </div>
                    <div class="widget-content tab-content bg-white p-20">
                        <div class="ct-chart tab-pane active scoreLineShadow" id="scoreLineToDay"></div>
                        <div class="ct-chart tab-pane scoreLineShadow" id="scoreLineToWeek"></div>
                        <div class="ct-chart tab-pane scoreLineShadow" id="scoreLineToMonth"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">New Orders</h4>
                        <a class="heading-elements-toggle">
                            <i class="la la-ellipsis-v font-medium-3"></i>
                        </a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li>
                                    <a data-action="reload">
                                        <i class="ft-rotate-cw"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content">
                        <div id="new-orders" class="media-list position-relative">
                            <div class="table-responsive">
                                <table id="new-orders-table" class="table table-hover table-xl mb-0">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0">Product</th>
                                            <th class="border-top-0">Customers</th>
                                            <th class="border-top-0">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $pro = DB::table('products')->orderBy('id', 'desc')->limit(5)->get();
                                        @endphp
                                        @foreach($pro as $key => $value)
                                        <tr>
                                            <td class="text-truncate">
                                                {{ \Illuminate\Support\Str::limit($value->product_title, 10, $end='...') }}
                                            </td>
                                            <td class="text-truncate p-1">
                                                <ul class="list-unstyled users-list m-0">
                                                    <li data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="John Doe" class="avatar avatar-sm pull-up">
                                                        <img class="media-object rounded-circle" src="{{ asset('images/avatar-s-19.png') }}" alt="Avatar">
                                                    </li>
                                                    <li data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Katherine Nichols" class="avatar avatar-sm pull-up">
                                                        <img class="media-object rounded-circle" src="{{ asset('images/avatar-s-18.png') }}" alt="Avatar">
                                                    </li>
                                                </ul>
                                            </td>
                                            <td class="text-truncate">${{$value->regular_price}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="row">
            <div id="recent-transactions" class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Recent Transactions</h4>
                        <a class="heading-elements-toggle">
                            <i class="la la-ellipsis-v font-medium-3"></i>
                        </a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li>
                                    <a class="btn btn-sm btn-danger box-shadow-2 round btn-min-width pull-right" href="invoice-summary.html" target="_blank">Invoice Summary</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table id="recent-orders" class="table table-hover table-xl mb-0">
                                <thead>
                                    <tr>
                                        <th class="border-top-0">Status</th>
                                        <th class="border-top-0">Invoice#</th>
                                        <th class="border-top-0">Customer Name</th>
                                        <th class="border-top-0">Products</th>
                                        <!--<th class="border-top-0">Categories</th>-->
                                        <th class="border-top-0">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $key => $item)
                                    <tr>
                                        <td class="text-truncate">
                                            <i class="la la-dot-circle-o success font-medium-1 mr-1"></i> Paid
                                        </td>
                                        <td class="text-truncate">
                                            <a href="#">{{$item->invoice_number}}</a>
                                        </td>
                                        <td class="text-truncate">
                                            {{-- <span class="avatar avatar-xs">
                                                <img class="box-shadow-2" src="{{ asset('images/avatar-s-4.png') }}" alt="avatar">
                                            </span> --}}
                                            <span>{{$item->delivery_first_name}} {{$item->delivery_last_name}}</span>
                                        </td>
                                        <td class="text-truncate p-1">
                                            <ul class="list-unstyled users-list m-0">
                                                @php
                                                    $prdName = \DB::table('orders_products')->where('orders_id',$item->id)->get();
                                                    $prdCat = \DB::table('products')->where('id',$item->order_products_product_id)->first();
                                                @endphp
                                                
                                                @foreach($prdName as $key => $item)
                                                @php
                                                    $image = DB::table('products')->where('id',$item->order_products_product_id)->first();
                                                @endphp
                                                <li data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="{{$item->order_products_name}}" class="avatar avatar-sm pull-up">
                                                    <img class="media-object rounded-circle no-border-top-radius no-border-bottom-radius" src="{{ asset($image->image) }}" alt="Avatar">
                                                </li>
                                                @if($key >2)
                                                <li class="avatar avatar-sm">
                                                    <span class="badge badge-info">+1 more</span>
                                                </li>
                                                @endif
                                                @endforeach
                                                <!--<li data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Willie Torres" class="avatar avatar-sm pull-up">-->
                                                <!--    <img class="media-object rounded-circle no-border-top-radius no-border-bottom-radius" src="{{ asset('uploads/product/GS142bw.png') }}" alt="Avatar">-->
                                                <!--</li>-->
                                                <!--<li data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Rebecca Jones" class="avatar avatar-sm pull-up">-->
                                                <!--    <img class="media-object rounded-circle no-border-top-radius no-border-bottom-radius" src="{{ asset('uploads/product/GS357-345-553allbw.png') }}" alt="Avatar">-->
                                                <!--</li>-->
                                                
                                            </ul>
                                        </td>
                                        <!--<td>-->
                                        <!--    <button type="button" class="btn btn-sm btn-outline-danger round">{{$prdCat->category}}</button>-->
                                        <!--</td>-->
                                        <td class="text-truncate">${{$item->order_products_subtotal}}</td>
                                    </tr>
                                    @endforeach
                                    <!--<tr>-->
                                    <!--    <td class="text-truncate">-->
                                    <!--        <i class="la la-dot-circle-o danger font-medium-1 mr-1"></i> Declined-->
                                    <!--    </td>-->
                                    <!--    <td class="text-truncate">-->
                                    <!--        <a href="#">INV-001002</a>-->
                                    <!--    </td>-->
                                    <!--    <td class="text-truncate">-->
                                    <!--        <span class="avatar avatar-xs">-->
                                    <!--            <img class="box-shadow-2" src="{{ asset('images/avatar-s-5.png') }}" alt="avatar">-->
                                    <!--        </span>-->
                                    <!--        <span>Doris R.</span>-->
                                    <!--    </td>-->
                                    <!--    <td class="text-truncate p-1">-->
                                    <!--        <ul class="list-unstyled users-list m-0">-->
                                    <!--            <li data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Kimberly Simmons" class="avatar avatar-sm pull-up">-->
                                    <!--                <img class="media-object rounded-circle no-border-top-radius no-border-bottom-radius" src="{{ asset('uploads/product/K376-SSbw.png') }}" alt="Avatar">-->
                                    <!--            </li>-->
                                    <!--        </ul>-->
                                    <!--    </td>-->
                                    <!--    <td>-->
                                    <!--        <button type="button" class="btn btn-sm btn-outline-warning round">C8 Ankle Bracelets</button>-->
                                    <!--    </td>-->
                                    <!--    <td>-->
                                    <!--        <div class="progress progress-sm mt-1 mb-0 box-shadow-2">-->
                                    <!--            <div class="progress-bar bg-gradient-x-warning" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>-->
                                    <!--        </div>-->
                                    <!--    </td>-->
                                    <!--    <td class="text-truncate">$ 1850.00</td>-->
                                    <!--</tr>-->
                                    <!--<tr>-->
                                    <!--    <td class="text-truncate">-->
                                    <!--        <i class="la la-dot-circle-o warning font-medium-1 mr-1"></i> Pending-->
                                    <!--    </td>-->
                                    <!--    <td class="text-truncate">-->
                                    <!--        <a href="#">INV-001003</a>-->
                                    <!--    </td>-->
                                    <!--    <td class="text-truncate">-->
                                    <!--        <span class="avatar avatar-xs">-->
                                    <!--            <img class="box-shadow-2" src="{{ asset('images/avatar-s-6.png') }}" alt="avatar">-->
                                    <!--        </span>-->
                                    <!--        <span>Megan S.</span>-->
                                    <!--    </td>-->
                                    <!--    <td class="text-truncate p-1">-->
                                    <!--        <ul class="list-unstyled users-list m-0">-->
                                    <!--            <li data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Kimberly Simmons" class="avatar avatar-sm pull-up">-->
                                    <!--                <img class="media-object rounded-circle no-border-top-radius no-border-bottom-radius" src="{{ asset('uploads/product/K091bothbw.png') }}" alt="Avatar">-->
                                    <!--            </li>-->
                                    <!--            <li data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Willie Torres" class="avatar avatar-sm pull-up">-->
                                    <!--                <img class="media-object rounded-circle no-border-top-radius no-border-bottom-radius" src="{{ asset('uploads/product/K339bothbw.png') }}" alt="Avatar">-->
                                    <!--            </li>-->
                                    <!--        </ul>-->
                                    <!--    </td>-->
                                    <!--    <td>-->
                                    <!--        <button type="button" class="btn btn-sm btn-outline-success round">Chevy Bowtie Pendants</button>-->
                                    <!--    </td>-->
                                    <!--    <td>-->
                                    <!--        <div class="progress progress-sm mt-1 mb-0 box-shadow-2">-->
                                    <!--            <div class="progress-bar bg-gradient-x-success" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>-->
                                    <!--        </div>-->
                                    <!--    </td>-->
                                    <!--    <td class="text-truncate">$ 3200.00</td>-->
                                    <!--</tr>-->
                                    <!--<tr>-->
                                    <!--    <td class="text-truncate">-->
                                    <!--        <i class="la la-dot-circle-o success font-medium-1 mr-1"></i> Paid-->
                                    <!--    </td>-->
                                    <!--    <td class="text-truncate">-->
                                    <!--        <a href="#">INV-001004</a>-->
                                    <!--    </td>-->
                                    <!--    <td class="text-truncate">-->
                                    <!--        <span class="avatar avatar-xs">-->
                                    <!--            <img class="box-shadow-2" src="{{ asset('images/avatar-s-7.png') }}" alt="avatar">-->
                                    <!--        </span>-->
                                    <!--        <span>Andrew D.</span>-->
                                    <!--    </td>-->
                                    <!--    <td class="text-truncate p-1">-->
                                    <!--        <ul class="list-unstyled users-list m-0">-->
                                    <!--            <li data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Kimberly Simmons" class="avatar avatar-sm pull-up">-->
                                    <!--                <img class="media-object rounded-circle no-border-top-radius no-border-bottom-radius" src="{{ asset('uploads/product/K210-C7bw.png') }}" alt="Avatar">-->
                                    <!--            </li>-->
                                    <!--            <li data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Willie Torres" class="avatar avatar-sm pull-up">-->
                                    <!--                <img class="media-object rounded-circle no-border-top-radius no-border-bottom-radius" src="{{ asset('uploads/product/K214-C7bw.png') }}" alt="Avatar">-->
                                    <!--            </li>-->
                                    <!--            <li class="avatar avatar-sm">-->
                                    <!--                <span class="badge badge-info">+1 more</span>-->
                                    <!--            </li>-->
                                    <!--        </ul>-->
                                    <!--    </td>-->
                                    <!--    <td>-->
                                    <!--        <button type="button" class="btn btn-sm btn-outline-info round">C7 Corvette Accessories</button>-->
                                    <!--    </td>-->
                                    <!--    <td>-->
                                    <!--        <div class="progress progress-sm mt-1 mb-0 box-shadow-2">-->
                                    <!--            <div class="progress-bar bg-gradient-x-info" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>-->
                                    <!--        </div>-->
                                    <!--    </td>-->
                                    <!--    <td class="text-truncate">$ 4500.00</td>-->
                                    <!--</tr>-->
                                    <!--<tr>-->
                                    <!--    <td class="text-truncate">-->
                                    <!--        <i class="la la-dot-circle-o success font-medium-1 mr-1"></i> Paid-->
                                    <!--    </td>-->
                                    <!--    <td class="text-truncate">-->
                                    <!--        <a href="#">INV-001005</a>-->
                                    <!--    </td>-->
                                    <!--    <td class="text-truncate">-->
                                    <!--        <span class="avatar avatar-xs">-->
                                    <!--            <img class="box-shadow-2" src="{{ asset('images/avatar-s-9.png') }}" alt="avatar">-->
                                    <!--        </span>-->
                                    <!--        <span>Walter R.</span>-->
                                    <!--    </td>-->
                                    <!--    <td class="text-truncate p-1">-->
                                    <!--        <ul class="list-unstyled users-list m-0">-->
                                    <!--            <li data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Kimberly Simmons" class="avatar avatar-sm pull-up">-->
                                    <!--                <img class="media-object rounded-circle no-border-top-radius no-border-bottom-radius" src="{{ asset('uploads/product/K385bwBothrev.png') }}" alt="Avatar">-->
                                    <!--            </li>-->
                                    <!--            <li data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="Willie Torres" class="avatar avatar-sm pull-up">-->
                                    <!--                <img class="media-object rounded-circle no-border-top-radius no-border-bottom-radius" src="{{ asset('uploads/product/K384-SSbw.png') }}" alt="Avatar">-->
                                    <!--            </li>-->
                                    <!--        </ul>-->
                                    <!--    </td>-->
                                    <!--    <td>-->
                                    <!--        <button type="button" class="btn btn-sm btn-outline-danger round">New Camaro Jewelry</button>-->
                                    <!--    </td>-->
                                    <!--    <td>-->
                                    <!--        <div class="progress progress-sm mt-1 mb-0 box-shadow-2">-->
                                    <!--            <div class="progress-bar bg-gradient-x-danger" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>-->
                                    <!--        </div>-->
                                    <!--    </td>-->
                                    <!--    <td class="text-truncate">$ 1500.00</td>-->
                                    <!--</tr>-->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="row match-height">
            <div class="col-xl-8 col-lg-12">
                <div class="card">
                    <div class="card-content ">
                        <div id="cost-revenue" class="height-250 position-relative"></div>
                    </div>
                    <div class="card-footer">
                        <div class="row mt-1">
                            <div class="col-3 text-center">
                                <h6 class="text-muted">Total Products</h6>
                                <h2 class="block font-weight-normal">{{count($totalPrd)}}</h2>
                                <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                    <div class="progress-bar bg-gradient-x-info" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <h6 class="text-muted">Total Sales</h6>
                                <h2 class="block font-weight-normal">64.54 M</h2>
                                <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                    <div class="progress-bar bg-gradient-x-warning" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <h6 class="text-muted">Total Cost</h6>
                                <h2 class="block font-weight-normal">24.38 B</h2>
                                <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                    <div class="progress-bar bg-gradient-x-danger" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <h6 class="text-muted">Total Revenue</h6>
                                <h2 class="block font-weight-normal">${{$orderTotalPrice}}</h2>
                                <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                    <div class="progress-bar bg-gradient-x-success" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body sales-growth-chart">
                            <div id="monthly-sales" class="height-250"></div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="chart-title mb-1 text-center">
                            <h6>Total monthly Sales.</h6>
                        </div>
                        <div class="chart-stats text-center">
                            <a href="#" class="btn btn-sm btn-danger box-shadow-2 mr-1">Statistics <i class="ft-bar-chart"></i></a> <span class="text-muted">for the last year.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="row d-none">
            <div class="col-lg-12 col-md-12">
                <div class="dashboard">
                    <h1 style="text-align: center; margin: 10px 0px 30px">Welcome To {{ config('app.name') }}</h1>

                    <img alt="" style=" width: 200px; margin: 0px auto; display: flex; " class="img-responsive" id="blah1" src="{{ asset(!empty($logo->img_path)?asset($logo->img_path):'') }}">
                </div>
            </div>
            <!-- Column -->
        </div>
        <!-- ============================================================== -->
        <!-- End chart box one -->
        <!-- chart box two -->
        <!-- ============================================================== -->

    </div>
@endsection

@push('js')
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!--c3 JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartist/0.11.4/chartist.min.js" integrity="sha512-9rxMbTkN9JcgG5euudGbdIbhFZ7KGyAuVomdQDI9qXfPply9BJh0iqA7E/moLCatH2JD4xBGHwV6ezBkCpnjRQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('plugins/components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js') }}"></script>
    <script src="{{ asset('plugins/vendors/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('plugins/vendors/morrisjs/morris.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard-ecommerce.min.js') }}"></script>
@endpush
