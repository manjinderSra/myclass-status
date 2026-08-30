@include('errors.minimal', [
    'status' => $exception->getStatusCode(),
    'title' => 'This request could not be completed.',
    'message' => 'Please check the address or return to the previous page and try again.',
    'icon' => '!',
    'cardTitle' => 'Request unavailable',
    'cardMessage' => 'The requested action is not available in its current form.',
    'showBackButton' => true,
])
