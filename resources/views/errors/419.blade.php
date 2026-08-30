@include('errors.minimal', [
    'status' => 419,
    'title' => 'Your session has expired.',
    'message' => 'For your security, this page expired after being inactive. Return to the previous page, refresh it, and try again.',
    'icon' => '↻',
    'cardTitle' => 'Session expired',
    'cardMessage' => 'No changes were submitted. Refresh the form before trying again.',
    'showBackButton' => true,
])
