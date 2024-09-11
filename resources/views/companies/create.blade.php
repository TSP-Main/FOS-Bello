@extends('layout.app')
@section('title', 'Companies | FO - Food Ordering System')

@section('content')
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Create Company</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Company</li>
                            <li class="breadcrumb-item active" aria-current="page">Create Company</li>
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
            {{-- <div class="box-header with-border">
                <h4 class="box-title">Form Validation</h4>
                <h6 class="box-subtitle">Bootstrap Form Validation check the <a class="text-warning" href="http://reactiveraven.github.io/jqBootstrapValidation/">official website </a></h6>
            </div> --}}
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col">
                        <form action="{{ route('companies.store') }}" method="post" id="company_form" class="form-horizontal needs-validation" role="form" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-12">						
                                    <div class="form-group">
                                        <h5>Company Name <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="name" class="form-control" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <h5>Email <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="email" name="email" class="form-control" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <h5>Address <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="address" class="form-control" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                    <div class="row">
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            <h5>Expiry Date <span class="text-danger">*</span></h5>
                                            <div class="controls">
                                                <input type="date" name="subscription_date" class="form-control" required data-validation-required-message="This field is required"> 
                                            </div>
                                        </div>
                                     </div>
                                     <div class="col-md-6">
                                        <div class="form-group" >
                                            <label class="fw-700 fs-16 form-label">Status</label>
                                            <div class="radio-list">
                                                <label class="radio-inline p-0 me-10">
                                                    <div class="radio radio-info">
                                                        <input type="radio" name="status" id="radio1" value="1">
                                                        <label for="radio1">Active</label>
                                                    </div>
                                                </label>
                                                <label class="radio-inline">
                                                    <div class="radio radio-info">
                                                        <input type="radio" name="status" id="radio2" value="2">
                                                        <label for="radio2">Unactive</label>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <div class="text-xs-right">
                                    <button type="submit" class="btn btn-info">Save</button>
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