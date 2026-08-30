#!/bin/bash

# This script tests the password update API endpoint
# Replace these variables with actual values
TOKEN="your_actual_bearer_token_here"
API_URL="http://localhost:8000/api/student/update-password"

# Test the API endpoint
echo "Testing Password Update API Endpoint..."
curl -X POST \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "current_password": "your_current_password",
    "password": "Your_New_Password123!",
    "password_confirmation": "Your_New_Password123!"
  }' \
  $API_URL

echo -e "\n\nNote: Replace 'your_actual_bearer_token_here' with a valid student bearer token"
echo "and update the current and new password values as needed." 