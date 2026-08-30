# Teacher API Documentation

This document provides details on how to use the Teacher API endpoints for mobile app integration.

## Authentication

### Login

This endpoint authenticates a teacher and returns a bearer token for subsequent API calls.

**URL**: `/api/teacher/login`

**Method**: `POST`

**Content-Type**: `application/json`

**Request Body**:
```json
{
  "employee_id": "TCH-12345",
  "password": "your_password"
}
```

**Success Response**:
```json
{
  "success": true,
  "message": "Login successful",
  "token": "your_bearer_token_here",
  "teacher": {
    "id": 1,
    "name": "John Doe",
    "email": "john.doe@example.com",
    "employee_id": "TCH-12345",
    "school_id": 1,
    "profile_image": "path/to/image.jpg",
    "subject": "Mathematics"
  }
}
```

**Error Response**:
```json
{
  "success": false,
  "message": "Invalid credentials",
  "error_code": "INVALID_CREDENTIALS"
}
```

## Protected Routes

The following routes require authentication. Include the bearer token in the Authorization header:

```
Authorization: Bearer your_token_here
```

### Get Teacher Profile

Retrieves the authenticated teacher's profile information.

**URL**: `/api/teacher/profile`

**Method**: `GET`

**Success Response**:
```json
{
  "success": true,
  "teacher": {
    "id": 1,
    "name": "John Doe",
    "email": "john.doe@example.com",
    "employee_id": "TCH-12345",
    "school_id": 1,
    "profile_image": "path/to/image.jpg",
    "subject": "Mathematics",
    "gender": "Male",
    "primary_contact": "+1234567890",
    "date_of_joining": "2022-01-15",
    "qualification": "M.Sc. Mathematics",
    "status": "active"
  }
}
```

**Error Response**:
```json
{
  "success": false,
  "message": "Unauthorized. Invalid or missing token.",
  "error_code": "UNAUTHORIZED"
}
```

### Logout

Invalidates the current bearer token.

**URL**: `/api/teacher/logout`

**Method**: `POST`

**Success Response**:
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

**Error Response**:
```json
{
  "success": false,
  "message": "Unauthorized. Invalid or missing token.",
  "error_code": "UNAUTHORIZED"
}
```

## Error Codes

- `INVALID_CREDENTIALS`: The provided employee ID or password is incorrect
- `UNAUTHORIZED`: The request lacks valid authentication credentials
- `NOT_A_TEACHER`: The authenticated user is not a teacher
- `PROFILE_NOT_FOUND`: Teacher profile could not be found
- `SERVER_ERROR`: An unexpected server error occurred

## Testing

You can use the provided shell script to test the login endpoint:

```bash
./teacher_login_curl.sh "TCH-12345" "your_password"
```

To test the profile endpoint:

```bash
curl -X GET "http://localhost:8000/api/teacher/profile" \
  -H "Authorization: Bearer your_token_here" \
  -H "Accept: application/json"
```

To test the logout endpoint:

```bash
curl -X POST "http://localhost:8000/api/teacher/logout" \
  -H "Authorization: Bearer your_token_here" \
  -H "Accept: application/json"
```

## Timetable API

### Get Teacher's Complete Timetable

Retrieves the complete teaching schedule for the authenticated teacher.

**URL**: `/api/teacher/timetable`

**Method**: `GET`

**Authentication**: Required (Bearer Token)

**Success Response**:
```json
{
  "success": true,
  "data": {
    "timetable": {
      "Monday": [
        {
          "id": 1,
          "start_time": "08:00:00",
          "end_time": "08:45:00",
          "period_type": "regular",
          "subject": {
            "id": 1,
            "name": "Mathematics"
          },
          "class": {
            "name": "Class 10",
            "section": "A"
          }
        },
        {
          "id": 2,
          "start_time": "09:00:00",
          "end_time": "09:45:00",
          "period_type": "regular",
          "subject": {
            "id": 1,
            "name": "Mathematics"
          },
          "class": {
            "name": "Class 9",
            "section": "B"
          }
        }
      ],
      "Tuesday": [
        {
          "id": 3,
          "start_time": "10:00:00",
          "end_time": "10:45:00",
          "period_type": "regular",
          "subject": {
            "id": 1,
            "name": "Mathematics"
          },
          "class": {
            "name": "Class 8",
            "section": "C"
          }
        }
      ]
    },
    "classes": [
      {
        "class_name": "Class 10",
        "section_name": "A",
        "timetable_id": 1
      },
      {
        "class_name": "Class 9",
        "section_name": "B",
        "timetable_id": 2
      },
      {
        "class_name": "Class 8",
        "section_name": "C",
        "timetable_id": 3
      }
    ]
  }
}
```

**Error Response**:
```json
{
  "success": false,
  "message": "Unauthorized. Invalid or missing token.",
  "error_code": "UNAUTHORIZED"
}
```

### Get Today's Timetable

Retrieves the teaching schedule for the current day for the authenticated teacher.

**URL**: `/api/teacher/timetable/today`

**Method**: `GET`

**Authentication**: Required (Bearer Token)

**Success Response**:
```json
{
  "success": true,
  "data": {
    "day": "Monday",
    "date": "2023-06-12",
    "periods": [
      {
        "id": 1,
        "start_time": "08:00:00",
        "end_time": "08:45:00",
        "period_type": "regular",
        "subject": {
          "id": 1,
          "name": "Mathematics"
        },
        "class": {
          "name": "Class 10",
          "section": "A"
        }
      },
      {
        "id": 2,
        "start_time": "09:00:00",
        "end_time": "09:45:00",
        "period_type": "regular",
        "subject": {
          "id": 1,
          "name": "Mathematics"
        },
        "class": {
          "name": "Class 9",
          "section": "B"
        }
      }
    ]
  }
}
```

**Error Response**:
```json
{
  "success": false,
  "message": "Unauthorized. Invalid or missing token.",
  "error_code": "UNAUTHORIZED"
}
```

### Get Class Timetable

Retrieves the complete timetable for a specific class-section that the teacher teaches.

**URL**: `/api/teacher/timetable/class/{timetableId}`

**Method**: `GET`

**Authentication**: Required (Bearer Token)

**URL Parameters**:
- `timetableId` - The ID of the timetable to retrieve details for

**Success Response**:
```json
{
  "success": true,
  "data": {
    "class": "Class 10",
    "section": "A",
    "timetable": {
      "Monday": [
        {
          "id": 1,
          "start_time": "08:00:00",
          "end_time": "08:45:00",
          "period_type": "regular",
          "subject": {
            "id": 1,
            "name": "Mathematics"
          },
          "teacher": {
            "id": 5,
            "name": "John Doe",
            "is_you": true
          }
        },
        {
          "id": 2,
          "start_time": "09:00:00",
          "end_time": "09:45:00",
          "period_type": "regular",
          "subject": {
            "id": 2,
            "name": "Science"
          },
          "teacher": {
            "id": 6,
            "name": "Jane Smith",
            "is_you": false
          }
        },
        {
          "id": 3,
          "start_time": "10:00:00",
          "end_time": "10:30:00",
          "period_type": "break",
          "subject": "Lunch Break",
          "teacher": null
        }
      ],
      "Tuesday": [
        {
          "id": 4,
          "start_time": "08:00:00",
          "end_time": "08:45:00",
          "period_type": "regular",
          "subject": {
            "id": 1,
            "name": "Mathematics"
          },
          "teacher": {
            "id": 5,
            "name": "John Doe",
            "is_you": true
          }
        }
      ]
    }
  }
}
```

**Error Responses**:

1. Unauthorized (401):
```json
{
  "success": false,
  "message": "Unauthorized. Invalid or missing token.",
  "error_code": "UNAUTHORIZED"
}
```

2. Not a teacher (403):
```json
{
  "success": false,
  "message": "Unauthorized. User is not a teacher.",
  "error_code": "NOT_A_TEACHER"
}
```

3. Timetable not found (404):
```json
{
  "success": false,
  "message": "Timetable not found",
  "error_code": "TIMETABLE_NOT_FOUND"
}
```

4. Not authorized to view this timetable (403):
```json
{
  "success": false,
  "message": "You are not assigned to teach in this class",
  "error_code": "NOT_AUTHORIZED"
}
```

5. Server error (500):
```json
{
  "success": false,
  "message": "An error occurred while fetching the class timetable",
  "error_code": "SERVER_ERROR"
}
``` 