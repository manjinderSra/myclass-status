#!/bin/bash

# This script tests the school media API endpoints
# Replace these variables with actual values
TOKEN="your_actual_bearer_token_here"
API_BASE_URL="http://localhost:8000/api"

# Test getting all media
echo "Testing Get All Media Endpoint..."
curl -X GET \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" \
  "$API_BASE_URL/media"

echo -e "\n\n"

# Test getting photos only
echo "Testing Get Photos Endpoint..."
curl -X GET \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" \
  "$API_BASE_URL/media/photos"

echo -e "\n\n"

# Test getting videos only
echo "Testing Get Videos Endpoint..."
curl -X GET \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" \
  "$API_BASE_URL/media/videos"

echo -e "\n\n"

# Test getting featured media
echo "Testing Get Featured Media Endpoint..."
curl -X GET \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" \
  "$API_BASE_URL/media/featured"

echo -e "\n\n"

# Test getting media categories
echo "Testing Get Media Categories Endpoint..."
curl -X GET \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" \
  "$API_BASE_URL/media/categories"

echo -e "\n\n"

# Test getting a specific media item
echo "Testing Get Specific Media Endpoint..."
curl -X GET \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" \
  "$API_BASE_URL/media/1"

echo -e "\n\nNote: Replace 'your_actual_bearer_token_here' with a valid student bearer token"
echo "and modify the ID in the last request as needed." 