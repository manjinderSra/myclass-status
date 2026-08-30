@include('errors.minimal', [
    'status' => $exception->getStatusCode(),
    'title' => 'The service encountered a problem.',
    'message' => 'We could not complete the request right now. Please try again shortly.',
    'icon' => '!',
    'cardTitle' => 'Service error',
    'cardMessage' => 'The error has been kept private and recorded for investigation.',
    'showBackButton' => true,
])
