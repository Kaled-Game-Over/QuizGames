# 🎯 Complete Dashboard API Testing Guide

## 📋 **Overview**
This guide provides comprehensive testing instructions for all Dashboard APIs in the Quiz Games application. The dashboard APIs are designed for curriculum management, analytics, and content creation.

---

## **🚀 Quick Setup**

### **1. Start the Server**
```bash
php artisan serve
```
Server will run at: `http://localhost:8000`

### **2. Open the API Tester**
- Open `api-tester.html` in your browser
- Or navigate to: `http://localhost:8000/api-tester`

### **3. Authentication First**
All dashboard APIs require authentication. Start by testing login/register.

---

## **🔐 Step 1: Authentication Testing**

### **1.1 Register a New User**
```bash
# Method: POST
# URL: http://localhost:8000/api/register
# Headers: Content-Type: application/json
# Body:
{
    "name": "Test Teacher",
    "email": "teacher@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Expected Response:**
```json
{
    "success": true,
    "message": "User registered successfully",
    "token": "your_auth_token_here"
}
```

### **1.2 Login**
```bash
# Method: POST
# URL: http://localhost:8000/api/login
# Headers: Content-Type: application/json
# Body:
{
    "email": "teacher@example.com",
    "password": "password123"
}
```

### **1.3 Assign Teacher Role**
```bash
# Method: POST
# URL: http://localhost:8000/api/assign-teacher-role
# Headers: Content-Type: application/json
# Body:
{
    "email": "teacher@example.com"
}
```

---

## **📊 Step 2: Analytics APIs Testing**

### **2.1 Get Dashboard Statistics**
```bash
# Method: GET
# URL: http://localhost:8000/dashboard/stats
# Headers: Accept: application/json
```

**Expected Response:**
```json
{
    "success": true,
    "data": {
        "total_players": 5,
        "total_children": 12,
        "total_grades": 3,
        "total_maps": 6,
        "total_stages": 18,
        "total_lessons": 54,
        "total_game_modes": 18,
        "total_questions": 108,
        "children_by_grade": [
            {"grade_level": "1st Grade", "count": 4},
            {"grade_level": "2nd Grade", "count": 3}
        ]
    }
}
```

### **2.2 Get All Players**
```bash
# Method: GET
# URL: http://localhost:8000/dashboard/players
# Headers: Accept: application/json
```

**Expected Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "children": [
                {
                    "id": 1,
                    "name": "Alice",
                    "age": 8,
                    "grade_level": "3rd Grade",
                    "performance": [],
                    "grades": []
                }
            ]
        }
    ]
}
```

### **2.3 Get Children by Grade**
```bash
# Method: GET
# URL: http://localhost:8000/dashboard/children-by-grade?grade_level=1st Grade
# Headers: Accept: application/json
```

**Expected Response:**
```json
{
    "success": true,
    "data": {
        "1st Grade": [
            {
                "id": 1,
                "name": "Alice",
                "age": 6,
                "grade_level": "1st Grade",
                "user": {...},
                "performance": {...},
                "grades": [...]
            }
        ]
    }
}
```

### **2.4 Get Complete Curriculum Structure**
```bash
# Method: GET
# URL: http://localhost:8000/dashboard/grades-with-maps
# Headers: Accept: application/json
```

**Expected Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "First Grade",
            "level": 1,
            "maps": [
                {
                    "id": 1,
                    "name": "Math Adventure",
                    "stages": [
                        {
                            "id": 1,
                            "name": "Addition Stage",
                            "lessons": [...],
                            "gameModes": [...]
                        }
                    ]
                }
            ]
        }
    ]
}
```

---

## **📚 Step 3: Curriculum Creation APIs**

### **3.1 Create Grade/Curriculum**
```bash
# Method: POST
# URL: http://localhost:8000/dashboard/grades
# Headers: 
#   Content-Type: application/json
#   Accept: application/json
#   X-CSRF-TOKEN: your_csrf_token
# Body:
{
    "name": "Second Grade",
    "description": "Second grade curriculum with advanced concepts",
    "level": 2
}
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Grade created successfully",
    "data": {
        "id": 2,
        "name": "Second Grade",
        "description": "Second grade curriculum with advanced concepts",
        "level": 2
    }
}
```

### **3.2 Create Map for Grade**
```bash
# Method: POST
# URL: http://localhost:8000/dashboard/maps
# Headers: 
#   Content-Type: application/json
#   Accept: application/json
#   X-CSRF-TOKEN: your_csrf_token
# Body:
{
    "grade_id": 1,
    "name": "Science Adventure Map",
    "description": "Interactive science learning map",
    "image_path": "/images/science-map.png"
}
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Map created successfully",
    "data": {
        "id": 2,
        "grade_id": 1,
        "name": "Science Adventure Map",
        "description": "Interactive science learning map",
        "image_path": "/images/science-map.png"
    }
}
```

### **3.3 Create Stage in Map**
```bash
# Method: POST
# URL: http://localhost:8000/dashboard/stages
# Headers: 
#   Content-Type: application/json
#   Accept: application/json
#   X-CSRF-TOKEN: your_csrf_token
# Body:
{
    "map_id": 1,
    "name": "Subtraction Stage",
    "description": "Learn basic subtraction concepts",
    "order": 2,
    "is_unlocked": true
}
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Stage created successfully",
    "data": {
        "id": 2,
        "map_id": 1,
        "name": "Subtraction Stage",
        "description": "Learn basic subtraction concepts",
        "order": 2,
        "is_unlocked": true
    }
}
```

### **3.4 Create Lesson in Stage**
```bash
# Method: POST
# URL: http://localhost:8000/dashboard/lessons
# Headers: 
#   Content-Type: application/json
#   Accept: application/json
#   X-CSRF-TOKEN: your_csrf_token
# Body:
{
    "stage_id": 1,
    "name": "Basic Subtraction",
    "description": "Learn to subtract numbers 1-10",
    "content": "This lesson teaches basic subtraction concepts with interactive examples.",
    "order": 1
}
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Lesson created successfully",
    "data": {
        "id": 2,
        "stage_id": 1,
        "name": "Basic Subtraction",
        "description": "Learn to subtract numbers 1-10",
        "content": "This lesson teaches basic subtraction concepts with interactive examples.",
        "order": 1
    }
}
```

### **3.5 Create Game Mode**
```bash
# Method: POST
# URL: http://localhost:8000/dashboard/game-modes
# Headers: 
#   Content-Type: application/json
#   Accept: application/json
#   X-CSRF-TOKEN: your_csrf_token
# Body:
{
    "stage_id": 1,
    "name": "Puzzle Mode",
    "description": "Interactive puzzle solving game",
    "type": "puzzle",
    "settings": "{\"time_limit\": 120, \"difficulty\": \"medium\", \"hints_enabled\": true}"
}
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Game mode created successfully",
    "data": {
        "id": 2,
        "stage_id": 1,
        "name": "Puzzle Mode",
        "description": "Interactive puzzle solving game",
        "type": "puzzle",
        "settings": "{\"time_limit\": 120, \"difficulty\": \"medium\", \"hints_enabled\": true}"
    }
}
```

### **3.6 Create Question**
```bash
# Method: POST
# URL: http://localhost:8000/dashboard/questions
# Headers: 
#   Content-Type: application/json
#   Accept: application/json
#   X-CSRF-TOKEN: your_csrf_token
# Body:
{
    "game_mode_id": 1,
    "question_text": "What is 5 - 2?",
    "question_type": "multiple_choice",
    "options": "[\"2\", \"3\", \"4\", \"5\"]",
    "correct_answer": "3",
    "points": 10,
    "difficulty": "easy"
}
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Question created successfully",
    "data": {
        "id": 2,
        "game_mode_id": 1,
        "question_text": "What is 5 - 2?",
        "question_type": "multiple_choice",
        "options": "[\"2\", \"3\", \"4\", \"5\"]",
        "correct_answer": "3",
        "points": 10,
        "difficulty": "easy"
    }
}
```

---

## **✏️ Step 4: Update APIs Testing**

### **4.1 Update Lesson**
```bash
# Method: PUT
# URL: http://localhost:8000/dashboard/lessons/1
# Headers: 
#   Content-Type: application/json
#   Accept: application/json
#   X-CSRF-TOKEN: your_csrf_token
# Body:
{
    "name": "Updated Basic Addition",
    "description": "Updated lesson description with more details",
    "content": "Updated lesson content with more examples and interactive elements.",
    "order": 1
}
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Lesson updated successfully",
    "data": {
        "id": 1,
        "stage_id": 1,
        "name": "Updated Basic Addition",
        "description": "Updated lesson description with more details",
        "content": "Updated lesson content with more examples and interactive elements.",
        "order": 1
    }
}
```

### **4.2 Update Game Mode**
```bash
# Method: PUT
# URL: http://localhost:8000/dashboard/game-modes/1
# Headers: 
#   Content-Type: application/json
#   Accept: application/json
#   X-CSRF-TOKEN: your_csrf_token
# Body:
{
    "name": "Updated Quiz Mode",
    "description": "Updated game mode with enhanced features",
    "type": "quiz",
    "settings": "{\"time_limit\": 90, \"questions_count\": 15, \"shuffle_questions\": true}"
}
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Game mode updated successfully",
    "data": {
        "id": 1,
        "stage_id": 1,
        "name": "Updated Quiz Mode",
        "description": "Updated game mode with enhanced features",
        "type": "quiz",
        "settings": "{\"time_limit\": 90, \"questions_count\": 15, \"shuffle_questions\": true}"
    }
}
```

### **4.3 Update Question**
```bash
# Method: PUT
# URL: http://localhost:8000/dashboard/questions/1
# Headers: 
#   Content-Type: application/json
#   Accept: application/json
#   X-CSRF-TOKEN: your_csrf_token
# Body:
{
    "question_text": "What is 3 + 4?",
    "question_type": "multiple_choice",
    "options": "[\"5\", \"6\", \"7\", \"8\"]",
    "correct_answer": "7",
    "points": 15,
    "difficulty": "medium"
}
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Question updated successfully",
    "data": {
        "id": 1,
        "game_mode_id": 1,
        "question_text": "What is 3 + 4?",
        "question_type": "multiple_choice",
        "options": "[\"5\", \"6\", \"7\", \"8\"]",
        "correct_answer": "7",
        "points": 15,
        "difficulty": "medium"
    }
}
```

---

## **🛠️ Step 5: Using the API Tester Interface**

### **5.1 Opening the API Tester**
1. Start your Laravel server: `php artisan serve`
2. Open `api-tester.html` in your browser
3. The interface will show all available API endpoints

### **5.2 Testing Workflow**
1. **Authentication First**: Click "Test Login" or "Test Register"
2. **Analytics Testing**: Use the yellow dashboard buttons
3. **Creation Testing**: Use the purple curriculum buttons
4. **Update Testing**: Use the orange update buttons

### **5.3 Button Categories**
- **🔐 Green Buttons**: Authentication APIs
- **👥 Blue Buttons**: User Management APIs
- **📊 Yellow Buttons**: Dashboard Analytics APIs
- **📚 Purple Buttons**: Curriculum Creation APIs
- **✏️ Orange Buttons**: Update APIs

---

## **🔍 Step 6: Manual Testing with cURL**

### **6.1 Test Analytics APIs**
```bash
# Get dashboard statistics
curl -X GET "http://localhost:8000/dashboard/stats" \
  -H "Accept: application/json"

# Get all players
curl -X GET "http://localhost:8000/dashboard/players" \
  -H "Accept: application/json"

# Get children by grade
curl -X GET "http://localhost:8000/dashboard/children-by-grade?grade_level=1st%20Grade" \
  -H "Accept: application/json"

# Get complete curriculum
curl -X GET "http://localhost:8000/dashboard/grades-with-maps" \
  -H "Accept: application/json"
```

### **6.2 Test Creation APIs**
```bash
# Create a grade
curl -X POST "http://localhost:8000/dashboard/grades" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-CSRF-TOKEN: YOUR_CSRF_TOKEN" \
  -d '{
    "name": "Third Grade",
    "description": "Third grade curriculum",
    "level": 3
  }'

# Create a map
curl -X POST "http://localhost:8000/dashboard/maps" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-CSRF-TOKEN: YOUR_CSRF_TOKEN" \
  -d '{
    "grade_id": 1,
    "name": "English Adventure",
    "description": "Interactive English learning",
    "image_path": "/images/english-map.png"
  }'
```

### **6.3 Test Update APIs**
```bash
# Update a lesson
curl -X PUT "http://localhost:8000/dashboard/lessons/1" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-CSRF-TOKEN: YOUR_CSRF_TOKEN" \
  -d '{
    "name": "Updated Lesson Name",
    "description": "Updated description"
  }'
```

---

## **📝 Step 7: Error Handling & Validation**

### **7.1 Common Error Responses**

**Authentication Error (401):**
```json
{
    "message": "Unauthenticated."
}
```

**CSRF Token Error (419):**
```json
{
    "message": "CSRF token mismatch."
}
```

**Validation Error (422):**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "name": ["The name field is required."],
        "email": ["The email field is required."]
    }
}
```

**Not Found Error (404):**
```json
{
    "message": "No query results for model [App\\Models\\Lesson] 1"
}
```

### **7.2 Required Fields Validation**

**Grade Creation:**
- `name` (required, string, max 255)
- `level` (required, integer, min 1)
- `description` (optional, string)

**Map Creation:**
- `grade_id` (required, exists in grades table)
- `name` (required, string, max 255)
- `description` (optional, string)
- `image_path` (optional, string)

**Stage Creation:**
- `map_id` (required, exists in maps table)
- `name` (required, string, max 255)
- `order` (required, integer, min 1)
- `description` (optional, string)
- `is_unlocked` (optional, boolean)

**Lesson Creation:**
- `stage_id` (required, exists in stages table)
- `name` (required, string, max 255)
- `order` (required, integer, min 1)
- `description` (optional, string)
- `content` (optional, string)

**Game Mode Creation:**
- `stage_id` (required, exists in stages table)
- `name` (required, string, max 255)
- `type` (required, string, max 255)
- `description` (optional, string)
- `settings` (optional, JSON)

**Question Creation:**
- `game_mode_id` (required, exists in game_modes table)
- `question_text` (required, string)
- `question_type` (required, string, max 255)
- `correct_answer` (required, string)
- `points` (required, integer, min 1)
- `difficulty` (required, string, in: easy,medium,hard)
- `options` (optional, JSON)

---

## **✅ Step 8: Testing Checklist**

### **Authentication Testing**
- [ ] Register new user works
- [ ] Login with valid credentials works
- [ ] Login with invalid credentials returns error
- [ ] Assign teacher role works
- [ ] Protected routes require authentication

### **Analytics Testing**
- [ ] Get dashboard stats returns correct data
- [ ] Get all players returns user data with children
- [ ] Get children by grade filters correctly
- [ ] Get grades with maps returns complete structure

### **Creation Testing**
- [ ] Create grade with valid data
- [ ] Create map with valid grade_id
- [ ] Create stage with valid map_id
- [ ] Create lesson with valid stage_id
- [ ] Create game mode with valid stage_id
- [ ] Create question with valid game_mode_id

### **Update Testing**
- [ ] Update lesson with valid data
- [ ] Update game mode with valid data
- [ ] Update question with valid data
- [ ] Update with invalid ID returns 404

### **Error Handling**
- [ ] Invalid authentication returns 401
- [ ] Missing CSRF token returns 419
- [ ] Invalid validation returns 422
- [ ] Not found resources return 404

---

## **🚀 Step 9: Advanced Testing Scenarios**

### **9.1 Complete Curriculum Creation Flow**
1. Create a grade
2. Create multiple maps for the grade
3. Create stages for each map
4. Create lessons for each stage
5. Create game modes for each stage
6. Create questions for each game mode
7. Verify the complete structure with `getGradesWithMaps`

### **9.2 Data Integrity Testing**
1. Try to create a map with non-existent grade_id
2. Try to create a stage with non-existent map_id
3. Try to create a lesson with non-existent stage_id
4. Verify foreign key constraints work correctly

### **9.3 Performance Testing**
1. Create large amounts of test data
2. Test analytics APIs with large datasets
3. Verify response times are acceptable

---

## **📊 Step 10: API Response Examples**

### **10.1 Successful Creation Response**
```json
{
    "success": true,
    "message": "Resource created successfully",
    "data": {
        "id": 1,
        "name": "Example Resource",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

### **10.2 Analytics Response**
```json
{
    "success": true,
    "data": {
        "total_players": 10,
        "total_children": 25,
        "total_grades": 5,
        "total_maps": 15,
        "total_stages": 45,
        "total_lessons": 135,
        "total_game_modes": 45,
        "total_questions": 270,
        "children_by_grade": [
            {"grade_level": "1st Grade", "count": 8},
            {"grade_level": "2nd Grade", "count": 7},
            {"grade_level": "3rd Grade", "count": 10}
        ]
    }
}
```

### **10.3 Error Response**
```json
{
    "success": false,
    "message": "Error description here"
}
```

---

## **🎯 Step 11: Integration Testing**

### **11.1 Frontend Integration**
Once all APIs are tested and working:

1. **Dashboard Analytics**: Display real-time statistics
2. **Curriculum Management**: Create and edit curriculum content
3. **User Management**: Manage players and children
4. **Content Updates**: Modify lessons, game modes, and questions

### **11.2 Real-time Updates**
- Use WebSockets for live dashboard updates
- Implement real-time notifications
- Show live player activity

### **11.3 Data Visualization**
- Create charts for analytics data
- Display curriculum structure visually
- Show player progress tracking

---

## **🔧 Troubleshooting**

### **Common Issues:**

1. **CSRF Token Issues**
   - Ensure you're including the CSRF token in headers
   - Check that the token is valid and not expired

2. **Authentication Issues**
   - Verify you're logged in before testing protected routes
   - Check that the token is being sent correctly

3. **Validation Errors**
   - Review the required fields for each API
   - Ensure data types match the requirements

4. **Database Issues**
   - Run migrations: `php artisan migrate`
   - Seed test data: `php artisan db:seed`

---

## **🎉 Success Criteria**

Your dashboard APIs are fully functional when:

- [ ] All authentication flows work correctly
- [ ] All analytics APIs return proper data
- [ ] All creation APIs create resources successfully
- [ ] All update APIs modify resources correctly
- [ ] Error handling works for all scenarios
- [ ] Data validation prevents invalid input
- [ ] Foreign key relationships are maintained
- [ ] API responses follow consistent format

**🚀 Your Dashboard APIs are ready for production!**

---

## **📚 Additional Resources**

- **API Tester**: `api-tester.html`
- **Route Definitions**: `routes/web.php`
- **Controller Logic**: `app/Http/Controllers/DashboardController.php`
- **Database Models**: `app/Models/`

**Happy Testing! 🎯** 