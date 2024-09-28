<header class="main-header">
	<style>
		/* Existing blink animation */
		.blink {
			animation: blink-animation 1s steps(2, start) infinite;
		}
		
		@keyframes blink-animation {
			from, to {
				opacity: 0.5;
			}
			50% {
				opacity: 1;
			}
		}
		
		/* Continuous blinking effect */
		.blink-effect {
			animation: blink-effect-animation 1s steps(2, start) infinite;
		}
		
		@keyframes blink-effect-animation {
			0%, 100% {
				opacity: 1;
			}
			50% {
				opacity: 0.3;
			}
		}
		</style>
		


	<div class="d-flex align-items-center logo-box justify-content-start">
		<a href="#" class="waves-effect waves-light nav-link d-none d-md-inline-block mx-10 push-btn bg-transparent hover-primary" data-toggle="push-menu" role="button">
			<span class="icon-Align-left"><span class="path1"></span><span class="path2"></span><span class="path3"></span></span>
		</a>	
		<!-- Logo -->
		<a href="{{ route('dashboard')}}" class="logo">
		  <!-- logo-->
		  <div class="logo-lg">
			  <span class="light-logo"><img src="{{ asset('assets/theme/images/bello_logo_160x55.png') }}" alt="logo"></span>
			  <span class="dark-logo"><img src="{{ asset('assets/theme/images/bello_logo_160x55.png') }}" alt="logo"></span>
		  </div>
		</a>	
	</div>  
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
	  <div class="app-menu">
		<ul class="header-megamenu nav">
			<li class="btn-group nav-item d-md-none">
				<a href="#" class="waves-effect waves-light nav-link push-btn btn-info-light" data-toggle="push-menu" role="button">
					<span class="icon-Align-left"><span class="path1"></span><span class="path2"></span><span class="path3"></span></span>
			    </a>
			</li>
			<li class="btn-group nav-item d-none d-xl-inline-block">
				<div class="app-menu">
					<div class="search-bx mx-5">
						<form>
							<div class="input-group">
							  <input type="search" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
							  <div class="input-group-append">
								<button class="btn" type="submit" id="button-addon3"><i class="ti-search"></i></button>
							  </div>
							</div>
						</form>
					</div>
				</div>
			</li>
		</ul> 
	  </div>
		
      <div class="navbar-custom-menu r-side">
        <ul class="nav navbar-nav">	
			<li class="btn-group nav-item d-lg-inline-flex d-none">
				<a href="#" data-provide="fullscreen" class="waves-effect waves-light nav-link full-screen btn-info-light" title="Full Screen">
					<i class="icon-Expand-arrows"><span class="path1"></span><span class="path2"></span></i>
			    </a>
			</li>	
		  	<!-- Notifications -->
			<li class="dropdown notifications-menu">
				<span class="label label-primary">0</span> <!-- Initial count of notifications -->
				<a href="#" class="waves-effect waves-light dropdown-toggle btn-primary-light" data-bs-toggle="dropdown" title="Notifications">
					<i class="icon-Notifications"><span class="path1"></span><span class="path2"></span></i>
				</a>
				<ul class="dropdown-menu animated bounceIn">
					<li class="header">
						<div class="p-20">
							<div class="flexbox">
								<div>
									<h4 class="mb-0 mt-0">Notifications</h4>
								</div>
							</div>
						</div>
					</li>
					<li>
						<!-- inner menu: contains the actual data -->
						<ul class="menu sm-scrol">
							<!-- Notifications will be inserted here by JavaScript -->
						</ul>
					</li>
					{{-- <li class="footer">
						<a href="#">View all</a>
					</li> --}}
				</ul>
			</li>
			  
	      <!-- Right Sidebar Toggle Button -->
          <li class="btn-group nav-item d-xl-none d-inline-flex">
              <a href="#" class="push-btn right-bar-btn waves-effect waves-light nav-link full-screen btn-info-light">
			  	<span class="icon-Layout-left-panel-1"><span class="path1"></span><span class="path2"></span></span>
			  </a>
          </li>
	      <!-- User Account-->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle p-0 text-dark hover-primary ms-md-30 ms-10" data-bs-toggle="dropdown" title="User">
				<span class="ps-30 d-md-inline-block d-none"></span> <strong class="d-md-inline-block d-none">{{ Auth::user()->name }}</strong><img src="{{ asset('assets/theme/images/avatar/avatar-11.png')}}" class="user-image rounded-circle avatar bg-white mx-10" alt="User Image">
            </a>
            <ul class="dropdown-menu animated flipInX">
              <li class="user-body">
				 <a class="dropdown-item" href="#"><i class="ti-user text-muted me-2"></i> Profile</a>
				 <a class="dropdown-item" href="#"><i class="ti-wallet text-muted me-2"></i> My Wallet</a>
				 <a class="dropdown-item" href="#"><i class="ti-settings text-muted me-2"></i> Settings</a>
				 <div class="dropdown-divider"></div>
				 <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="ti-lock text-muted me-2" ></i> Logout</a>
				 
				 <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
              </li>
            </ul>
          </li>	
			
        </ul>
      </div>
	  <script>
		document.addEventListener('DOMContentLoaded', function() {
			function fetchNotifications() {
				fetch('/notifications').then(response => response.json()).then(notifications => {
						const notificationsMenu = document.querySelector('.dropdown-menu .menu');
						notificationsMenu.innerHTML = ''; // Clear existing notifications

						notifications.forEach(notification => {
							const data = JSON.parse(notification.data);

							const li = document.createElement('li');
							li.innerHTML = `
								<a href="${data.url}" class="notification-item" data-id="${notification.id}">
									<i class="fa ${getIconClass(data.type)} text-${data.type}"></i> ${data.message}
								</a>
							`;
							notificationsMenu.appendChild(li);
						});

						// Update notification count
						const notificationCount = document.querySelector('.label.label-primary');
						notificationCount.textContent = notifications.length;

						// Trigger blink effect if there are new notifications
						if (notifications.length > 0) {
							const notificationIcon = document.querySelector('.notifications-menu .dropdown-toggle');
							notificationIcon.classList.add('blink-effect');
						}
					})
					.catch(error => console.error('Error fetching notifications:', error));
			}

			function getIconClass(type) {
				switch (type) {
					case 'info': return 'fa-info-circle';
					case 'warning': return 'fa-exclamation-triangle';
					case 'success': return 'fa-check-circle';
					case 'danger': return 'fa-times-circle';
					default: return 'fa-bell';
				}
			}

			function clearNotifications() {
				fetch('/notifications/clear', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
					}
				})
				.then(response => {
					if (response.ok) {
						fetchNotifications(); // Refresh notifications
					} else {
						console.error('Failed to clear notifications:', response.statusText);
					}
				})
				.catch(error => console.error('Error clearing notifications:', error));
			}

			// function deleteNotification(id) {
			//     fetch(`/notifications/${id}/delete`, {
			//         method: 'POST',
			//         headers: {
			//             'Content-Type': 'application/json',
			//             'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
			//         }
			//     })
			//     .then(response => {
			//         if (response.ok) {
			//             fetchNotifications();
			//         } else {
			//             console.error('Failed to delete notification:', response.statusText);
			//         }
			//     })
			//     .catch(error => console.error('Error deleting notification:', error));
			// }

			fetchNotifications();

			setInterval(fetchNotifications, 10000); // Refresh every 10 seconds

			// document.getElementById('clear-notifications').addEventListener('click', function(event) {
			// 	event.preventDefault();
			// 	clearNotifications();
			// });

			document.querySelector('.notifications-menu .dropdown-toggle').addEventListener('click', function() {
				this.classList.remove('blink-effect');
			});

			document.addEventListener('click', function(event) {
				if (event.target.matches('.notification-item')) {
					// event.preventDefault();
					const notificationId = event.target.getAttribute('data-id');
					// deleteNotification(notificationId);
				}
			});
		});
	  </script>
    </nav>
</header>