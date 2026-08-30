#!/bin/bash

# Colors for terminal output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# API Base URL - Change this to match your environment
API_BASE_URL="http://localhost:8000/api"

# Function to show usage instructions
show_usage() {
    echo -e "${YELLOW}Usage:${NC}"
    echo -e "  $0 <token>"
    echo -e "\n${YELLOW}Description:${NC}"
    echo -e "  This script tests the teacher students API endpoint to retrieve all students taught by the authenticated teacher."
    echo -e "\n${YELLOW}Parameters:${NC}"
    echo -e "  ${GREEN}token${NC}  - The teacher's authentication token"
    echo -e "\n${YELLOW}Example:${NC}"
    echo -e "  $0 1|abcdefghijklmnopqrstuvwxyz123456"
}

# Check if token is provided
if [ -z "$1" ]; then
    echo -e "${RED}Error: Missing required parameter - token${NC}"
    show_usage
    exit 1
fi

TOKEN="$1"

# Make the API request to get students taught by the teacher
echo -e "\n${YELLOW}Fetching students taught by the teacher...${NC}"
curl -s -X GET \
    -H "Accept: application/json" \
    -H "Authorization: Bearer $TOKEN" \
    "${API_BASE_URL}/teacher/students" | jq

echo -e "\n${GREEN}Request completed.${NC}" 