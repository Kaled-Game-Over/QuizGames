<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard API Tester</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .response {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
            white-space: pre-wrap;
            font-family: monospace;
        }
        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .auth-btn { background-color: #28a745 !important; }
        .auth-btn:hover { background-color: #218838 !important; }
        .user-btn { background-color: #17a2b8 !important; }
        .user-btn:hover { background-color: #138496 !important; }
        .dashboard-btn { 
            background-color: #ffc107 !important; 
            color: #212529 !important; 
        }
        .dashboard-btn:hover { background-color: #e0a800 !important; }
        .curriculum-btn { background-color: #6f42c1 !important; }
        .curriculum-btn:hover { background-color: #5a32a3 !important; }
        .update-btn { background-color: #fd7e14 !important; }
        .update-btn:hover { background-color: #e8690b !important; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎯 Dashboard API Tester</h1>
        <p>Test your Laravel Dashboard routes here. Base URL: <strong>http://localhost:8000</strong></p>
        
        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #007bff;">
            <h4>📋 How to Test:</h4>
            <ol>
                <li><strong>🔐 Authentication:</strong> First use "Test Login" to get a token</li>
                <li><strong>📊 Analytics:</strong> Test dashboard stats and player data</li>
                <li><strong>📚 Curriculum:</strong> Create grades, maps, stages, lessons, game modes, and questions</li>
                <li><strong>✏️ Updates:</strong> Modify existing content using update APIs</li>
            </ol>
        </div>

        <div class="preset-buttons">
            <h3>🔐 Authentication APIs:</h3>
            <button onclick="loadPreset('register')" class="auth-btn">Test Register</button>
            <button onclick="loadPreset('login')" class="auth-btn">Test Login</button>
            
            <h3>📊 Dashboard Analytics APIs:</h3>
            <button onclick="loadPreset('dashboard-stats')" class="dashboard-btn">Get Dashboard Stats</button>
            <button onclick="loadPreset('dashboard-players')" class="dashboard-btn">Get All Players</button>
            <button onclick="loadPreset('dashboard-children-grade')" class="dashboard-btn">Get Children by Grade</button>
            <button onclick="loadPreset('dashboard-grades-maps')" class="dashboard-btn">Get Grades with Maps</button>
            
            <h3>📚 Curriculum Management APIs:</h3>
            <button onclick="loadPreset('create-grade')" class="curriculum-btn">Create Grade</button>
            <button onclick="loadPreset('create-map')" class="curriculum-btn">Create Map</button>
            <button onclick="loadPreset('create-stage')" class="curriculum-btn">Create Stage</button>
            <button onclick="loadPreset('create-lesson')" class="curriculum-btn">Create Lesson</button>
            <button onclick="loadPreset('create-game-mode')" class="curriculum-btn">Create Game Mode</button>
            <button onclick="loadPreset('create-question')" class="curriculum-btn">Create Question</button>
            
            <h3>✏️ Update APIs:</h3>
            <button onclick="loadPreset('update-lesson')" class="update-btn">Update Lesson</button>
            <button onclick="loadPreset('update-game-mode')" class="update-btn">Update Game Mode</button>
            <button onclick="loadPreset('update-question')" class="update-btn">Update Question</button>
        </div>

        <form id="apiForm">
            <div class="form-group">
                <label for="method">Method:</label>
                <select id="method">
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                    <option value="PUT">PUT</option>
                    <option value="DELETE">DELETE</option>
                </select>
            </div>

            <div class="form-group">
                <label for="url">URL:</label>
                <input type="text" id="url" value="/register" placeholder="Enter endpoint (e.g., /register)">
            </div>

            <div class="form-group">
                <label for="headers">Headers:</label>
                <textarea id="headers" rows="4" placeholder='{"Content-Type": "application/json", "Accept": "application/json"}'></textarea>
            </div>

            <div class="form-group">
                <label for="body">Body (for POST/PUT):</label>
                <textarea id="body" rows="8" placeholder='{"name": "Test User", "email": "test@example.com", "password": "password123", "password_confirmation": "password123"}'></textarea>
            </div>

            <button type="button" onclick="sendRequest()">Send Request</button>
            <button type="button" onclick="clearResponse()">Clear Response</button>
        </form>

        <div id="response" class="response" style="display: none;"></div>
        
        <div id="auth-status" style="margin-top: 20px; padding: 10px; border-radius: 4px; display: none;">
            <strong>🔐 Authentication Status:</strong> <span id="auth-status-text"></span>
        </div>
    </div>

    <script>
        let authToken = '';

        function loadPreset(type) {
            const methodSelect = document.getElementById('method');
            const urlInput = document.getElementById('url');
            const headersInput = document.getElementById('headers');
            const bodyInput = document.getElementById('body');

            switch(type) {
                case 'register':
                    methodSelect.value = 'POST';
                    urlInput.value = '/register';
                    headersInput.value = JSON.stringify({
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }, null, 2);
                    bodyInput.value = JSON.stringify({
                        name: 'Test User',
                        email: 'test@example.com',
                        password: 'password123',
                        password_confirmation: 'password123'
                    }, null, 2);
                    break;

                case 'login':
                    methodSelect.value = 'POST';
                    urlInput.value = '/login';
                    headersInput.value = JSON.stringify({
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }, null, 2);
                    bodyInput.value = JSON.stringify({
                        email: 'test@example.com',
                        password: 'password123'
                    }, null, 2);
                    break;

                case 'dashboard-stats':
                    methodSelect.value = 'GET';
                    urlInput.value = '/dashboard/stats';
                    headersInput.value = JSON.stringify({
                        'Accept': 'application/json'
                    }, null, 2);
                    bodyInput.value = '';
                    break;

                case 'dashboard-players':
                    methodSelect.value = 'GET';
                    urlInput.value = '/dashboard/players';
                    headersInput.value = JSON.stringify({
                        'Accept': 'application/json'
                    }, null, 2);
                    bodyInput.value = '';
                    break;

                case 'dashboard-children-grade':
                    methodSelect.value = 'GET';
                    urlInput.value = '/dashboard/children-by-grade?grade_level=1st Grade';
                    headersInput.value = JSON.stringify({
                        'Accept': 'application/json'
                    }, null, 2);
                    bodyInput.value = '';
                    break;

                case 'dashboard-grades-maps':
                    methodSelect.value = 'GET';
                    urlInput.value = '/dashboard/grades-with-maps';
                    headersInput.value = JSON.stringify({
                        'Accept': 'application/json'
                    }, null, 2);
                    bodyInput.value = '';
                    break;

                case 'create-grade':
                    methodSelect.value = 'POST';
                    urlInput.value = '/dashboard/grades';
                    headersInput.value = JSON.stringify({
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }, null, 2);
                    bodyInput.value = JSON.stringify({
                        name: 'First Grade',
                        description: 'First grade curriculum',
                        level: 1
                    }, null, 2);
                    break;

                case 'create-map':
                    methodSelect.value = 'POST';
                    urlInput.value = '/dashboard/maps';
                    headersInput.value = JSON.stringify({
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }, null, 2);
                    bodyInput.value = JSON.stringify({
                        grade_id: 1,
                        name: 'Math Adventure Map',
                        description: 'Interactive math learning map',
                        image_path: '/images/math-map.png'
                    }, null, 2);
                    break;

                case 'create-stage':
                    methodSelect.value = 'POST';
                    urlInput.value = '/dashboard/stages';
                    headersInput.value = JSON.stringify({
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }, null, 2);
                    bodyInput.value = JSON.stringify({
                        map_id: 1,
                        name: 'Addition Stage',
                        description: 'Learn basic addition',
                        order: 1,
                        is_unlocked: true
                    }, null, 2);
                    break;

                case 'create-lesson':
                    methodSelect.value = 'POST';
                    urlInput.value = '/dashboard/lessons';
                    headersInput.value = JSON.stringify({
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }, null, 2);
                    bodyInput.value = JSON.stringify({
                        stage_id: 1,
                        name: 'Basic Addition',
                        description: 'Learn to add numbers 1-10',
                        content: 'This lesson teaches basic addition concepts.',
                        order: 1
                    }, null, 2);
                    break;

                case 'create-game-mode':
                    methodSelect.value = 'POST';
                    urlInput.value = '/dashboard/game-modes';
                    headersInput.value = JSON.stringify({
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }, null, 2);
                    bodyInput.value = JSON.stringify({
                        stage_id: 1,
                        name: 'Quiz Mode',
                        description: 'Multiple choice questions',
                        type: 'quiz',
                        settings: '{"time_limit": 60, "questions_count": 10}'
                    }, null, 2);
                    break;

                case 'create-question':
                    methodSelect.value = 'POST';
                    urlInput.value = '/dashboard/questions';
                    headersInput.value = JSON.stringify({
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }, null, 2);
                    bodyInput.value = JSON.stringify({
                        game_mode_id: 1,
                        question_text: 'What is 2 + 3?',
                        question_type: 'multiple_choice',
                        options: '["4", "5", "6", "7"]',
                        correct_answer: '5',
                        points: 10,
                        difficulty: 'easy'
                    }, null, 2);
                    break;

                case 'update-lesson':
                    methodSelect.value = 'PUT';
                    urlInput.value = '/dashboard/lessons/1';
                    headersInput.value = JSON.stringify({
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }, null, 2);
                    bodyInput.value = JSON.stringify({
                        name: 'Updated Basic Addition',
                        description: 'Updated lesson description',
                        content: 'Updated lesson content with more examples.',
                        order: 1
                    }, null, 2);
                    break;

                case 'update-game-mode':
                    methodSelect.value = 'PUT';
                    urlInput.value = '/dashboard/game-modes/1';
                    headersInput.value = JSON.stringify({
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }, null, 2);
                    bodyInput.value = JSON.stringify({
                        name: 'Updated Quiz Mode',
                        description: 'Updated game mode description',
                        type: 'quiz',
                        settings: '{"time_limit": 90, "questions_count": 15}'
                    }, null, 2);
                    break;

                case 'update-question':
                    methodSelect.value = 'PUT';
                    urlInput.value = '/dashboard/questions/1';
                    headersInput.value = JSON.stringify({
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }, null, 2);
                    bodyInput.value = JSON.stringify({
                        question_text: 'What is 3 + 4?',
                        question_type: 'multiple_choice',
                        options: '["5", "6", "7", "8"]',
                        correct_answer: '7',
                        points: 15,
                        difficulty: 'medium'
                    }, null, 2);
                    break;
            }
        }

        async function sendRequest() {
            const method = document.getElementById('method').value;
            const url = document.getElementById('url').value;
            const headersText = document.getElementById('headers').value;
            const bodyText = document.getElementById('body').value;

            let headers = {};
            try {
                headers = JSON.parse(headersText);
            } catch (e) {
                alert('Invalid JSON in headers');
                return;
            }

            const fullUrl = window.location.origin + url;
            const responseDiv = document.getElementById('response');

            try {
                const options = {
                    method: method,
                    headers: headers
                };

                if (bodyText && (method === 'POST' || method === 'PUT')) {
                    options.body = bodyText;
                }

                const response = await fetch(fullUrl, options);
                const responseText = await response.text();
                
                let responseData;
                try {
                    responseData = JSON.parse(responseText);
                } catch (e) {
                    responseData = responseText;
                }

                const result = {
                    status: response.status,
                    statusText: response.statusText,
                    headers: Object.fromEntries(response.headers.entries()),
                    data: responseData
                };

                responseDiv.innerHTML = JSON.stringify(result, null, 2);
                responseDiv.className = 'response ' + (response.ok ? 'success' : 'error');
                responseDiv.style.display = 'block';

                // If this was a login request and we got a token, save it
                if (url === '/login' && response.ok && responseData.token) {
                    authToken = responseData.token;
                    updateAuthStatus(true, 'Authenticated - Token saved!');
                    alert('Token saved! You can now test protected routes.');
                }

            } catch (error) {
                responseDiv.innerHTML = 'Error: ' + error.message;
                responseDiv.className = 'response error';
                responseDiv.style.display = 'block';
            }
        }

        function clearResponse() {
            document.getElementById('response').style.display = 'none';
        }

        function updateAuthStatus(isAuthenticated, message) {
            const statusDiv = document.getElementById('auth-status');
            const statusText = document.getElementById('auth-status-text');
            
            if (isAuthenticated) {
                statusDiv.style.display = 'block';
                statusDiv.style.backgroundColor = '#d4edda';
                statusDiv.style.border = '1px solid #c3e6cb';
                statusDiv.style.color = '#155724';
                statusText.textContent = message;
            } else {
                statusDiv.style.display = 'block';
                statusDiv.style.backgroundColor = '#f8d7da';
                statusDiv.style.border = '1px solid #f5c6cb';
                statusDiv.style.color = '#721c24';
                statusText.textContent = 'Not authenticated - Please login first';
            }
        }

        // Load register preset by default
        loadPreset('register');
    </script>
</body>
</html> 