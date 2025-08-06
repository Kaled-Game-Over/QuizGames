# 🔧 Troubleshooting Guide: "Failed to fetch" Error

## 🚨 **Common Causes & Solutions**

### **1. Server Not Running**
**Problem**: Laravel server is not started
**Solution**: 
```bash
php artisan serve
```
**Check**: Visit `http://localhost:8000` in browser

### **2. Wrong URL in API Tester**
**Problem**: API tester using wrong base URL
**Solution**: 
- Open `api-tester.html` in browser
- Check that base URL is `http://localhost:8000` (not `/api`)
- Verify the URL field shows correct endpoints

### **3. CORS Issues**
**Problem**: Browser blocking cross-origin requests
**Solution**: 
- Open `api-tester.html` from `http://localhost:8000` instead of `file://`
- Or use the simple test page: `simple-test.html`

### **4. Authentication Required**
**Problem**: Dashboard routes require login
**Solution**: 
1. First test login: `POST http://localhost:8000/login`
2. Then test dashboard routes

---

## **🛠️ Step-by-Step Fix**

### **Step 1: Verify Server is Running**
```bash
# In your terminal
php artisan serve
```
**Expected**: `Server running on [http://127.0.0.1:8000]`

### **Step 2: Test Basic Connection**
1. Open browser
2. Go to `http://localhost:8000`
3. Should see Laravel welcome page

### **Step 3: Test API Tester**
1. Open `api-tester.html` in browser
2. Click "Test Login" button
3. Check if you get a response (even if 401)

### **Step 4: Use Simple Test Page**
1. Open `simple-test.html` in browser
2. Click "Test Server Connection"
3. Should show response (even if 401 Unauthorized)

---

## **🔍 Debugging Steps**

### **Check Browser Console**
1. Open browser developer tools (F12)
2. Go to Console tab
3. Look for error messages
4. Common errors:
   - `CORS policy: No 'Access-Control-Allow-Origin'`
   - `Failed to fetch`
   - `Network Error`

### **Check Network Tab**
1. Open browser developer tools (F12)
2. Go to Network tab
3. Try the API request
4. Check if request is being sent
5. Check response status

### **Test with cURL**
```bash
# Test if server responds
curl http://localhost:8000

# Test dashboard route
curl -X GET "http://localhost:8000/dashboard/stats" \
  -H "Accept: application/json"
```

---

## **✅ Quick Test Commands**

### **Test 1: Server Connection**
```bash
Invoke-WebRequest -Uri "http://localhost:8000" -Method GET
```

### **Test 2: Dashboard Route**
```bash
Invoke-WebRequest -Uri "http://localhost:8000/dashboard/stats" -Method GET -Headers @{"Accept"="application/json"}
```

### **Test 3: Login Route**
```bash
$body = @{email="test@example.com"; password="password123"} | ConvertTo-Json
Invoke-WebRequest -Uri "http://localhost:8000/login" -Method POST -Body $body -ContentType "application/json"
```

---

## **🎯 Expected Results**

### **✅ Working (Expected Responses)**
- **401 Unauthorized**: Route exists, need authentication
- **419 CSRF Token**: Route exists, need CSRF token
- **200 OK**: Route exists and working

### **❌ Not Working (Error Responses)**
- **Connection refused**: Server not running
- **404 Not Found**: Route doesn't exist
- **500 Internal Server Error**: Server error

---

## **🚀 Quick Fix Checklist**

- [ ] **Server running**: `php artisan serve`
- [ ] **Browser can access**: `http://localhost:8000`
- [ ] **API tester opened**: `api-tester.html`
- [ ] **Console checked**: No JavaScript errors
- [ ] **Network tab checked**: Request being sent
- [ ] **Simple test tried**: `simple-test.html`

---

## **📞 Still Having Issues?**

If you're still getting "Failed to fetch":

1. **Check if server is running**:
   ```bash
   php artisan serve
   ```

2. **Try the simple test page**:
   - Open `simple-test.html` in browser
   - Click "Test Server Connection"

3. **Check browser console**:
   - Press F12
   - Look for error messages

4. **Test with PowerShell**:
   ```powershell
   Invoke-WebRequest -Uri "http://localhost:8000/dashboard/stats" -Method GET
   ```

**The APIs are working correctly** - the issue is likely with the browser connection or CORS settings. 