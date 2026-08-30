#!/bin/bash

# This script tests the student announcements API endpoint

# Check if the token was provided
if [ "$1" == "" ]; then
    echo "Usage: $0 <student_token>"
    echo "Example: $0 'your_auth_token_here'"
    exit 1
fi

# Base URL for the API
API_URL="http://localhost:8000/api"

# Student token from command line argument
STUDENT_TOKEN=$1

# Test the announcements endpoint
echo "Testing Student Announcements API..."
curl -s -X GET \
    -H "Authorization: Bearer ${STUDENT_TOKEN}" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    "${API_URL}/student/announcements" | jq

echo "Done!" 