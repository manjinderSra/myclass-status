#!/bin/bash

# Use this script to test the student's weekly timetable API
# Replace YOUR_IP and YOUR_TOKEN with your actual values
# Example: bash student_weekly_timetable_curl.sh YOUR_TOKEN_HERE

IP_ADDRESS=192.168.1.93
PORT=8000
TOKEN=${1:-"YOUR_TOKEN_HERE"}

echo "Testing weekly timetable API at http://$IP_ADDRESS:$PORT/api/student/timetable/weekly"
echo "Using token: ${TOKEN:0:10}..."

curl -X GET \
  http://$IP_ADDRESS:$PORT/api/student/timetable/weekly \
  -H "Authorization: Bearer $TOKEN" \
  -H 'Accept: application/json'

echo -e "\n\nNote: To get a token, first login using the student_login_curl.sh script"
echo "Usage: ./student_weekly_timetable_curl.sh YOUR_TOKEN_HERE"
echo -e "\nThis endpoint returns the student's complete weekly timetable, including:"
echo "- Timetable organized by days of the week"
echo "- All periods for each day in chronological order"
echo "- Subject and teacher information for regular periods"
echo "- Break periods and other special periods"
echo "- Only days with scheduled periods are included" 