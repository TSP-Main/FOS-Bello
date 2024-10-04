@extends('layout.app')
@section('title', 'Discount Codes | FO - Food Ordering System')

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
                <h4 class="page-title">Create Discount Codes</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Restaurant</li>
                            <li class="breadcrumb-item active" aria-current="page">Discount Codes</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <!-- Email Configuration -->
        <div class="box">
            <div class="box-header">
                <h4 class="box-title">Create Discount Code</h4>  
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col">
                        <form action="{{ route('discount.store') }}" method="post" class="form-horizontal needs-validation" role="form" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-xs-12 col-md-4 col-lg-4">						
                                    <div class="form-group">
                                        <h5>Dicsount Code <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="code" value="{{ old('code')}}" class="form-control" placeholder="Enter Discount Code" required data-validation-required-message="This field is required" style="text-transform: uppercase;">
                                            @error('code')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4 col-lg-4">						
                                    <div class="form-group">
                                        <h5>Discount Type <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <select name="type" id="type" required class="select form-select">
                                                <option value="">Select</option>
                                                <option value="1">Percentage</option>
                                                <option value="2">Amount</option>
                                            </select>
                                            @error('type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4 col-lg-4">						
                                    <div class="form-group">
                                        <h5>Discount Amount/Percentage <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="rate" value="{{ old('rate') }}" class="form-control" placeholder="Enter Amount/Percentage" required data-validation-required-message="This field is required"> 
                                            @error('rate')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-4 col-lg-4">						
                                    <div class="form-group">
                                        <h5>Discount Expiry <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <div class="controls">
                                                <input type="date" name="expiry" class="form-control" required data-validation-required-message="This field is required"> 
                                                @error('expiry')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4 col-lg-4">						
                                    <div class="form-group">
                                        <h5>Order Minimum Amount</h5>
                                        <div class="controls">
                                            <input type="text" name="minimum_amount" value="{{ old('minimum_amount') }}" class="form-control" placeholder="Enter minimum order amount">
                                            @error('minimum_amount')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-xs-right">
                                <button type="submit" class="btn btn-info">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="box-body">
                <div class="table-responsive rounded card-table">
                    <table class="table border-no" id="discount_table">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Amount/Percentage</th>
                                <th>Order Minimum Amount</th>
                                <th>Expiry</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($discounts as $discount)
                                <tr>
                                    <td>{{ $discount->code }} </td>
                                    <td>{{ $discount->type == 1 ? 'Percentage' : 'Amount' }}</td>
                                    <td>{{ $discount->type == 1 ? $discount->rate . '%' : $discount->rate}}</td>
                                    <td>{{ $discount->minimum_amount}}</td>
                                    <td>{{ $discount->formatted_expiry_date}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script>
        $('#discount_table').dataTable();
        $(document).ready(function() {
            $('input[name="code"]').on('input', function () {
                this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
            });
        });
    </script>
@endsection