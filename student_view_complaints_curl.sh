#!/bin/bash

# Use this script to test the student complaints retrieval API
# Replace YOUR_IP and YOUR_TOKEN with your actual values
# Example: bash student_view_complaints_curl.sh YOUR_TOKEN_HERE

IP_ADDRESS=192.168.1.93
PORT=8000
TOKEN=${1:-"test_token_12345"}  # Using a test token for troubleshooting

echo "Testing complaint retrieval API at http://$IP_ADDRESS:$PORT/api/student/complaints"
echo "Using token: ${TOKEN:0:10}..."

curl -X GET \
  http://$IP_ADDRESS:$PORT/api/student/complaints \
  -H "Authorization: Bearer $TOKEN" \
  -H 'Accept: application/json'

echo -e "\n\nNote: To get a token, first login using the student_login_curl.sh script"
echo "Usage: ./student_view_complaints_curl.sh YOUR_TOKEN_HERE"
echo -e "\nThis endpoint returns all complaints submitted by the authenticated student, including:"
echo "- Complaint ID and nature"
echo "- Description of the issue"
echo "- Current status (pending, in_progress, resolved, rejected)"
echo "- Response from school administration (if available)"
echo "- Submission and resolution timestamps"