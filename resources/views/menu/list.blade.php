@extends('layout.app')
@section('title', 'Menu | FO - Food Ordering System')

@section('content')
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Menu</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Menu</li>
                            <li class="breadcrumb-item active" aria-current="page">Menu Item List</li>
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
                            <table class="table border-no" id="example1">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Required</th>
                                        <th>Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($options as $option)
                                        <tr class="hover-primary">
                                            <td>{{ $option->name }}</td>
                                            <td>{{ config('constants.YES_NO')[$option->is_required] }}</td>
                                            <td>{{ config('constants.PRODUCT_OPTIONS_TYPE')[$option->option_type] }}</td>
                                            <td>
                                                <a class="btn btn-primary" href="{{ route('options.edit', base64_encode($option->id)) }}">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach --}}
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