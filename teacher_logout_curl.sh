#!/bin/bash

# Script to test the teacher logout API endpoint
# Usage: ./teacher_logout_curl.sh TOKEN
# Example: ./teacher_logout_curl.sh "1|abcdefghijklmnopqrstuvwxyz1234567890"

# Check if token was provided
if [ -z "$1" ]; then
  echo "Error: Bearer token is required"
  echo "Usage: ./teacher_logout_curl.sh TOKEN"
  echo "Example: ./teacher_logout_curl.sh \"1|abcdefghijklmnopqrstuvwxyz1234567890\""
  exit 1
fi

TOKEN=$1
API_URL="http://localhost:8000/api/teacher/logout"

echo "Testing teacher logout API"
echo "API URL: $API_URL"
echo "----------------------------------------------"

# Make the API request with curl using bearer token
echo "Running logout request:"
curl -s -X POST "$API_URL" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" \
  | jq '.' || echo "Error parsing JSON response. Raw response:" && curl -s -X POST "$API_URL" -H "Authorization: Bearer $TOKEN" -H "Accept: application/json"

echo ""
echo "----------------------------------------------"
echo "If successful, you should see a JSON response with:"
echo "1. A success status"
echo "2. A message confirming logout"
echo ""
echo "IMPORTANT: After logout, the token will be invalidated and cannot be used again."
echo "You'll need to log in again to get a new token for future requests."
echo ""
echo "----------------------------------------------"
echo "Troubleshooting if you get an error:"
echo "1. Check your Laravel logs at storage/logs/laravel.log for the detailed error message"
echo "2. Make sure the token is valid and not expired"
echo "3. Verify that the token hasn't already been invalidated by a previous logout" 