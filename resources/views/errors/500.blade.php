@include('errors.minimal', [
    'status' => 500,
    'title' => 'Something went wrong on our side.',
    'message' => 'The request could not be completed. The issue has been recorded, so please try again shortly.',
    'icon' => '!',
    'cardTitle' => 'Unexpected error',
    'cardMessage' => 'Your information is safe. Return home or try the request again later.',
    'showBackButton' => true,
])
