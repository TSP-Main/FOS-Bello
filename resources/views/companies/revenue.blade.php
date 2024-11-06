@extends('layout.app')
@section('title', 'Revenue | FO - Food Ordering System')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header" style="margin-right: 0;">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h4 class="page-title">Revenue</h4>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Revenue</li>
                        <li class="breadcrumb-item active" aria-current="page">Revenue List</li>
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
                        <table class="table border-no" id="revenueTable">
                            <thead>
                                <tr>
                                    <th>Restaurant Name</th>
                                    <th>Package</th>
                                    <th>Plan</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($revenue as $record)
                                    <tr>
                                        <td>{{ $record->company->name }} </td>
                                        <td>{{ config('constants')['PACKAGES'][$record->package] }}</td>
                                        <td>{{ config('constants')['PLAN'][$record->plan] }}</td>
                                        <td>{{ $record->amount}}</td>
                                        <td>{{ $record->status}}</td>
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
        $('#revenueTable').dataTable();
    </script>
    
@endsection