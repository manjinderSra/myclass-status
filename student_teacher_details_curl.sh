#!/bin/bash

# Use this script to test the student's teacher details API
# Replace YOUR_IP, YOUR_TOKEN, and TEACHER_ID with your actual values
# Example: bash student_teacher_details_curl.sh YOUR_TOKEN_HERE 5

IP_ADDRESS=192.168.1.93
PORT=8000
TOKEN=${1:-"test_token_12345"}  # Using a test token for troubleshooting
TEACHER_ID=${2:-1}  # Default teacher ID is 1

echo "Testing teacher details API at http://$IP_ADDRESS:$PORT/api/student/teachers/$TEACHER_ID"
echo "Using token: ${TOKEN:0:10}..."

curl -X GET \
  http://$IP_ADDRESS:$PORT/api/student/teachers/$TEACHER_ID \
  -H "Authorization: Bearer $TOKEN" \
  -H 'Accept: application/json'

echo -e "\n\nNote: To get a token, first login using the student_login_curl.sh script"
echo "Usage: ./student_teacher_details_curl.sh YOUR_TOKEN_HERE TEACHER_ID"
echo -e "\nThis endpoint returns detailed information about a specific teacher, including:"
echo "- Complete teacher profile information"
echo "- Teacher's schedule in the student's class"
echo "- Subjects taught by the teacher"
echo "- Qualifications and experience"
echo "- Contact details and profile image" 