# API Testing Examples

## Program and Event Images API

### Testing Program Images API

```bash
# Get all program images for authenticated user's school
curl -X GET "http://your-domain.com/api/program-images" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"

# Get featured program images
curl -X GET "http://your-domain.com/api/program-images?featured=1" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"

# Get images for a specific program
curl -X GET "http://your-domain.com/api/program-images?program_id=1" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"

# Limit the number of results
curl -X GET "http://your-domain.com/api/program-images?limit=5" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"
```

### Testing Event Images API

```bash
# Get all event images for authenticated user's school
curl -X GET "http://your-domain.com/api/event-images" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"

# Get featured event images
curl -X GET "http://your-domain.com/api/event-images?featured=1" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"

# Get images for a specific event
curl -X GET "http://your-domain.com/api/event-images?event_id=1" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"

# Get images for events in a specific program
curl -X GET "http://your-domain.com/api/event-images?program_id=1" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"

# Get images for events with a specific status
curl -X GET "http://your-domain.com/api/event-images?status=upcoming" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"
```

### Testing Gallery Images API

```bash
# Get all gallery images
curl -X GET "http://your-domain.com/api/gallery-images" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"

# Get only program images in gallery
curl -X GET "http://your-domain.com/api/gallery-images?type=program" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"

# Get only event images in gallery
curl -X GET "http://your-domain.com/api/gallery-images?type=event" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"

# Get featured images in gallery
curl -X GET "http://your-domain.com/api/gallery-images?featured=1" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"

# Limit the number of results
curl -X GET "http://your-domain.com/api/gallery-images?limit=10" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"
```

## Updated Event Images API Testing

```bash
# Get all events (fixed API)
curl --location --request GET "http://your-domain.com/api/event-images" \
--header "Authorization: Bearer YOUR_API_TOKEN" \
--header "Accept: application/json"

# Debug event images API (helps identify issues)
curl --location --request GET "http://your-domain.com/api/debug-event-images" \
--header "Authorization: Bearer YOUR_API_TOKEN" \
--header "Accept: application/json"

# Get events with specific filters
curl --location --request GET "http://your-domain.com/api/event-images?status=upcoming&featured=1&limit=5" \
--header "Authorization: Bearer YOUR_API_TOKEN" \
--header "Accept: application/json"
```

## Using the API in Mobile Apps

For mobile app integration, you can use these endpoints to:

1. Display program images in a carousel on the home screen
2. Show event images in the events section
3. Create a gallery view that combines both program and event images
4. Show featured programs and events in a special section

Example mobile app implementation:

```javascript
// React Native example
import React, { useEffect, useState } from 'react';
import { View, FlatList, Image, Text } from 'react-native';
import axios from 'axios';

const GalleryScreen = () => {
  const [gallery, setGallery] = useState({ all: [], programs: [], events: [] });
  const [loading, setLoading] = useState(true);
  
  useEffect(() => {
    const fetchGallery = async () => {
      try {
        const response = await axios.get('http://your-domain.com/api/gallery-images', {
          headers: {
            'Authorization': `Bearer ${YOUR_API_TOKEN}`,
            'Accept': 'application/json'
          }
        });
        
        if (response.data.success) {
          setGallery(response.data.data);
        }
      } catch (error) {
        console.error('Error fetching gallery:', error);
      } finally {
        setLoading(false);
      }
    };
    
    fetchGallery();
  }, []);
  
  return (
    <View>
      <Text style={{ fontSize: 20, fontWeight: 'bold' }}>Featured Gallery</Text>
      
      {loading ? (
        <Text>Loading gallery...</Text>
      ) : (
        <FlatList
          data={gallery.all}
          keyExtractor={(item) => `${item.type}-${item.id}`}
          renderItem={({ item }) => (
            <View style={{ margin: 10 }}>
              <Image
                source={{ uri: item.image_url }}
                style={{ width: '100%', height: 200, borderRadius: 10 }}
              />
              <Text style={{ fontWeight: 'bold', marginTop: 5 }}>{item.title}</Text>
              {item.type === 'event' && (
                <Text>Event Date: {item.event_date}</Text>
              )}
            </View>
          )}
        />
      )}
    </View>
  );
};

export default GalleryScreen;
```

## Teacher Homework API Endpoints

### Get All Homework for Teacher

This endpoint returns all homework assignments created by the authenticated teacher.

**URL**: `/api/teacher/homework`
**Method**: `GET`
**Auth required**: Yes (Teacher token)

**Optional Query Parameters**:
- `class`: Filter by class name
- `section`: Filter by section ID
- `date_from`: Filter by homework date (start date)
- `date_to`: Filter by homework date (end date)

**Success Response**:
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

This endpoint allows teachers to create a new homework assignment.

**URL**: `/api/teacher/homework`
**Method**: `POST`
**Auth required**: Yes (Teacher token)

**Request Body**:
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

**Success Response**:
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

This endpoint returns details for a specific homework assignment.

**URL**: `/api/teacher/homework/{id}`
**Method**: `GET`
**Auth required**: Yes (Teacher token)

**Success Response**:
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

This endpoint allows teachers to update an existing homework assignment.

**URL**: `/api/teacher/homework/{id}`
**Method**: `PUT`
**Auth required**: Yes (Teacher token)

**Request Body**:
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

**Success Response**:
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

This endpoint allows teachers to delete a homework assignment.

**URL**: `/api/teacher/homework/{id}`
**Method**: `DELETE`
**Auth required**: Yes (Teacher token)

**Success Response**:
```json
{
  "success": true,
  "message": "Homework deleted successfully"
}
```

**Note**: Teachers can only update or delete homework assignments that they have created. 