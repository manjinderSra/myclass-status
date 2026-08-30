#!/bin/bash

# Script to fetch Terms and Conditions via API using bearer token
# Usage: ./student_terms_conditions_curl.sh TOKEN
# Example: ./student_terms_conditions_curl.sh "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."

# Check if token was provided
if [ -z "$1" ]; then
  echo "Error: Bearer token is required"
  echo "Usage: ./student_terms_conditions_curl.sh TOKEN"
  echo "Example: ./student_terms_conditions_curl.sh \"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...\""
  exit 1
fi

TOKEN=$1
API_URL="http://localhost:8000/api/terms-conditions"

echo "Fetching Terms and Conditions using Bearer Token Authentication"
echo "API URL: $API_URL"
echo "----------------------------------------------"

# Make the API request with curl using bearer token - with verbose output for debugging
echo "Running verbose request (for debugging):"
curl -v -X GET "$API_URL" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"

echo ""
echo "----------------------------------------------"
echo "Running normal request:"

# Make the regular API request
curl -s -X GET "$API_URL" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" \
  | jq '.' || echo "Error parsing JSON response. Raw response:" && curl -s -X GET "$API_URL" -H "Authorization: Bearer $TOKEN" -H "Accept: application/json"

echo "----------------------------------------------"
echo "Troubleshooting if you get an error:"
echo "1. Check your Laravel logs at storage/logs/laravel.log for the detailed error message"
echo "2. Make sure Laravel Sanctum is properly configured"
echo "3. Verify that your database connection is working"
echo "4. Confirm that the TermsCondition model and table exist"
echo "5. Ensure your token is from a valid student with a school_id"
echo "6. Run the database migration: php artisan migrate"
echo ""
echo "To see the routes in your application, run: php artisan route:list"
