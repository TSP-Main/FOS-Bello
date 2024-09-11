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
    @elseif (session()->has('warning'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Note!</strong> {{ session()->get('warning')}}
        </div>
    @endif

    <div class="col-12">
        <div class="box">
          <div class="box-header with-border">
            <h4 class="box-title">Requests Tab</h4>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs customtab2" role="tablist">
                <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#incomingRequestTab" role="tab"><span class="hidden-sm-up"><i class="ion-home"></i></span> <span class="hidden-xs-down">Incoming Request</span></a> </li>
                <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#rejectedRequestTab" role="tab"><span class="hidden-sm-up"><i class="ion-person"></i></span> <span class="hidden-xs-down">Rejected Request</span></a> </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- incoming orders tab -->
                <div class="tab-pane active" id="incomingRequestTab" role="tabpanel">
                    <div class="p-15">
                        <div class="table-responsive rounded card-table">
                            <table class="table border-no" id="incomingRequestTable">
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
                                    @foreach ($incomingRequests as $incomingRequest)
                                        <tr>
                                            <td>{{ $incomingRequest->name}}</td>
                                            <td>{{ $incomingRequest->email }}</td>
                                            <td>{{ $incomingRequest->phone}}</td>
                                            <td>{{ $incomingRequest->owner_name}}</td>
                                            <td> 
                                                <form action="{{ route('companies.incoming.action', base64_encode($incomingRequest->id)) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="action" value="accept">
                                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to accept this request?');">Accept</button>
                                                </form>
                                                
                                                <form action="{{ route('companies.incoming.action', base64_encode($incomingRequest->id)) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to reject this request?');">Reject</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Accepted Orders Tab -->
                <div class="tab-pane" id="rejectedRequestTab" role="tabpanel">
                    <div class="p-15">
                        <div class="table-responsive rounded card-table">
                            <table class="table border-no" id="rejectedRequestTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Owner Name</th>
                                        {{-- <th>Actions</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rejectedRequests as $rejectedRequest)
                                        <tr>
                                            <td>{{ $rejectedRequest->name}}</td>
                                            <td>{{ $rejectedRequest->email }}</td>
                                            <td>{{ $rejectedRequest->phone}}</td>
                                            <td>{{ $rejectedRequest->owner_name}}</td>
                                            {{-- <td> 
                                                <form action="{{ route('companies.incoming.action', base64_encode($incomingRequest->id)) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="action" value="accept">
                                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to accept this request?');">Accept</button>
                                                </form>
                                                
                                                <form action="{{ route('companies.incoming.action', base64_encode($incomingRequest->id)) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to reject this request?');">Reject</button>
                                                </form>
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
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
        $('#incomingRequestTable').dataTable();
        $('#rejectedRequestTable').dataTable();
    </script>
@endsection