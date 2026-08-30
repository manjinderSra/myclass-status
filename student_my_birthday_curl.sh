#!/bin/bash

# Use this script to test the student's own birthday API
# Replace YOUR_IP and YOUR_TOKEN with your actual values
# Example: bash student_my_birthday_curl.sh YOUR_TOKEN_HERE

IP_ADDRESS=192.168.1.93
PORT=8000
TOKEN=${1:-"test_token_12345"}  # Using a test token for troubleshooting

echo "Testing my birthday API at http://$IP_ADDRESS:$PORT/api/student/birthday/my"
echo "Using token: ${TOKEN:0:10}..."

curl -X GET \
  http://$IP_ADDRESS:$PORT/api/student/birthday/my \
  -H "Authorization: Bearer $TOKEN" \
  -H 'Accept: application/json'

echo -e "\n\nNote: To get a token, first login using the student_login_curl.sh script"
echo "Usage: ./student_my_birthday_curl.sh YOUR_TOKEN_HERE"
echo -e "\nThis endpoint returns the authenticated student's own birthday details, including:"
echo "- Birthday date information (year, month, day)"
echo "- Current age and next age"
echo "- Number of days until next birthday"
echo "- Flag if today is the birthday" 