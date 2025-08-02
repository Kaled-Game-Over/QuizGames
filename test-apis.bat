@echo off
echo ========================================
echo API Testing Script
echo ========================================
echo.

echo 1. Testing Register API...
curl -X POST http://localhost:8000/api/register ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"name\": \"Test User\", \"email\": \"test@example.com\", \"password\": \"password123\", \"password_confirmation\": \"password123\"}"
echo.
echo.

echo 2. Testing Login API...
curl -X POST http://localhost:8000/api/login ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"email\": \"test@example.com\", \"password\": \"password123\"}"
echo.
echo.

echo 3. Testing Assign Teacher Role API...
curl -X POST http://localhost:8000/api/assign-teacher-role ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"email\": \"test@example.com\"}"
echo.
echo.

echo Note: For protected routes, you need to add the Authorization header with your token
echo Example: -H "Authorization: Bearer YOUR_TOKEN_HERE"
echo.
pause 