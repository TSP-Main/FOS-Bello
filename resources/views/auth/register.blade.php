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
					<div class="col-lg-5 col-md-5 col-12">
						<div class="bg-white rounded10 shadow-lg">
							<div class="content-top-agile p-20 pb-0">
								<a href="/"><img src="{{ asset('assets/theme/images/bello_logo.png') }}" alt="" width="100px" height="100px"></a>
								<h2 class="text-primary">Let's Get Started</h2>
								<p class="mb-0">Sign in to continue to Food Ordering System.</p>
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif							
							</div>
							<div class="p-40">
								<form action="{{ route('register.self') }}" method="post">
									@csrf
									<div class="form-group">
                                        <span>Full Name</span>
										<div class="input-group mb-3">
											{{-- <span class="input-group-text bg-transparent">Full Name</span> --}}
											<input id="owner_name" type="text" class="form-control ps-15 bg-transparent @error('owner_name') is-invalid @enderror" name="owner_name" value="{{ old('owner_name') }}" required autocomplete="owner_name" autofocus>

											@error('owner_name')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
									</div>
                                    <div class="form-group">
                                        <span>Restaurant Name</span>
										<div class="input-group mb-3">
											{{-- <span class="input-group-text bg-transparent">Restaurant Name</span> --}}
											<input id="restaurant_name" type="text" class="form-control ps-15 bg-transparent @error('restaurant_name') is-invalid @enderror" name="restaurant_name" value="{{ old('restaurant_name') }}" required autocomplete="restaurant_name" autofocus>

											@error('restaurant_name')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
									</div>
                                    <div class="form-group">
                                        <span>Email</span>
										<div class="input-group mb-3">
											{{-- <span class="input-group-text bg-transparent">Email</span> --}}
											<input id="email" type="email" class="form-control ps-15 bg-transparent @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

											@error('email')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
									</div>
                                    <div class="form-group">
                                        <span>Phone</span>
										<div class="input-group mb-3">
											{{-- <span class="input-group-text bg-transparent">Phone</span> --}}
											<input id="phone" type="text" class="form-control ps-15 bg-transparent @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>

											@error('phone')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
									</div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-danger mt-10">SIGN UP</button>
                                        </div>
                                    </div>
								</form>	
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

</body>
</html>
