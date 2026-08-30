#!/bin/bash

# Script to test the teacher login API endpoint
# Usage: ./teacher_login_curl.sh EMPLOYEE_ID PASSWORD
# Example: ./teacher_login_curl.sh "TCH-12345" "password123"

# Check if arguments were provided
if [ -z "$1" ] || [ -z "$2" ]; then
  echo "Error: Both employee ID and password are required"
  echo "Usage: ./teacher_login_curl.sh EMPLOYEE_ID PASSWORD"
  echo "Example: ./teacher_login_curl.sh \"TCH-12345\" \"password123\""
  exit 1
fi

EMPLOYEE_ID=$1
PASSWORD=$2
API_URL="http://localhost:8000/api/teacher/login"

echo "Testing teacher login API"
echo "API URL: $API_URL"
echo "Employee ID: $EMPLOYEE_ID"
echo "----------------------------------------------"

# Make the API request with curl using the provided credentials
echo "Running login request:"
curl -s -X POST "$API_URL" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"employee_id\":\"$EMPLOYEE_ID\", \"password\":\"$PASSWORD\"}" \
  | jq '.' || echo "Error parsing JSON response. Raw response:" && curl -s -X POST "$API_URL" -H "Content-Type: application/json" -H "Accept: application/json" -d "{\"employee_id\":\"$EMPLOYEE_ID\", \"password\":\"$PASSWORD\"}"

echo ""
echo "----------------------------------------------"
echo "If successful, you should see a JSON response with:"
echo "1. A success status"
echo "2. A token for authentication"
echo "3. Teacher information"
echo ""
echo "To test the profile API endpoint with the token, run:"
echo "curl -s -X GET \"http://localhost:8000/api/teacher/profile\" -H \"Authorization: Bearer YOUR_TOKEN\" -H \"Accept: application/json\" | jq '.'"
echo ""
echo "To logout, run:"
echo "curl -s -X POST \"http://localhost:8000/api/teacher/logout\" -H \"Authorization: Bearer YOUR_TOKEN\" -H \"Accept: application/json\" | jq '.'"
echo "----------------------------------------------"
echo "Troubleshooting if you get an error:"
echo "1. Check your Laravel logs at storage/logs/laravel.log for the detailed error message"
echo "2. Make sure Laravel Sanctum is properly configured"
echo "3. Verify that the teacher with the given employee ID exists in your database"
echo "4. Ensure the password is correct"
echo "5. Check that the User model uses the HasApiTokens trait" 