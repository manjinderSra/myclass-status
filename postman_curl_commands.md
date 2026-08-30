# Teacher Homework API - Postman CURL Commands

Below are the curl commands for testing the Teacher Homework API endpoints in Postman. Replace `YOUR_AUTH_TOKEN` with the actual teacher authentication token.

## 1. Get All Homework (GET /api/teacher/homework)

```bash
curl --location 'http://localhost:8000/api/teacher/homework' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer YOUR_AUTH_TOKEN'
```

### With Filters

```bash
curl --location 'http://localhost:8000/api/teacher/homework?class=Class%2010&section=A&date_from=2023-01-01&date_to=2023-12-31' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer YOUR_AUTH_TOKEN'
```

## 2. Create New Homework (POST /api/teacher/homework)

```bash
curl --location 'http://localhost:8000/api/teacher/homework' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer YOUR_AUTH_TOKEN' \
--form 'class="Class 10"' \
--form 'section="A"' \
--form 'homework_date="2023-06-15"' \
--form 'submission_date="2023-06-20"' \
--form 'description="Complete exercises 1-10 from Chapter 5"' \
--form 'image=@"/path/to/your/image.jpg"'
```

## 3. Get Specific Homework (GET /api/teacher/homework/{id})

```bash
curl --location 'http://localhost:8000/api/teacher/homework/1' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer YOUR_AUTH_TOKEN'
```

## 4. Update Homework (PUT /api/teacher/homework/{id})

```bash
curl --location --request PUT 'http://localhost:8000/api/teacher/homework/1' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer YOUR_AUTH_TOKEN' \
--form 'class="Class 10"' \
--form 'section="A"' \
--form 'homework_date="2023-06-15"' \
--form 'submission_date="2023-06-22"' \
--form 'description="Complete exercises 1-15 from Chapter 5"' \
--form 'image=@"/path/to/your/updated_image.jpg"'
```

## 5. Delete Homework (DELETE /api/teacher/homework/{id})

```bash
curl --location --request DELETE 'http://localhost:8000/api/teacher/homework/1' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer YOUR_AUTH_TOKEN'
```

## Notes for Postman Import

1. In Postman, you can directly import these curl commands:
   - Click on "Import" in the top left
   - Select the "Raw text" tab
   - Paste any of the curl commands above
   - Click "Continue" and then "Import"

2. For testing with Postman:
   - Create an environment variable called `token` with your authentication token
   - Replace `YOUR_AUTH_TOKEN` with `{{token}}` in the requests
   - Set the base URL as an environment variable for easier management

3. For file uploads in Postman:
   - In the request builder, select "Body" tab
   - Choose "form-data"
   - For the image field, select "File" from the dropdown and browse for your file 