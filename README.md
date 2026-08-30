<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Student Transport API for Mobile App

## Authentication
All API endpoints are secured with Bearer token authentication. To access the API, you need to obtain an authentication token by logging in through the appropriate endpoint for your user type (student, parent).

## Endpoints
You can use either of these endpoints:
```
GET /api/transport/student
GET /api/student/transport
```

### Headers
```
Authorization: Bearer YOUR_TOKEN_HERE
Accept: application/json
```

### Description
This endpoint automatically uses the authenticated user's information to retrieve transport details. No additional parameters are required - just include the bearer token of the student or parent.

### Testing Mode
For testing purposes, the API now includes a relaxed authentication mode:
- If the token belongs to a student, it will use that student's transport details
- If the token belongs to a parent, it will find their student's transport details
- If the token belongs to another role but has a school_id, it will find a student with transport enabled
- As a last resort, it will return details for any student with transport enabled

### Response Format
```json
{
  "success": true,
  "transport_details": {
    "student": {
      "id": 1,
      "admission_number": "ADM123456",
      "student_id": "STD123456",
      "name": "John Smith"
    },
    "pickup_point": {
      "id": 1,
      "name": "Main Gate",
      "latitude": "28.6139",
      "longitude": "77.2090",
      "sequence": 1
    },
    "route": {
      "id": 1,
      "name": "North Route",
      "description": "Route covering northern areas"
    },
    "vehicle": {
      "id": 1,
      "number": "BUS001",
      "model": "School Bus 2022",
      "capacity": 40
    },
    "driver": {
      "id": 1,
      "name": "David Johnson",
      "contact": "9876543210",
      "license_number": "DL12345678"
    }
  }
}
```

### Error Responses

#### Unauthorized Access
```json
{
  "success": false,
  "message": "Unauthorized access"
}
```

#### Student Not Found
```json
{
  "success": false,
  "message": "Student not found"
}
```

#### Transport Not Enabled
```json
{
  "success": false,
  "message": "Transport not enabled for this student"
}
```

#### No Vehicle Assigned
```json
{
  "success": false,
  "message": "No vehicle assigned to this route"
}
```

## Usage
This API endpoint is designed for mobile applications to retrieve student transport information. It includes:
- Student details
- Pickup point information with coordinates and sequence number
- Route information
- Vehicle details
- Driver contact information

The endpoint requires a valid student or parent authentication token to access the transport data.

## Implementation Notes
- The API doesn't require a school_id for filtering as it uses the student's pickup point directly
- Pickup points are linked to routes via the route_detail_id field
- The transport data is returned based solely on the authenticated user, no additional parameters needed

## Program and Event Image API Endpoints

These endpoints allow teachers and students to access images related to school programs and events.

### Get Program Images

```
GET /api/program-images
```

Returns images of programs for the authenticated user's school.

**Parameters:**
- `program_id` (optional): Filter by specific program ID
- `featured` (optional): Set to `1` to get only featured programs
- `limit` (optional): Limit the number of results (default: 10)

**Response:**
```json
{
  "success": true,
  "message": "Program images retrieved successfully",
  "data": [
    {
      "id": 1,
      "title": "Science Program",
      "description": "Our advanced science curriculum",
      "coordinator": "Dr. Jane Smith",
      "image_url": "https://example.com/storage/programs/science.jpg",
      "is_featured": true,
      "created_at": "2023-06-15 10:30:00"
    }
  ]
}
```

### Get Event Images

```
GET /api/event-images
```

Returns images of events for the authenticated user's school.

**Parameters:**
- `event_id` (optional): Filter by specific event ID
- `program_id` (optional): Filter by specific program ID
- `featured` (optional): Set to `1` to get only featured events
- `status` (optional): Filter by event status (`upcoming`, `ongoing`, `completed`)
- `limit` (optional): Limit the number of results (default: 10)

**Response:**
```json
{
  "success": true,
  "message": "Event images retrieved successfully",
  "data": [
    {
      "id": 1,
      "title": "Annual Science Fair",
      "description": "Showcasing student science projects",
      "event_date": "2023-07-20",
      "location": "School Auditorium",
      "image_url": "https://example.com/storage/events/science_fair.jpg",
      "status": "upcoming",
      "is_featured": true,
      "program_id": 1,
      "program_name": "Science Program"
    }
  ]
}
```

### Get Gallery Images

```
GET /api/gallery-images
```

Returns a combined gallery of program and event images.

**Parameters:**
- `type` (optional): Filter by type (`program` or `event`)
- `featured` (optional): Set to `1` to get only featured items
- `limit` (optional): Limit the number of results (default: 20)

**Response:**
```json
{
  "success": true,
  "message": "Gallery images retrieved successfully",
  "data": {
    "all": [
      {
        "id": 1,
        "title": "Science Program",
        "type": "program",
        "image_url": "https://example.com/storage/programs/science.jpg",
        "is_featured": true
      },
      {
        "id": 1,
        "title": "Annual Science Fair",
        "type": "event",
        "event_date": "2023-07-20",
        "image_url": "https://example.com/storage/events/science_fair.jpg",
        "status": "upcoming",
        "is_featured": true
      }
    ],
    "programs": [
      {
        "id": 1,
        "title": "Science Program",
        "type": "program",
        "image_url": "https://example.com/storage/programs/science.jpg",
        "is_featured": true
      }
    ],
    "events": [
      {
        "id": 1,
        "title": "Annual Science Fair",
        "type": "event",
        "event_date": "2023-07-20",
        "image_url": "https://example.com/storage/events/science_fair.jpg",
        "status": "upcoming",
        "is_featured": true
      }
    ]
  }
}
```

## Teacher Homework API Endpoints

These endpoints allow teachers to manage homework assignments for their classes and subjects.

### Get All Homework for Teacher

```
GET /api/teacher/homework
```

Returns all homework assignments created by the authenticated teacher.

**Parameters:**
- `class` (optional): Filter by class name
- `section` (optional): Filter by section ID
- `date_from` (optional): Filter by homework date (start date)
- `date_to` (optional): Filter by homework date (end date)

**Response:**
```json
{
  "success": true,
  "data": {
    "homework": [
      {
        "id": 1,
        "class_name": "Class 10",
        "section": "A",
        "subject": "Mathematics",
        "homework_date": "2023-10-15",
        "submission_date": "2023-10-20",
        "description": "Complete exercises 5.1 to 5.5",
        "image_url": "http://example.com/storage/homework_images/1697356842_math_hw.jpg",
        "created_by": "John Doe",
        "created_at": "2023-10-15 10:30:42"
      }
    ],
    "teaching_assignments": [
      {
        "class_id": 5,
        "class_name": "Class 10",
        "sections": [
          {
            "id": 15,
            "name": "A"
          },
          {
            "id": 16,
            "name": "B"
          }
        ]
      }
    ],
    "subject": {
      "id": 3,
      "name": "Mathematics"
    }
  }
}
```

### Create New Homework

```
POST /api/teacher/homework
```

Creates a new homework assignment for the authenticated teacher's subject.

**Request Body:**
```json
{
  "class": "Class 10",
  "section": 15,
  "homework_date": "2023-10-15",
  "submission_date": "2023-10-20",
  "description": "Complete exercises 5.1 to 5.5",
  "image": "[binary file data]"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Homework added successfully",
  "homework": {
    "id": 1,
    "class_name": "Class 10",
    "section_id": 15,
    "subject_id": 3,
    "homework_date": "2023-10-15",
    "submission_date": "2023-10-20",
    "description": "Complete exercises 5.1 to 5.5",
    "image_url": "http://example.com/storage/homework_images/1697356842_math_hw.jpg"
  }
}
```

### Get Homework Details

```
GET /api/teacher/homework/{id}
```

Returns details for a specific homework assignment.

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "class_name": "Class 10",
    "section": {
      "id": 15,
      "name": "A"
    },
    "subject": {
      "id": 3,
      "name": "Mathematics"
    },
    "homework_date": "2023-10-15",
    "submission_date": "2023-10-20",
    "description": "Complete exercises 5.1 to 5.5",
    "image_url": "http://example.com/storage/homework_images/1697356842_math_hw.jpg",
    "created_by": "John Doe",
    "created_at": "2023-10-15 10:30:42",
    "updated_at": "2023-10-15 10:30:42"
  }
}
```

### Update Homework

```
PUT /api/teacher/homework/{id}
```

Updates an existing homework assignment.

**Request Body:**
```json
{
  "class": "Class 10",
  "section": 15,
  "homework_date": "2023-10-16",
  "submission_date": "2023-10-21",
  "description": "Updated: Complete exercises 5.1 to 5.10",
  "image": "[binary file data]"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Homework updated successfully",
  "homework": {
    "id": 1,
    "class_name": "Class 10",
    "section_id": 15,
    "subject_id": 3,
    "homework_date": "2023-10-16",
    "submission_date": "2023-10-21",
    "description": "Updated: Complete exercises 5.1 to 5.10",
    "image_url": "http://example.com/storage/homework_images/1697443242_math_hw_updated.jpg"
  }
}
```

### Delete Homework

```
DELETE /api/teacher/homework/{id}
```

Deletes a homework assignment.

**Response:**
```json
{
  "success": true,
  "message": "Homework deleted successfully"
}
```

**Note:** Teachers can only update or delete homework assignments that they have created. The subject is automatically set to the teacher's assigned subject.
