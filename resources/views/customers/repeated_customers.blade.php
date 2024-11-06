@extends('layout.app')
@section('title', 'Repeated Customers | FO - Food Ordering System')

@section('content')
    {{-- <style>
        .badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 12px;
        color: #fff;
        text-align: center;
       }
        .category-status {
            flex: 0 0 auto;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            margin-right:15px;
        }
        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .status-active {
            background-color: #28a745;
        }
        .status-inactive {
            background-color: #dc3545;
        }
        .status-draft {
            background-color: #fd7e14;
        }
    </style> --}}

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Customers</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Customers</li>
                            <li class="breadcrumb-item active" aria-current="page">Repeated Customers</li>
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
                            <table class="table border-no" id="repeated_customers_table">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Order Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $customer)
                                        <tr class="hover-primary">
                                            <td> {{ $customer->email }} </td>
                                            <td> {{ $customer->order_count }} </td>
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
        $('#repeated_customers_table').dataTable();
    </script>
@endsection
