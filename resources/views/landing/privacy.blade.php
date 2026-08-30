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
    <style>
        .privacy-policy .content h3 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .privacy-policy .content h4 {
            font-size: 1.2rem;
            font-weight: 600;
        }
    </style>
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
                            <h1>Privacy Policy<br></h1>
                            <p class="mb-0">At My Class Status, we are committed to protecting your personal data in
                                compliance with global privacy laws. Our platform collects necessary information—such as
                                student records, staff details, and usage data—solely to deliver seamless educational
                                management services.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- End Page Title -->
        <section class="privacy-policy" data-aos="fade-up" data-aos-delay="100">
            <div class="container">
                <p>Last Updated: 18/04/2025</p>
                <p class="fst-italic">
                    Welcome to <b>My Class Status</b>, your trusted School & College ERP System. At My Class Status, we
                    are
                    committed to protecting your privacy and ensuring the security of your personal information. This
                    Privacy Policy explains how we collect, use, disclose, and safeguard your data when you use our
                    platform.

                    By accessing or using My Class Status, you agree to the terms outlined in this Privacy Policy. If
                    you do not agree with our practices, please refrain from using our services.
                </p>
                <div class="content">
                    <h3>1. Information We Collect</h3>
                    <p>To provide a seamless educational and administrative experience, we collect various types of
                        information, including:</p>
                    <h4>A. Personal Information</h4>
                    <ul>
                        <li> <b>Students: </b>Name, date of birth, contact details, academic records, attendance data,
                            and
                            parent/guardian information.</li>
                        <li> <b>Teachers & Staff:</b> Name, contact details, employment records, qualifications, and
                            performance
                            data.</li>
                        <li><b>Parents/Guardians:</b> Name, contact details, relationship to the student, and access
                            credentials.</li>
                        <li><b>Administrators:</b> Login credentials, role-based permissions, and institutional details.
                        </li>
                    </ul>
                    <h4>B. Academic & Operational Data</h4>
                    <ul>
                        <li>Class schedules, exam results, assignments, grades, and disciplinary records.</li>
                        <li>Fee payment details, library records, and transportation logs (if applicable).</li>
                        <li>Communication logs between users (e.g., teacher-parent messages, announcements).</li>
                    </ul>
                    <h4>C. Technical & Usage Data</h4>
                    <ul>
                        <li>IP addresses, device information (browser, OS, hardware), and cookies.</li>
                        <li>Log files, session duration, and interaction patterns within the platform.</li>
                    </ul>
                    <h4>D. Third-Party Data</h4>
                    <ul>
                        <li>If integrated, data from external services (e.g., payment gateways, LMS tools, cloud
                            storage).</li>
                    </ul>
                    <h3>2. How We Use Your Information</h3>
                    <p>We process your data to:</p>
                    <ul>
                        <li><b>Provide & Improve Services:</b> Facilitate academic management, attendance tracking, fee
                            processing, and communication.</li>
                        <li>
                            <b>Enhance Security:</b> Prevent fraud, unauthorized access, and ensure compliance with
                            institutional policies.
                        </li>
                        <li> <b>Personalize Experience:</b> Offer tailored dashboards, notifications, and academic
                            insights.
                        </li>
                        <li><b>Legal & Administrative Compliance:</b> Meet regulatory requirements, audit purposes, and
                            institutional reporting.</li>
                        <li><b>Communication:</b> Send updates, alerts, and institutional announcements via email/SMS.
                        </li>
                    </ul>
                    <h3>3. Data Sharing & Disclosure</h3>
                    <p>We may share information with:</p>
                    <ul>
                        <li>
                            Authorized School/College Personnel: Teachers, administrators, and staff for academic and
                            operational purposes.
                        </li>
                        <li>Parents/Guardians: Access to student progress, attendance, and performance reports.
                        </li>
                        <li>Third-Party Service Providers: Payment processors, cloud hosting, and analytics tools (under
                            strict confidentiality agreements).</li>
                        <li>
                            Legal & Regulatory Authorities: If required by law (e.g., subpoenas, child protection laws).
                        </li>
                    </ul>
                    <p>We do not sell or rent your personal data to advertisers or external marketers.</p>
                    <h3>4. Data Security Measures</h3>
                    <p>We implement robust security protocols, including:</p>
                    <ul>
                        <li>Encryption: SSL/TLS for data transmission, AES for stored data.</li>
                        <li>
                            Access Controls: Role-based permissions and multi-factor authentication (MFA).
                        </li>
                        <li>Regular Audits: Vulnerability assessments and penetration testing.</li>
                        <li>Employee Training: Strict confidentiality agreements for staff handling sensitive data.</li>
                    </ul>
                    <p>Despite our efforts, no system is 100% secure. Users must also protect their login credentials
                        and report suspicious activity immediately.
                    </p>
                    <h3>5. Data Retention & Deletion</h3>
                    <ul>
                        <li>We retain data as long as necessary for academic, legal, or operational purposes.</li>
                        <li>Inactive accounts may be archived or anonymized after a specified period.</li>
                        <li>Users (or institutions) may request data deletion, subject to legal obligations.</li>
                    </ul>
                    <h3>6. Cookies & Tracking Technologies</h3>
                    <p>We use cookies to:</p>
                    <ul>
                        <li>Enhance user experience (e.g., session management, preferences).</li>
                        <li>Analyze traffic patterns via tools like Google Analytics (anonymous data).</li>
                        <li>Users can disable cookies via browser settings but may lose certain functionalities.</li>
                    </ul>
                </div>
            </div>
        </section>

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
                        <li><a href="{{route('landing.term')}}">Terms of service</a></li>
                        <li><a href="{{route('landing.privacy')}}">Privacy policy</a></li>
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
            <p>© <span>Copyright</span> <strong class="px-1 sitename">Manjinder Singh</strong> <span>All Rights
                    Reserved
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