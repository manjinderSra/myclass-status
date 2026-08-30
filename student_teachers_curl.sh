#!/bin/bash

# Use this script to test the student's teachers API
# Replace YOUR_IP and YOUR_TOKEN with your actual values
# Example: bash student_teachers_curl.sh YOUR_TOKEN_HERE

IP_ADDRESS=192.168.1.93
PORT=8000
TOKEN=${1:-"test_token_12345"}  # Using a test token for troubleshooting

echo "Testing teachers list API at http://$IP_ADDRESS:$PORT/api/student/teachers"
echo "Using token: ${TOKEN:0:10}..."

curl -X GET \
  http://$IP_ADDRESS:$PORT/api/student/teachers \
  -H "Authorization: Bearer $TOKEN" \
  -H 'Accept: application/json'

echo -e "\n\nNote: To get a token, first login using the student_login_curl.sh script"
echo "Usage: ./student_teachers_curl.sh YOUR_TOKEN_HERE"
echo -e "\nThis endpoint returns the list of teachers from the student's timetable, including:"
echo "- Basic teacher information (name, email, contact)"
echo "- Subjects taught by each teacher"
echo "- Teacher qualifications and experience"
echo "- Profile images if available" 