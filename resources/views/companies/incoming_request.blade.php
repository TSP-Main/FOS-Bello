@extends('layout.app')
@section('title', 'Incoming Request | FO - Food Ordering System')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header" style="margin-right: 0;">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h4 class="page-title">Incoming Request List</h4>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Companies</li>
                        <li class="breadcrumb-item active" aria-current="page">Incoming Request List</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>

<!-- Main content -->
<section class="content">
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
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive rounded card-table">
                        <table class="table border-no" id="example1">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Owner Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $request)
                                    <tr>
                                        <td>{{ $request->name}}</td>
                                        <td>{{ $request->email }}</td>
                                        <td>{{ $request->phone}}</td>
                                        <td>{{ $request->owner_name}}</td>
                                        <td> 
                                            <a class="btn btn-primary" href="{{ route('companies.incoming.action', base64_encode($request->id)) }}" onclick="return confirmAction()">Accept</a>
                                        </td>
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
        function confirmAction() {
            return confirm("Are you sure you want to accept this request?");
        }
    </script>
@endsection