@extends('layout.app')
@section('title', 'Company | FO - Food Ordering System')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header" style="margin-right: 0;">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h4 class="page-title">Company List</h4>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item" aria-current="page">Company</li>
                        <li class="breadcrumb-item active" aria-current="page">Company List</li>
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

    <!-- Reason Modal -->
    <div class="modal fade" id="reasonModal" tabindex="-1" role="dialog" aria-labelledby="reasonModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reasonModalLabel">Enter Reason for Token Generation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="reasonForm">
                        @csrf
                        <input type="hidden" id="companyId" name="company_id">
                        <div class="form-group">
                            <label for="reason">Reason</label>
                            <textarea id="reason" name="reason" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="button" class="btn btn-primary" id="submitReason">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive rounded card-table">
                        <table class="table border-no" id="activeRestaurantTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Expiry Date</th>
                                    <th>Subscription/Renew</th>
                                    <th>Api Token</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($companies as $company)
                                    <tr>
                                        <td>{{ $company->name }} </td>
                                        <td>{{ $company->email }}</td>
                                        <td>{{ $company->address}}</td>
                                        <td>
                                            @if ($company->status == config('constants.ACTIVE_RESTAURANT'))
                                                <span class="status-pill active">Active</span>
                                            @elseif ($company->status == config('constants.IN_ACTIVE_RESTAURANT'))
                                                <span class="status-pill inactive">In Active</span>
                                            @endif
                                        </td>
                                        <td>{{ $company->formatted_expiry_date}}</td>
                                        <td>{{ $company->formatted_accepted_date}}</td>
                                        <td>
                                            <span class="tokenDisplay">{{ $company->token }}</span>
                                            <button type="button" class="btn btn-sm btn-primary generate-token-btn" data-company-id="{{ base64_encode($company->id) }}">
                                                Generate New Token
                                            </button>
                                            <div id="message" style="display:none; color: green;">Token refreshed</div>
                                        </td>
                                        <td> 
                                            <a href="{{ route('companies.edit', base64_encode($company->id)) }}"><i class="fa fa-edit" style="font-size: x-large;"></i></a>
                                            <a target="_blank" href="{{ route('companies.view', base64_encode($company->id)) }}"><i class="fa fa-eye" style="font-size: x-large;"></i></a>
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
        $('#activeRestaurantTable').dataTable();

        $(document).ready(function() {
            $('.generate-token-btn').click(function() {
                var companyId = $(this).data('company-id');
                $('#companyId').val(companyId);
                $('#reasonModal').modal('show');
            });
            $('#submitReason').click(function() {
                var companyId = $('#companyId').val();
                var reason = $('#reason').val();

                $.ajax({
                    url: '{{ route("companies.generate.token") }}',
                    type: 'POST',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        company_id: companyId,
                        reason: reason
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Token generated successfully');
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection