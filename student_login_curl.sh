#!/bin/bash

# Use this script to test the student login API
# Replace YOUR_IP with your actual IP address if needed
# Example: bash student_login_curl.sh

IP_ADDRESS=192.168.1.93
PORT=8000

echo "Testing student login API at http://$IP_ADDRESS:$PORT/api/student/login"
echo "This API now returns school name and logo in the response"

curl -X POST \
  http://$IP_ADDRESS:$PORT/api/student/login \
  -H 'Content-Type: application/json' \
  -H 'Accept: application/json' \
  -d '{
    "student_id": "STU12345",
    "password": "password123"
}'

echo -e "\n\nNote: To start the server with a specific IP, run:"
echo "php artisan serve --host=$IP_ADDRESS --port=$PORT"
echo -e "\n\nTo use this API with correct authentication:"
echo "1. First obtain a token using the login API above"
echo "2. Then use the token in the Authorization header for other endpoints:"
echo -e "\ncurl -X GET http://$IP_ADDRESS:$PORT/api/student/profile \\"
echo "  -H 'Authorization: Bearer YOUR_TOKEN_HERE' \\"
echo "  -H 'Accept: application/json'"
echo -e "\nExample response includes:"
echo "- Student information (name, ID, class, etc.)"
echo "- School information (name, logo, tagline)"
echo "- Parent contact details" 