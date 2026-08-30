# Teacher API Usage Guide

This guide provides comprehensive instructions on how to use the Teacher API endpoints for your mobile application integration.

## Authentication Flow

The typical authentication flow for teachers in your mobile app should be:

1. User enters employee ID and password
2. App sends login request to `/api/teacher/login`
3. Server validates credentials and returns token
4. App stores token securely for future requests
5. App includes token in Authorization header for all subsequent API calls
6. When user logs out, app sends request to `/api/teacher/logout` to invalidate the token

## API Endpoints

### 1. Login

**Endpoint:** `/api/teacher/login`
**Method:** `POST`
**Content Type:** `application/json`

**Request:**
```json
{
  "employee_id": "TCH-12345",
  "password": "password123"
}
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Login successful",
  "token": "1|abcdefghijklmnopqrstuvwxyz1234567890",
  "teacher": {
    "id": 1,
    "name": "John Doe",
    "email": "john.doe@example.com",
    "employee_id": "TCH-12345",
    "school_id": 1,
    "school_name": "ABC Public School",
    "profile_image": "uploads/teachers/profile/john_doe.jpg",
    "subject": "Mathematics"
  }
}
```

**Error Response (401 Unauthorized):**
```json
{
  "success": false,
  "message": "Invalid credentials",
  "error_code": "INVALID_CREDENTIALS"
}
```

**Error Response (422 Unprocessable Entity):**
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "employee_id": [
      "The employee id field is required."
    ],
    "password": [
      "The password field is required."
    ]
  }
}
```

### 2. Get Teacher Profile

**Endpoint:** `/api/teacher/profile`
**Method:** `GET`
**Headers:**
- `Authorization: Bearer {token}`
- `Accept: application/json`

**Success Response (200 OK):**
```json
{
  "success": true,
  "teacher": {
    "id": 1,
    "name": "John Doe",
    "email": "john.doe@example.com",
    "employee_id": "TCH-12345",
    "school_id": 1,
    "school_name": "ABC Public School",
    "profile_image": "uploads/teachers/profile/john_doe.jpg",
    "subject": "Mathematics",
    "gender": "Male",
    "primary_contact": "+1234567890",
    "date_of_joining": "2022-01-15",
    "qualification": "M.Sc. Mathematics",
    "status": "active"
  }
}
```

**Error Response (401 Unauthorized):**
```json
{
  "success": false,
  "message": "Unauthorized. Invalid or missing token.",
  "error_code": "UNAUTHORIZED"
}
```

**Error Response (403 Forbidden):**
```json
{
  "success": false,
  "message": "Unauthorized. User is not a teacher.",
  "error_code": "NOT_A_TEACHER"
}
```

### 3. Logout

**Endpoint:** `/api/teacher/logout`
**Method:** `POST`
**Headers:**
- `Authorization: Bearer {token}`
- `Accept: application/json`

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

**Error Response (401 Unauthorized):**
```json
{
  "success": false,
  "message": "Unauthorized. Invalid or missing token.",
  "error_code": "UNAUTHORIZED"
}
```

## Announcements API

### Get All Announcements

Retrieves all announcements for the authenticated teacher.

**URL**: `/api/teacher/announcements`

**Method**: `GET`

**Authentication**: Required (Bearer Token)

**Success Response**:
```json
{
  "success": true,
  "data": {
    "announcements": [
      {
        "id": 1,
        "title": "Staff Meeting",
        "message": "There will be a staff meeting on Friday at 3 PM",
        "publish_date": "2023-07-15",
        "created_by": "Principal",
        "created_at": "2023-07-10 09:30:00"
      },
      {
        "id": 2,
        "title": "Holiday Notice",
        "message": "School will be closed on Monday for Independence Day",
        "publish_date": "2023-07-12",
        "created_by": "Admin",
        "created_at": "2023-07-08 14:45:00"
      }
    ],
    "count": 2
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

### Get Announcement Details

Retrieves details of a specific announcement.

**URL**: `/api/teacher/announcements/{id}`

**Method**: `GET`

**Authentication**: Required (Bearer Token)

**Success Response**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Staff Meeting",
    "message": "There will be a staff meeting on Friday at 3 PM",
    "publish_date": "2023-07-15",
    "created_by": "Principal",
    "created_at": "2023-07-10 09:30:00",
    "updated_at": "2023-07-10 09:30:00"
  }
}
```

**Error Response**:
```json
{
  "success": false,
  "message": "Announcement not found or not accessible",
  "error_code": "ANNOUNCEMENT_NOT_FOUND"
}
```

## Birthdays API

### Get All Teacher Birthdays

Retrieves all teacher birthdays from the same school.

**URL**: `/api/teacher/birthdays`

**Method**: `GET`

**Authentication**: Required (Bearer Token)

**Success Response**:
```json
{
  "success": true,
  "data": {
    "today": "2023-06-12",
    "birthdays_count": 5,
    "birthdays": [
      {
        "id": 1,
        "employee_id": "TCH12345",
        "name": "John Smith",
        "subject": {
          "id": 1,
          "name": "Mathematics"
        },
        "dob": "1985-01-15",
        "birthday_month": "January",
        "birthday_day": "15",
        "is_today": false,
        "profile_image": "http://example.com/storage/teachers/john.jpg"
      },
      {
        "id": 2,
        "employee_id": "TCH12346",
        "name": "Jane Doe",
        "subject": {
          "id": 2,
          "name": "Science"
        },
        "dob": "1988-06-12",
        "birthday_month": "June",
        "birthday_day": "12",
        "is_today": true,
        "profile_image": "http://example.com/storage/teachers/jane.jpg"
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

### Get Teacher's Own Birthday

Retrieves the authenticated teacher's own birthday details with age and countdown information.

**URL**: `/api/teacher/birthday/my`

**Method**: `GET`

**Authentication**: Required (Bearer Token)

**Success Response**:
```json
{
  "success": true,
  "data": {
    "employee_id": "TCH12345",
    "name": "John Smith",
    "dob": "1985-06-15",
    "birthday_month": "June",
    "birthday_day": "15",
    "age": 38,
    "next_age": 39,
    "is_today": false,
    "days_until_next_birthday": 3,
    "profile_image": "http://example.com/storage/teachers/john.jpg"
  }
}
```

**Error Responses**:

1. DOB not available (404):
```json
{
  "success": false,
  "message": "Birth date not available for this teacher",
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

### Get Subject Colleague Birthdays

Retrieves birthdays for teachers who teach the same subject as the authenticated teacher.

**URL**: `/api/teacher/birthday/subject-colleagues`

**Method**: `GET`

**Authentication**: Required (Bearer Token)

**Success Response**:
```json
{
  "success": true,
  "data": {
    "subject_id": 1,
    "subject_name": "Mathematics",
    "today": "2023-06-12",
    "birthdays_count": 3,
    "birthdays": [
      {
        "id": 2,
        "employee_id": "TCH12346",
        "name": "Jane Doe",
        "type": "teacher",
        "dob": "1988-01-22",
        "birthday_month": "January",
        "birthday_day": "22",
        "is_today": false,
        "profile_image": "http://example.com/storage/teachers/jane.jpg"
      },
      {
        "id": 3,
        "employee_id": "TCH12347",
        "name": "Robert Johnson",
        "type": "teacher",
        "dob": "1982-06-12",
        "birthday_month": "June",
        "birthday_day": "12",
        "is_today": true,
        "profile_image": "http://example.com/storage/teachers/robert.jpg"
      },
      {
        "id": 4,
        "employee_id": "TCH12348",
        "name": "Sarah Williams",
        "type": "teacher",
        "dob": "1990-12-05",
        "birthday_month": "December",
        "birthday_day": "05",
        "is_today": false,
        "profile_image": "http://example.com/storage/teachers/sarah.jpg"
      }
    ]
  }
}
```

**Error Responses**:

1. No subject assigned (404):
```json
{
  "success": false,
  "message": "Teacher not assigned to any subject",
  "error_code": "NO_SUBJECT_ASSIGNED"
}
```

2. Server error (500):
```json
{
  "success": false,
  "message": "An unexpected error occurred while retrieving colleague birthdays",
  "error_code": "SERVER_ERROR"
}
```

## Testing the API

We've provided shell scripts to help you test each endpoint:

### Login Test

```bash
./teacher_login_curl.sh "TCH-12345" "password123"
```

### Profile Test

```bash
./teacher_profile_curl.sh "1|abcdefghijklmnopqrstuvwxyz1234567890"
```

### Logout Test

```bash
./teacher_logout_curl.sh "1|abcdefghijklmnopqrstuvwxyz1234567890"
```

## Implementation in Mobile Apps

### Android (Kotlin) Example

```kotlin
// Login Request
private fun loginTeacher(employeeId: String, password: String) {
    val requestBody = JSONObject().apply {
        put("employee_id", employeeId)
        put("password", password)
    }
    
    val request = JsonObjectRequest(
        Request.Method.POST,
        "${BASE_URL}/api/teacher/login",
        requestBody,
        { response ->
            try {
                val success = response.getBoolean("success")
                if (success) {
                    // Store token in secure storage
                    val token = response.getString("token")
                    saveTokenToSecureStorage(token)
                    
                    // Parse teacher info
                    val teacherObject = response.getJSONObject("teacher")
                    val teacher = Teacher(
                        id = teacherObject.getInt("id"),
                        name = teacherObject.getString("name"),
                        email = teacherObject.getString("email"),
                        // ... other fields
                    )
                    
                    // Navigate to dashboard
                    navigateToDashboard(teacher)
                } else {
                    // Handle error
                    val message = response.getString("message")
                    showError(message)
                }
            } catch (e: Exception) {
                showError("Error parsing response: ${e.message}")
            }
        },
        { error ->
            showError("Network error: ${error.message}")
        }
    )
    
    requestQueue.add(request)
}

// Get Profile
private fun getTeacherProfile() {
    val token = getTokenFromSecureStorage()
    
    val request = object : JsonObjectRequest(
        Request.Method.GET,
        "${BASE_URL}/api/teacher/profile",
        null,
        { response ->
            // Process response
        },
        { error ->
            // Handle error
        }
    ) {
        override fun getHeaders(): MutableMap<String, String> {
            return HashMap<String, String>().apply {
                put("Authorization", "Bearer $token")
                put("Accept", "application/json")
            }
        }
    }
    
    requestQueue.add(request)
}

// Logout
private fun logoutTeacher() {
    val token = getTokenFromSecureStorage()
    
    val request = object : JsonObjectRequest(
        Request.Method.POST,
        "${BASE_URL}/api/teacher/logout",
        null,
        { response ->
            // Clear token from storage
            clearTokenFromSecureStorage()
            // Navigate to login screen
            navigateToLogin()
        },
        { error ->
            // Handle error
        }
    ) {
        override fun getHeaders(): MutableMap<String, String> {
            return HashMap<String, String>().apply {
                put("Authorization", "Bearer $token")
                put("Accept", "application/json")
            }
        }
    }
    
    requestQueue.add(request)
}
```

### iOS (Swift) Example

```swift
// Login Request
func loginTeacher(employeeId: String, password: String) {
    guard let url = URL(string: "\(baseURL)/api/teacher/login") else { return }
    
    let parameters: [String: Any] = [
        "employee_id": employeeId,
        "password": password
    ]
    
    var request = URLRequest(url: url)
    request.httpMethod = "POST"
    request.addValue("application/json", forHTTPHeaderField: "Content-Type")
    request.addValue("application/json", forHTTPHeaderField: "Accept")
    
    do {
        request.httpBody = try JSONSerialization.data(withJSONObject: parameters)
    } catch {
        print("Error creating request body: \(error)")
        return
    }
    
    URLSession.shared.dataTask(with: request) { data, response, error in
        guard let data = data else {
            print("Network error: \(error?.localizedDescription ?? "Unknown error")")
            return
        }
        
        do {
            if let json = try JSONSerialization.jsonObject(with: data) as? [String: Any] {
                if let success = json["success"] as? Bool, success {
                    // Store token securely
                    if let token = json["token"] as? String {
                        KeychainHelper.standard.save(token, service: "teacher-api", account: "token")
                    }
                    
                    // Process teacher data
                    if let teacherData = json["teacher"] as? [String: Any] {
                        // Create teacher object and navigate to dashboard
                    }
                } else {
                    // Handle error
                    if let message = json["message"] as? String {
                        DispatchQueue.main.async {
                            self.showAlert(message: message)
                        }
                    }
                }
            }
        } catch {
            print("Error parsing response: \(error)")
        }
    }.resume()
}

// Get Profile
func getTeacherProfile() {
    guard let url = URL(string: "\(baseURL)/api/teacher/profile") else { return }
    guard let token = KeychainHelper.standard.read(service: "teacher-api", account: "token") else { return }
    
    var request = URLRequest(url: url)
    request.httpMethod = "GET"
    request.addValue("Bearer \(token)", forHTTPHeaderField: "Authorization")
    request.addValue("application/json", forHTTPHeaderField: "Accept")
    
    URLSession.shared.dataTask(with: request) { data, response, error in
        // Process response
    }.resume()
}

// Logout
func logoutTeacher() {
    guard let url = URL(string: "\(baseURL)/api/teacher/logout") else { return }
    guard let token = KeychainHelper.standard.read(service: "teacher-api", account: "token") else { return }
    
    var request = URLRequest(url: url)
    request.httpMethod = "POST"
    request.addValue("Bearer \(token)", forHTTPHeaderField: "Authorization")
    request.addValue("application/json", forHTTPHeaderField: "Accept")
    
    URLSession.shared.dataTask(with: request) { data, response, error in
        // Clear token and navigate to login screen
        KeychainHelper.standard.delete(service: "teacher-api", account: "token")
    }.resume()
}
```

## Security Considerations

1. **Token Storage**: Always store tokens in secure storage:
   - Android: Use EncryptedSharedPreferences or Android Keystore
   - iOS: Use Keychain Services

2. **HTTPS**: Ensure all API communication happens over HTTPS

3. **Token Expiry**: Handle token expiration gracefully by redirecting to login

4. **Logout**: Always call the logout endpoint when the user logs out to invalidate the token on the server

5. **Error Handling**: Implement proper error handling for all API calls 