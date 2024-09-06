@extends('layout.app')
@section('title', 'Products | FO - Food Ordering System')

@section('content')

    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Edit Product</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Products</li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
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
                        <form action="{{ route('products.update') }}" method="post" id="product_form" class="form-horizontal needs-validation" role="form" novalidate enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h5>Title <span class="text-danger">*</span></h5>
                                            <div class="controls">
                                                <input type="text" name="title" value="{{ $product->title }}" class="form-control" required data-validation-required-message="This field is required"> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h5>Price <span class="text-danger">*</span></h5>
                                            <div class="controls">
                                                <input type="text" name="price" value="{{ $product->price }}" class="form-control" required data-validation-required-message="This field is required"> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>Category <span class="text-danger">*</span></h5>
                                            <div class="controls">
                                                <select name="category_id" id="category_id" class="form-select">
                                                    <option value="">Select Category</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>Select Sides</h5>
                                            <div class="controls">
                                                <select name="options[]" id="options" class="selectpicker" multiple>
                                                    @foreach ($options as $option)
                                                        <option value="{{ $option->id }}" {{ in_array($option->id, $product_options) ? 'selected' : ''}}>{{ $option->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>Ask for Special Instruction <span class="text-danger">*</span></h5>
                                            <div class="controls">
                                                <select name="ask_instruction" id="ask_instruction" class="form-select" required>
                                                    @foreach ($yesNo as $key => $val)
                                                        <option value="{{ $key }}" {{ $product->ask_instruction == $key ? 'selected' : '' }} >{{ $val }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <h5>Description <span class="text-danger">*</span></h5>
                                            <div class="controls">
                                                <textarea rows="5" name="description" class="form-control" placeholder="Product Description">{{$product->description}}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h5>Select Product Images</h5>
                                            <div class="controls">
                                                <input class="form-control" name="images" type="file" id="images" multiple accept="image/*">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{-- <h5>Select Product Images</h5> --}}
                                            <div class="controls">
                                                <div class="mt-5" style="border-radius: 50%">
                                                    @if ($productImage)
                                                        <img style="border-radius: 50%; width:100px; height:100px"  src="{{ asset('storage/product_images/'. $productImage->path)}}" alt="">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            
                                <div class="text-xs-right">
                                    <button type="submit" class="btn btn-info">Save</button>
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