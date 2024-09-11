@extends('layout.app')
@section('title', 'Edit Companies | FO - Food Ordering System')

@section('content')

    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Edit Company</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Company</li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Company</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <!-- Basic Forms -->
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col">
                        <form action="{{ route('companies.update') }}" method="post" id="company_form" class="form-horizontal needs-validation" role="form" novalidate>
                            @csrf
                            <input type="hidden" name="id" value="{{ base64_encode($company->id) }}">
                            <div class="row">
                                <div class="col-12">						
                                    <div class="form-group">
                                        <h5>Company Name <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="name" value="{{ $company->name }}" class="form-control" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <h5>Email <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="email" name="email" value="{{ $company->email }}" class="form-control" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <h5>Address <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="address" value="{{ $company->address }}" class="form-control" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <h5>Expiry Date <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="date" name="subscription_date" value="{{ $company->subscription_date }}" class="form-control" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="fw-700 fs-16 form-label">Status</label>
                                            <div class="radio-list">
                                                <label class="radio-inline p-0 me-10">
                                                    <div class="radio radio-info">
                                                        <input type="radio" name="status" id="radio1" value="1" {{ old('status', $company->status) == 1 ? 'checked' : '' }}>
                                                        <label for="radio1">Active</label>
                                                    </div>
                                                </label>
                                                <label class="radio-inline">
                                                    <div class="radio radio-info">
                                                        <input type="radio" name="status" id="radio2" value="2" {{ old('status', $company->status) == 2 ? 'checked' : '' }}>
                                                        <label for="radio2">Inactive</label>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="text-xs-right">
                                    <button type="submit" class="btn btn-info">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->

@endsection