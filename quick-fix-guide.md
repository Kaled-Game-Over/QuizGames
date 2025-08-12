# 🚨 Quick Fix Guide - 500 Errors

## **Problem**: All APIs returning 500 Internal Server Error

## **🔧 Immediate Fix Steps**

### **Step 1: Clear Laravel Cache**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### **Step 2: Check Database**
```bash
php artisan migrate:status
php artisan migrate
```

### **Step 3: Check Laravel Logs**
```bash
tail -f storage/logs/laravel.log
```

### **Step 4: Test Basic Route**
```bash
curl http://localhost:8000
```

## **📋 Complete API Testing Guide**

### **🔐 Authentication APIs**
- `POST /api/register` - Register user
- `POST /api/login` - Login user
- `POST /api/logout` - Logout user
- `GET /api/user` - Get user profile

### **📊 Dashboard APIs**
- `GET /dashboard/stats` - Get statistics
- `GET /dashboard/players` - Get all players
- `GET /dashboard/children-by-grade` - Get children by grade
- `GET /dashboard/grades-with-maps` - Get curriculum

### **📚 Creation APIs**
- `POST /dashboard/grades` - Create grade
- `POST /dashboard/maps` - Create map
- `POST /dashboard/stages` - Create stage
- `POST /dashboard/lessons` - Create lesson
- `POST /dashboard/game-modes` - Create game mode
- `POST /dashboard/questions` - Create question

### **✏️ Update APIs**
- `PUT /dashboard/lessons/{id}` - Update lesson
- `PUT /dashboard/game-modes/{id}` - Update game mode
- `PUT /dashboard/questions/{id}` - Update question

## **🎯 Expected Responses**

**✅ Success (200)**
```json
{
  "success": true,
  "data": {...}
}
```

**❌ Error (500)**
```json
{
  "message": "Internal Server Error"
}
```

## **🚀 Quick Test Commands**

```bash
# Test registration
curl -X POST "http://localhost:8000/api/register" \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","email":"test@example.com","password":"password123","password_confirmation":"password123"}'

# Test dashboard
curl -X GET "http://localhost:8000/dashboard/stats" \
  -H "Accept: application/json"
```

**Fix the 500 errors first, then test the APIs!** 🎯 