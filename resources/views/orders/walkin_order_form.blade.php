@extends('layout.app')
@section('title', 'WalkIn Order | FO - Food Ordering System')
<style>
    .table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.total-amount {
    margin-top: 15px;
    font-weight: bold;
}
</style>
@section('content')

    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Walk In Order</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Order</li>
                            <li class="breadcrumb-item active" aria-current="page">Walk In Order</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col-xs-12 col-md-6 col-lg-6">						
                                <div class="form-group">
                                    <h5>Category <span class="text-danger">*</span></h5>
                                    <div class="controls position-relative">
                                        <select class="form-control select2 category" name="category" id="category" style="width: 100%;">
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row product-container"></div>

                        <div class="cart-summary mt-4">
                            <h4>Cart Summary</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="cart-items">
                                    <!-- Cart items -->
                                </tbody>
                            </table>
                            <div class="total-amount">
                                <strong>Total: </strong><span class="total-price">{{ $currencySymbol }}0.00</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button id="confirmOrder" class="btn btn-success">Confirm Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            let cart = [];
            $('.category').select2({
                placeholder: "Select Category",
                allowClear: true
            });

            $('#category').change(function() {
                var categoryId = $(this).val();
                
                $('.product-container').empty();

                if (categoryId) {
                    $.ajax({
                        url: "{{ route('products.by.category') }}",
                        type: 'GET',
                        data: { category_id: categoryId },
                        success: function(response) {
                            $.each(response.products, function(index, product) {
                                var imageUrl = product.images.length > 0 ? '{{ asset('storage/product_images') }}/' + product.images[0].path : '{{ asset('assets/theme/images/default_product_image.jpg') }}';

                                $('.product-container').append(`
                                    <div class="col-xxxl-3 col-xl-4 col-lg-4 col-12">
                                        <div class="box overflow-h">
                                            <div class="box-body p-0">
                                                <div>
                                                    <img class="rounded img-fluid" src="${imageUrl}" alt="${product.title}">
                                                </div>
                                            </div>
                                            <div class="box-body">
                                                <div class="info-content">
                                                    <h4 class="mb-10 mt-0">${product.title}</h4>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h4 class="mb-0 text-primary">${@json($currencySymbol)}${product.price}</h4>
                                                        <button class="btn btn-primary add-to-cart" data-product-id="${product.id}" data-product-name="${product.title}" data-product-price="${product.price}">Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `);
                            });

                            // Add button with each product
                            $('.add-to-cart').on('click', function() {
                                var productId = $(this).data('product-id');
                                var productName = $(this).data('product-name');
                                var productPrice = parseFloat($(this).data('product-price'));

                                addToCart(productId, productName, productPrice);
                            });
                        }
                    });
                }
            });

            // Function to add products to the cart
            function addToCart(productId, productName, productPrice) {
                // Check if the product is already in the cart
                var existingProduct = cart.find(item => item.id === productId);

                if (existingProduct) {
                    existingProduct.quantity += 1;
                } else {
                    cart.push({ id: productId, name: productName, price: productPrice, quantity: 1 });
                }

                updateCartDisplay();
            }

            function updateCartDisplay() {
                $('.cart-items').empty();
                let totalAmount = 0;

                cart.forEach(item => {
                    const itemTotal = item.price * item.quantity;
                    totalAmount += itemTotal;

                    $('.cart-items').append(`
                        <tr class="cart-item">
                            <td>${item.name}</td>
                            <td>$${item.price.toFixed(2)}</td>
                            <td>
                                <button class="btn btn-sm btn-secondary update-quantity" data-product-id="${item.id}" data-action="decrease">-</button>
                                ${item.quantity}
                                <button class="btn btn-sm btn-secondary update-quantity" data-product-id="${item.id}" data-action="increase">+</button>
                            </td>
                            <td>$${itemTotal.toFixed(2)}</td>
                            <td>
                                <button class="btn btn-sm btn-danger remove-item" data-product-id="${item.id}">Remove</button>
                            </td>
                        </tr>
                    `);
                });

                $('.total-price').text(`$${totalAmount.toFixed(2)}`); // Update total amount display

                // Attach event listeners to cart item buttons
                $('.update-quantity').on('click', function() {
                    var productId = $(this).data('product-id');
                    var action = $(this).data('action');
                    updateQuantity(productId, action);
                });

                $('.remove-item').on('click', function() {
                    var productId = $(this).data('product-id');
                    removeFromCart(productId);
                });
            }

            // Function to update quantity of a cart item
            function updateQuantity(productId, action) {
                var product = cart.find(item => item.id === productId);

                if (product) {
                    if (action === 'increase') {
                        product.quantity += 1;
                    } else if (action === 'decrease' && product.quantity > 1) {
                        product.quantity -= 1;
                    }
                }
                updateCartDisplay();
            }

            // Function to remove a product from the cart
            function removeFromCart(productId) {
                cart = cart.filter(item => item.id !== productId);
                updateCartDisplay();
            }

            // confirm Order Button
            $('#confirmOrder').on('click', function() {
                const orderData = {
                    items: cart,
                    total: calculateTotal()
                };

                $.ajax({
                    url: "{{ route('orders.walkin.store') }}",
                    type: 'POST',
                    data: JSON.stringify({
                        "_token": "{{ csrf_token() }}",
                        "data": orderData // Assuming orderData is already an object
                    }),
                    contentType: 'application/json',
                    success: function(response) {
                        console.log(response);
                        // alert("Order placed successfully!");
                        // cart = []; // Clear the cart after order is placed
                        // updateCartDisplay(); // Update cart display
                    },
                    error: function(xhr, status, error) {
                        console.error("Error placing order:", error);
                        alert("There was an error placing your order. Please try again.");
                    }
                });
            });

            // Function to calculate total amount
            function calculateTotal() {
                return cart.reduce((total, item) => total + (item.price * item.quantity), 0).toFixed(2);
            }
        });
    </script>
@endsection