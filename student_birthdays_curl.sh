#!/bin/bash

# Use this script to test the student birthdays API
# Replace YOUR_IP and YOUR_TOKEN with your actual values
# Example: bash student_birthdays_curl.sh YOUR_TOKEN_HERE

IP_ADDRESS=192.168.1.93
PORT=8000
TOKEN=${1:-"test_token_12345"}  # Using a test token for troubleshooting

echo "Testing birthdays API at http://$IP_ADDRESS:$PORT/api/student/birthdays"
echo "Using token: ${TOKEN:0:10}..."

curl -X GET \
  http://$IP_ADDRESS:$PORT/api/student/birthdays \
  -H "Authorization: Bearer $TOKEN" \
  -H 'Accept: application/json'

echo -e "\n\nNote: To get a token, first login using the student_login_curl.sh script"
echo "Usage: ./student_birthdays_curl.sh YOUR_TOKEN_HERE"
echo -e "\nThis endpoint returns the list of all student birthdays, including:"
echo "- Student name and profile image"
echo "- Class and section information"
echo "- Complete birthday date information (year, month, day)"
echo "- Flag for birthdays that are today" 