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
								<h2 style="color: #F8A61B">Renewal</h2>
								<p class="mb-0">Hello {{ $userName }} from {{ $companyName }}.</p>
								<p class="mb-0">Your restaurant is inactive due to subscription expired.</p>
								<p>Kindly renew it to continue.</p>
								@if (session('success'))
									<div class="alert alert-success alert-dismissible fade show" role="alert">
										{{ session('success') }}
										<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
									</div>
								@endif						
							</div>
							<div class="p-40">
								<form action="{{ route('renewal.store') }}" method="post" id="renewal-form">
									@csrf
									<input type="hidden" name="stripe_key" id="stripe_key" value="{{ env('STRIPE_API_KEY') }}">
									<input type="hidden" name="user_id" id="user_id" value="{{ $userId }}">
									<input type="hidden" name="company_id" id="company_id" value="{{ $companyId }}">

									<div class="form-group row mb-3">
										<div class="col-md-6">
											<span>Package</span>
											<div class="input-group mb-3">
												<select name="package" id="package" required class="form-select" aria-invalid="false">
													<option value="">Select Package</option>
													<option value="1" {{ $package == 1 ? 'selected' : '' }}>Basic</option>
													<option value="2" {{ $package == 2 ? 'selected' : '' }}>Delux</option>
													<option value="3" {{ $package == 3 ? 'selected' : '' }}>Premium</option>
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
													<option value="1" {{ $plan == 1 ? 'selected' : '' }}>Monthly</option>
													<option value="2" {{ $plan == 2 ? 'selected' : '' }}>Yearly</option>
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
                                            <button type="submit" class="btn btn-danger mt-10">Renew</button>
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
	
		const form = document.querySelector('#renewal-form');
		form.addEventListener('submit', async (event) => {
			event.preventDefault();
	
			// Create payment method
			const { paymentMethod, error } = await stripe.createPaymentMethod({
				type: 'card',
				card: cardElement,
			});
	
			if (error) {
				// Show error in card-errors div
				document.getElementById('card-errors').textContent = error.message;
				return; // Exit if there was an error creating the payment method
			}
	
			// Send form data to the server to create PaymentIntent
			const response = await fetch('{{ route("renewal.store") }}', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
				},
				body: JSON.stringify({
					user_id: document.getElementById('user_id').value,
					company_id: document.getElementById('company_id').value,
					package: document.getElementById('package').value,
					plan: document.getElementById('plan').value,
					payment_method: paymentMethod.id, // Use the created payment method
				}),
			});
	
			const data = await response.json();
	
			// Handle PaymentIntent confirmation with the client_secret
			if (data.client_secret) {
				const result = await stripe.confirmCardPayment(data.client_secret);
				if (result.error) {
					document.getElementById('card-errors').textContent = result.error.message;
				} else {
					form.submit(); // On successful payment, submit form
				}
			} else {
				document.getElementById('card-errors').textContent = "Failed to initiate payment.";
			}
		});
	</script>
</body>
</html>
