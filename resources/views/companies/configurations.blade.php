@extends('layout.app')
@section('title', 'Timezone | FO - Food Ordering System')

@section('content')
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Set Restaurant Configurations</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Restaurant</li>
                            <li class="breadcrumb-item active" aria-current="page">Configurations</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <!-- Basic Forms -->
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col">
                        <form action="{{ route('timezone.store') }}" method="post" id="timezone_form" class="form-horizontal needs-validation" role="form" novalidate>
                            @csrf
                            <div class="row">
                                {{-- <div class="col-12">						
                                    <div class="form-group">
                                        <h5>Select Timezone <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <select name="timezone" id="timezone" required class="select2 form-select">
                                                <option value="">Select</option>
                                                @foreach ($timezonesList as $val)
                                                    <option value="{{ $val }}" {{ $timezone == $val ? 'selected' : ''}}>{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                <div class="text-xs-right">
                                    <button type="submit" class="btn btn-info">Save</button>
                                </div> --}}
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

@section('script')
    <script>
        $(document).ready(function() {
            $('#timezone').select2({
                placeholder: 'Select a Restaurant',
                allowClear: true
            });
        });
    </script>
@endsection