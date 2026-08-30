@include('errors.minimal', [
    'status' => 503,
    'title' => 'We will be back shortly.',
    'message' => 'My Class Status is temporarily unavailable while maintenance is in progress. Please try again in a few minutes.',
    'icon' => '⚙',
    'cardTitle' => 'Maintenance in progress',
    'cardMessage' => 'Thank you for your patience while we prepare the service.',
    'showBackButton' => false,
])
