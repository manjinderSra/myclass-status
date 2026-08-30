@include('errors.minimal', [
    'status' => 403,
    'title' => 'You do not have access to this page.',
    'message' => 'Your account is signed in, but it does not have permission to view this resource.',
    'icon' => '!',
    'cardTitle' => 'Access restricted',
    'cardMessage' => 'Contact your school administrator if you believe you should have access.',
    'showBackButton' => true,
])
