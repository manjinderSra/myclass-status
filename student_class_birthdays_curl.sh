#!/bin/bash

# Use this script to test the class birthdays API
# Replace YOUR_IP and YOUR_TOKEN with your actual values
# Example: bash student_class_birthdays_curl.sh YOUR_TOKEN_HERE

IP_ADDRESS=192.168.1.93
PORT=8000
TOKEN=${1:-"test_token_12345"}  # Using a test token for troubleshooting

echo "Testing class birthdays API at http://$IP_ADDRESS:$PORT/api/student/birthday/class"
echo "Using token: ${TOKEN:0:10}..."

curl -X GET \
  http://$IP_ADDRESS:$PORT/api/student/birthday/class \
  -H "Authorization: Bearer $TOKEN" \
  -H 'Accept: application/json'

echo -e "\n\nNote: To get a token, first login using the student_login_curl.sh script"
echo "Usage: ./student_class_birthdays_curl.sh YOUR_TOKEN_HERE"
echo -e "\nThis endpoint returns birthdays for the student's class and teachers, including:"
echo "- Student classmates' birthdays"
echo "- Class teachers' birthdays"
echo "- Type indicator (student/teacher)"
echo "- Birthday date information"
echo "- Flag for birthdays that are today" 