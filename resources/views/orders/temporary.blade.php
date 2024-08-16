@extends('layout.app')
@section('title', 'Orders | FO - Food Ordering System')

@section('content')
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

    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Pending Orders List</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Pending Order</li>
                            <li class="breadcrumb-item active" aria-current="page">Pending Orders List</li>
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
                        <div class="table-responsive rounded card-table">
                            <table class="table border-no" id="example1">
                                <thead>
                                    <tr>
                                        <th>#</th>
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
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->name }}</td>
                                            <td>{{ $order->phone }}</td>
                                            <td>{{ $order ->address}}</td>
                                            <td>£{{ $order->total }}</td>
                                            <td>{{ $order->order_type }}</td>
                                            <td>{{ $order->payment_option }}</td>
                                            <td>
                                                {{-- <a href="{{ route('orders.approve', $order->id) }}" class="btn btn-success btn-sm">Approve</a> --}}
                                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#orderApprovalModal" data-order-id="{{ $order->id }}">
                                                    Approve
                                                </button>
                                                <a href="{{ route('orders.reject', $order->id) }}" class="btn btn-danger btn-sm">Reject</a>
                            
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
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var orderApprovalModal = document.getElementById('orderApprovalModal');
            orderApprovalModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var orderId = button.getAttribute('data-order-id');
        
                var form = document.getElementById('approveOrderForm');
                form.action = '/orders/approve/' + orderId;
            });
        });
    </script>
@endsection
