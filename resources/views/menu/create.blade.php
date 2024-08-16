@extends('layout.app')
@section('title', 'Menu | FO - Food Ordering System')

@section('content')

    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Create Menu</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Menu</li>
                            <li class="breadcrumb-item active" aria-current="page">Create Menu</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <!-- Basic Forms -->
        <form action="{{ route('menu.store') }}" method="post" id="menu_form" class="form-horizontal needs-validation" role="form" novalidate>
            @csrf
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Menu Items</h4>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col">
                            <div class="option_value_container">
                                <div class="row option_value_div" >
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h5>Categories <span class="text-danger">*</span></h5>
                                            <div class="controls">
                                                <select name="category_id[]" id="category_id" class="form-select category-select" required>
                                                    <option value="">Select Category</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>

                                                
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h5>Products <span class="text-danger">*</span></h5>
                                            <div class="controls product-select" >
                                                <select class="form-select"></select>
                                            </div>
                                        </div>
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
            function fetchProducts($categorySelect) {
                var categoryId = $categorySelect.val();
                var $productSelectDiv = $categorySelect.closest('.option_value_div').find('.product-select');

                if (categoryId) {
                    $.ajax({
                        url: '{{ route("products.by.category") }}',
                        type: 'GET',
                        data: { category_id: categoryId },
                        success: function(data) {
                            $productSelectDiv.empty();
                            var $select = $('<select name="product_id[]" class="selectpicker" multiple></select>');
                            $select.append('<option value="all">Select All</option>');
                            $.each(data.products, function(index, product) {
                                $select.append('<option value="'+ product.id +'">'+ product.title +'</option>');
                                $productSelectDiv.append($select);
                            });
                            $select.selectpicker('refresh');

                            // Add change event listener for the new select element
                            $select.change(function() {
                                var selectedOptions = $(this).val();
                                var allOption = $(this).find('option[value="all"]');

                                if (selectedOptions.includes('all')) {
                                    // Select all options
                                    $(this).find('option').prop('selected', true);
                                } else if (!selectedOptions.length) {
                                    // If nothing is selected, unselect all
                                    $(this).find('option').prop('selected', false);
                                } else {
                                    // If "Select All" was previously selected and now is not, deselect "Select All"
                                    if (allOption.is(':selected')) {
                                        allOption.prop('selected', false);
                                    }
                                }

                                $(this).selectpicker('refresh');
                            });
                        }
                    });
                } else {
                    $productSelectDiv.empty();
                }
            }

            // category change event
            $(document).on('change', '.category-select', function() {
                fetchProducts($(this));
            });

            $('.add-value').click(function(e){
                e.preventDefault();
                var $container = $('.option_value_container');
                var $originalDiv = $container.find('.option_value_div').first();
                var $clone = $originalDiv.clone();

                // Clear input values in the cloned div
                $clone.find('input').val('');
                $clone.find('.product-select').empty();

                $container.append($clone);
            });
        });
    </script>
@endsection