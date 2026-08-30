@include('errors.minimal', [
    'status' => 429,
    'title' => 'Too many requests.',
    'message' => 'We received several requests in a short period. Please wait a moment before trying again.',
    'icon' => '…',
    'cardTitle' => 'Please slow down',
    'cardMessage' => 'This temporary limit helps keep the service fast and secure.',
    'showBackButton' => true,
])
