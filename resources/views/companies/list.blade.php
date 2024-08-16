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
                        <table class="table border-no" id="example1">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Api Token</th>
                                    <th>Subscription Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($companies as $company)
                                <td>
                                    <div class="company-status">
                                        @if ($company->status == 1)
                                        <span class="status-dot status-active" title="Status/Active" style="margin-right: 10px;"></span>
                                        @elseif ($company->status == 2)
                                        <span class="status-dot status-inactive" title="Status/Inactive"></span>
                                        @endif
                                        <span class="company-name">{{ $company->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $company->email }}</td>
                                <td>{{ $company->address}}</td>
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
                                <td>{{ $company->subscription_date}}</td>
                                <td> <a class="btn btn-primary" href="{{ route('companies.edit', base64_encode($company->id)) }}">Edit</a>
                                </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script>
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
                        });
                    </script>

                    <script>
                        $(document).ready(function() {
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

                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

@endsection