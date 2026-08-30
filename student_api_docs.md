# Student Mobile App API Documentation

This document provides details about the API endpoints available for the student mobile application.

## Base URL

```
http://YOUR_SERVER_IP:PORT/api
```

## Authentication

All API requests (except login) require authentication using a bearer token. Include the token in the request header:

```
Authorization: Bearer YOUR_TOKEN_HERE
```

## Endpoints

### 1. Student Login

Authenticates a student and returns a token for subsequent API calls.

**URL:** `/student/login`  
**Method:** `POST`  
**Content-Type:** `application/json`

**Request Body:**
```json
{
  "student_id": "STU-ABCD1234",
  "password": "your_password"
}
```

**Successful Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "warning": null,
  "data": {
    "token": "ACCESS_TOKEN_STRING",
    "student_id": "STU-ABCD1234",
    "name": "John Doe",
    "email": "john.doe@example.com",
    "admission_number": "ADM12345",
    "class": "Class 10",
    "section": "A",
    "profile_image": "http://example.com/storage/profile/image.jpg",
    "school_id": 1,
    "school": {
      "name": "ABC School",
      "logo": "http://example.com/storage/logo.jpg",
      "tagline": "Excellence in Education"
    }
  }
}
```

**Error Responses:**

1. Student not found (404):
```json
{
  "success": false,
  "message": "Student not found",
  "error_code": "STUDENT_NOT_FOUND"
}
```

2. Invalid password (401):
```json
{
  "success": false,
  "message": "Invalid password",
  "error_code": "INVALID_PASSWORD"
}
```

3. Inactive account (403):
```json
{
  "success": false,
  "message": "Student account is inactive",
  "error_code": "ACCOUNT_INACTIVE"
}
```

4. Validation error (422):
```json
{
  "success": false,
  "message": "Validation failed",
  "error_code": "VALIDATION_ERROR",
  "errors": {
    "student_id": ["The student id field is required."]
  }
}
```

5. Server error (500):
```json
{
  "success": false,
  "message": "An unexpected error occurred",
  "error_code": "SERVER_ERROR"
}
```

### 2. Student Logout

Logs out a student by invalidating the current token.

**URL:** `/student/logout`  
**Method:** `POST`  
**Authentication:** Required

**Successful Response:**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "An error occurred while logging out",
  "error_code": "LOGOUT_ERROR"
}
```

### 3. Student Profile

Retrieves the authenticated student's profile information with complete details.

**URL:** `/student/profile`  
**Method:** `GET`  
**Authentication:** Required

**Successful Response:**
```json
{
  "success": true,
  "data": {
    "student_id": "STU-ABCD1234",
    "admission_number": "ADM12345",
    "name": "John Doe",
    "email": "john.doe@example.com",
    "phone": "1234567890",
    "class": "Class 10",
    "section": "A",
    "profile_image": "http://example.com/storage/profile/image.jpg",
    "gender": "male",
    "dob": "2005-05-15",
    "blood_group": "O+",
    "address": "123 School Street, City",
    "school_id": 1,
    "status": "active",
    "academic_year": "2023-2024",
    "school": {
      "name": "ABC School",
      "logo": "http://example.com/storage/logo.jpg",
      "tagline": "Excellence in Education",
      "website": "https://abcschool.edu",
      "address": "123 Education Road, School District"
    },
    "parent_details": {
      "father_name": "Robert Doe",
      "father_phone": "9876543210",
      "father_email": "robert.doe@example.com",
      "mother_name": "Jane Doe",
      "mother_phone": "9876543211",
      "mother_email": "jane.doe@example.com",
      "guardian_name": null,
      "guardian_phone": null,
      "guardian_email": null
    }
  }
}
```

**Error Responses:**

1. Authentication error (401):
```json
{
  "message": "Unauthenticated."
}
```

2. Server error (500):
```json
{
  "success": false,
  "message": "An unexpected error occurred while retrieving the profile",
  "error_code": "SERVER_ERROR"
}
```

### 4. Student Announcements

Retrieves all announcements intended for students from the student's school.

**URL:** `/student/announcements`  
**Method:** `GET`  
**Authentication:** Required

**Successful Response:**
```json
{
  "success": true,
  "data": {
    "announcements": [
      {
        "id": 1,
        "title": "Exam Schedule for Term 2",
        "message": "The final exams for Term 2 will begin on December 5th, 2023. Please find the detailed schedule attached to the notice board.",
        "publish_date": "2023-11-25",
        "created_at": "2023-11-24 14:30:00"
      },
      {
        "id": 2,
        "title": "Annual Sports Day",
        "message": "The Annual Sports Day will be held on November 15th, 2023. All students are required to participate in at least one event.",
        "publish_date": "2023-11-01",
        "created_at": "2023-10-30 10:15:00"
      }
    ],
    "count": 2
  }
}
```

**Error Responses:**

1. Authentication error (401):
```json
{
  "message": "Unauthenticated."
}
```

2. Student not found (404):
```json
{
  "success": false,
  "message": "Authenticated student not found",
  "error_code": "STUDENT_NOT_FOUND"
}
```

3. Server error (500):
```json
{
  "success": false,
  "message": "Failed to fetch announcements: [error message]",
  "error_code": "ANNOUNCEMENT_FETCH_ERROR"
}
```

### 5. Student Details

Retrieves comprehensive information about the authenticated student using the token. This endpoint provides complete student details including personal information, class details, and parent information.

**URL:** `/student/details`  
**Method:** `GET`  
**Authentication:** Required

**Successful Response:**
```json
{
  "success": true,
  "data": {
    "token": "ACCESS_TOKEN_STRING",
    "student_id": "STU-ABCD1234",
    "name": "John Doe",
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@example.com",
    "admission_number": "ADM12345",
    "gender": "male",
    "dob": "2005-05-15",
    "phone": "1234567890",
    "blood_group": "O+",
    "class": "Class 10",
    "class_id": 15,
    "section": "A",
    "section_id": 3,
    "roll_number": "25",
    "profile_image": "http://example.com/storage/profile/image.jpg",
    "school_id": 1,
    "academic_year": "2023-2024",
    "status": "active",
    "house": "Blue House",
    "religion": "Christianity",
    "category": "General",
    "school": {
      "name": "ABC School",
      "logo": "http://example.com/storage/logo.jpg",
      "tagline": "Excellence in Education"
    },
    "parent_details": {
      "father_name": "Robert Doe",
      "father_phone": "9876543210",
      "mother_name": "Jane Doe",
      "mother_phone": "9876543211"
    }
  }
}
```

**Error Responses:**

1. Authentication error (401):
```json
{
  "message": "Unauthenticated."
}
```

2. Student not found (404):
```json
{
  "success": false,
  "message": "Authenticated student not found",
  "error_code": "STUDENT_NOT_FOUND"
}
```

3. Server error (500):
```json
{
  "success": false,
  "message": "An error occurred while retrieving student details",
  "error_code": "SERVER_ERROR"
}
```

### 6. Complete Timetable

Retrieves the complete timetable for the authenticated student's class.

**URL:** `/student/timetable`  
**Method:** `GET`  
**Authentication:** Required

**Successful Response:**
```json
{
  "success": true,
  "data": {
    "class": "Class 10",
    "section": "A",
    "periods": [
      {
        "id": 1,
        "day": "Monday",
        "start_time": "09:00:00",
        "end_time": "09:45:00",
        "period_type": "regular",
        "subject": {
          "id": 5,
          "name": "Mathematics"
        },
        "teacher": {
          "id": 12,
          "name": "John Smith"
        }
      },
      {
        "id": 2,
        "day": "Monday",
        "start_time": "09:45:00",
        "end_time": "10:30:00",
        "period_type": "extra",
        "name": "Lunch Break"
      }
    ]
  }
}
```

**Error Responses:**

1. No class assigned (404):
```json
{
  "success": false,
  "message": "Student not assigned to any class or section",
  "error_code": "NO_CLASS_ASSIGNED"
}
```

2. Timetable not found (404):
```json
{
  "success": false,
  "message": "No timetable found for this class",
  "error_code": "TIMETABLE_NOT_FOUND"
}
```

3. Server error (500):
```json
{
  "success": false,
  "message": "An unexpected error occurred while retrieving the timetable",
  "error_code": "SERVER_ERROR"
}
```

### 7. Today's Timetable

Retrieves the timetable for the current day only.

**URL:** `/student/timetable/today`  
**Method:** `GET`  
**Authentication:** Required

**Successful Response:**
```json
{
  "success": true,
  "data": {
    "class": "Class 10",
    "section": "A",
    "day": "Monday",
    "date": "2023-06-12",
    "periods": [
      {
        "id": 1,
        "day": "Monday",
        "start_time": "09:00:00",
        "end_time": "09:45:00",
        "period_type": "regular",
        "subject": {
          "id": 5,
          "name": "Mathematics"
        },
        "teacher": {
          "id": 12,
          "name": "John Smith"
        }
      },
      {
        "id": 2,
        "day": "Monday",
        "start_time": "09:45:00",
        "end_time": "10:30:00",
        "period_type": "extra",
        "name": "Lunch Break"
      }
    ]
  }
}
```

**Error Responses:**

1. No class assigned (404):
```json
{
  "success": false,
  "message": "Student not assigned to any class or section",
  "error_code": "NO_CLASS_ASSIGNED"
}
```

2. Timetable not found (404):
```json
{
  "success": false,
  "message": "No timetable found for this class",
  "error_code": "TIMETABLE_NOT_FOUND"
}
```

3. Server error (500):
```json
{
  "success": false,
  "message": "An unexpected error occurred while retrieving today's timetable",
  "error_code": "SERVER_ERROR"
}
```

### 8. Weekly Timetable

Retrieves the full week timetable organized by days of the week.

**URL:** `/student/timetable/weekly`  
**Method:** `GET`  
**Authentication:** Required

**Successful Response:**
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
          "start_time": "09:00:00",
          "end_time": "09:45:00",
          "period_type": "regular",
          "subject": {
            "id": 5,
            "name": "Mathematics"
          },
          "teacher": {
            "id": 12,
            "name": "John Smith"
          }
        },
        {
          "id": 2,
          "start_time": "09:45:00",
          "end_time": "10:30:00",
          "period_type": "extra",
          "name": "Lunch Break"
        }
      ],
      "Tuesday": [
        {
          "id": 3,
          "start_time": "09:00:00",
          "end_time": "09:45:00",
          "period_type": "regular",
          "subject": {
            "id": 6,
            "name": "Science"
          },
          "teacher": {
            "id": 13,
            "name": "Jane Doe"
          }
        }
      ]
    }
  }
}
```

**Error Responses:**

1. No class assigned (404):
```json
{
  "success": false,
  "message": "Student not assigned to any class or section",
  "error_code": "NO_CLASS_ASSIGNED"
}
```

2. Timetable not found (404):
```json
{
  "success": false,
  "message": "No timetable found for this class",
  "error_code": "TIMETABLE_NOT_FOUND"
}
```

3. Server error (500):
```json
{
  "success": false,
  "message": "An unexpected error occurred while retrieving the weekly timetable",
  "error_code": "SERVER_ERROR"
}
```

### 9. Teacher List from Timetable

Retrieves the list of teachers who teach the student's class based on the timetable.

**URL:** `/student/teachers`  
**Method:** `GET`  
**Authentication:** Required

**Successful Response:**
```json
{
  "success": true,
  "data": {
    "class": "Class 10",
    "section": "A",
    "teachers_count": 2,
    "teachers": [
      {
        "id": 12,
        "employee_id": "EMP-12345",
        "name": "John Smith",
        "email": "john.smith@example.com",
        "gender": "male",
        "contact": "9876543210",
        "profile_image": "http://example.com/storage/teachers/john.jpg",
        "qualification": "M.Sc, B.Ed",
        "experience": "5 years",
        "subjects": [
          {
            "id": 5,
            "name": "Mathematics"
          }
        ]
      },
      {
        "id": 13,
        "employee_id": "EMP-12346",
        "name": "Jane Doe",
        "email": "jane.doe@example.com",
        "gender": "female",
        "contact": "9876543211",
        "profile_image": "http://example.com/storage/teachers/jane.jpg",
        "qualification": "M.A, B.Ed",
        "experience": "7 years",
        "subjects": [
          {
            "id": 6,
            "name": "English"
          },
          {
            "id": 7,
            "name": "History"
          }
        ]
      }
    ]
  }
}
```

**Error Responses:**

1. No class assigned (404):
```json
{
  "success": false,
  "message": "Student not assigned to any class or section",
  "error_code": "NO_CLASS_ASSIGNED"
}
```

2. Timetable not found (404):
```json
{
  "success": false,
  "message": "No timetable found for this class",
  "error_code": "TIMETABLE_NOT_FOUND"
}
```

3. Server error (500):
```json
{
  "success": false,
  "message": "An unexpected error occurred while retrieving the teachers list",
  "error_code": "SERVER_ERROR"
}
```

### 10. Teacher Details

Retrieves detailed information about a specific teacher including their schedule in the student's class.

**URL:** `/student/teachers/{id}`  
**Method:** `GET`  
**Authentication:** Required

**URL Parameters:**
- `id` - The ID of the teacher to retrieve details for

**Successful Response:**
```json
{
  "success": true,
  "data": {
    "id": 12,
    "employee_id": "EMP-12345",
    "name": "John Smith",
    "first_name": "John",
    "last_name": "Smith",
    "email": "john.smith@example.com",
    "gender": "male",
    "primary_contact": "9876543210",
    "date_of_birth": "1985-05-15",
    "date_of_joining": "2018-06-10",
    "profile_image": "http://example.com/storage/teachers/john.jpg",
    "blood_group": "O+",
    "qualification": "M.Sc, B.Ed",
    "work_experience": "5 years",
    "languages_known": "English, Hindi",
    "subject": {
      "id": 5,
      "name": "Mathematics"
    },
    "schedule": {
      "Monday": [
        {
          "id": 1,
          "start_time": "09:00:00",
          "end_time": "09:45:00",
          "subject": {
            "id": 5,
            "name": "Mathematics"
          }
        }
      ],
      "Wednesday": [
        {
          "id": 8,
          "start_time": "11:00:00",
          "end_time": "11:45:00",
          "subject": {
            "id": 5,
            "name": "Mathematics"
          }
        }
      ]
    }
  }
}
```

**Error Responses:**

1. Teacher not found (404):
```json
{
  "success": false,
  "message": "Teacher not found",
  "error_code": "TEACHER_NOT_FOUND"
}
```

2. Server error (500):
```json
{
  "success": false,
  "message": "An error occurred while retrieving teacher details",
  "error_code": "SERVER_ERROR"
}
```

### 11. Student Birthdays

Retrieves a list of all student birthdays from the same school.

**URL:** `/student/birthdays`  
**Method:** `GET`  
**Authentication:** Required

**Successful Response:**
```json
{
  "success": true,
  "data": {
    "today": "2023-06-12",
    "birthdays_count": 5,
    "birthdays": [
      {
        "id": 15,
        "student_id": "STU12345",
        "name": "John Doe",
        "class": "Class 10",
        "section": "A",
        "dob": "2005-01-15",
        "birthday_month": "January",
        "birthday_day": "15",
        "is_today": false,
        "profile_image": "http://example.com/storage/students/john.jpg"
      },
      {
        "id": 18,
        "student_id": "STU12348",
        "name": "Jane Smith",
        "class": "Class 10",
        "section": "B",
        "dob": "2005-06-12",
        "birthday_month": "June",
        "birthday_day": "12",
        "is_today": true,
        "profile_image": "http://example.com/storage/students/jane.jpg"
      },
      {
        "id": 22,
        "student_id": "STU12352",
        "name": "Robert Johnson",
        "class": "Class 11",
        "section": "A",
        "dob": "2004-06-25",
        "birthday_month": "June",
        "birthday_day": "25",
        "is_today": false,
        "profile_image": "http://example.com/storage/students/robert.jpg"
      },
      {
        "id": 25,
        "student_id": "STU12355",
        "name": "Alice Williams",
        "class": "Class 9",
        "section": "C",
        "dob": "2006-08-15",
        "birthday_month": "August",
        "birthday_day": "15",
        "is_today": false,
        "profile_image": "http://example.com/storage/students/alice.jpg"
      },
      {
        "id": 28,
        "student_id": "STU12358",
        "name": "David Brown",
        "class": "Class 9",
        "section": "A",
        "dob": "2006-12-24",
        "birthday_month": "December",
        "birthday_day": "24",
        "is_today": false,
        "profile_image": "http://example.com/storage/students/david.jpg"
      }
    ]
  }
}
```

**Error Responses:**

1. Authentication error (401):
```json
{
  "message": "Unauthenticated."
}
```

2. Server error (500):
```json
{
  "success": false,
  "message": "An unexpected error occurred while retrieving all birthdays",
  "error_code": "SERVER_ERROR"
}
```

### 12. Student's Own Birthday

Retrieves the authenticated student's own birthday details with age and countdown information.

**URL:** `/student/birthday/my`  
**Method:** `GET`  
**Authentication:** Required

**Successful Response:**
```json
{
  "success": true,
  "data": {
    "student_id": "STU12345",
    "name": "John Doe",
    "dob": "2005-06-15",
    "birthday_month": "June",
    "birthday_day": "15",
    "age": 18,
    "next_age": 19,
    "is_today": false,
    "days_until_next_birthday": 3,
    "profile_image": "http://example.com/storage/students/john.jpg"
  }
}
```

**Error Responses:**

1. DOB not available (404):
```json
{
  "success": false,
  "message": "Birth date not available for this student",
  "error_code": "DOB_NOT_AVAILABLE"
}
```

2. Server error (500):
```json
{
  "success": false,
  "message": "An unexpected error occurred while retrieving birthday information",
  "error_code": "SERVER_ERROR"
}
```

### 13. Class Birthdays with Teachers

Retrieves birthdays for all students in the same class and their teachers.

**URL:** `/student/birthday/class`  
**Method:** `GET`  
**Authentication:** Required

**Successful Response:**
```json
{
  "success": true,
  "data": {
    "class": "Class 10",
    "section": "A",
    "today": "2023-06-12",
    "students_count": 3,
    "teachers_count": 2,
    "birthdays_count": 5,
    "birthdays": [
      {
        "id": 15,
        "student_id": "STU12345",
        "name": "John Doe",
        "type": "student",
        "dob": "2005-01-15",
        "birthday_month": "January",
        "birthday_day": "15",
        "is_today": false,
        "profile_image": "http://example.com/storage/students/john.jpg"
      },
      {
        "id": 12,
        "employee_id": "EMP-12345",
        "name": "David Smith",
        "type": "teacher",
        "dob": "1980-03-22",
        "birthday_month": "March",
        "birthday_day": "22",
        "is_today": false,
        "profile_image": "http://example.com/storage/teachers/david.jpg"
      },
      {
        "id": 18,
        "student_id": "STU12348",
        "name": "Jane Smith",
        "type": "student",
        "dob": "2005-06-12",
        "birthday_month": "June",
        "birthday_day": "12",
        "is_today": true,
        "profile_image": "http://example.com/storage/students/jane.jpg"
      },
      {
        "id": 13,
        "employee_id": "EMP-12346",
        "name": "Sarah Johnson",
        "type": "teacher",
        "dob": "1985-09-05",
        "birthday_month": "September",
        "birthday_day": "05",
        "is_today": false,
        "profile_image": "http://example.com/storage/teachers/sarah.jpg"
      },
      {
        "id": 22,
        "student_id": "STU12352",
        "name": "Robert Johnson",
        "type": "student",
        "dob": "2005-12-25",
        "birthday_month": "December",
        "birthday_day": "25",
        "is_today": false,
        "profile_image": "http://example.com/storage/students/robert.jpg"
      }
    ]
  }
}
```

**Error Responses:**

1. No class assigned (404):
```json
{
  "success": false,
  "message": "Student not assigned to any class or section",
  "error_code": "NO_CLASS_ASSIGNED"
}
```

2. Server error (500):
```json
{
  "success": false,
  "message": "An unexpected error occurred while retrieving class birthdays",
  "error_code": "SERVER_ERROR"
}
```

## Complaint Endpoints

### 14. Submit Complaint

Allows a student to submit a complaint to the school administration.

**URL:** `/student/complaints`  
**Method:** `POST`  
**Authentication:** Required  
**Content-Type:** `application/json`

**Request Body:**
```json
{
  "nature": "Academic Issue",
  "description": "I am having trouble understanding the algebra lessons. Would it be possible to arrange some extra classes or tutoring sessions?"
}
```

**Successful Response:**
```json
{
  "success": true,
  "message": "Complaint submitted successfully",
  "data": {
    "complaint_id": "COMP-202306-0001",
    "nature": "Academic Issue",
    "description": "I am having trouble understanding the algebra lessons. Would it be possible to arrange some extra classes or tutoring sessions?",
    "status": "pending",
    "created_at": "2023-06-09 14:30:25"
  }
}
```

**Error Responses:**

1. Validation error (422):
```json
{
  "success": false,
  "message": "Validation failed",
  "error_code": "VALIDATION_ERROR",
  "errors": {
    "nature": ["The nature field is required."],
    "description": ["The description field is required."]
  }
}
```

2. Server error (500):
```json
{
  "success": false,
  "message": "An unexpected error occurred while submitting the complaint",
  "error_code": "SERVER_ERROR"
}
```

### 15. My Complaints

Retrieves all complaints submitted by the authenticated student.

**URL:** `/student/complaints`  
**Method:** `GET`  
**Authentication:** Required

**Successful Response:**
```json
{
  "success": true,
  "data": {
    "complaints_count": 2,
    "complaints": [
      {
        "id": 1,
        "complaint_id": "COMP-202306-0001",
        "nature": "Academic Issue",
        "description": "I am having trouble understanding the algebra lessons. Would it be possible to arrange some extra classes or tutoring sessions?",
        "status": "resolved",
        "response": "We have arranged for extra algebra classes on Fridays after school. Please join these sessions for additional help.",
        "created_at": "2023-06-09 14:30:25",
        "resolved_at": "2023-06-10 09:15:40"
      },
      {
        "id": 2,
        "complaint_id": "COMP-202306-0002",
        "nature": "Library Access",
        "description": "I would like to request extended library hours during exam weeks.",
        "status": "pending",
        "response": null,
        "created_at": "2023-06-11 10:45:12",
        "resolved_at": null
      }
    ]
  }
}
```

**Error Responses:**

1. Server error (500):
```json
{
  "success": false,
  "message": "An unexpected error occurred while retrieving complaints",
  "error_code": "SERVER_ERROR"
}
```

### 16. Complaint Details

Retrieves detailed information about a specific complaint submitted by the student.

**URL:** `/student/complaints/{id}`  
**Method:** `GET`  
**Authentication:** Required

**URL Parameters:**
- `id` - The ID of the complaint to retrieve

**Successful Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "complaint_id": "COMP-202306-0001",
    "nature": "Academic Issue",
    "description": "I am having trouble understanding the algebra lessons. Would it be possible to arrange some extra classes or tutoring sessions?",
    "status": "resolved",
    "response": "We have arranged for extra algebra classes on Fridays after school. Please join these sessions for additional help.",
    "created_at": "2023-06-09 14:30:25",
    "updated_at": "2023-06-10 09:15:40",
    "resolved_at": "2023-06-10 09:15:40"
  }
}
```

**Error Responses:**

1. Complaint not found (404):
```json
{
  "success": false,
  "message": "Complaint not found",
  "error_code": "COMPLAINT_NOT_FOUND"
}
```

2. Server error (500):
```json
{
  "success": false,
  "message": "An unexpected error occurred while retrieving complaint details",
  "error_code": "SERVER_ERROR"
}
```

## Error Codes Reference

| Error Code | HTTP Status | Description |
|------------|-------------|-------------|
| STUDENT_NOT_FOUND | 404 | The requested student account was not found |
| INVALID_PASSWORD | 401 | The provided password is incorrect |
| ACCOUNT_INACTIVE | 403 | The student account is not active |
| VALIDATION_ERROR | 422 | Request validation failed |
| NO_CLASS_ASSIGNED | 404 | Student is not assigned to any class or section |
| TIMETABLE_NOT_FOUND | 404 | No timetable exists for the student's class |
| TEACHER_NOT_FOUND | 404 | The requested teacher was not found |
| DOB_NOT_AVAILABLE | 404 | Birth date not available for the student |
| COMPLAINT_NOT_FOUND | 404 | The requested complaint was not found |
| LOGOUT_ERROR | 500 | Error occurred during logout |
| SERVER_ERROR | 500 | Generic server error |

## Testing the API

A test script is provided (`student_login_curl.sh`) to help test the API endpoints.

To run the script:
```bash
./student_login_curl.sh
```

To start the server on a specific IP and port:
```bash
php artisan serve --host=192.168.1.93 --port=8000
```

## Teacher API Endpoints

### Teacher Login

Authenticates a teacher and returns a token.

- **URL**: `/api/teacher/login`
- **Method**: `POST`
- **Auth Required**: No
- **Request Body**:
  ```json
  {
    "employee_id": "T2023XXXX",
    "password": "password123"
  }
  ```
- **Success Response**:
  - **Code**: 200
  - **Content**:
    ```json
    {
      "success": true,
      "message": "Login successful",
      "token": "1|abcdefghijklmnopqrstuvwxyz123456",
      "teacher": {
        "id": 1,
        "name": "John Doe",
        "email": "john.teacher@example.com",
        "employee_id": "T2023XXXX",
        "school_id": 1,
        "school_name": "Example School",
        "profile_image": "http://example.com/storage/teachers/profile/image.jpg",
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
      }
    }
    ```
- **Error Response**:
  - **Code**: 401
  - **Content**:
    ```json
    {
      "success": false,
      "message": "Invalid credentials",
      "error_code": "INVALID_CREDENTIALS"
    }
    ```

### Get Teacher Profile

Retrieves the authenticated teacher's profile information.

- **URL**: `/api/teacher/profile`
- **Method**: `GET`
- **Auth Required**: Yes (Bearer Token)
- **Success Response**:
  - **Code**: 200
  - **Content**:
    ```json
    {
      "success": true,
      "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john.teacher@example.com",
        "employee_id": "T2023XXXX",
        "school_id": 1,
        "school_name": "Example School",
        "profile_image": "http://example.com/storage/teachers/profile/image.jpg",
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
      }
    }
    ```

### Change Teacher Password

Changes the password for the authenticated teacher.

- **URL**: `/api/teacher/change-password`
- **Method**: `POST`
- **Auth Required**: Yes (Bearer Token)
- **Request Body**:
  ```json
  {
    "current_password": "current_password",
    "new_password": "new_password",
    "new_password_confirmation": "new_password"
  }
  ```
- **Success Response**:
  - **Code**: 200
  - **Content**:
    ```json
    {
      "success": true,
      "message": "Password changed successfully"
    }
    ```
- **Error Response**:
  - **Code**: 422
  - **Content**:
    ```json
    {
      "success": false,
      "message": "Validation error",
      "errors": {
        "current_password": ["The current password is incorrect."],
        "new_password": ["The new password must be at least 8 characters."],
        "new_password_confirmation": ["The new password confirmation does not match."]
      },
      "error_code": "VALIDATION_ERROR"
    }
    ```

### Get Students Taught by Teacher

Retrieves all students that the authenticated teacher teaches.

- **URL**: `/api/teacher/students`
- **Method**: `GET`
- **Auth Required**: Yes (Bearer Token)
- **Success Response**:
  - **Code**: 200
  - **Content**:
    ```json
    {
      "success": true,
      "data": {
        "students_count": 25,
        "students": [
          {
            "id": 1,
            "student_id": "S2023001",
            "name": "Jane Smith",
            "class": "10",
            "section": "A",
            "profile_image": "http://example.com/storage/students/profile/image1.jpg"
          },
          {
            "id": 2,
            "student_id": "S2023002",
            "name": "John Smith",
            "class": "10",
            "section": "A",
            "profile_image": "http://example.com/storage/students/profile/image2.jpg"
          }
        ]
      }
    }
    ```

### Get Leave Applications

Retrieves leave applications submitted by students taught by the authenticated teacher.

- **URL**: `/api/teacher/leave-applications`
- **Method**: `GET`
- **Auth Required**: Yes (Bearer Token)
- **Query Parameters**:
  - `status` (optional): Filter by status (pending, approved, rejected)
- **Success Response**:
  - **Code**: 200
  - **Content**:
    ```json
    {
      "success": true,
      "data": {
        "leaves_count": 2,
        "leaves": [
          {
            "id": 1,
            "leave_id": "LEAVE-2023001",
            "student": {
              "id": 1,
              "student_id": "S2023001",
              "name": "Jane Smith",
              "class": "10",
              "section": "A",
              "profile_image": "http://example.com/storage/students/profile/image1.jpg"
            },
            "reason": "Medical",
            "description": "I need to visit the doctor for a regular check-up.",
            "from_date": "2023-05-10",
            "to_date": "2023-05-12",
            "days": 3,
            "status": "pending",
            "created_at": "2023-05-08 10:30:00"
          },
          {
            "id": 2,
            "leave_id": "LEAVE-2023002",
            "student": {
              "id": 2,
              "student_id": "S2023002",
              "name": "John Smith",
              "class": "10",
              "section": "A",
              "profile_image": "http://example.com/storage/students/profile/image2.jpg"
            },
            "reason": "Family Function",
            "description": "I need to attend my cousin's wedding ceremony.",
            "from_date": "2023-05-15",
            "to_date": "2023-05-16",
            "days": 2,
            "status": "approved",
            "created_at": "2023-05-12 11:45:00"
          }
        ]
      }
    }
    ```

### Get Leave Application Details

Retrieves details of a specific leave application.

- **URL**: `/api/teacher/leave-applications/{id}`
- **Method**: `GET`
- **Auth Required**: Yes (Bearer Token)
- **URL Parameters**:
  - `id`: The ID of the leave application
- **Success Response**:
  - **Code**: 200
  - **Content**:
    ```json
    {
      "success": true,
      "data": {
        "id": 1,
        "leave_id": "LEAVE-2023001",
        "student": {
          "id": 1,
          "student_id": "S2023001",
          "name": "Jane Smith",
          "class": "10",
          "section": "A",
          "email": "jane.student@example.com",
          "profile_image": "http://example.com/storage/students/profile/image1.jpg"
        },
        "reason": "Medical",
        "description": "I need to visit the doctor for a regular check-up.",
        "from_date": "2023-05-10",
        "to_date": "2023-05-12",
        "days": 3,
        "status": "pending",
        "attachment_url": "http://example.com/storage/leave_attachments/medical_certificate.pdf",
        "admin_remarks": null,
        "processed_by": null,
        "processed_at": null,
        "created_at": "2023-05-08 10:30:00"
      }
    }
    ```

### Update Leave Application Status

Updates the status of a leave application.

- **URL**: `/api/teacher/leave-applications/{id}/update-status`
- **Method**: `POST`
- **Auth Required**: Yes (Bearer Token)
- **URL Parameters**:
  - `id`: The ID of the leave application
- **Request Body**:
  ```json
  {
    "status": "approved",
    "admin_remarks": "Your leave application has been approved."
  }
  ```
- **Success Response**:
  - **Code**: 200
  - **Content**:
    ```json
    {
      "success": true,
      "message": "Leave application status updated successfully",
      "data": {
        "id": 1,
        "leave_id": "LEAVE-2023001",
        "status": "approved",
        "admin_remarks": "Your leave application has been approved.",
        "processed_at": "2023-05-09 14:25:00"
      }
    }
    ```
- **Error Response**:
  - **Code**: 422
  - **Content**:
    ```json
    {
      "success": false,
      "message": "Validation failed",
      "error_code": "VALIDATION_ERROR",
      "errors": {
        "status": ["The status field is required."],
        "admin_remarks": ["The admin remarks field is required when status is rejected."]
      }
    }
    ``` 