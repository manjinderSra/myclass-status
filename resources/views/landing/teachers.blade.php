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
              <h1>Teachers</h1>
              <p class="mb-0">Learn from industry experts with extensive knowledge and hands-on experience. Our trainers
                are dedicated to providing high-quality education, personalized guidance, and practical insights to help
                you excel in your field. With their expertise, you'll gain the skills and confidence needed for success.
              </p>
            </div>
          </div>
        </div>
      </div>

    </div><!-- End Page Title -->

    <!-- Trainers Section -->
    <section id="trainers" class="section trainers">

      <div class="container">

        <div class="row gy-5">

          <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="100">
            <div class="member-img">
              <img src="{{ asset('landing/img/team/team-1.png') }}" class="img-fluid" alt="">
              <div class="social">
                <a href="#"><i class="bi bi-twitter-x"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
            <div class="member-info text-center">
              <h4>Mr. Vikram Patel</h4>
              <span>Chemistry</span>
              <p>As a chemistry teacher, I appreciate how MyClassStatus organizes lab schedules and grades. The mobile
                app is convenient, though it could use a dark mode for late-night grading!</p>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="200">
            <div class="member-img">
              <img src="{{ asset('landing/img/team/team-2.png') }}" class="img-fluid" alt="">
              <div class="social">
                <a href="#"><i class="bi bi-twitter-x"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
            <div class="member-info text-center">
              <h4>Dr. Amit Sharma</h4>
              <span>Physics</span>
              <p>A fantastic app for managing class records. The analytics feature helps me identify students who need
                extra attention. The only suggestion would be to add more customization options.</p>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="300">
            <div class="member-img">
              <img src="{{ asset('landing/img/team/team-3.png') }}" class="img-fluid" alt="">
              <div class="social">
                <a href="#"><i class="bi bi-twitter-x"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
            <div class="member-info text-center">
              <h4>Mr. Vikram Patel</h4>
              <span>Chemistry</span>
              <p>As a chemistry teacher, I appreciate how MyClassStatus organizes lab schedules and grades. The mobile
                app is convenient, though it could use a dark mode for late-night grading!</p>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="400">
            <div class="member-img">
              <img src="{{ asset('landing/img/team/team-4.png') }}" class="img-fluid" alt="">
              <div class="social">
                <a href="#"><i class="bi bi-twitter-x"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
            <div class="member-info text-center">
              <h4>Prof. Sanjay Reddy</h4>
              <span>Biology</span>
              <p>An excellent tool for maintaining student progress reports. The attendance automation saves me a lot of
                time. Would love to see integration with other LMS platforms.</p>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="500">
            <div class="member-img">
              <img src="{{ asset('landing/img/team/team-5.png') }}" class="img-fluid" alt="">
              <div class="social">
                <a href="#"><i class="bi bi-twitter-x"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
            <div class="member-info text-center">
              <h4>Prof. Rajesh Kumar</h4>
              <span>Mathematics<br></span>
              <p>MyClassStatus has made tracking attendance and assignments so much easier! The interface is intuitive,
                and my students can now submit their work seamlessly. Highly recommended!</p>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="600">
            <div class="member-img">
              <img src="{{ asset('landing/img/team/team-6.png') }}" class="img-fluid" alt="">
              <div class="social">
                <a href="#"><i class="bi bi-twitter-x"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
            <div class="member-info text-center">
              <h4>Dr. Anil Desai</h4>
              <span>English Literature</span>
              <p>This app is a game-changer for managing essays and feedback. The cloud storage ensures no work is lost.
                More font/style options for comments would be a plus!</p>
            </div>
          </div><!-- End Team Member -->

        </div>

      </div>

    </section><!-- /Trainers Section -->

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