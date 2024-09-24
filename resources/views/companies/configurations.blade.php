@extends('layout.app')
@section('title', 'Configurations | FO - Food Ordering System')

@section('content')
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Set Restaurant Configurations</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Restaurant</li>
                            <li class="breadcrumb-item active" aria-current="page">Configurations</li>
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
                <h4 class="box-title">Email Configuration</h4>  
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col">
                        <form action="{{ route('email.store') }}" method="post" class="form-horizontal needs-validation" role="form" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-lg-6">						
                                    <div class="form-group">
                                        <h5>Mail Mailer <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="mailer" value="{{ $email->mailer ?? NULL }}" class="form-control" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-lg-6">						
                                    <div class="form-group">
                                        <h5>Mail Host <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="host" value="{{ $email->host ?? NULL }}" class="form-control" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-lg-6">						
                                    <div class="form-group">
                                        <h5>Mail Port <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="port" value="{{ $email->port ?? NULL }}" class="form-control" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-lg-6">						
                                    <div class="form-group">
                                        <h5>Mail From Name <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="name" value="{{ $email->name ?? NULL }}" class="form-control" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-lg-6">						
                                    <div class="form-group">
                                        <h5>Mail Username <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="username" value="{{ $email->username ?? NULL }}" class="form-control" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-lg-6">						
                                    <div class="form-group">
                                        <h5>Mail Password <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="password" value="{{ $email->password ?? NULL }}" class="form-control" required data-validation-required-message="This field is required"> 
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

        <!-- Stripe Configuration -->
        <div class="box">
            <div class="box-header">
                <h4 class="box-title">Stripe Configuration</h4>  
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col">
                        <form action="{{ route('stripe.store') }}" method="post" class="form-horizontal needs-validation" role="form" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-lg-6">						
                                    <div class="form-group">
                                        <h5>Stripe Key <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="stripe_key" value="{{ $stripe->stripe_key ?? NULL }}" class="form-control" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-lg-6">						
                                    <div class="form-group">
                                        <h5>Stripe Secret <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="stripe_secret" value="{{ $stripe->stripe_secret ?? NULL }}" class="form-control" required data-validation-required-message="This field is required"> 
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
    </section>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#timezone').select2({
                placeholder: 'Select a Restaurant',
                allowClear: true
            });
        });
    </script>
@endsection