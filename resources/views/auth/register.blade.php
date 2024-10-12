<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('assets/theme/images/bello_logo.png') }}">

    <title>Food Ordering System | TSP</title>
  
	<!-- Vendors Style-->
	<link rel="stylesheet" href="{{ asset('assets/theme/css/vendors_css.css') }}">
	  
	<!-- Style-->  
	<link rel="stylesheet" href="{{ asset('assets/theme/css/style.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/theme/css/skin_color.css')}}">	

</head>
	
<body class="hold-transition theme-primary bg-img" style="background-image: url(/assets/theme/images/auth-bg/bg-1.jpg)">
	
	<div class="container h-p100">
		<div class="row align-items-center justify-content-md-center h-p100">	
			
			<div class="col-12">
                
				<div class="row justify-content-center g-0">
					<div class="col-lg-6 col-md-6 col-12">
						<div class="bg-white rounded10 shadow-lg">
							<div class="content-top-agile p-20 pb-0">
								<a href="/"><img src="{{ asset('assets/theme/images/bello_logo.png') }}" alt="" width="100px" height="100px"></a>
								<h2 style="color: #F8A61B">Let's Get Started</h2>
								<p class="mb-0">Sign in to continue to Food Ordering System.</p>
								@if (session('success'))
									<div class="alert alert-success alert-dismissible fade show" role="alert">
										{{ session('success') }}
										<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
									</div>
								@endif						
							</div>
							<div class="p-40">
								<form action="{{ route('register.self') }}" method="post" id="payment-form">
									@csrf
									<input type="hidden" name="stripe_key" id="stripe_key" value="{{ env('STRIPE_API_KEY') }}">
									
									<div class="form-group row">
										<div class="col-md-12 col-lg-6">
											<span>Full Name</span>
											<div class="input-group mb-3">
												<input id="owner_name" type="text" class="form-control ps-15 bg-transparent @error('owner_name') is-invalid @enderror" name="owner_name" value="{{ old('owner_name') }}" required autocomplete="owner_name" autofocus>
												@error('owner_name')
													<span class="invalid-feedback" role="alert">
														<strong>{{ $message }}</strong>
													</span>
												@enderror
											</div>
										</div>
										
										<div class="col-md-12 col-lg-6">
											<span>Restaurant Name</span>
											<div class="input-group mb-3">
												<input id="restaurant_name" type="text" class="form-control ps-15 bg-transparent @error('restaurant_name') is-invalid @enderror" name="restaurant_name" value="{{ old('restaurant_name') }}" required autocomplete="restaurant_name" autofocus>
												@error('restaurant_name')
													<span class="invalid-feedback" role="alert">
														<strong>{{ $message }}</strong>
													</span>
												@enderror											
											</div>

										</div>
									</div>

									<div class="form-group row">
										<div class="col-md-12 col-lg-6">
											<span>Email</span>
											<div class="input-group mb-3">
												<input id="email" type="email" class="form-control ps-15 bg-transparent @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
												@error('email')
													<span class="invalid-feedback" role="alert">
														<strong>{{ $message }}</strong>
													</span>
												@enderror
											</div>
										</div>

										<div class="col-md-12 col-lg-6">
											<span>Phone</span>
											<div class="input-group mb-3">
												<input id="phone" type="text" class="form-control ps-15 bg-transparent @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>
												@error('phone')
													<span class="invalid-feedback" role="alert">
														<strong>{{ $message }}</strong>
													</span>
												@enderror
											</div>
										</div>
									</div>

									<div class="form-group row mb-3">
										<div class="col-md-6">
											<span>Package</span>
											<div class="input-group mb-3">
												<select name="package" id="package" required class="form-select" aria-invalid="false">
													<option value="">Select Package</option>
													<option value="1" {{ $package == 'basic' ? 'selected' : '' }}>Basic</option>
													<option value="2" {{ $package == 'delux' ? 'selected' : '' }}>Delux</option>
													<option value="3" {{ $package == 'premium' ? 'selected' : '' }}>Premium</option>
												</select>
												@error('package')
													<span class="invalid-feedback" role="alert">
														<strong>{{ $message }}</strong>
													</span>
												@enderror
											</div>
										</div>
									
										<div class="col-md-6">
											<span>Payment Plan</span>
											<div class="input-group mb-3">
												<select name="plan" id="plan" required class="form-select" aria-invalid="false">
													<option value="">Select Plan</option>
													<option value="1" {{ $plan == 'monthly' ? 'selected' : '' }}>Monthly</option>
													<option value="2" {{ $plan == 'yealry' ? 'selected' : '' }}>Yearly</option>
												</select>
												@error('plan')
													<span class="invalid-feedback" role="alert">
														<strong>{{ $message }}</strong>
													</span>
												@enderror
											</div>
										</div>
									</div>

									<div class="form-group">
										<span for="card-element">Card Detail</span>
										<div id="card-element"></div>
										<div id="card-errors" role="alert"></div>
									</div>

                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-danger mt-10">SIGN UP</button>
                                        </div>
                                    </div>
								</form>	
								
								<div class="text-center">
									<p class="mt-15 mb-0">Already have an account?<a href="/login" class="text-danger ms-5"> Sign In</a></p>
								</div>
								<div class="text-center">
									<p class="mt-15 mb-0">© 2024 <a href="https://techsolutionspro.co.uk/" target="_blank" class="ms-5 text-bold" style="color: #1eabae">Tech Solutions Pro</a>. All Rights Reserved.</p>
								</div>	
							</div>						
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!-- Vendor JS -->
	<script src="{{ asset('assets/theme/js/vendors.min.js')}}"></script>
	<script src="{{ asset('assets/theme/js/pages/chat-popup.js')}}"></script>
	<script src="{{ asset('assets/theme/assets/vendor_components/apexcharts-bundle/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/theme/assets/icons/feather-icons/feather.min.js') }}"></script>	

	<script src="https://js.stripe.com/v3/"></script>

    <script>
		const stripe = Stripe(document.getElementById('stripe_key').value);
		const elements = stripe.elements();
		const cardElement = elements.create('card');
		cardElement.mount('#card-element');
	
		const form = document.querySelector('#payment-form');
		form.addEventListener('submit', async (event) => {
		  event.preventDefault();
	
		  const { paymentMethod, error } = await stripe.createPaymentMethod('card', cardElement);
	
		  if (error) {
			document.getElementById('card-errors').textContent = error.message;
		  } else {
			// Create hidden input for paymentMethod.id and submit the form
			const input = document.createElement('input');
			input.type = 'hidden';
			input.name = 'payment_method';
			input.value = paymentMethod.id;
			form.appendChild(input);
	
			form.submit();
		  }
		});
	  </script>
</body>
</html>
