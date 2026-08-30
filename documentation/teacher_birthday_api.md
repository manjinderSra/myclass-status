# Teacher Birthday API Documentation

This document outlines the API endpoints available for teacher birthdays in the school management system.

## Authentication

All API endpoints require authentication using a Bearer token. Include the following header in all requests:

```
Authorization: Bearer {your_token}
```

## Endpoints

### 1. Get All Teacher Birthdays

Retrieves birthdays of all teachers from the same school.

- **URL:** `/api/teacher/birthdays/all`
- **Method:** `GET`
- **Auth Required:** Yes
- **Permissions Required:** Teacher role

#### Success Response

- **Code:** 200
- **Content:**
```json
{
    "success": true,
    "data": {
        "today": "2023-05-15",
        "birthdays_count": 10,
        "birthdays": [
            {
                "id": 1,
                "employee_id": "T001",
                "name": "John Doe",
                "subject": {
                    "id": 5,
                    "name": "Mathematics"
                },
                "dob": "1985-05-15",
                "birthday_month": "May",
                "birthday_day": "15",
                "is_today": true,
                "profile_image": "https://example.com/storage/teachers/profile/1.jpg"
            },
            // More teacher records...
        ]
    }
}
```

### 2. Get My Birthday

Retrieves the authenticated teacher's own birthday details.

- **URL:** `/api/teacher/birthdays/my`
- **Method:** `GET`
- **Auth Required:** Yes
- **Permissions Required:** Teacher role

#### Success Response

- **Code:** 200
- **Content:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "employee_id": "T001",
        "name": "John Doe",
        "subject": {
            "id": 5,
            "name": "Mathematics"
        },
        "dob": "1985-05-15",
        "birthday_month": "May",
        "birthday_day": "15",
        "is_today": true,
        "profile_image": "https://example.com/storage/teachers/profile/1.jpg"
    }
}
```

### 3. Get Same Subject Teachers' Birthdays

Retrieves birthdays of teachers who teach the same subject as the authenticated teacher.

- **URL:** `/api/teacher/birthdays/subject`
- **Method:** `GET`
- **Auth Required:** Yes
- **Permissions Required:** Teacher role

#### Success Response

- **Code:** 200
- **Content:**
```json
{
    "success": true,
    "data": {
        "subject": {
            "id": 5,
            "name": "Mathematics"
        },
        "today": "2023-05-15",
        "birthdays_count": 3,
        "birthdays": [
            {
                "id": 1,
                "employee_id": "T001",
                "name": "John Doe",
                "subject": {
                    "id": 5,
                    "name": "Mathematics"
                },
                "dob": "1985-05-15",
                "birthday_month": "May",
                "birthday_day": "15",
                "is_today": true,
                "profile_image": "https://example.com/storage/teachers/profile/1.jpg"
            },
            // More teacher records...
        ]
    }
}
```

### 4. Get Teaching Classes Birthdays

Retrieves birthdays of both students and teachers from the classes the authenticated teacher teaches.

- **URL:** `/api/teacher/birthdays/teaching-classes`
- **Method:** `GET`
- **Auth Required:** Yes
- **Permissions Required:** Teacher role

#### Success Response

- **Code:** 200
- **Content:**
```json
{
    "success": true,
    "data": {
        "classes": ["Class 9A", "Class 10B"],
        "today": "2023-05-15",
        "students_count": 45,
        "teachers_count": 8,
        "birthdays_count": 53,
        "birthdays": [
            {
                "id": 101,
                "student_id": "S101",
                "name": "Jane Smith",
                "type": "student",
                "class": "Class 9A",
                "section": "A",
                "dob": "2008-05-15",
                "birthday_month": "May",
                "birthday_day": "15",
                "is_today": true,
                "profile_image": "https://example.com/storage/students/profile/101.jpg"
            },
            {
                "id": 5,
                "employee_id": "T005",
                "name": "Robert Brown",
                "type": "teacher",
                "subject": {
                    "id": 3,
                    "name": "Science"
                },
                "dob": "1982-06-20",
                "birthday_month": "June",
                "birthday_day": "20",
                "is_today": false,
                "profile_image": "https://example.com/storage/teachers/profile/5.jpg"
            },
            // More student and teacher records...
        ]
    }
}
```

## Error Responses

### Unauthorized

- **Code:** 401
- **Content:**
```json
{
    "success": false,
    "message": "Unauthorized. Invalid or missing token.",
    "error_code": "UNAUTHORIZED"
}
```

### Not a Teacher

- **Code:** 403
- **Content:**
```json
{
    "success": false,
    "message": "Unauthorized. User is not a teacher.",
    "error_code": "NOT_A_TEACHER"
}
```

### Profile Not Found

- **Code:** 404
- **Content:**
```json
{
    "success": false,
    "message": "Teacher profile not found",
    "error_code": "PROFILE_NOT_FOUND"
}
```

### No Classes Assigned

- **Code:** 404
- **Content:**
```json
{
    "success": false,
    "message": "Teacher does not teach any classes",
    "error_code": "NO_CLASSES_ASSIGNED"
}
```

### Server Error

- **Code:** 500
- **Content:**
```json
{
    "success": false,
    "message": "An unexpected error occurred while retrieving birthdays",
    "error_code": "SERVER_ERROR"
}
``` 