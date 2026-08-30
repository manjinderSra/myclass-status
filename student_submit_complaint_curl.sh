#!/bin/bash

# Use this script to test the student complaint submission API
# Replace YOUR_IP and YOUR_TOKEN with your actual values
# Example: bash student_submit_complaint_curl.sh YOUR_TOKEN_HERE

IP_ADDRESS=192.168.1.93
PORT=8000
TOKEN=${1:-"test_token_12345"}  # Using a test token for troubleshooting

echo "Testing complaint submission API at http://$IP_ADDRESS:$PORT/api/student/complaints"
echo "Using token: ${TOKEN:0:10}..."

curl -X POST \
  http://$IP_ADDRESS:$PORT/api/student/complaints \
  -H "Authorization: Bearer $TOKEN" \
  -H 'Accept: application/json' \
  -H 'Content-Type: application/json' \
  -d '{
    "nature": "Academic Issue", 
    "description": "I am having trouble understanding the algebra lessons. Would it be possible to arrange some extra classes or tutoring sessions?"
  }'

echo -e "\n\nNote: To get a token, first login using the student_login_curl.sh script"
echo "Usage: ./student_submit_complaint_curl.sh YOUR_TOKEN_HERE"
echo -e "\nThis endpoint allows students to submit complaints to the school with:"
echo "- Nature/type of complaint"
echo "- Detailed description of the issue"
echo "The complaint will be available for review in the school panel's Complaint Box section." 