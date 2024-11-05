@extends('layout.app')
@section('title', 'User Profile | FO - Food Ordering System')

@section('content')
<style>
    .content-wrapper {
           margin-right: 0;
       }
   
       .main-header {
           margin-right: 0;
       }
</style>

    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Edit Profile</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">User</li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
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
        <!-- Basic Forms -->
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col">
                        <form action="{{ route('users.profile.update') }}" method="post" id="user_form" class="form-horizontal needs-validation" role="form" novalidate enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12">						
                                    <div class="form-group">
                                        <h5>Full Name <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="name" value="{{ $user->name }}" class="form-control" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <h5>Password</h5>
                                        <div class="controls">
                                            <input type="password" name="password" class="form-control" autocomplete="off"> 
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="images" class="form-label">Profile Pic</label>
                                        <input class="form-control" name="profile_pic" type="file" id="profile_pic" accept="image/*">
                                    </div>
                                </div>
                            
                                <div class="text-xs-right">
                                    <button type="submit" class="btn btn-info">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->

@endsection