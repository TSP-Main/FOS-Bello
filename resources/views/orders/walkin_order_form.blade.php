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

        <!-- Modal -->
        <div class="modal fade" id="productOptionsModal" tabindex="-1" aria-labelledby="productOptionsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productOptionsModalLabel">Product title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- <div class="form-group">
                            <input type="hidden" id="productId" />
                            <input type="hidden" id="productDetail" data-product-detail="" />
                            <div class="options"></div>
                            <div class="instruction"></div>
                        </div> --}}

                        <div id="productOptionsContent">
                            <!-- Options will be dynamically loaded here -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="addToCartWithOptions">Add to cart</button>
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

                                // console.log(product.options.length)
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
                                                        <button class="btn btn-primary add-to-cart" data-product-id="${product.id}" data-product-name="${product.title}" data-product-price="${product.price}" data-has-options="${product.options.length}">Add</button>
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
                                var hasOptions = $(this).data('has-options');

                                if (hasOptions) {
                                    // $('#productOptionsModal').modal('show');
                                    loadProductOptions(productId, productName, productPrice);
                                } else {
                                    addToCart(productId, productName, productPrice, {});
                                }
                            });
                        }
                    });
                }
            });

            // Load options and show modal for products with options
            function loadProductOptions(productId, productName, productPrice) {
                $.ajax({
                    url: "{{ route('product.options') }}", // Replace with your actual route
                    type: 'GET',
                    data: { product_id: productId },
                    success: function(response) {
                        let optionsHtml = '';

                        // Loop through each option category
                        $.each(response.options, function(categoryName, category) {
                            optionsHtml += `<div class="option-category"><h5>${categoryName}</h5>`;

                            // Loop through each option value in the category
                            $.each(category.option_values, function(index, optionValue) {
                                

                                if (optionValue.is_enable) { // Only show enabled options
                                    let optionPrice = parseFloat(optionValue.price) || 0;
                                    optionsHtml += `
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input option-input" name="option_${category.id}" value="${optionValue.id}" id="option_${optionValue.id}" data-options-value-name="${optionValue.name}" data-options-price="${optionPrice}">
                                            <label class="form-check-label" for="option_${optionValue.id}">${optionValue.name} (+${optionPrice.toFixed(2)})</label>
                                        </div>`;
                                }
                            });

                            optionsHtml += '</div><hr>'; // Close category div
                        });

                        $('#productOptionsContent').html(optionsHtml);
                        $('#productOptionsModal').modal('show');

                        // Set add-to-cart button in modal to pass selected options
                        $('#addToCartWithOptions').off().on('click', function() {
                            let selectedOptions = {};
                            let totalOptionPrice = 0;
                            $('.option-input:checked').each(function() {
                                let categoryId = $(this).attr('name').split('_')[1];
                                let optionId = $(this).val();
                                let optionValueName = $(this).data('options-value-name'); // Get the option name from data attribute
                                let optionPrice = parseFloat($(this).data('options-price'));
                                selectedOptions[categoryId] = {
                                    id: optionId,
                                    name: optionValueName // Store the option name as well
                                };
                                totalOptionPrice += optionPrice;
                            });
                            // Calculate the final price considering options
                            let finalPrice = productPrice + totalOptionPrice;
                            
                            addToCart(productId, productName, finalPrice, selectedOptions);
                            $('#productOptionsModal').modal('hide');
                        });
                    }
                });
            }

            // Function to add products to the cart
            function addToCart(productId, productName, productPrice, selectedOptions) {
                // Generate a string to display selected options
                let optionsString = Object.values(selectedOptions)
                    .map(option => option.name) // Access the name property from each option
                    .join(', '); // Correctly join option names into a single string

                // Include options in the product name for display
                let fullProductName = optionsString ? `${productName} (${optionsString})` : productName;

                // Check if the product is already in the cart
                var existingProduct = cart.find(item => item.id === productId && item.options === optionsString);

                if (existingProduct) {
                    existingProduct.quantity += 1; // Increment quantity if product already exists
                } else {
                    // Push new product with correctly formatted name and options
                    cart.push({ id: productId, name: fullProductName, title: productName,  price: productPrice, quantity: 1, options: optionsString });
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
                            <td>${@json($currencySymbol)}${item.price.toFixed(2)}</td>
                            <td>
                                <button class="btn btn-sm btn-secondary update-quantity" data-product-id="${item.id}" data-options='${JSON.stringify(item.options)}' data-action="decrease">-</button>
                                ${item.quantity}
                                <button class="btn btn-sm btn-secondary update-quantity" data-product-id="${item.id}" data-options='${JSON.stringify(item.options)}' data-action="increase">+</button>
                            </td>
                            <td>${@json($currencySymbol)}${itemTotal.toFixed(2)}</td>
                            <td>
                                <button class="btn btn-sm btn-danger remove-item" data-product-id="${item.id}" data-options='${JSON.stringify(item.options)}'>Remove</button>
                            </td>
                        </tr>
                    `);
                });

                $('.total-price').text(`${@json($currencySymbol)}${totalAmount.toFixed(2)}`); // Update total amount display

                // Attach event listeners to cart item buttons
                $('.update-quantity').on('click', function() {
                    var productId = $(this).data('product-id');
                    var options = JSON.parse($(this).data('options'));
                    var action = $(this).data('action');
                    updateQuantity(productId, options, action);
                });

                $('.remove-item').on('click', function() {
                    var productId = $(this).data('product-id');
                    var options = JSON.parse($(this).data('options'));
                    removeFromCart(productId, options);
                });
            }

            // Function to update quantity of a cart item
            function updateQuantity(productId, options, action) {
                var product = cart.find(item => item.id === productId && JSON.stringify(item.options) === JSON.stringify(options));

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
            function removeFromCart(productId, options) {
                cart = cart.filter(item => !(item.id === productId && JSON.stringify(item.options) === JSON.stringify(options)));
                updateCartDisplay();
            }

            // confirm Order Button
            $('#confirmOrder').on('click', function() {
                $(this).text("Processing...").prop('disabled', true);

                const orderData = {
                    items: cart,
                    total: calculateTotal()
                };

                $.ajax({
                    url: "{{ route('orders.walkin.store') }}",
                    type: 'POST',
                    data: JSON.stringify({
                        "_token": "{{ csrf_token() }}",
                        "data": orderData
                    }),
                    contentType: 'application/json',
                    success: function(response) {
                        if (response.print_url) {
                            window.location.href = response.print_url;
                        } else {
                            console.error("Print URL not received.");
                        }
                        // Clear the cart after order is placed
                        cart = [];
                        updateCartDisplay();
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