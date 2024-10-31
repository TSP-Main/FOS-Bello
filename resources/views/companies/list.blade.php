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
                                            <form class="refreshTokenForm" action="{{ route('companies.refreshToken', ['id' => base64_encode($company->id)]) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary" id="refreshTokenButton">
                                                    <span class="glyphicon glyphicon-refresh"></span>
                                                </button>
                                            </form>
                                            <div id="message" style="display:none; color: green;">Token refreshed</div>
                                        </td>
                                        <td> <a class="btn btn-primary" href="{{ route('companies.edit', base64_encode($company->id)) }}">Edit</a>
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
            $('form.refreshTokenForm').on('submit', function(event) {
                event.preventDefault();

                var $form = $(this);
                var $tokenDisplay = $form.siblings('.tokenDisplay');
                var $message = $form.siblings('.message');

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: $form.serialize(),
                    success: function(response) {

                        $tokenDisplay.text(response.newToken);
                        $message.fadeIn().delay(2000).fadeOut();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error refreshing token:', error);

                    }
                });
            });

            $('#refreshTokenForm').on('submit', function(event) {
                event.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#tokenDisplay').text(response.newToken);
                        $('#message').fadeIn().delay(2000).fadeOut();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error refreshing token:', error);

                    }
                });
            });
        });
    </script>
@endsection