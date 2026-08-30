#!/bin/bash

# Use this script to test the student's today timetable API
# Replace YOUR_IP and YOUR_TOKEN with your actual values
# Example: bash student_today_timetable_curl.sh YOUR_TOKEN_HERE

IP_ADDRESS=192.168.1.93
PORT=8000
TOKEN=${1:-"test_token_12345"}  # Using a test token for troubleshooting

echo "Testing today's timetable API at http://$IP_ADDRESS:$PORT/api/student/timetable/today"
echo "Using token: ${TOKEN:0:10}..."

curl -X GET \
  http://$IP_ADDRESS:$PORT/api/student/timetable/today \
  -H "Authorization: Bearer $TOKEN" \
  -H 'Accept: application/json'

echo -e "\n\nNote: To get a token, first login using the student_login_curl.sh script"
echo "Usage: ./student_today_timetable_curl.sh YOUR_TOKEN_HERE"
echo -e "\nThis endpoint returns the student's timetable for the current day, including:"
echo "- Today's date and day name"
echo "- List of periods in chronological order"
echo "- Subject and teacher information for each period"
echo "- Break periods and other special periods" 