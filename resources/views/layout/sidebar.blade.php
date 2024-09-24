<aside class="main-sidebar">
	<style>
		.main-sidebar {
			display: flex;
			flex-direction: column;
			height: 75vh; 
		}
		.sidebar {
			display: flex;
			flex-direction: column;
			height: 100%;
		}
		.multinav {
			flex-grow: 1; 
		}
		.sidebar-footer {
			margin-top: auto; 
			padding: 15px; 
			background-color: #f8f9fa;
			border-top: 1px solid #e9ecef; 
		}
		.treeview-menu {
			display: none; 
		}
		.treeview.active .treeview-menu {
			display: block; 
		}
		.sidebar-menu .active > a {
			background-color: #f4f4f4; 
		}
		.treeview-menu {
			transition: max-height 0.3s ease-out;
		}

	</style>
	
    <!-- sidebar-->
    <section class="sidebar position-relative">	
	  	<div class="multinav">
		  <div class="multinav-scroll" style="height: 100%;">	
			<!-- sidebar menu-->
			<ul class="sidebar-menu" data-widget="tree">
				<li class="">
					<a href="{{ route('dashboard')}}">
						<i class="icon-Home"></i>
						<span>Dashboard</span>
					</a>
				</li>

				@if(view_permission('orders'))
					<li class="treeview">
						<a href="#">
						<i class="icon-Clipboard-check"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
						<span>Order</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-right pull-right"></i>
						</span>
						</a>
						<ul class="treeview-menu">
						<li><a href="{{ route('orders.list') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Order List</a></li>
						</ul>
					</li>
				@endif

				@if(view_permission('company'))
					<li class="treeview">
						<a href="#">
							<i class="icon-Clipboard-check"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
						  <span>Restaurants Connected</span>
						  <span class="pull-right-container">
							<i class="fa fa-angle-right pull-right"></i>
						  </span>
						</a>
						<ul class="treeview-menu">
						  <li><a href="{{ route('companies.create') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add New</a></li>
						  <li><a href="{{ route('companies.list') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>List</a></li>
						  <li><a href="{{ route('companies.incoming.list') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Incoming Request</a></li>
						</ul>
					</li>
				@endif
				
				@if (view_permission('users'))
					<li class="treeview">
						<a href="#">
							<i class="icon-User"><span class="path1"></span><span class="path2"></span></i>
							<span>Restaurants Admins</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-right pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="{{ route('users.create') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add New</a></li>
							<li><a href="{{ route('users.list') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>List</a></li>
						</ul>
					</li>
				@endif
				@if (view_permission('categories'))
					<li class="treeview">
					<a href="#">
						<i class="icon-Clipboard-check"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
						<span>Categories</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-right pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li><a href="{{ route('category.create') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add New</a></li>
						<li><a href="{{ route('category.list') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>List</a></li>
						{{-- <li><a href=" {{ route('sample') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Order Details</a></li> --}}
					</ul>
					</li>
				@endif

				@if (view_permission('menu'))
					<li class="treeview">
						<a href="#">
							<i class="icon-Clipboard-check"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
						<span>Deals</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-right pull-right"></i>
						</span>
						</a>
						<ul class="treeview-menu">
						<li><a href="{{ route('menu.create') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add New</a></li>
						<li><a href="{{ route('menu.edit') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>List</a></li>
						<li class="treeview">
							<a href="#">
								<i class="icon-Chat-check"><span class="path1"></span><span class="path2"></span></i>
								<span>Options/Sides</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-right pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
							<li><a href="{{ route('options.create') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add New</a></li>
							<li><a href="{{ route('options.list') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>List</a></li>
							</ul>
						</li>
						<li class="treeview">
							<a href="#">
								<i class="icon-Clipboard-check"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
							<span>Products</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-right pull-right"></i>
							</span>
							</a>
							<ul class="treeview-menu">
							<li><a href="{{ route('products.create') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add New</a></li>
							<li><a href="{{ route('products.list') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>List</a></li>
							</ul>
						</li>
					</ul>
					</li>
				@endif

				@if (view_permission('setup'))
					<li class="treeview">
						<a href="#">
							<i class="icon-Clipboard-check"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
							<span>Restaurant Setup</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-right pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="{{ route('schedules.create') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add Schedule</a></li>
							<li><a href="{{ route('radius.create') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add Radius</a></li>
							<li><a href="{{ route('timezone.create') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Set Timezone</a></li>
							<li><a href="{{ route('configurations.create') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Configurations</a></li>
						</ul>
					</li>
				@endif

				@if (view_permission('newsletter'))
					<li class="treeview">
						<a href="#">
							<i class="icon-Clipboard-check"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
							<span>Newsletter</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-right pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="{{ route('subscriptions.list') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Subscriptions</a></li>
						</ul>
					</li>
				@endif
			</ul>

			<div class="sidebar-widgets">
				{{-- <div class="mx-25 mb-30 pb-20 side-bx bg-primary bg-food-dark rounded20">
					<div class="text-center">
						<img src="#" class="sideimg" alt="">
						<h3 class="title-bx">Add Menu</h3>
						<a href="#" class="text-white py-10 fs-16 mb-0">
							Manage Your food and beverages menu <i class="mdi mdi-arrow-right"></i>
						</a>
					</div>
				</div> --}}
				 <!-- Copyright Section -->
				 <div class="sidebar-footer">
					<div class="copyright text-start">
						<p><strong class="d-block">Tech Solutions Pro</strong> © 2024</p>
					</div>
				</div>
			</div>
		  </div>
		</div>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
			<script>
			$(document).ready(function() {
				$('.treeview > a').on('click', function(e) {
					e.preventDefault(); 
					var $treeview = $(this).closest('.treeview');
					$treeview.toggleClass('active');
					$('.treeview').not($treeview).removeClass('active');
				});
				var currentUrl = window.location.href;
				$('.sidebar-menu a').each(function() {
					var $link = $(this);
					if ($link.attr('href') === currentUrl) {
						$link.closest('li').addClass('active'); 
						$link.closest('.treeview').addClass('active'); 
					}
				});
			});
			</script>

    </section>
</aside>