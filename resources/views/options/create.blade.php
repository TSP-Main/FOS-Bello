@extends('layout.app')
@section('title', 'Options/Sides | FO - Food Ordering System')

@section('content')

    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Add Options/Sides</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Options/Sides</li>
                            <li class="breadcrumb-item active" aria-current="page">Add Options/Sides</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <!-- Basic Forms -->
        <form action="{{ route('options.store') }}" method="post" id="options_form" class="form-horizontal needs-validation" role="form" novalidate>
            @csrf
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Option/Sides</h4>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h5>Name <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="name" class="form-control" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h5>Required <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <select name="is_required" id="is_required" class="form-select">
                                                <option value="">Select</option>
                                                @foreach ($yesNo as $key => $val)
                                                    <option value="{{ $key }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h5>Options Type <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <select name="option_type" id="option_type" class="form-select">
                                                <option value="">Select</option>
                                                @foreach ($optionsType as $key => $val)
                                                    <option value="{{ $key }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Option Values</h4>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col">
                            <div class="row option_value_div template" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h5>Name <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="value_name[]" class="form-control"> 
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h5>Price <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="value_price[]" class="form-control"> 
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-option-value" style="margin-top: 28px;">Remove</button>
                                </div>
                            </div>

                            <div class="option_value_container">
                                <div class="row option_value_div" >
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h5>Name <span class="text-danger">*</span></h5>
                                            <div class="controls">
                                                <input type="text" name="value_name[]" class="form-control" required data-validation-required-message="This field is required"> 
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>Price <span class="text-danger">*</span></h5>
                                            <div class="controls">
                                                <input type="text" name="value_price[]" class="form-control"> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger remove-option-value" style="margin-top: 28px;">Remove</button>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <a href="#" class="add-value waves-effect waves-circle btn btn-social-icon btn-circle btn-primary">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                            
                            <div class="text-xs-right">
                                <button type="submit" class="btn btn-info">Save</button>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-body -->
            </div>
        </form>
        <!-- /.box -->
    </section>
    <!-- /.content -->

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.add-value').click(function(){
                // var $container = $('.option_value_container');
                // var $originalDiv = $container.find('.option_value_div').first();
                // var $clone = $originalDiv.clone();

                // // Clear input values in the cloned div
                // $clone.find('input').val('');

                // $container.append($clone);

                var $container = $('.option_value_container');
                var $clone = $('.template').clone().removeClass('template').show();

                // Clear input values in the cloned div (already empty in the template)
                $container.append($clone);
            });

            $(document).on('click', '.remove-option-value', function() {
                $(this).closest('.option_value_div').remove();
            });
        });
    </script>
@endsection