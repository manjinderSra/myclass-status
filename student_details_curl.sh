#!/bin/bash

# Use this script to test the student details API
# Replace YOUR_IP and YOUR_TOKEN with your actual values
# Example: bash student_details_curl.sh YOUR_TOKEN_HERE

IP_ADDRESS=192.168.1.93
PORT=8000
TOKEN=${1:-"YOUR_TOKEN_HERE"}

echo "Testing student details API at http://$IP_ADDRESS:$PORT/api/student/details"
echo "Using token: ${TOKEN:0:10}..."

curl -X GET \
  http://$IP_ADDRESS:$PORT/api/student/details \
  -H "Authorization: Bearer $TOKEN" \
  -H 'Accept: application/json'

echo -e "\n\nNote: To get a token, first login using the student_login_curl.sh script"
echo "Usage: ./student_details_curl.sh YOUR_TOKEN_HERE"
echo -e "\nThis endpoint now returns comprehensive student information, including:"
echo "- Student personal details (name, gender, DOB, blood group, etc.)"
echo "- Class and section information with IDs"
echo "- School information (name, logo, tagline)"
echo "- Parent contact information"
echo "- Academic details (roll number, academic year, status)"
echo -e "\nUse this endpoint to get complete student information in a single API call." 