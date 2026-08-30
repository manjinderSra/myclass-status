#!/bin/bash
# API curl commands for testing student leave application endpoints

# Base URL for API calls
BASE_URL="http://192.168.1.93:8000/api"

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${YELLOW}Student Leave Application API Testing${NC}\n"

# 0. Try to authenticate with hard-coded student credentials
echo -e "${GREEN}0. Attempting to authenticate with a known student...${NC}"

# Try different student_id combinations 
STUDENT_IDS=("9" "S001" "ST001" "ST-2023-001" "student1")
PASSWORD="password123"

for STUDENT_ID in "${STUDENT_IDS[@]}"; do
  echo -e "${YELLOW}Trying student_id: ${STUDENT_ID}${NC}"
  LOGIN_RESPONSE=$(curl -s -X POST \
    "${BASE_URL}/student/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{
      "student_id": "'${STUDENT_ID}'",
      "password": "'${PASSWORD}'"
    }')
  
  echo "$LOGIN_RESPONSE" | jq
  
  # Extract token if login was successful
  TOKEN=$(echo "$LOGIN_RESPONSE" | jq -r '.data.token')
  
  # If we got a token, break the loop
  if [[ "$TOKEN" != "null" && -n "$TOKEN" ]]; then
    echo -e "${GREEN}Login successful with student_id: ${STUDENT_ID}!${NC}"
    BEARER_TOKEN="$TOKEN"
    break
  fi
done

# If we didn't get a token, use a placeholder
if [[ -z "$BEARER_TOKEN" || "$BEARER_TOKEN" == "null" ]]; then
  echo -e "${RED}Could not authenticate with any student credentials. Using a placeholder token.${NC}"
  # Try to get an actual token from an admin or another API
  BEARER_TOKEN="test_token_for_debugging"
fi

echo -e "\n----------------------------------------\n"

# 1. Submit a new leave application - Testing the fixed ID generation
echo -e "${GREEN}1. Submitting a new leave application with the fixed ID generation...${NC}"
SUBMIT_RESPONSE=$(curl -v -X POST \
  "${BASE_URL}/student/leaves" \
  -H "Authorization: Bearer ${BEARER_TOKEN}" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "reason": "Testing Fixed ID Generation",
    "description": "This is a test to see if the fixed leave ID generation works properly.",
    "from_date": "2023-12-20",
    "to_date": "2023-12-22"
  }')

echo -e "\n${SUBMIT_RESPONSE}\n"

echo -e "\n----------------------------------------\n"

# 2. Get all leave applications to verify the new one was created
echo -e "${GREEN}2. Fetching all leave applications to verify the new one...${NC}"
curl -s -X GET \
  "${BASE_URL}/student/leaves" \
  -H "Authorization: Bearer ${BEARER_TOKEN}" \
  -H "Accept: application/json" | jq

echo -e "\n----------------------------------------\n"

echo -e "${GREEN}API Testing Complete!${NC}"
