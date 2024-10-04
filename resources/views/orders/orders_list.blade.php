@extends('layout.app')
@section('title', 'Orders | FO - Food Ordering System')

@section('content')
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Orders List</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Order</li>
                            <li class="breadcrumb-item active" aria-current="page">Orders List</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group fill">
                                    <input type="text" id="dateRangeFilter" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group fill">
                                    <button id="todayFilter" class="btn btn-primary">Today</button>
                                    <button id="yesterdayFilter" class="btn btn-primary">Yesterday</button>
                                    <button id="thisMonthFilter" class="btn btn-primary">Last 30 Days</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive rounded card-table">
                            <table class="table border-no" id="orders_table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Received Date Time</th>
                                        <th>Status</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Total</th>
                                        <th>Original Bill</th>
                                        <th>Discount Code</th>
                                        <th>Discount Amount</th>
                                        <th>Order Type</th>
                                        <th>Payment Option</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                    <tr  class="hover-primary">
                                        <td><a href="{{ route('orders.detail', ["id" => base64_encode($order->id) ]) }}"> #{{ $order->id }} </a></td>
                                        <td>{{ $order->formatted_created_at }}</td>
                                        <td>{{ $order->order_status ? config('constants')['ORDER_STATUS'][$order->order_status] : 'New' }}</td>
                                        <td>{{ $order->name }}</td>
                                        <td>{{ $order->phone }}</td>
                                        <td>{{ $order ->address}}</td>
                                        <td>{{ $currencySymbol . $order->total }}</td>
                                        <td>{{ $currencySymbol . $order->original_bill }}</td>
                                        <td>{{ $order->discount_code }}</td>
                                        <td>{{ $currencySymbol . $order->discount_amount }}</td>
                                        <td>{{ $order->order_type }}</td>
                                        <td>{{ $order->payment_option }}</td>
                                        <td>
                                            <a href="{{route('orders.print', ['id' => base64_encode($order->id)])}}" target="_balnk" class="btn btn-success btn-sm" ><i class="fa fa-print"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
    
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            const ORDER_STATUS = {
                1: 'Accepted',
                2: 'Rejected',
                3: 'Delivered',
                4: 'Canceled',
            };

            let table = $('#orders_table').DataTable();
            
            $('#dateRangeFilter').daterangepicker({
                    opens: 'left'
                }, function(start, end, label) {
                    const startDate = $('#dateRangeFilter').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    const endDate = $('#dateRangeFilter').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    fetchFilteredData(startDate, endDate);
            });

            // "Today" button click event
            $('#todayFilter').click(function() {
                const today = moment().format('YYYY-MM-DD');
                fetchFilteredData(today, today);
            });

            // "Yesterday" button click event
            $('#yesterdayFilter').click(function() {
                const yesterday = moment().subtract(1, 'days').format('YYYY-MM-DD');
                fetchFilteredData(yesterday, yesterday);
            });

            // "This Month" button click event
            $('#thisMonthFilter').click(function() {
                const startOfMonth = moment().startOf('month').format('YYYY-MM-DD');
                const endOfMonth = moment().endOf('month').format('YYYY-MM-DD');
                fetchFilteredData(startOfMonth, endOfMonth);
            });

            function fetchFilteredData(startDate, endDate) {
                $.ajax({
                    url: '{{ route("orders.filter") }}',
                    type: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(response) {
                        table.destroy();
                        $('#orders_table tbody').empty();

                        response.forEach(function(order) {
                            console.log(order.id)

                            let orderStatus = ORDER_STATUS[order.order_status] ? ORDER_STATUS[order.order_status] : 'New';

                            let orderRow = `
                            <tr class="hover-primary">
                                <td><a href="/orders/detail/${btoa(order.id)}">#${order.id}</td>
                                <td>${order.formatted_created_at}</td>
                                <td>${orderStatus}</td>
                                <td>${order.name}</td>
                                <td>${order.phone}</td>
                                <td>${order.address}</td>
                                <td>{{ $currencySymbol }}${order.total}</td>
                                <td>{{ $currencySymbol }}${order.original_bill}</td>
                                <td>${order.discount_code ? order.discount_code : ''}</td>
                                <td>{{ $currencySymbol }}${order.discount_amount}</td>
                                <td>${order.order_type}</td>
                                <td>${order.payment_option}</td>
                               <td>
                                    <a href="/orders/print/${btoa(order.id)}" target="_blank" class="btn btn-success btn-sm">
                                        <i class="fa fa-print"></i>
                                    </a>
                                </td>
                            </tr>`;
                        
                            // Append the new row to the table body
                            $('#orders_table tbody').append(orderRow);
                        });
                        table = $('#orders_table').DataTable();
                    },
                    error: function(error) {
                        console.error('Error fetching filtered data:', error);
                    }
                });
            }
        })
    </script>
@endsection