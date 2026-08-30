#!/bin/bash
# Debug script for POST requests to Laravel API

# Colors for terminal output
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
BASE_URL="http://192.168.1.93:8000/api"
STUDENT_ID="1"  # Replace with a valid student ID
STUDENT_PASSWORD="password123"

echo -e "${BLUE}=== Laravel API POST Request Debugging ===${NC}\n"

# Step 1: Test the API server health
echo -e "${YELLOW}Step 1: Testing API server health...${NC}"
if curl -s --head --request GET "${BASE_URL}" | grep "200 OK" > /dev/null; then 
    echo -e "${GREEN}API server is responding with 200 OK${NC}"
else
    echo -e "${RED}API server is not responding properly. Check if Laravel is running.${NC}"
    echo -e "${YELLOW}Trying to reach: ${BASE_URL}${NC}"
    curl -v --head --request GET "${BASE_URL}"
fi

echo -e "\n----------------------------------------\n"

# Step 2: Authenticate and get token
echo -e "${YELLOW}Step 2: Attempting to authenticate...${NC}"
AUTH_RESPONSE=$(curl -s -X POST \
  "${BASE_URL}/student/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "student_id": "'"${STUDENT_ID}"'",
    "password": "'"${STUDENT_PASSWORD}"'"
  }')

echo -e "Auth response:\n${AUTH_RESPONSE}\n"

# Try to extract token
TOKEN=$(echo "${AUTH_RESPONSE}" | grep -o '"token":"[^"]*' | sed 's/"token":"//')

if [[ -z "$TOKEN" ]]; then
    echo -e "${RED}Failed to obtain authentication token. Using a placeholder.${NC}"
    TOKEN="DEBUG_TEST_TOKEN"
else
    echo -e "${GREEN}Successfully obtained authentication token.${NC}"
fi

echo -e "\n----------------------------------------\n"

# Step 3: Test POST request with different content types
echo -e "${YELLOW}Step 3: Testing POST requests with different content types...${NC}"

# Test 1: JSON Content Type
echo -e "${BLUE}Test 1: Using application/json content type...${NC}"
curl -v -X POST \
  "${BASE_URL}/student/leaves" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "reason": "Debug Test",
    "description": "Testing POST requests",
    "from_date": "2023-12-20",
    "to_date": "2023-12-22"
  }'

echo -e "\n----------------------------------------\n"

# Test 2: Form URL Encoded
echo -e "${BLUE}Test 2: Using application/x-www-form-urlencoded content type...${NC}"
curl -v -X POST \
  "${BASE_URL}/student/leaves" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  --data-urlencode "reason=Debug Test Form" \
  --data-urlencode "description=Testing POST requests with form data" \
  --data-urlencode "from_date=2023-12-20" \
  --data-urlencode "to_date=2023-12-22"

echo -e "\n----------------------------------------\n"

# Test 3: Multipart Form Data
echo -e "${BLUE}Test 3: Using multipart/form-data content type...${NC}"
curl -v -X POST \
  "${BASE_URL}/student/leaves" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json" \
  -F "reason=Debug Test Multipart" \
  -F "description=Testing POST requests with multipart form data" \
  -F "from_date=2023-12-20" \
  -F "to_date=2023-12-22"

echo -e "\n----------------------------------------\n"

# Step 4: Check CSRF protection
echo -e "${YELLOW}Step 4: Testing with CSRF token...${NC}"
echo -e "${BLUE}Attempting to get CSRF token from web page...${NC}"

# Try to get CSRF token
CSRF_TOKEN=$(curl -s -c cookie.txt -b cookie.txt "http://localhost:8000" | grep -o 'name="_token" value="[^"]*' | sed 's/name="_token" value="//')

if [[ -z "$CSRF_TOKEN" ]]; then
    echo -e "${RED}Failed to obtain CSRF token. Using a placeholder.${NC}"
    CSRF_TOKEN="DEBUG_CSRF_TOKEN"
else
    echo -e "${GREEN}Successfully obtained CSRF token: ${CSRF_TOKEN}${NC}"
fi

# Test with CSRF token
echo -e "${BLUE}Test 4: Using CSRF token with POST request...${NC}"
curl -v -X POST \
  "${BASE_URL}/student/leaves" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: ${CSRF_TOKEN}" \
  -b cookie.txt \
  -c cookie.txt \
  -d '{
    "reason": "CSRF Test",
    "description": "Testing POST requests with CSRF token",
    "from_date": "2023-12-20",
    "to_date": "2023-12-22"
  }'

echo -e "\n----------------------------------------\n"

# Step 5: Debugging tips
echo -e "${YELLOW}Step 5: Debugging Tips${NC}"
echo -e "${GREEN}1. Check Laravel logs in storage/logs/laravel.log${NC}"
echo -e "${GREEN}2. Ensure API routes are properly defined in routes/api.php${NC}"
echo -e "${GREEN}3. Check middleware for any request blocking${NC}"
echo -e "${GREEN}4. Verify that the controller methods correctly handle the request data${NC}"
echo -e "${GREEN}5. Test the same endpoints in Postman or similar tools${NC}"

echo -e "\n----------------------------------------\n"
echo -e "${BLUE}=== Debugging Complete ===${NC}\n"

# Clean up
rm -f cookie.txt
