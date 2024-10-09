<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Bello-fos</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{ asset('assets/landing-page/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets/landing-page/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/landing-page/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/landing-page/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/landing-page/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/landing-page/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/landing-page/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('assets/landing-page/css/main.css') }}" rel="stylesheet">

</head>

<body class="index-page">

  <header id="header" class="header sticky-top">

    <div class="branding d-flex align-items-center" style="padding: 0">
      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="#" class="logo d-flex justify-content-center align-items-center">
          <img src="{{ asset('assets/landing-page/images/bello-logo.png') }}" alt="Logo">
        </a>      
        <nav id="navmenu" class="navmenu">
          <ul class="nav-buttons">
            <li><a href="/register" class="btn outlined">Become a Partner</a></li>
            <li><a href="/login" class="btn filled">Log In</a></li>
      </div>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
      </div>
    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section light-background">

      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center hero-text" data-aos="zoom-out">
            <h1>Focus on Flavours While We Manage Your Orders!</h1>
            <p>Seamless Ordering & Operations For Your Restaurant's Online Presence.</p>
            <div class="d-flex">
              <a href="#featured-services" class="btn hero-btn">Learn more</a>
            </div>
          </div>
        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- Featured Services Section -->
    <section id="featured-services" class="featured-services section how-it-works">
      <div class="container">
        <h2>HOW IT WORKS</h2>
        <div class="row gy-4">

          <div class="col-xl-3 col-md-6 d-flex cards-row" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item position-relative card">
              <div class="icon"><img src="{{ asset('assets/landing-page/images/icon2.png') }}" alt=""></div>
              <button style="white-space: nowrap;">Browse Our Menu</button>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-3 col-md-6 d-flex  cards-row" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item position-relative card">
              <div class="icon"><img src="{{ asset('assets/landing-page/images/icon1.png') }}" alt=""></i></div>
              <button>Customise Order</button>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-3 col-md-6 d-flex cards-row" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item position-relative card">
              <div class="icon"><img src="{{ asset('assets/landing-page/images/icons (3).webp') }}" alt=""></i></div>
              <button>Fast Delivery</button>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-3 col-md-6 d-flex cards-row" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item position-relative card">
              <div class="icon"><img src="{{ asset('assets/landing-page/images/icons (4).webp') }}" alt=""></i></div>
              <button>Enjoy</button>
            </div>
          </div><!-- End Service Item -->

        </div>

      </div>

    </section><!-- /Featured Services Section -->

    <!-- Stats Section -->
    <section id="stats" class="stats section counter-section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center counter">
            <div class="stats-item">
              <h3>{{ $users }}</h3>
              <p>Users</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center counter">
            <div class="stats-item">
              <h3>{{ $patners }}</h3>
            <p>Partners</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center counter">
            <div class="stats-item">
              <h3>+5K</h3>
              <p>Reviews</p>
            </div>
          </div><!-- End Stats Item -->

        </div>

      </div>

    </section><!-- /Stats Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container">

        <div class="row gy-3">

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <img src="{{ asset('assets/landing-page/images/illustration 2.webp') }}" alt="" class="img-fluid">
          </div>

          <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
            <div class="about-content ps-0 ps-lg-3">
              <h2>Experience the best from our handpicked selection of local eateries</h2>
              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
              </p>
            </div>

          </div>
        </div>

      </div>

    </section><!-- /About Section -->

    <section id="about" class="about section">

      <div class="container">

        <div class="row gy-3">
          <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
            <div class="about-content ps-0 ps-lg-3">
              <h2>Experience the best from our handpicked selection of local eateries</h2>
              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
              </p>
            </div>
          
         
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <img src="{{ asset('assets/landing-page/images/illustration 1.webp') }}" alt="" class="img-fluid">
          </div>

        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Customer reviews</h2>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="swiper init-swiper" data-speed="600" data-delay="5000" data-breakpoints="{ &quot;320&quot;: { &quot;slidesPerView&quot;: 1, &quot;spaceBetween&quot;: 40 }, &quot;1200&quot;: { &quot;slidesPerView&quot;: 3, &quot;spaceBetween&quot;: 40 } }">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 1,
                  "spaceBetween": 40
                },
                "1200": {
                  "slidesPerView": 3,
                  "spaceBetween": 20
                }
              }
            }
          </script>
          <div class="swiper-wrapper">

            <div class="swiper-slide">
              <div class="testimonial-item">
            <p>
              <i class=" bi bi-quote quote-icon-left"></i>
                <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span>
                <i class="bi bi-quote quote-icon-right"></i>
                <span class="author-name">Tresha F</span>
                </p>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                  <span class="author-name">Sally G</span>
                </p>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                  <span class="author-name">Robert F</span>
                </p>
              </div>
            </div><!-- End testimonial item -->

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>

    </section><!-- /Testimonials Section -->


    
    <!-- Pricing Section -->
    <section id="pricing" class="pricing section" style="margin-top: -150px;">

      <!-- Section Title -->
      <div class="container section-title price-card" data-aos="fade-up">
        <div class="container">
          <h2>THE <span>BEST</span> CHOICE FOR YOU</h2>
          {{-- <p style="text-align: center">Free trial period of 14 days, unlocking all features</p> --}}
          <div class="switch-container">
              <span class="switch-label">Monthly</span>
              <label class="switch">
                <input type="checkbox" id="toggleSwitch">
                <span class="slider"></span>
              </label>              
              </label>
              <span class="switch-label">Yearly</span>
          </div>
      </div><!-- End Section Title -->

      <div class="container pt-4">

        <div class="row gy-3 justify-content-between">

          <!-- Basic Package -->
          <div class="col-xl-3 col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="pricing-item" style="background-color: #FCFAFA; border: 1px solid black;">
              <h3 style="color:black">Basic</h3>
              <p style="color:black; text-align:justify">
                It will include the Software Api for the customer to connect to their app as well as Api integration documents to help. Please note we will not do this for the customer they will set it up themselves.
              </p>
              <h4 class="price" data-monthly="35" data-yearly="357">35£ /Mo</h4>
              <p class="yearly-discount" style="color:green; display:none;">15% Off</p>
              <p>20£ one-off Api generation fee</p>
              <div class="btn-wrap">
                <a href="#" class="btn-buy" style="background-color: #FECD7A;">Try for free</a>
              </div>
            </div>
          </div><!-- End Pricing Item -->

          <!-- Delux Package -->
          <div class="col-xl-3 col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="pricing-item featured" style="background-color: #1EABAE;">
              <div class="button-container">
                <a href="#" class="btn-popular" style="background-color: #FECD7A;">Most Popular</a>
              </div>
              <h3 style="color:#FFFFFF">Delux</h3>
              <p style="color:#FFFFFF; text-align:justify">
                We create the website WordPress and connect the software to it and help setup the menu, delivery addresses, content for their site and the site etc.
              </p>
              <h4 style="color:#FFFFFF" class="price" data-monthly="35" data-yearly="357">35£ /Mo</h4>
              <p class="yearly-discount" style="color:green; display:none;">15% Off</p>
              <p class="text-white">
                1000£ setup fee <br>
                100£ a year hosting of the new site
              </p>
              <p class="text-white"> ( Tablet and printer will be purchased by the customer )</p>
              <div class="btn-wrap">
                <a href="#" class="btn-buy" style="background-color: #FFFFFF;">Try for free</a>
              </div>
            </div>
          </div><!-- End Pricing Item -->          

          <!-- Premium Package -->
          <div class="col-xl-3 col-lg-6" data-aos="fade-up" data-aos-delay="400">
            <div class="pricing-item" style="background-color: #FCFAFA; border: 1px solid black;">
              <h3 style="color:black">Premium</h3>
              <p style="color:black">
                Full help to set up the website and tablet at the shop etc.
              </p>
              <h4 class="price" data-monthly="35" data-yearly="357">35£ /Mo</h4>
              <p class="yearly-discount" style="color:green; display:none;">15% Off</p>
              <p>
                1500£ custom website <br>
                100£ a year hosting of the new site
              </p>
              <div class="btn-wrap">
                <a href="#" class="btn-buy" style="background-color: #FECD7A;">Try for free</a>
              </div>
            </div>
          </div><!-- End Pricing Item -->

        </div>

      </div>

    </section><!-- /Pricing Section -->

    <!-- Faq Section -->
    <section id="faq" class="faq section" style="margin-top: -150px;">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>FAQ'S</h2>
        <p><span>We have answers to your questions.</span></p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row justify-content-center">

          <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">

            <div class="faq-container">

              <div class="faq-item faq-active">
                <h3>What is Bello?</h3>
                <div class="faq-content">
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>What devices does Bello support?</h3>
                <div class="faq-content">
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>How can I use Bello?</h3>
                <div class="faq-content">
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

            </div>

          </div><!-- End Faq Column-->

        </div>

      </div>

    </section><!-- /Faq Section -->


  </main>

    <footer id="footer" class="footer light-background">

      <div class="container footer-top">
        <div class="row gy-4">
          <div class="col-lg-5 col-md-12 footer-about">
            <a href="#" class="logo d-flex align-items-center">
              <img src="{{ asset('assets/landing-page/images/bello-logo.png') }}" alt="Logo" style="height: 80px; width: 80px;">
            </a>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim</p>
          </div>
  
          {{-- <div class="col-lg-2 col-6 footer-links">
            <h4>Menu</h4>
            <ul>
              <li><a href="#">Home</a></li>
              <li><a href="#">Best Choice</a></li>
              <li><a href="#">Best Price</a></li>
              <li><a href="#">Best Location</a></li>
            </ul>
          </div> --}}
  
          <div class="col-lg-2 col-6 footer-links">
            <h4>Service</h4>
          <ul>
            <li><a href="#">FAQS</a></li>
            <li><a href="#">How We Work</a></li>
            <li><a href="#">Security</a></li>
          </ul>
          </div>
  
          <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
            <h4>About Us</h4>
            <p><a href="#" style="color: unset;">Careers</a></p>
            <p><a href="#" style="color: unset;">Features</a></p>
            <p><a href="#" style="color: unset;">News</a></p>
            <p><a href="#" style="color: unset;">Blogs</a></p>
          </div>
  
        </div>
      </div>

    <div class="container copyright text-center mt-4">
      <p>© 2024<strong class="px-1 sitename"><a href="https://techsolutionspro.co.uk/" target="_blank">Tech Solutions Pro</a></strong> <span>All Rights Reserved</span></p>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader">
    <div></div>
    <div></div>
    <div></div>
    <div></div>
  </div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/landing-page/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/landing-page/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('assets/landing-page/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('assets/landing-page/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets/landing-page/vendor/waypoints/noframework.waypoints.js') }}"></script>
  <script src="{{ asset('assets/landing-page/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('assets/landing-page/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/landing-page/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
  <script src="{{ asset('assets/landing-page/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>

  <!-- Main JS File -->
  <script src="{{ asset('assets/landing-page/js/main.js') }}"></script>

  <script>
    // JavaScript to toggle prices
    document.getElementById('toggleSwitch').addEventListener('change', function () {
    const isYearly = this.checked;
    const prices = document.querySelectorAll('.price');
    const discounts = document.querySelectorAll('.yearly-discount');
    
    prices.forEach((price, index) => {
      const monthlyPrice = parseFloat(price.getAttribute('data-monthly'));
      const yearlyPrice = parseFloat(price.getAttribute('data-yearly'));

      if (isYearly) {
        price.textContent = yearlyPrice.toFixed(2) + '£ /Yr';
        discounts[index].style.display = 'block';  // Show "15% Off"
      } else {
        price.textContent = monthlyPrice.toFixed(2) + '£ /Mo';
        discounts[index].style.display = 'none';  // Hide "15% Off"
      }
    });
  });
  </script>

</body>

</html>