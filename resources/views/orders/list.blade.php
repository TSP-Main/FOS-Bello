@extends('layout.app')
@section('title', 'Orders | FO - Food Ordering System')

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Success!</strong> {{ session()->get('success')}}
        </div>
    @elseif (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Error!</strong> {{ session()->get('error')}}
        </div>
    @endif

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

    <!-- Order Approval Modal -->
    <div class="modal fade" id="orderApprovalModal" tabindex="-1" aria-labelledby="orderApprovalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderApprovalModalLabel">Select Delivery Time</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="approveOrderForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="delivery_time">Delivery Time</label>
                            <select name="delivery_time" id="delivery_time" class="form-control" required>
                                <option value="15">15 min</option>
                                <option value="30">30 min</option>
                                <option value="45">45 min</option>
                                <option value="60">60 min</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Approve Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">

        <div class="col-12">
            <div class="box">
              <div class="box-header with-border">
                <h4 class="box-title">Orders Tab</h4>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs customtab2" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#incomingOrdersTab" role="tab"><span class="hidden-sm-up"><i class="ion-home"></i></span> <span class="hidden-xs-down">Incoming</span></a> </li>
                    <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#acceptedOrdersTab" role="tab"><span class="hidden-sm-up"><i class="ion-person"></i></span> <span class="hidden-xs-down">Accepted</span></a> </li>
                    <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#deliveredOrdersTab" role="tab"><span class="hidden-sm-up"><i class="ion-person"></i></span> <span class="hidden-xs-down">Delivered</span></a> </li>
                    <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#rejectedOrdersTab" role="tab"><span class="hidden-sm-up"><i class="ion-person"></i></span> <span class="hidden-xs-down">Rejected</span></a> </li>
                    <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#canceledOrdersTab" role="tab"><span class="hidden-sm-up"><i class="ion-person"></i></span> <span class="hidden-xs-down">Canceled</span></a> </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- incoming orders tab -->
                    <div class="tab-pane active" id="incomingOrdersTab" role="tabpanel">
                        <div class="p-15">
                            <div class="table-responsive rounded card-table">
                                <table class="table border-no" id="incomingOrders">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Received Date Time</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>Total</th>
                                            <th>Order Type</th>
                                            <th>Payment Option</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($incomingOrders as $incomingOrder)
                                            <tr  class="hover-primary">
                                                <td><a href="{{ route('orders.detail', ["id" => base64_encode($incomingOrder->id) ]) }}"> #{{ $incomingOrder->id }} </a></td>
                                                <td>{{ $incomingOrder->formatted_created_at }}</td>
                                                <td>{{ $incomingOrder->name }}</td>
                                                <td>{{ $incomingOrder->phone }}</td>
                                                <td>{{ $incomingOrder ->address}}</td>
                                                <td>£{{ $incomingOrder->total }}</td>
                                                <td>{{ $incomingOrder->order_type }}</td>
                                                <td>{{ $incomingOrder->payment_option }}</td>
                                                <td>
                                                    <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#orderApprovalModal" data-order-id="{{ base64_encode($incomingOrder->id) }}"><i class="fa fa-check"></i></a>
                                                    <a href="#" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirmRejectOrder({{ $incomingOrder->id }});"><i class="fa fa-ban"></i></a>
                                                    <form id="reject-order-form-{{ $incomingOrder->id }}" action="{{ route('orders.update', base64_encode($incomingOrder->id)) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        <input type="hidden" name="reject" value="1">
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Accepted Orders Tab -->
                    <div class="tab-pane" id="acceptedOrdersTab" role="tabpanel">
                        <div class="p-15">
                            <div class="table-responsive rounded card-table">
                                <table class="table border-no" id="acceptedOrders">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Accepted Date Time</th>
                                            <th>Customer Name</th>
                                            <th>Address</th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($acceptedOrders as $acceptedOrder)
                                            <tr class="hover-primary">
                                                <td><a href="{{ route('orders.detail', ["id" => base64_encode($acceptedOrder->id) ]) }}"> #{{ $acceptedOrder->id }} </a></td>
                                                <td>{{ $acceptedOrder->formatted_updated_at }}</td>
                                                <td>{{ $acceptedOrder->name }}</td>
                                                <td>{{ $acceptedOrder->address ?? NULL}}</td>
                                                <td>£{{ $acceptedOrder->total}}</td>
                                                <td>
                                                    <a href="#" class="btn btn-success btn-sm" onclick="event.preventDefault(); document.getElementById('deliver-order-form-{{ $acceptedOrder->id }}').submit();"><i class="fa fa-check"></i></a>
                                                    <form id="deliver-order-form-{{ $acceptedOrder->id }}" action="{{ route('orders.update', base64_encode($acceptedOrder->id)) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        <input type="hidden" name="deliver" value="1">
                                                    </form>

                                                    <a href="#" class="btn btn-danger btn-sm" onclick="event.preventDefault(); document.getElementById('cancel-order-form-{{ $acceptedOrder->id }}').submit();"><i class="fa fa-close"></i></a>
                                                    <form id="cancel-order-form-{{ $acceptedOrder->id }}" action="{{ route('orders.update', base64_encode($acceptedOrder->id)) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        <input type="hidden" name="cancel" value="1">
                                                    </form>

                                                    <a href="{{route('orders.print', ['id' => base64_encode($acceptedOrder->id)])}}" target="_balnk" class="btn btn-success btn-sm" ><i class="fa fa-print"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Delivered Orderes Tab -->
                    <div class="tab-pane" id="deliveredOrdersTab" role="tabpanel">
                        <div class="p-15">
                            <div class="table-responsive rounded card-table">
                                <table class="table border-no" id="deliveredOrdersTab">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Date</th>
                                            <th>Customer Name</th>
                                            <th>Address</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($deliveredOrders as $deliveredOrder)
                                            <tr class="hover-primary">
                                                <td><a href="{{ route('orders.detail', ["id" => base64_encode($deliveredOrder->id) ]) }}" target="_blank"> #{{ $deliveredOrder->id }} </a></td>
                                                <td>{{ $deliveredOrder->formatted_updated_at }}</td>
                                                <td>{{ $deliveredOrder->name }}</td>
                                                <td>{{ $deliveredOrder->address ?? NULL}}</td>
                                                <td>£{{ $deliveredOrder->total}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Rejected Orders Tab -->
                    <div class="tab-pane" id="rejectedOrdersTab" role="tabpanel">
                        <div class="p-15">
                            <div class="table-responsive rounded card-table">
                                <table class="table border-no" id="rejectedOrdersTab">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Date</th>
                                            <th>Customer Name</th>
                                            <th>Address</th>
                                            <th>Amount</th>
                                            {{-- <th>Status Order</th> --}}
                                            {{-- <th></th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rejectedOrders as $rejectedOrder)
                                            <tr class="hover-primary">
                                                <td><a href="{{ route('orders.detail', ["id" => base64_encode($rejectedOrder->id) ]) }}" target="_blank"> #{{ $rejectedOrder->id }} </a></td>
                                                <td>{{ $rejectedOrder->formatted_updated_at }}</td>
                                                <td>{{ $rejectedOrder->name }}</td>
                                                <td>{{ $rejectedOrder->address ?? NULL}}</td>
                                                <td>£{{ $rejectedOrder->total}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Canceled Orders Tab -->
                    <div class="tab-pane" id="canceledOrdersTab" role="tabpanel">
                        <div class="p-15">
                            <div class="table-responsive rounded card-table">
                                <table class="table border-no" id="canceledOrdersTab">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Date</th>
                                            <th>Customer Name</th>
                                            <th>Address</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($canceledOrders as $canceledOrder)
                                            <tr class="hover-primary">
                                                <td><a href="{{ route('orders.detail', ["id" => base64_encode($canceledOrder->id) ]) }}" target="_blank"> #{{ $canceledOrder->id }} </a></td>
                                                <td>{{ $canceledOrder->formatted_updated_at }}</td>
                                                <td>{{ $canceledOrder->name }}</td>
                                                <td>{{ $canceledOrder->address ?? NULL}}</td>
                                                <td>£{{ $canceledOrder->total}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
    
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var orderApprovalModal = document.getElementById('orderApprovalModal');
            orderApprovalModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var orderId = button.getAttribute('data-order-id');
        
                var form = document.getElementById('approveOrderForm');
                form.action = '/orders/update/' + orderId;
            });
        });
        
        function checkIncomingOrders() {
            $.ajax({
                url: "{{ route('orders.incoming') }}",
                method: 'GET',
                success: function(response) {
                    $('#incomingOrders tbody').html('');
                    response.incomingOrders.forEach(order => {
                        $('#incomingOrders tbody').append(`
                            <tr>
                                <td><a href="/orders/detail/${btoa(order.id)}">#${order.id}</td>
                                <td>${order.formatted_created_at}</td>
                                <td>${order.name}</td>
                                <td>${order.phone}</td>
                                <td>${order.address ? order.address : ''}</td>
                                <td>£${order.total}</td>
                                <td>${order.order_type}</td>
                                <td>${order.payment_option}</td>
                                <td>
                                    <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#orderApprovalModal" data-order-id="${btoa(order.id)}"><i class="fa fa-check"></i></a>
                                    <a href="#" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirmRejectOrder(${order.id});"><i class="fa fa-ban"></i></a>
                                    <form id="reject-order-form-${order.id}" action="/orders/update/${btoa(order.id)}" method="POST" style="display: none;">
                                        @csrf
                                        <input type="hidden" name="reject" value="1">
                                    </form>
                                    
                                </td>
                            </tr>
                        `);
                    });
                },
                error: function() {
                    console.log('Error fetching orders');
                }
            });
        }
        // Check for new incoming orders every 5 seconds
        setInterval(checkIncomingOrders, 5000);

        function confirmRejectOrder(orderId) {
            if (confirm('Are you sure you want to reject this order?')) {
                document.getElementById('reject-order-form-' + orderId).submit();
            }
        }

        $(document).ready(function () {
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
            }

            $('.nav-tabs a').on('shown.bs.tab', function (e) {
                var tabName = $(e.target).attr('href');
                localStorage.setItem('activeTab', tabName);
            });
        });
    </script>
@endsection