<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>my classes status</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{ asset('landing/img/fav.png') }}" rel="icon">
  <link href="{{ asset('landing/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('landing/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('landing/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('landing/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('landing/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('landing/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('landing/css/main.css') }}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Mentor
  * Template URL: https://bootstrapmade.com/mentor-free-education-bootstrap-theme/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  @include('landing.components.header')

  <main class="main">

    <!-- Page Title -->
    <div class="page-title" data-aos="fade">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8">
              <h1>Pricing</h1>
              <p class="mb-0">Choose a plan that fits your needs! We offer flexible and affordable pricing to ensure the
                best value for individuals and institutions. Our plans are designed to provide comprehensive features,
                seamless access, and top-tier support to enhance your experience.</p>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <!-- Pricing Section -->
    <section id="pricing" class="pricing section">

      <div class="container">

         <div class="row gy-3">

          @forelse($plans as $plan)
          @if($plan->id!=6)
          <div class="col-xl-3 col-lg-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
            <div class="pricing-item {{ $plan->is_popular ? 'featured' : '' }}">
              @if($plan->is_popular)
              <span class="advanced">Popular</span>
              @endif
              <h3>{{ $plan->name }}</h3>
              <h4><sup>&#8377;</sup>{{ number_format($plan->price, 2) }}<span> / {{ $plan->billing_cycle }}</span></h4>
              <ul>
                <li>{{ $plan->max_students ==0 ?"Unlimited" : $plan->max_students }} Students</li>
                <li>{{ $plan->max_teachers ==0 ?"Unlimited" : $plan->max_teachers }} Teachers</li>
                <li>{{ $plan->max_staff ==0 ?"Unlimited" : $plan->max_staff }} Staff</li>
                @php
                  // Get some key features to display (limit to 4)
                  $keyFeatures = $plan->features->where('feature_group', '!=', 'limits')->take(4);
                @endphp
                @foreach($keyFeatures as $feature)
                  <li>{{ $feature->name }}</li>
                @endforeach
              </ul>
              <div class="btn-wrap">
                <a href="{{ route('landing.plan.details', $plan->id) }}" class="btn-buy">View Details</a>
              </div>
            </div>
          </div>
          @endif
          @empty
          <div class="col-12 text-center">
            <p>No subscription plans available at the moment.</p>
          </div>
          @endforelse

        </div>
      </div>

    </section><!-- /Pricing Section -->

  </main>

  <footer id="footer" class="footer position-relative light-background">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="{{route('landing.index')}}" class="logo d-flex align-items-center">
            <img src="{{ asset('landing/img/Group (2).png') }}" alt="logo" height="50px">
          </a>
          <div class="footer-contact pt-3">
            <p>38-13/15, Rohini colony, vayupuri,</p>
            <p>Sainikpuri, Hyderabad-500094</p>
            <p>Beside st.Andrew high school</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+919398566857</span></p>
            <p><strong>Email:</strong> <span>Solutions@myclassstatus.com</span></p>
          </div>
           <div class="social-links d-flex mt-4">
            <a href="https://x.com/myclassstatus
"><i class="bi bi-twitter-x"></i></a>
            <a href="https://www.facebook.com/myclassstatus/
"><i class="bi bi-facebook"></i></a>
            <a href="https://www.instagram.com/myclassstatus/
"><i class="bi bi-instagram"></i></a>
            <a href="https://www.linkedin.com/company/my-class-status/
"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-md-3 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><a href="/">Home</a></li>
            <li><a href="{{route('landing.about')}}">About Us</a></li>
            <li><a href="{{route('landing.contact')}}">Contact Us</a></li>
            <li><a href="{{route('landing.term')}}">Terms Of Service</a></li>
            <li><a href="{{route('landing.privacy')}}">Privacy Policy</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-3 footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><a href="#">Student Information System (SIS)</a></li>
            <li><a href="#">Admission & Enrollment Management</a></li>
            <li><a href="#">Fee & Finance Management</a></li>
            <li><a href="#">Academic Management</a></li>
            <li><a href="#"> Examination & Results Management</a></li>
          </ul>
        </div>


      </div>
    </div>
    <div class="container-fluid copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">Manjinder Singh</strong> <span>All Rights Reserved
          Designed By <a href="https://github.com/manjinderSra">Manjinder Singh</a></span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->

      </div>
    </div>

  </footer>
  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('landing/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('landing/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('landing/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('landing/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('landing/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('landing/vendor/swiper/swiper-bundle.min.js') }}"></script>

  <!-- Main JS File -->
  <script src="{{ asset('landing/js/main.js') }}"></script>

</body>

</html>