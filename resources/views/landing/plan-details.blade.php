<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>{{ $plan->name }} - Plan Details</title>

  <link href="{{ asset('landing/img/fav.png') }}" rel="icon">
  <link href="{{ asset('landing/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('landing/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('landing/css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">

@include('landing.components.header')

<main class="main">

  <!-- Page Title -->
  <div class="page-title text-center py-4 bg-light">
    <h1 class="fw-bold" style="color:black;">{{ $plan->name }} Plan</h1>
    <p class="text-muted">{{ $plan->description ?? 'Get the most value with this subscription plan.' }}</p>
  </div>

  <!-- Plan Detail Section -->
  <section class="section py-5">
    <div class="container">
      <div class="row justify-content-center">

        <div class="col-lg-6">
          <div class="card shadow border-0 p-4">
            <h2 class="text-center">
              <span class="fw-bold">&#8377;{{ number_format($plan->price, 2) }}</span>
              <small class="text-muted">/ {{ ucfirst($plan->billing_cycle) }}</small>
            </h2>

            @if($plan->is_popular)
              <div class="text-center mb-3">
                <span class="badge bg-success rounded-pill px-3 py-2">Most Popular</span>
              </div>
            @endif

            <ul class="list-group list-group-flush mb-4">
              <li class="list-group-item">
                <strong>Students:</strong> {{ $plan->max_students == 0 ? 'Unlimited' : $plan->max_students }}
              </li>
              <li class="list-group-item">
                <strong>Teachers:</strong> {{ $plan->max_teachers == 0 ? 'Unlimited' : $plan->max_teachers }}
              </li>
              <li class="list-group-item">
                <strong>Staff:</strong> {{ $plan->max_staff == 0 ? 'Unlimited' : $plan->max_staff }}
              </li>
            </ul>

            <h5 class="fw-bold mb-3">Included Features:</h5>
            <ul class="list-unstyled">
              @forelse($plan->features as $feature)
                <li class="mb-2">
                  <i class="bi bi-check-circle-fill text-success me-2"></i> {{ $feature->name }}
                  @if($feature->pivot->allowed_value)
                    <span class="text-muted">({{ $feature->pivot->allowed_value }})</span>
                  @endif
                </li>
              @empty
                <li class="text-muted">No extra features listed.</li>
              @endforelse
            </ul>

            <div class="text-center mt-4">
              
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

</main>



<script src="{{ asset('landing/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('landing/js/main.js') }}"></script>

</body>
</html>
