@extends('layout.app')
@section('title', 'View Company | FO - Food Ordering System')

@section('content')

    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">View Company</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Company</li>
                            <li class="breadcrumb-item active" aria-current="page">View Company</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h1 class="mb-0">{{ $company->name }}</h1>
                    </div>
                </div>
            </div>
            <div class="box-body border-bottom">
                <div class="d-flex align-items-center">
                    <i class="fa fa-phone me-10 fs-24"></i>
                    <h4 class="mb-0">{{ $company->phone }}</h4>
                </div>
            </div>
            <div class="box-body border-bottom">
                <div class="d-flex align-items-center">
                    <i class="fa fa-map-marker me-10 fs-24"></i>
                    <h4 class="mb-0 text-black">{{ $company->address }}</h4>
                </div>
            </div>
            <div class="box-body">
                <h4 class="mb-10">Joining Date</h4>
                <p>{{ $company->formatted_accepted_date }}</p>
            </div>
            <div class="box-body">
                <h4 class="mb-10">Subscription Expiry Date</h4>
                <p>{{ $company->formatted_expiry_date }}</p>
            </div>
        </div>

        <div class="box">
            <div class="box-body">
                <h2>Subscription Logs</h2>
                <div class="table-responsive rounded card-table">
                    <table class="table border-no" id="activeRestaurantTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Package</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($company->transactions as $transaction)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ config('constants')['PACKAGES'][$transaction->package] }}
                                    </td>
                                    <td>
                                        {{ config('constants')['PLAN'][$transaction->plan] }}
                                    </td>
                                    <td>{{ $transaction->amount }}</td>
                                    <td>{{ $transaction->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="box-body">
                <h2>Api Token Logs</h2>
                <div class="table-responsive rounded card-table">
                    <table class="table border-no" id="activeRestaurantTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Reason</th>
                                <th>New Token</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($company->apiTokenLogs as $apiLog)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $apiLog->reason }}</td>
                                    <td>{{ $apiLog->new_token }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

@endsection