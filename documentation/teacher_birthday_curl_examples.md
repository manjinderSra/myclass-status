# Teacher Birthday API - cURL Examples

This document provides cURL examples for testing the Teacher Birthday API endpoints.

## Authentication

All endpoints require a valid bearer token. You can obtain a token by logging in:

```bash
curl --location 'http://localhost:8000/api/teacher/login' \
--header 'Content-Type: application/json' \
--header 'Accept: application/json' \
--data-raw '{
    "employee_id": "YOUR_EMPLOYEE_ID",
    "password": "YOUR_PASSWORD"
}'
```

## Endpoints

### Get All Teacher Birthdays

```bash
curl --location 'http://localhost:8000/api/teacher/birthdays' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer YOUR_TOKEN_HERE'
```

### Get Teacher's Own Birthday

```bash
curl --location 'http://localhost:8000/api/teacher/birthday/my' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer YOUR_TOKEN_HERE'
```

### Get Subject Colleague Birthdays

```bash
curl --location 'http://localhost:8000/api/teacher/birthday/subject-colleagues' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer YOUR_TOKEN_HERE'
```

### Get Teaching Classes Birthdays

```bash
curl --location 'http://localhost:8000/api/teacher/birthdays/teaching-classes' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer YOUR_TOKEN_HERE'
```

## Testing Tips

1. Replace `YOUR_TOKEN_HERE` with the actual token received from the login endpoint.
2. Replace `localhost:8000` with your actual API server URL.
3. You can save the response to a file by adding `--output response.json` to any command.
4. To see detailed request/response information, add the `-v` (verbose) flag.

Example with verbose output:

```bash
curl -v --location 'http://localhost:8000/api/teacher/birthdays' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer YOUR_TOKEN_HERE'
```

This endpoint returns birthdays of both students and teachers from classes that the authenticated teacher teaches. It's useful for teachers to keep track of birthdays of students in their classes as well as other teachers who teach the same classes. 