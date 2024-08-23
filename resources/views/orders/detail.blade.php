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

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xxxl-4 col-12">
                <div class="box">
                    <div class="box-body">
                        <div class="d-flex align-items-center">
                            <img class="me-10 rounded-circle avatar avatar-xl b-2 border-primary" src="../images/avatar/1.jpg" alt="">
                            <div>
                                <h4 class="mb-0">{{ $orderDetails->name}}</h4>
                                <span class="fs-14 text-info">Customer</span>
                            </div>
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
                            <i class="fa fa-map-marker me-10 fs-24"></i>
                            <h4 class="mb-0 text-black">{{ $orderDetails->address }}</h4>
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
                                    <th>Item</th>
                                    <th style="min-width: 300px;">Product info</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th style="text-align:center">Total</th>
                                    <th style="text-align:center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderDetails->details as $item)
                                    <tr>
                                        <td><img src="../images/product/product-1.png" alt="" width="80"></td>
                                        <td>
                                            {{-- <h6>MAIN COURSE</h6> --}}
                                            <h4>{{ $item->product_title }}</h4>
                                            <span>{{ $item->options }}</span>
                                        </td>
                                        <td>£{{ $item->sub_total / $item->quantity }}</td>
                                        <td width="70">{{ $item->quantity }}</td>
                                        <td width="100" align="center" class="fw-900">£{{ $item->sub_total }}</td>
                                        <td align="center"><a href="javascript:void(0)" class="btn btn-circle btn-primary btn-xs" title="" data-toggle="tooltip" data-original-title="Delete"><i class="ti-trash"></i></a></td>
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