# 🎯 Complete Dashboard API Testing Guide

## 📋 **Quick Start**

1. **Start Server**: `php artisan serve`
2. **Open Tester**: Open `api-tester.html` in browser
3. **Test Authentication First**: Use "Test Login" button
4. **Follow Testing Steps Below**

---

## **🔐 Step 1: Authentication (Required First)**

### **Login**
```bash
POST http://localhost:8000/api/login
Content-Type: application/json

{
    "email": "test@example.com",
    "password": "password123"
}
```

### **Register**
```bash
POST http://localhost:8000/api/register
Content-Type: application/json

{
    "name": "Test Teacher",
    "email": "teacher@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

---

## **📊 Step 2: Analytics APIs**

### **2.1 Dashboard Statistics**
```bash
GET http://localhost:8000/dashboard/stats
Accept: application/json
```

**Response:**
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
        "children_by_grade": [...]
    }
}
```

### **2.2 All Players**
```bash
GET http://localhost:8000/dashboard/players
Accept: application/json
```

### **2.3 Children by Grade**
```bash
GET http://localhost:8000/dashboard/children-by-grade?grade_level=1st Grade
Accept: application/json
```

### **2.4 Complete Curriculum**
```bash
GET http://localhost:8000/dashboard/grades-with-maps
Accept: application/json
```

---

## **📚 Step 3: Creation APIs**

### **3.1 Create Grade**
```bash
POST http://localhost:8000/dashboard/grades
Content-Type: application/json
X-CSRF-TOKEN: your_csrf_token

{
    "name": "Second Grade",
    "description": "Second grade curriculum",
    "level": 2
}
```

### **3.2 Create Map**
```bash
POST http://localhost:8000/dashboard/maps
Content-Type: application/json
X-CSRF-TOKEN: your_csrf_token

{
    "grade_id": 1,
    "name": "Science Adventure",
    "description": "Interactive science learning",
    "image_path": "/images/science-map.png"
}
```

### **3.3 Create Stage**
```bash
POST http://localhost:8000/dashboard/stages
Content-Type: application/json
X-CSRF-TOKEN: your_csrf_token

{
    "map_id": 1,
    "name": "Subtraction Stage",
    "description": "Learn basic subtraction",
    "order": 2,
    "is_unlocked": true
}
```

### **3.4 Create Lesson**
```bash
POST http://localhost:8000/dashboard/lessons
Content-Type: application/json
X-CSRF-TOKEN: your_csrf_token

{
    "stage_id": 1,
    "name": "Basic Subtraction",
    "description": "Learn to subtract numbers 1-10",
    "content": "This lesson teaches basic subtraction concepts.",
    "order": 1
}
```

### **3.5 Create Game Mode**
```bash
POST http://localhost:8000/dashboard/game-modes
Content-Type: application/json
X-CSRF-TOKEN: your_csrf_token

{
    "stage_id": 1,
    "name": "Puzzle Mode",
    "description": "Interactive puzzle solving",
    "type": "puzzle",
    "settings": "{\"time_limit\": 120, \"difficulty\": \"medium\"}"
}
```

### **3.6 Create Question**
```bash
POST http://localhost:8000/dashboard/questions
Content-Type: application/json
X-CSRF-TOKEN: your_csrf_token

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

---

## **✏️ Step 4: Update APIs**

### **4.1 Update Lesson**
```bash
PUT http://localhost:8000/dashboard/lessons/1
Content-Type: application/json
X-CSRF-TOKEN: your_csrf_token

{
    "name": "Updated Basic Addition",
    "description": "Updated lesson description",
    "content": "Updated lesson content with more examples.",
    "order": 1
}
```

### **4.2 Update Game Mode**
```bash
PUT http://localhost:8000/dashboard/game-modes/1
Content-Type: application/json
X-CSRF-TOKEN: your_csrf_token

{
    "name": "Updated Quiz Mode",
    "description": "Updated game mode description",
    "type": "quiz",
    "settings": "{\"time_limit\": 90, \"questions_count\": 15}"
}
```

### **4.3 Update Question**
```bash
PUT http://localhost:8000/dashboard/questions/1
Content-Type: application/json
X-CSRF-TOKEN: your_csrf_token

{
    "question_text": "What is 3 + 4?",
    "question_type": "multiple_choice",
    "options": "[\"5\", \"6\", \"7\", \"8\"]",
    "correct_answer": "7",
    "points": 15,
    "difficulty": "medium"
}
```

---

## **🛠️ Step 5: Using the API Tester**

### **5.1 Button Categories**
- **🔐 Green**: Authentication APIs
- **📊 Yellow**: Analytics APIs  
- **📚 Purple**: Creation APIs
- **✏️ Orange**: Update APIs

### **5.2 Testing Workflow**
1. Click "Test Login" to authenticate
2. Test Analytics APIs (yellow buttons)
3. Test Creation APIs (purple buttons)
4. Test Update APIs (orange buttons)

---

## **🔍 Step 6: Manual Testing with cURL**

### **Analytics Testing**
```bash
# Dashboard stats
curl -X GET "http://localhost:8000/dashboard/stats" \
  -H "Accept: application/json"

# All players
curl -X GET "http://localhost:8000/dashboard/players" \
  -H "Accept: application/json"
```

### **Creation Testing**
```bash
# Create grade
curl -X POST "http://localhost:8000/dashboard/grades" \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: YOUR_CSRF_TOKEN" \
  -d '{"name": "Third Grade", "level": 3}'
```

### **Update Testing**
```bash
# Update lesson
curl -X PUT "http://localhost:8000/dashboard/lessons/1" \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: YOUR_CSRF_TOKEN" \
  -d '{"name": "Updated Lesson"}'
```

---

## **📝 Step 7: Error Responses**

### **Authentication Error (401)**
```json
{"message": "Unauthenticated."}
```

### **CSRF Error (419)**
```json
{"message": "CSRF token mismatch."}
```

### **Validation Error (422)**
```json
{
    "message": "The given data was invalid.",
    "errors": {"name": ["The name field is required."]}
}
```

---

## **✅ Step 8: Testing Checklist**

### **Authentication**
- [ ] Login works with valid credentials
- [ ] Protected routes require authentication
- [ ] Invalid credentials return error

### **Analytics**
- [ ] Dashboard stats return correct data
- [ ] Players API returns user data with children
- [ ] Children by grade filters correctly
- [ ] Grades with maps returns complete structure

### **Creation**
- [ ] Create grade with valid data
- [ ] Create map with valid grade_id
- [ ] Create stage with valid map_id
- [ ] Create lesson with valid stage_id
- [ ] Create game mode with valid stage_id
- [ ] Create question with valid game_mode_id

### **Updates**
- [ ] Update lesson with valid data
- [ ] Update game mode with valid data
- [ ] Update question with valid data
- [ ] Invalid ID returns 404

### **Error Handling**
- [ ] Invalid auth returns 401
- [ ] Missing CSRF returns 419
- [ ] Invalid validation returns 422
- [ ] Not found returns 404

---

## **🚀 Step 9: Complete Testing Flow**

### **9.1 Full Curriculum Creation**
1. Create grade → Get grade_id
2. Create map with grade_id → Get map_id
3. Create stage with map_id → Get stage_id
4. Create lesson with stage_id → Get lesson_id
5. Create game mode with stage_id → Get game_mode_id
6. Create question with game_mode_id
7. Verify with `getGradesWithMaps`

### **9.2 Data Integrity Testing**
- Try creating resources with invalid foreign keys
- Verify foreign key constraints work
- Test with missing required fields

---

## **🎯 Success Criteria**

Your dashboard APIs are ready when:

- [ ] All authentication flows work
- [ ] All analytics APIs return data
- [ ] All creation APIs work
- [ ] All update APIs work
- [ ] Error handling works correctly
- [ ] Data validation prevents invalid input
- [ ] Foreign key relationships maintained
- [ ] API responses follow consistent format

**🎉 Dashboard APIs are ready for frontend integration!**

---

## **📚 Available Routes Summary**

### **Analytics Routes**
- `GET /dashboard/stats` - Dashboard statistics
- `GET /dashboard/players` - All players with children
- `GET /dashboard/children-by-grade` - Children grouped by grade
- `GET /dashboard/grades-with-maps` - Complete curriculum structure

### **Creation Routes**
- `POST /dashboard/grades` - Create grade
- `POST /dashboard/maps` - Create map
- `POST /dashboard/stages` - Create stage
- `POST /dashboard/lessons` - Create lesson
- `POST /dashboard/game-modes` - Create game mode
- `POST /dashboard/questions` - Create question

### **Update Routes**
- `PUT /dashboard/lessons/{id}` - Update lesson
- `PUT /dashboard/game-modes/{id}` - Update game mode
- `PUT /dashboard/questions/{id}` - Update question

**Happy Testing! 🎯** 