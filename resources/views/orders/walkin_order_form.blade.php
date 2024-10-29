@extends('layout.app')
@section('title', 'WalkIn Order | FO - Food Ordering System')

@section('content')

    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Walk In Order</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Order</li>
                            <li class="breadcrumb-item active" aria-current="page">Walk In Order</li>
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
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col">
                        <form action="{{ route('users.store') }}" method="post" id="user_form" class="form-horizontal needs-validation" role="form" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-12">						
                                    <div class="form-group">
                                        <h5>Customer Name</h5>
                                        <div class="controls">
                                            <input type="text" name="name" class="form-control"> 
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <h5>Menu <span class="text-danger">*</span></h5>
                                        {{-- <div class="controls">
                                            <select name="category" id="category" required class="form-select">
                                                <option value="">Select Items</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->title }} ( {{$companyDetail->currency_symbol . $product->price}} ) </option>
                                                @endforeach
                                            </select>
                                        </div> --}}

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select class="form-control select2" style="width: 100%;">
                                                    <option value="">Select Items</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->title }} ( {{$companyDetail->currency_symbol . $product->price}} ) </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                          </div>
                                    </div>
                                </div>
                            
                                <div class="text-xs-right">
                                    <button type="submit" class="btn btn-info">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select Items",
                allowClear: true
            });
        });
    </script>
@endsection