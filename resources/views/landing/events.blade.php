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
              <h1>Events</h1>
              <p class="mb-0">Experience engaging and insightful events designed to inspire learning and collaboration.
                From workshops to seminars, our events bring together experts, educators, and learners to share
                knowledge, exchange ideas, and drive innovation. Join us to stay ahead in your field!</p>
            </div>
          </div>
        </div>
      </div>

    </div><!-- End Page Title -->

    <!-- Events Section -->
    <section id="events" class="events section">

      <div class="container" data-aos="fade-up">

        <div class="row">
          <div class="col-md-6 d-flex align-items-stretch">
            <div class="card">
              <div class="card-img">
                <img src="{{ asset('landing/img/events-item-1.jpg') }}" alt="...">
              </div>
              <div class="card-body">
                <h5 class="card-title"><a href="">General ERP Events</a></h5>
                <p class="fst-italic text-center">Sunday, September 26th at 7:00 pm</p>
                <p class="card-text">In an ERP management system, general events play a crucial role in keeping all
                  stakeholders informed and ensuring smooth operations. These events include upcoming events and
                  notifications, which provide timely updates about important academic, administrative, and
                  institutional activities. Additionally, the institutional calendar and key dates section highlights
                  significant milestones such as academic sessions, holidays, and examination schedules.</p>
              </div>
            </div>
          </div>
          <div class="col-md-6 d-flex align-items-stretch">
            <div class="card">
              <div class="card-img">
                <img src="{{ asset('landing/img/events-item-2.jpg') }}" alt="...">
              </div>
              <div class="card-body">
                <h5 class="card-title"><a href="">Library & Resource Management Events</a></h5>
                <p class="fst-italic text-center">Sunday, November 15th at 7:00 pm</p>
                <p class="card-text">A well-managed library and resource system is essential for any institution, and an
                  ERP system helps streamline its operations efficiently. New book arrivals & library updates keep
                  students and faculty informed about the latest additions to the library, ensuring access to updated
                  academic materials. Library due date & fine alerts serve as timely reminders for book returns,
                  preventing overdue fines and encouraging responsible usage of resources.</p>
              </div>
            </div>

          </div>
        </div>

      </div>

    </section><!-- /Events Section -->

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
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
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