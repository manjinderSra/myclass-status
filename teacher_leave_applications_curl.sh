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
    echo -e "  $0 <action> <token> [params]"
    echo -e "\n${YELLOW}Actions:${NC}"
    echo -e "  ${GREEN}list${NC}         - List all leave applications"
    echo -e "  ${GREEN}list-status${NC}  - List leave applications by status (pending, approved, rejected)"
    echo -e "  ${GREEN}view${NC}         - View details of a specific leave application"
    echo -e "  ${GREEN}update${NC}       - Update status of a leave application"
    echo -e "\n${YELLOW}Parameters:${NC}"
    echo -e "  ${GREEN}token${NC}        - The teacher's authentication token"
    echo -e "  ${GREEN}status${NC}       - For list-status: pending, approved, or rejected"
    echo -e "  ${GREEN}id${NC}           - For view/update: The leave application ID"
    echo -e "  ${GREEN}new_status${NC}   - For update: pending, approved, or rejected"
    echo -e "  ${GREEN}remarks${NC}      - For update: Remarks for the leave application (required for rejection)"
    echo -e "\n${YELLOW}Examples:${NC}"
    echo -e "  $0 list 1|abcdefghijklmnopqrstuvwxyz123456"
    echo -e "  $0 list-status 1|abcdefghijklmnopqrstuvwxyz123456 pending"
    echo -e "  $0 view 1|abcdefghijklmnopqrstuvwxyz123456 5"
    echo -e "  $0 update 1|abcdefghijklmnopqrstuvwxyz123456 5 approved \"Leave approved\""
}

# Check if action and token are provided
if [ -z "$1" ] || [ -z "$2" ]; then
    echo -e "${RED}Error: Missing required parameters${NC}"
    show_usage
    exit 1
fi

ACTION="$1"
TOKEN="$2"

case "$ACTION" in
    list)
        echo -e "\n${YELLOW}Fetching all leave applications...${NC}"
        curl -s -X GET \
            -H "Accept: application/json" \
            -H "Authorization: Bearer $TOKEN" \
            "${API_BASE_URL}/teacher/leave-applications" | jq
        ;;
        
    list-status)
        if [ -z "$3" ]; then
            echo -e "${RED}Error: Missing status parameter${NC}"
            show_usage
            exit 1
        fi
        STATUS="$3"
        echo -e "\n${YELLOW}Fetching leave applications with status: ${STATUS}...${NC}"
        curl -s -X GET \
            -H "Accept: application/json" \
            -H "Authorization: Bearer $TOKEN" \
            "${API_BASE_URL}/teacher/leave-applications?status=${STATUS}" | jq
        ;;
        
    view)
        if [ -z "$3" ]; then
            echo -e "${RED}Error: Missing leave application ID${NC}"
            show_usage
            exit 1
        fi
        ID="$3"
        echo -e "\n${YELLOW}Fetching details for leave application ID: ${ID}...${NC}"
        curl -s -X GET \
            -H "Accept: application/json" \
            -H "Authorization: Bearer $TOKEN" \
            "${API_BASE_URL}/teacher/leave-applications/${ID}" | jq
        ;;
        
    update)
        if [ -z "$3" ] || [ -z "$4" ]; then
            echo -e "${RED}Error: Missing required parameters${NC}"
            show_usage
            exit 1
        fi
        ID="$3"
        NEW_STATUS="$4"
        REMARKS="${5:-}"
        
        echo -e "\n${YELLOW}Updating leave application ID: ${ID} to status: ${NEW_STATUS}...${NC}"
        curl -s -X POST \
            -H "Accept: application/json" \
            -H "Content-Type: application/json" \
            -H "Authorization: Bearer $TOKEN" \
            -d "{\"status\":\"${NEW_STATUS}\", \"admin_remarks\":\"${REMARKS}\"}" \
            "${API_BASE_URL}/teacher/leave-applications/${ID}/update-status" | jq
        ;;
        
    *)
        echo -e "${RED}Error: Invalid action '${ACTION}'${NC}"
        show_usage
        exit 1
        ;;
esac

echo -e "\n${GREEN}Request completed.${NC}" 