import fetch from 'node-fetch';

const BASE_URL = 'http://127.0.0.1:8000/api';
let authToken = '';

async function testAPI(method, endpoint, body = null, headers = {}) {
    const url = BASE_URL + endpoint;
    const options = {
        method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...headers
        }
    };

    if (body && (method === 'POST' || method === 'PUT')) {
        options.body = JSON.stringify(body);
    }

    try {
        const response = await fetch(url, options);
        const responseText = await response.text();
        
        let responseData;
        try {
            responseData = JSON.parse(responseText);
        } catch (e) {
            responseData = responseText;
        }

        return {
            status: response.status,
            statusText: response.statusText,
            data: responseData
        };
    } catch (error) {
        return {
            status: 0,
            statusText: 'Network Error',
            data: error.message
        };
    }
}

async function runTests() {
    console.log('🚀 Starting API Tests...\n');

    // Test 1: Register User
    console.log('1. Testing User Registration...');
    const registerResult = await testAPI('POST', '/register', {
        name: 'Test User 2',
        email: 'test2@example.com',
        password: 'password123',
        password_confirmation: 'password123'
    });
    console.log(`Status: ${registerResult.status} - ${registerResult.statusText}`);
    console.log('Response:', JSON.stringify(registerResult.data, null, 2));
    console.log('---\n');

    // Test 2: Login User
    console.log('2. Testing User Login...');
    const loginResult = await testAPI('POST', '/login', {
        email: 'test2@example.com',
        password: 'password123'
    });
    console.log(`Status: ${loginResult.status} - ${loginResult.statusText}`);
    if (loginResult.data && loginResult.data.token) {
        authToken = loginResult.data.token;
        console.log('✅ Token received and saved');
    }
    console.log('Response:', JSON.stringify(loginResult.data, null, 2));
    console.log('---\n');

    // Test 3: Assign Teacher Role
    console.log('3. Testing Teacher Role Assignment...');
    const teacherResult = await testAPI('POST', '/assign-teacher-role', {
        email: 'test2@example.com'
    });
    console.log(`Status: ${teacherResult.status} - ${teacherResult.statusText}`);
    console.log('Response:', JSON.stringify(teacherResult.data, null, 2));
    console.log('---\n');

    // Test 4: Get User Profile (Protected Route)
    console.log('4. Testing Get User Profile (Protected Route)...');
    const userResult = await testAPI('GET', '/user', null, {
        'Authorization': `Bearer ${authToken}`
    });
    console.log(`Status: ${userResult.status} - ${userResult.statusText}`);
    console.log('Response:', JSON.stringify(userResult.data, null, 2));
    console.log('---\n');

    // Test 5: Create Child (Protected Route)
    console.log('5. Testing Create Child (Protected Route)...');
    const createChildResult = await testAPI('POST', '/children', {
        name: 'Test Child',
        age: 8,
        grade_level: '3rd Grade'
    }, {
        'Authorization': `Bearer ${authToken}`
    });
    console.log(`Status: ${createChildResult.status} - ${createChildResult.statusText}`);
    console.log('Response:', JSON.stringify(createChildResult.data, null, 2));
    console.log('---\n');

    // Test 6: Get Children List (Protected Route)
    console.log('6. Testing Get Children List (Protected Route)...');
    const childrenResult = await testAPI('GET', '/children', null, {
        'Authorization': `Bearer ${authToken}`
    });
    console.log(`Status: ${childrenResult.status} - ${childrenResult.statusText}`);
    console.log('Response:', JSON.stringify(childrenResult.data, null, 2));
    console.log('---\n');

    // Test 7: Get Lesson (Protected Route)
    console.log('7. Testing Get Lesson (Protected Route)...');
    const lessonResult = await testAPI('GET', '/lessons/1', null, {
        'Authorization': `Bearer ${authToken}`
    });
    console.log(`Status: ${lessonResult.status} - ${lessonResult.statusText}`);
    console.log('Response:', JSON.stringify(lessonResult.data, null, 2));
    console.log('---\n');

    // Test 8: Get Game Mode (Protected Route)
    console.log('8. Testing Get Game Mode (Protected Route)...');
    const gameModeResult = await testAPI('GET', '/game-modes/1', null, {
        'Authorization': `Bearer ${authToken}`
    });
    console.log(`Status: ${gameModeResult.status} - ${gameModeResult.statusText}`);
    console.log('Response:', JSON.stringify(gameModeResult.data, null, 2));
    console.log('---\n');

    console.log('✅ All tests completed!');
}

runTests().catch(console.error); 