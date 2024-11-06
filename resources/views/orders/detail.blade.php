@extends('layout.app')
@section('title', 'Orders | FO - Food Ordering System')

@section('content')

    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Orders Detail</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Order</li>
                            <li class="breadcrumb-item active" aria-current="page">Orders Detail</li>
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
                        <div class="progress d-none" id="loadingProgressApprove" style="width: 100%;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
                        </div>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="approveOrderButton" onclick="showLoadingApprove()">Approve Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Order Reject Modal -->
    <div class="modal fade" id="orderRejectedModal" tabindex="-1" aria-labelledby="orderRejectedModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderRejectedModalLabel">Enter Rject Reason</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="rejectedOrderForm" method="POST">
                    @csrf
                    <input type="hidden" name="reject" value="1">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="delivery_time">Reject Reason</label>
                            <input type="text" name="reject_reason" id="reject_reason" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="progress d-none" id="loadingProgressReject" style="width: 100%;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
                        </div>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="rejectedOrderButton" onclick="showLoadingReject()">Reject Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xxxl-4 col-12">
                <div class="box">
                    <div class="box-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img class="me-10 rounded-circle avatar avatar-xl b-2 border-primary" src="{{ asset('assets/theme/images/user.svg') }}" alt="">
                                <div>
                                    <h4 class="mb-0">{{ $orderDetails->name }}</h4>
                                    <span class="fs-14 text-info">Customer</span>
                                </div>
                            </div>
                            @if ($orderDetails->order_status == 0)
                                <div>
                                    <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#orderApprovalModal" data-order-id="{{ base64_encode($orderDetails->id) }}"><i class="fa fa-check"></i></a>
                                    <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#orderRejectedModal" data-order-id="{{ base64_encode($orderDetails->id) }}"><i class="fa fa-ban"></i></a>
                                </div>
                            @elseif ($orderDetails->order_status == 1)
                                <div>
                                    <a href="#" class="btn btn-success me-2" onclick="event.preventDefault(); document.getElementById('deliver-order-form-{{ $orderDetails->id }}').submit();">
                                        <i class="fa fa-truck"></i>
                                    </a>
                                    <form id="deliver-order-form-{{ $orderDetails->id }}" action="{{ route('orders.update', base64_encode($orderDetails->id)) }}" method="POST" style="display: none;">
                                        @csrf
                                        <input type="hidden" name="deliver" value="1">
                                    </form>

                                    <a href="#" class="btn btn-danger" onclick="event.preventDefault(); confirmCancelOrder({{ $orderDetails->id }});">
                                        <i class="fa fa-times-circle"></i>
                                    </a>
                                    <form id="cancel-order-form-{{ $orderDetails->id }}" action="{{ route('orders.update', base64_encode($orderDetails->id)) }}" method="POST" style="display: none;">
                                        @csrf
                                        <input type="hidden" name="cancel" value="1">
                                    </form>
                                </div>
                            @endif


                        </div>
                    </div>

                    <div class="box-body border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-phone me-10 fs-24"></i>
                            <h4 class="mb-0">{{ $orderDetails->phone }}</h4>
                        </div>
                    </div>
                    <div class="box-body border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-envelope me-10 fs-24"></i>
                            <h4 class="mb-0 text-black">{{ $orderDetails->email }}</h4>
                        </div>
                    </div>
                    <div class="box-body border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-map-marker me-10 fs-24"></i>
                            <h4 class="mb-0 text-black">{{ $orderDetails->address . ($orderDetails->postcode ? ', '.$orderDetails->postcode : '') }}</h4>
                        </div>
                    </div>
                    <div class="box-body">
                        <h4 class="mb-10">Order Notes</h4>
                        <p>{{ $orderDetails->order_note }}</p>
                    </div>
                </div>
            </div>
            <div class="col-xxxl-8 col-12">
                <div class="box">
                  <div class="box-body">
                    <div class="table-responsive-xl">
                        <table class="table product-overview">
                            <thead>
                                <tr>
                                    <th style="min-width: 200px;">Product Title</th>
                                    <th>Special Inctruction</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th style="text-align:center">Total</th>
                                    {{-- <th style="text-align:center">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderDetails->details as $item)
                                    <tr>
                                        <td>
                                            {{-- <h6>MAIN COURSE</h6> --}}
                                            <h4>{{ $item->product_title }}</h4>
                                            <span>{{ $item->options }}</span>
                                        </td>
                                        <td>{{ $item->item_instruction }}</td>
                                        <!-- temporary delivery charges -->
                                        <td>£{{ number_format($item->sub_total / $item->quantity, 2) }}</td>
                                        <td width="70">{{ $item->quantity }}</td>
                                        <td width="100" align="center" class="fw-900">£{{ $item->sub_total }}</td>
                                        {{-- <td align="center"><a href="javascript:void(0)" class="btn btn-circle btn-primary btn-xs" title="" data-toggle="tooltip" data-original-title="Delete"><i class="ti-trash"></i></a></td> --}}
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
        document.addEventListener('DOMContentLoaded', function () {
            var orderApprovalModal = document.getElementById('orderApprovalModal');
            orderApprovalModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var orderId = button.getAttribute('data-order-id');
        
                var form = document.getElementById('approveOrderForm');
                form.action = '/orders/update/' + orderId;
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            var orderRejectedModal = document.getElementById('orderRejectedModal');
            orderRejectedModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var orderId = button.getAttribute('data-order-id');
        
                var form = document.getElementById('rejectedOrderForm');
                form.action = '/orders/update/' + orderId;
            });
        });

        function showLoadingApprove() {
            var approveOrderButton = document.getElementById('approveOrderButton');
            var loadingProgress = document.getElementById('loadingProgressApprove');

            // Disable the submit button and show the loading indicator
            approveOrderButton.disabled = true;
            loadingProgress.classList.remove('d-none');

            document.getElementById('approveOrderForm').submit();
        }

        function showLoadingReject() {
            var rejectOrderButton = document.getElementById('rejectedOrderButton');
            var loadingProgress = document.getElementById('loadingProgressReject');

            // Disable the submit button and show the loading indicator
            rejectOrderButton.disabled = true;
            loadingProgress.classList.remove('d-none');

            document.getElementById('rejectedOrderForm').submit();
        }

        function confirmRejectOrder(orderId) {
            if (confirm('Are you sure you want to reject this order?')) {
                document.getElementById('reject-order-form-' + orderId).submit();
            }
        }

        function confirmCancelOrder(orderId) {
            if (confirm('Are you sure you want to cancel this order?')) {
                document.getElementById('cancel-order-form-' + orderId).submit();
            }
        }
    </script>
@endsection