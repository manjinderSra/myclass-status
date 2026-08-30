<header id="header" class="header d-flex align-items-center sticky-top">
  <div class="container-fluid container-xl position-relative d-flex align-items-center">

    <a href="{{route('landing.index')}}" class="logo d-flex align-items-center me-auto">
      <img src="{{ asset('landing/img/Group (2).png') }}" alt="logo" height="50px">
    </a>

    <nav id="navmenu" class="navmenu">
      <ul>
        <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
        <li><a href="{{route('landing.about')}}" class="{{ request()->routeIs('landing.about') ? 'active' : '' }}">About Us</a></li>
        <li><a href="{{route('landing.pricing')}}" class="{{ request()->routeIs('landing.pricing') ? 'active' : '' }}">Pricing</a></li>
        <li><a href="{{route('landing.contact')}}" class="{{ request()->routeIs('landing.contact') ? 'active' : '' }}">Contact Us</a></li>
      </ul>
      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </nav>

    <div class="d-flex gap-2 m-3">
      <a href="{{ route('school.login') }}" class=" btn-getstarted ">
        <i class="fas fa-school"></i> School Login
      </a>
      <a href="{{ route('student.login') }}" class=" btn-getstarted ">
        <i class="fas fa-user-graduate"></i> Student Login
      </a>
      <a href="{{ route('teacher.login') }}" class=" btn-getstarted ">
        <i class="fas fa-chalkboard-teacher"></i> Teacher Login
      </a>
    </div>
  </div>
</header>