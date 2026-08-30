#!/bin/bash

# This script tests the terms and conditions API endpoint
# Replace these variables with actual values
TOKEN="your_actual_bearer_token_here"
API_URL="http://localhost:8000/api/terms-and-conditions"

# Test the API endpoint
echo "Testing Terms and Conditions API Endpoint..."
curl -X GET \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" \
  $API_URL

echo -e "\n\nNote: Replace 'your_actual_bearer_token_here' with a valid student bearer token"
echo "To obtain a token, a student must login through the mobile app or API authentication endpoint" 