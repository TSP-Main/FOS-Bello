@extends('layout.app')
@section('title', 'Dashboard | FO - Food Ordering System')
{{-- @section('pageTitle', 'Dashboard')
@section('breadcrumTitle', 'User Dashboard') --}}

@section('content')

    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Order Details</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Order</li>
                            <li class="breadcrumb-item active" aria-current="page">Order Details</li>
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
                                <h4 class="mb-0">Johen doe</h4>
                                <span class="fs-14 text-info">Customer</span>
                            </div>
                        </div>
                    </div>
                    <div class="box-body border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-phone me-10 fs-24"></i>
                            <h4 class="mb-0">+1 123 456 7890</h4>
                        </div>
                    </div>
                    <div class="box-body border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-map-marker me-10 fs-24"></i>
                            <h4 class="mb-0 text-black">1623 E Updahl Ct, Harrison, ID, 83833</h4>
                        </div>
                    </div>
                    <div class="box-body">
                        <h4 class="mb-10">Order Nots</h4>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxxl-12 col-lg-6 col-12">
                        <div class="box">
                            <div class="box-header no-border">
                                <h4 class="box-title">Delivery Person</h4>
                            </div>
                            <div class="box-body text-center">
                                <div class="text-center">
                                    <img src="../images/avatar/3.jpg" class="mb-20 avatar avatar-xxl b-2 border-primary" alt="">
                                    <div>
                                        <h4 class="mb-10 fw-500">Boone Doe</h4>
                                        <span class="fs-14 font-w400">Join since April 21, 2019</span>
                                    </div>
                                </div>
                                <div class="user-social-acount mt-20">
                                    <a href="javascript:void(0);" class="btn btn-circle btn-primary-light"><i class="fa fa-phone"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-circle btn-primary-light"><i class="fa fa-map-marker"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxxl-12 col-lg-6 col-12">
                        <div class="box">
                            <div class="box-header no-border">
                                <h4 class="box-title">
                                    Customer Favourite
                                </h4>
                            </div>
                            <div class="box-body text-center">
                                <div class="bar mx-auto" data-peity='{ "fill": ["#2196f3", "#3da643", "#FDAC42"], "height": 150, "width": 320, "padding":0.2 }'>52,38,24</div>
                                <div class="d-flex justify-content-center mt-30">
                                    <div class="d-flex text-start">
                                        <i class="fa fa-circle text-info me-5"></i>
                                        <h4 class="fw-600">25% <br><small class="text-fade">Pizza</small></h4>
                                    </div>
                                    <div class="d-flex text-start mx-50">
                                        <i class="fa fa-circle text-danger me-5"></i>
                                        <h4 class="fw-600">15% <br><small class="text-fade">Juice </small></h4>
                                    </div>
                                    <div class="d-flex text-start">
                                        <i class="fa fa-circle text-primary me-5"></i>
                                        <h4 class="fw-600">21% <br><small class="text-fade">Dessert </small></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxxl-8 col-12">
                <div class="box">
                    <div class="box-body">
                        <ol class="c-progress-steps">
                            <li class="c-progress-steps__step  done"><span>Order Created</span></li>
                            <li class="c-progress-steps__step  done"><span>Payment Success</span></li>
                            <li class="c-progress-steps__step  current"><span>On Delivery</span></li>
                            <li class="c-progress-steps__step"><span>Order Delivered</span></li>
                        </ol>
                    </div>
                </div>
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
                                <tr>
                                    <td><img src="../images/product/product-1.png" alt="" width="80"></td>
                                    <td>
                                        <h6>MAIN COURSE</h6>
                                        <h4>Instant Pot Pad Thai</h4>
                                    </td>
                                    <td>$270</td>
                                    <td width="70">
                                        <input type="number" class="form-control" placeholder="1" min="0" max="5">
                                    </td>
                                    <td width="100" align="center" class="fw-900">$270</td>
                                    <td align="center"><a href="javascript:void(0)" class="btn btn-circle btn-primary btn-xs" title="" data-toggle="tooltip" data-original-title="Delete"><i class="ti-trash"></i></a></td>
                                </tr>
                                <tr>
                                    <td><img src="../images/product/product-2.png" alt="" width="80"></td>
                                    <td>
                                        <h6>MAIN COURSE</h6>
                                        <h4>Instant Pot Pad Thai</h4>
                                    </td>
                                    <td>$270</td>
                                    <td>
                                        <input type="number" class="form-control" placeholder="1" min="0" max="5">
                                    </td>
                                    <td align="center" class="fw-900">$270</td>
                                    <td align="center"><a href="javascript:void(0)" class="btn btn-circle btn-primary btn-xs" title="" data-toggle="tooltip" data-original-title="Delete"><i class="ti-trash"></i></a></td>
                                </tr>
                                <tr>
                                    <td><img src="../images/product/product-3.png" alt="" width="80"></td>
                                    <td>
                                        <h6>MAIN COURSE</h6>
                                        <h4>Instant Pot Pad Thai</h4>
                                    </td>
                                    <td>$270</td>
                                    <td>
                                        <input type="number" class="form-control" placeholder="1" min="0" max="5">
                                    </td>
                                    <td align="center" class="fw-900">$270</td>
                                    <td align="center"><a href="javascript:void(0)" class="btn btn-circle btn-primary btn-xs" title="" data-toggle="tooltip" data-original-title="Delete"><i class="ti-trash"></i></a></td>
                                </tr>
                                <tr>
                                    <td><img src="../images/product/product-4.png" alt="" width="80"></td>
                                    <td>
                                        <h6>MAIN COURSE</h6>
                                        <h4>Instant Pot Pad Thai</h4>
                                    </td>
                                    <td>$270</td>
                                    <td>
                                        <input type="number" class="form-control" placeholder="1" min="0" max="5">
                                    </td>
                                    <td align="center" class="fw-900">$270</td>
                                    <td align="center"><a href="javascript:void(0)" class="btn btn-circle btn-primary btn-xs" title="" data-toggle="tooltip" data-original-title="Delete"><i class="ti-trash"></i></a></td>
                                </tr>																
                            </tbody>
                        </table>
                    </div>

                    </div>
                </div>					
                <div class="box">
                    <div class="box-body">
                            <div id="chartdiv2" class="h-300"></div> 
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection