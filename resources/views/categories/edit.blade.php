@extends('layout.app')
@section('title', 'Categories | FO - Food Ordering System')
{{-- @section('pageTitle', 'Dashboard')
@section('breadcrumTitle', 'User Dashboard') --}}

@section('content')
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Edit Category</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Categories</li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Category</li>
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
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col">
                        <form method="POST" action="{{ route('category.update', ['id' => $category->id]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="fw-700 fs-16 form-label">Category Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name', $category->name) }}" placeholder="Category Name">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="fw-700 fs-16 form-label">Slug</label>
                                        <input type="text" class="form-control" name="slug" value="{{ old('slug', $category->slug) }}" placeholder="Slug">
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="fw-700 fs-16 form-label">Status</label>
                                        <div class="radio-list">
                                            <label class="radio-inline p-0 me-10">
                                                <div class="radio radio-info">
                                                    <input type="radio" name="status" id="radio1" value="1" {{ old('status', $category->status) == 1 ? 'checked' : '' }}>
                                                    <label for="radio1">Active</label>
                                                </div>
                                            </label>
                                            <label class="radio-inline">
                                                <div class="radio radio-info">
                                                    <input type="radio" name="status" id="radio3" value="3" {{ old('status', $category->status) == 3 ? 'checked' : '' }}>
                                                    <label for="radio3">Draft</label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="fw-700 fs-16 form-label">Description</label>
                                        <textarea class="form-control p-20" name="desc" rows="2" placeholder="Description">{{ old('desc', $category->desc) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="box-title mt-20">Uploaded Icon</h4>
                                    <div class="product-img text-start">
                                        @if ($category->icon_file)
                                            <img id="preview-icon" src="{{ asset('storage/' . $category->icon_file) }}" alt="" class="mb-15 preview-image">
                                        @else
                                            <img id="preview-icon" src="{{ asset('images/product-placeholder.png') }}" alt="" class="mb-15 preview-image">
                                        @endif
                                        <p>Upload Icon</p>
                                        <div class="btn btn-info mb-20">
                                            <input type="file" class="upload" id="icon_file" name="icon_file">
                                        </div>
                                        <button class="btn btn-danger delete-file mb-20">Delete</button>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <h4 class="box-title mt-20">Uploaded Image</h4>
                                    <div class="product-img text-start">
                                        @if ($category->background_image)
                                            <img id="preview-image" src="{{ asset('storage/' . $category->background_image) }}" alt="" class="mb-15 preview-image">
                                        @else
                                            <img id="preview-image" src="{{ asset('images/product-placeholder.png') }}" alt="" class="mb-15 preview-image">
                                        @endif
                                        <p>Upload Image</p>
                                        <div class="btn btn-info mb-20">
                                            <input type="file" class="upload" id="background_image" name="background_image">
                                        </div>
                                        <button class="btn btn-danger delete-file mb-20">Delete</button>
                                    </div>
                                </div>
                                <div class="form-actions mt-10">
                                    <button type="submit" class="btn btn-primary"> <i class="fa fa-check"></i> Update Category</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                    <script>
                        $(document).ready(function() {
                            // Handle icon_file input change
                            $('#icon_file').on('change', function() {
                                previewImage(this, '#preview-icon');
                            });
                    
                            // Handle background_image input change
                            $('#background_image').on('change', function() {
                                previewImage(this, '#preview-image');
                            });
    
                            // Function to preview selected image
                            function previewImage(input, previewId) {
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    $(previewId).attr('src', e.target.result);
                                }
                                reader.readAsDataURL(input.files[0]);
                            }
                            
                            // Handle delete button click
                            $('.delete-file').on('click', function() {
                                event.preventDefault();
                                
                                var inputId = $(this).siblings('.btn-info').children('input').attr('id');
                                $('#' + inputId).val(''); 
                                $(this).siblings('.preview-image').attr('src', '{{ asset('images/product-placeholder.png') }}'); // Reset preview image
                            });
               
						});
					</script>
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
