@extends('layout.app')
@section('title', 'Dashboard | FOS - Food Ordering System')

@section('content')
<!-- Main content -->
<section class="content">
    {{-- software manager role --}}
    @if (Auth::user()->role == 1)
        <div class="row">
            <div class="col-xxxl-3 col-lg-6 col-12">
                <div class="box">
                    <div class="box-body">
                        <div class="d-flex align-items-start">
                            <div>
                                <img src="{{ asset('assets/theme/images/dashboad_logo/total_companies.webp')}}" class="w-80 me-20" alt="" />
                            </div>
                            <div>
                                <h2 class="my-0 fw-700">{{ $totalCompanies }}</h2>
                                <p class="text-fade mb-0">Total Companies</p>
                                {{-- <p class="fs-12 mb-0 text-success"><span class="badge badge-pill badge-success-light me-5"><i class="fa fa-arrow-up"></i></span>3% (15 Days)</p> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxxl-3 col-lg-6 col-12">
                <div class="box">
                    <div class="box-body">
                        <div class="d-flex align-items-start">
                            <div>
                                <img src="{{ asset('assets/theme/images/dashboad_logo/total_active.webp')}}" class="w-80 me-20" alt="" />
                            </div>
                            <div>
                                <h2 class="my-0 fw-700">{{ $totalActive }}</h2>
                                <p class="text-fade mb-0">Total Active</p>
                                {{-- <p class="fs-12 mb-0 text-success"><span class="badge badge-pill badge-success-light me-5"><i class="fa fa-arrow-up"></i></span>8% (15 Days)</p> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxxl-3 col-lg-6 col-12">
                <div class="box">
                    <div class="box-body">
                        <div class="d-flex align-items-start">
                            <div>
                                <img src="{{ asset('assets/theme/images/dashboad_logo/total_inactive.webp')}}" class="w-80 me-20" alt="" />
                            </div>
                            <div>
                                <h2 class="my-0 fw-700">{{ $totalInActive }}</h2>
                                <p class="text-fade mb-0">Total Inactive</p>
                                {{-- <p class="fs-12 mb-0 text-primary"><span class="badge badge-pill badge-primary-light me-5"><i class="fa fa-arrow-down"></i></span>2% (15 Days)</p> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxxl-3 col-lg-6 col-12">
                <div class="box">
                    <div class="box-body">
                        <div class="d-flex align-items-start">
                            <div>
                                <img src="{{ asset('assets/theme/images/dashboad_logo/total_revenue.webp')}}" class="w-80 me-20" alt="" />
                            </div>
                            <div>
                                <h2 class="my-0 fw-700">$789k</h2>
                                <p class="text-fade mb-0">Total Revenue</p>
                                {{-- <p class="fs-12 mb-0 text-primary"><span class="badge badge-pill badge-primary-light me-5"><i class="fa fa-arrow-down"></i></span>12% (15 Days)</p> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxxl-7 col-xl-6 col-lg-6 col-12">
                <div class="box">
                    <div class="box-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="box-title mb-0">Daily Revenue</h4>
                                <p class="mb-0 text-mute">Lorem ipsum dolor</p>
                            </div>
                            <div class="text-end">
                                <h3 class="box-title mb-0 fw-700">$ 154K</h3>
                                <p class="mb-0"><span class="text-success">+ 1.5%</span> than last week</p>
                            </div>
                        </div>
                        <div id="chart" class="mt-20"></div>
                    </div>
                </div>
            </div>
            <div class="col-xxxl-5 col-xl-6 col-lg-6 col-12">
                <div class="box">
                    <div class="box-body">
                        <h4 class="box-title">Customer Flow</h4>
                        <div class="d-md-flex d-block justify-content-between">
                            <div>
                                <h3 class="mb-0 fw-700">$2,780k</h3>
                                <p class="mb-0 text-primary"><small>In Restaurant</small></p>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-700">$1,410k</h3>
                                <p class="mb-0 text-danger"><small>Online Order</small></p>
                            </div>
                        </div>
                        <div id="yearly-comparison"></div>
                    </div>
                </div>
            </div>
            <div class="col-xxxl-5 col-12">
                <div class="box">
                    <div class="box-header no-border">
                        <h4 class="box-title">
                            Trending Keyword
                            <small class="subtitle">Lorem ipsum dolor sit amet, consectetur adipisicing elit</small>
                        </h4>
                    </div>
                    <div class="box-body pt-0">
                        <div>
                            <div class="progress mb-5">
                                <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="text-primary">#paneer</p>
                                <p class="text-mute">420 times</p>
                            </div>
                        </div>
                        <div>
                            <div class="progress mb-5">
                                <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="text-primary">#breakfast</p>
                                <p class="text-mute">150 times</p>
                            </div>
                        </div>
                        <div>
                            <div class="progress mb-5">
                                <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="text-primary">#tea</p>
                                <p class="text-mute">120 times</p>
                            </div>
                        </div>
                    </div>
                    <div class="box-body pt-0">
                        <h4 class="box-title d-block">
                            Others Tag
                        </h4>
                        <div class="d-inline-block">
                            <a href="#" class="waves-effect waves-light btn btn-outline btn-rounded btn-primary mb-5">#panjabifood</a>
                            <a href="#" class="waves-effect waves-light btn btn-outline btn-rounded btn-primary mb-5">#chainissfood</a>
                            <a href="#" class="waves-effect waves-light btn btn-outline btn-rounded btn-primary mb-5">#pizza</a>
                            <a href="#" class="waves-effect waves-light btn btn-outline btn-rounded btn-primary mb-5">#burgar</a>
                            <a href="#" class="waves-effect waves-light btn btn-outline btn-rounded btn-primary mb-5">#coffee</a>
                            <a href="#" class="waves-effect waves-light btn btn-outline btn-rounded btn-primary mb-5">20+</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxxl-7 col-12">
                <div class="box">
                    <div class="box-body">
                        <div class="pt-30">
                            <h4 class="box-title mb-20">New Subscriptions</h4>
                            @foreach ($latestSubscriptions as $latestSubscription)
                                <div class="bb-1 pb-10 mb-20 d-lg-flex d-block justify-content-between">
                                    <div class="d-flex">
                                        {{-- <img src="{{ asset('assets/theme/images/avatar/4.jpg')}}" class="w-40 h-40 me-10 rounded100" alt=""> --}}
                                        <div>
                                            <p class="mb-0">{{ $latestSubscription->name }} </p>
                                            <p class="mb-0"><small class="text-mute">Subscription End Date {{ $latestSubscription->expiry_date }}</small></p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0 me-10">{{ $latestSubscription->address }}</p>
                                        {{-- <div class="bg-info rounded-circle w-30 h-30 l-h-30 text-center">
                                            <i class="fa fa-location-arrow"></i>
                                        </div> --}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="form-group fill">
                    <label for="dateRangeFilter">Date Range Filter:</label>
                    <input type="text" id="dateRangeFilter" class="form-control" />
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Total Orders Card -->
            <div class="col-xxxl-3 col-lg-6 col-12">
                <div class="box">
                    <div class="box-body">
                        <!-- Dropdown Filter Icon -->
                        <div class="dropdown" style="position: absolute; top: 10px; right: 10px;">
                            <a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-v" style="font-size: 24px; cursor: pointer;"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" data-filter="today" data-target="totalOrders">Today</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="yesterday" data-target="totalOrders">Yesterday</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="last7days" data-target="totalOrders">Last 7 Days</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="last30days" data-target="totalOrders">Last 30 Days</a></li>
                            </ul>
                        </div>
                        <div class="d-flex align-items-start">
                            <div>
                                <img src="{{ asset('assets/theme/images/food/online-order-1.png')}}" class="w-80 me-20" alt="" />
                            </div>
                            <div>
                                <h2 class="my-0 fw-700" id="totalOrders">{{ $dashboard_data['totalOrders'] }}</h2>
                                <p class="text-fade mb-0">Total Order</p>
                                <p class="fs-12 mb-0 text-success"><span class="badge badge-pill badge-success-light me-5"><i class="fa fa-arrow-up"></i></span>3% (15 Days)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Deliverd Orders Card -->
            <div class="col-xxxl-3 col-lg-6 col-12">
                <div class="box">
                    <div class="box-body">
                        <!-- Dropdown Filter Icon -->
                        <div class="dropdown" style="position: absolute; top: 10px; right: 10px;">
                            <a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-v" style="font-size: 24px; cursor: pointer;"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" data-filter="today" data-target="totalDelivered">Today</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="yesterday" data-target="totalDelivered">Yesterday</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="last7days" data-target="totalDelivered">Last 7 Days</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="last30days" data-target="totalDelivered">Last 30 Days</a></li>
                            </ul>
                        </div>
                        <div class="d-flex align-items-start">
                            <div>
                                <img src="{{ asset('assets/theme/images/food/online-order-2.png')}}" class="w-80 me-20" alt="" />
                            </div>
                            <div>
                                <h2 class="my-0 fw-700" id="totalDelivered">{{ $dashboard_data['totalDelivered'] }}</h2>
                                <p class="text-fade mb-0">Total Delivered</p>
                                <p class="fs-12 mb-0 text-success"><span class="badge badge-pill badge-success-light me-5"><i class="fa fa-arrow-up"></i></span>8% (15 Days)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Canceled Orders Card -->
            <div class="col-xxxl-3 col-lg-6 col-12">
                <div class="box">
                    <div class="box-body">
                        <!-- Dropdown Filter Icon -->
                        <div class="dropdown" style="position: absolute; top: 10px; right: 10px;">
                            <a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-v" style="font-size: 24px; cursor: pointer;"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" data-filter="today" data-target="totalCancelled">Today</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="yesterday" data-target="totalCancelled">Yesterday</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="last7days" data-target="totalCancelled">Last 7 Days</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="last30days" data-target="totalCancelled">Last 30 Days</a></li>
                            </ul>
                        </div>
                        <div class="d-flex align-items-start">
                            <div>
                                <img src="{{ asset('assets/theme/images/food/online-order-3.png')}}" class="w-80 me-20" alt="" />
                            </div>
                            <div>
                                <h2 class="my-0 fw-700" id="totalCancelled">{{ $dashboard_data['totalCancelled'] }}</h2>
                                <p class="text-fade mb-0">Total Canceled</p>
                                <p class="fs-12 mb-0 text-primary"><span class="badge badge-pill badge-primary-light me-5"><i class="fa fa-arrow-down"></i></span>2% (15 Days)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Revenue Card -->
            <div class="col-xxxl-3 col-lg-6 col-12">
                <div class="box">
                    <div class="box-body">
                        <!-- Dropdown Filter Icon -->
                        <div class="dropdown" style="position: absolute; top: 10px; right: 10px;">
                            <a class="" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-v" style="font-size: 24px; cursor: pointer;"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" data-filter="today" data-target="totalRevenue">Today</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="yesterday" data-target="totalRevenue">Yesterday</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="last7days" data-target="totalRevenue">Last 7 Days</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="last30days" data-target="totalRevenue">Last 30 Days</a></li>
                            </ul>
                        </div>
                        <div class="d-flex align-items-start">
                            <div>
                                <img src="{{ asset('assets/theme/images/food/online-order-4.png')}}" class="w-80 me-20" alt="" />
                            </div>
                            <div>
                                <h2 class="my-0 fw-700" id="totalRevenue">£{{ $dashboard_data['totalRevenue'] }}</h2>
                                <p class="text-fade mb-0">Total Revenue</p>
                                <p class="fs-12 mb-0 text-primary"><span class="badge badge-pill badge-primary-light me-5"><i class="fa fa-arrow-down"></i></span>12% (15 Days)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weekly Revenue Graph -->
            <div class="col-xxxl-7 col-xl-6 col-lg-6 col-12">
                <div class="box">
                    <div class="box-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="box-title mb-0">Weekly Revenue</h4>
                                {{-- <p class="mb-0 text-mute">Lorem ipsum dolor</p> --}}
                            </div>
                            <div class="text-end">
                                @php
                                    $percentageChange = $revenue['percentage'];
                                    $colorClass = $percentageChange >= 0 ? 'text-success' : 'text-danger';
                                    $sign = $percentageChange >= 0 ? '+' : '';
                                @endphp

                                <h3 class="box-title mb-0 fw-700">£{{ $revenue['lastSevenDaysRevenue'] }}</h3>
                                <p class="mb-0"><span class="{{$colorClass}}">{{ $sign . number_format($percentageChange, 1) }}%</span> than last week</p>
                            </div>
                        </div>
                        <div id="chartRevenueRestaurant" class="mt-20"></div>
                    </div>
                </div>
            </div>
            <div class="col-xxxl-5 col-xl-6 col-lg-6 col-12">
                <div class="box">
                    <div class="box-body">
                        <h4 class="box-title">Customer Flow</h4>
                        <div class="d-md-flex d-block justify-content-between">
                            <div>
                                <h3 class="mb-0 fw-700">$2,780k</h3>
                                <p class="mb-0 text-primary"><small>In Restaurant</small></p>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-700">$1,410k</h3>
                                <p class="mb-0 text-danger"><small>Online Order</small></p>
                            </div>
                        </div>
                        <div id="yearly-comparison"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>
<!-- /.content -->
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Handle individual card filter clicks
            $('.dropdown-item').on('click', function(e) {
                e.preventDefault();

                const filterType = $(this).data('filter');
                const targetCard = $(this).data('target');

                let startDate, endDate;

                // Calculate the date range based on the filter type
                switch (filterType) {
                    case 'today':
                        startDate = moment().format('YYYY-MM-DD');
                        endDate = moment().format('YYYY-MM-DD');
                        break;
                    case 'yesterday':
                        startDate = moment().subtract(1, 'days').format('YYYY-MM-DD');
                        endDate = startDate;
                        break;
                    case 'last7days':
                        startDate = moment().subtract(6, 'days').format('YYYY-MM-DD');
                        endDate = moment().format('YYYY-MM-DD');
                        break;
                    case 'last30days':
                        startDate = moment().subtract(29, 'days').format('YYYY-MM-DD');
                        endDate = moment().format('YYYY-MM-DD');
                        break;
                }
                fetchFilteredDataIndividual(startDate, endDate, targetCard);
            });

            function fetchFilteredDataIndividual(startDate, endDate, targetCard) {
                $.ajax({
                    url: '{{ route("dashboard.filter") }}',
                    type: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                    },
                    success: function(data) {
                        // Update specific card with the filtered data
                        if(targetCard == 'totalOrders'){
                            $('#' + targetCard).text(data.stats.totalOrders);
                        } else if(targetCard == 'totalDelivered') {
                            $('#' + targetCard).text(data.stats.totalDelivered);
                        } else if(targetCard == 'totalCancelled') {
                            $('#' + targetCard).text(data.stats.totalCancelled);
                        } else if(targetCard == 'totalRevenue') {
                            $('#' + targetCard).text('£' +data.stats.totalRevenue);
                        }

                    },
                    error: function(error) {
                        console.error('Error fetching filtered data:', error);
                    }
                });
            }

            $('#dateRangeFilter').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                const startDate = $('#dateRangeFilter').data('daterangepicker').startDate.format('YYYY-MM-DD');
                const endDate = $('#dateRangeFilter').data('daterangepicker').endDate.format('YYYY-MM-DD');
                fetchFilteredData(startDate, endDate);
            });

            function fetchFilteredData(startDate, endDate) {
                $.ajax({
                    url: '{{ route("dashboard.filter") }}',
                    type: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(data) {
                        // Update your dashboard with the filtered data
                        // console.log(data.stats.totalOrders);
                        $('#totalOrders').text(data.stats.totalOrders);
                        $('#totalDelivered').text(data.stats.totalDelivered);
                        $('#totalCancelled').text(data.stats.totalCancelled);
                        $('#totalRevenue').text('£' +data.stats.totalRevenue);

                    },
                    error: function(error) {
                        console.error('Error fetching filtered data:', error);
                    }
                });
            }
        });
    </script>

@if(isset($chartData))
    <script>
        var options = {
            series: [{
                name: 'Revenue',
                data: @json($chartData['series']),
            }],
            chart: {
                height: 350,
                type: 'area',
                zoom: {
                    enabled: false
                },
            },
            colors: ["#4c95dd"],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                categories: @json($chartData['categories']),
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "£" + val;
                    }
                },
            },
        };

        var chart = new ApexCharts(document.querySelector("#chartRevenueRestaurant"), options);
        chart.render();
    </script>
@endif
@endsection