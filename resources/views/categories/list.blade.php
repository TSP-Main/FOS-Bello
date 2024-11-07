@extends('layout.app')
@section('title', 'Categories | FO - Food Ordering System')

@section('content')
    <style>
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
    </style>

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">All Categories</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Categories</li>
                            <li class="breadcrumb-item active" aria-current="page">Categories</li>
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
                            <table class="table border-no" id="categories_table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Products Count</th>
                                        <th>Sort Order</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr class="hover-primary">
                                            <td>
                                                <div class="row">
                                                    <h4> {{ $category->name }} </h4>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($category->status == 1)
                                                    <span class="badge status-active" title="Status/Active">Active</span>
                                                @elseif ($category->status == 2)
                                                    <span class="badge status-inactive" title="Status/Unactive">Inactive</span>
                                                @elseif ($category->status == 3)
                                                    <span class="badge status-draft" title="Status/Draft">Draft</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ count($category->products) }}
                                            </td>
                                            <td>{{ $category->sort_order }}</td>
                                            <td>
                                                <a class="btn btn-primary" href="{{ route('category.edit', base64_encode($category->id)) }}">Edit</a>
                                                <form action="{{ route('category.destroy', $category->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger " onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                                                </form>
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
        $('#categories_table').dataTable();
    </script>
@endsection
