# Dashboard API Testing Script
Write-Host "=== COMPREHENSIVE DASHBOARD API TESTING ===" -ForegroundColor Green
Write-Host "Starting tests..." -ForegroundColor Yellow

# Test 1: Authentication APIs
Write-Host "`n1. Testing Authentication APIs..." -ForegroundColor Cyan

# Test Register
try {
    $body = @{
        name = "Test User"
        email = "test@example.com"
        password = "password123"
        password_confirmation = "password123"
    } | ConvertTo-Json
    
    $response = Invoke-WebRequest -Uri "http://localhost:8000/api/register" -Method POST -Body $body -ContentType "application/json" -Headers @{"Accept"="application/json"}
    Write-Host "✅ Register API: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "❌ Register API: $($_.Exception.Message)" -ForegroundColor Red
}

# Test Login
try {
    $body = @{
        email = "test@example.com"
        password = "password123"
    } | ConvertTo-Json
    
    $response = Invoke-WebRequest -Uri "http://localhost:8000/api/login" -Method POST -Body $body -ContentType "application/json" -Headers @{"Accept"="application/json"}
    Write-Host "✅ Login API: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "❌ Login API: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 2: Dashboard Analytics APIs
Write-Host "`n2. Testing Dashboard Analytics APIs..." -ForegroundColor Cyan

# Test Dashboard Stats
try {
    $response = Invoke-WebRequest -Uri "http://localhost:8000/dashboard/stats" -Method GET -Headers @{"Accept"="application/json"}
    Write-Host "✅ Dashboard Stats: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "❌ Dashboard Stats: $($_.Exception.Message)" -ForegroundColor Red
}

# Test Players
try {
    $response = Invoke-WebRequest -Uri "http://localhost:8000/dashboard/players" -Method GET -Headers @{"Accept"="application/json"}
    Write-Host "✅ Players API: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "❌ Players API: $($_.Exception.Message)" -ForegroundColor Red
}

# Test Children by Grade
try {
    $response = Invoke-WebRequest -Uri "http://localhost:8000/dashboard/children-by-grade" -Method GET -Headers @{"Accept"="application/json"}
    Write-Host "✅ Children by Grade: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "❌ Children by Grade: $($_.Exception.Message)" -ForegroundColor Red
}

# Test Grades with Maps
try {
    $response = Invoke-WebRequest -Uri "http://localhost:8000/dashboard/grades-with-maps" -Method GET -Headers @{"Accept"="application/json"}
    Write-Host "✅ Grades with Maps: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "❌ Grades with Maps: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 3: Creation APIs
Write-Host "`n3. Testing Creation APIs..." -ForegroundColor Cyan

# Test Create Grade
try {
    $body = @{
        name = "Test Grade"
        description = "Test Description"
        level = 1
    } | ConvertTo-Json
    
    $response = Invoke-WebRequest -Uri "http://localhost:8000/dashboard/grades" -Method POST -Body $body -ContentType "application/json" -Headers @{"Accept"="application/json"}
    Write-Host "✅ Create Grade: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "❌ Create Grade: $($_.Exception.Message)" -ForegroundColor Red
}

# Test Create Map
try {
    $body = @{
        grade_id = 1
        name = "Test Map"
        description = "Test Map Description"
    } | ConvertTo-Json
    
    $response = Invoke-WebRequest -Uri "http://localhost:8000/dashboard/maps" -Method POST -Body $body -ContentType "application/json" -Headers @{"Accept"="application/json"}
    Write-Host "✅ Create Map: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "❌ Create Map: $($_.Exception.Message)" -ForegroundColor Red
}

# Test Create Stage
try {
    $body = @{
        map_id = 1
        name = "Test Stage"
        description = "Test Stage Description"
        order = 1
        is_unlocked = $true
    } | ConvertTo-Json
    
    $response = Invoke-WebRequest -Uri "http://localhost:8000/dashboard/stages" -Method POST -Body $body -ContentType "application/json" -Headers @{"Accept"="application/json"}
    Write-Host "✅ Create Stage: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "❌ Create Stage: $($_.Exception.Message)" -ForegroundColor Red
}

# Test Create Lesson
try {
    $body = @{
        stage_id = 1
        name = "Test Lesson"
        description = "Test Lesson Description"
        content = "Test content"
        order = 1
    } | ConvertTo-Json
    
    $response = Invoke-WebRequest -Uri "http://localhost:8000/dashboard/lessons" -Method POST -Body $body -ContentType "application/json" -Headers @{"Accept"="application/json"}
    Write-Host "✅ Create Lesson: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "❌ Create Lesson: $($_.Exception.Message)" -ForegroundColor Red
}

# Test Create Game Mode
try {
    $body = @{
        stage_id = 1
        name = "Test Game Mode"
        description = "Test Game Mode Description"
        type = "quiz"
        settings = '{"time_limit": 60}'
    } | ConvertTo-Json
    
    $response = Invoke-WebRequest -Uri "http://localhost:8000/dashboard/game-modes" -Method POST -Body $body -ContentType "application/json" -Headers @{"Accept"="application/json"}
    Write-Host "✅ Create Game Mode: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "❌ Create Game Mode: $($_.Exception.Message)" -ForegroundColor Red
}

# Test Create Question
try {
    $body = @{
        game_mode_id = 1
        question_text = "What is 2 + 2?"
        question_type = "multiple_choice"
        options = '["3", "4", "5", "6"]'
        correct_answer = "4"
        points = 10
        difficulty = "easy"
    } | ConvertTo-Json
    
    $response = Invoke-WebRequest -Uri "http://localhost:8000/dashboard/questions" -Method POST -Body $body -ContentType "application/json" -Headers @{"Accept"="application/json"}
    Write-Host "✅ Create Question: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "❌ Create Question: $($_.Exception.Message)" -ForegroundColor Red
}

# Test 4: Update APIs
Write-Host "`n4. Testing Update APIs..." -ForegroundColor Cyan

# Test Update Lesson
try {
    $body = @{
        name = "Updated Lesson"
        description = "Updated Description"
    } | ConvertTo-Json
    
    $response = Invoke-WebRequest -Uri "http://localhost:8000/dashboard/lessons/1" -Method PUT -Body $body -ContentType "application/json" -Headers @{"Accept"="application/json"}
    Write-Host "✅ Update Lesson: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "❌ Update Lesson: $($_.Exception.Message)" -ForegroundColor Red
}

# Test Update Game Mode
try {
    $body = @{
        name = "Updated Game Mode"
        description = "Updated Description"
    } | ConvertTo-Json
    
    $response = Invoke-WebRequest -Uri "http://localhost:8000/dashboard/game-modes/1" -Method PUT -Body $body -ContentType "application/json" -Headers @{"Accept"="application/json"}
    Write-Host "✅ Update Game Mode: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "❌ Update Game Mode: $($_.Exception.Message)" -ForegroundColor Red
}

# Test Update Question
try {
    $body = @{
        question_text = "Updated question"
        correct_answer = "5"
    } | ConvertTo-Json
    
    $response = Invoke-WebRequest -Uri "http://localhost:8000/dashboard/questions/1" -Method PUT -Body $body -ContentType "application/json" -Headers @{"Accept"="application/json"}
    Write-Host "✅ Update Question: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "❌ Update Question: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`n=== TESTING COMPLETE ===" -ForegroundColor Green
Write-Host "All API tests finished!" -ForegroundColor Yellow 