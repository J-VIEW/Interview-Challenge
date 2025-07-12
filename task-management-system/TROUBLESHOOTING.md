# Troubleshooting Guide

## Login Issues

### Problem: "Unexpected JSON Error"

**Symptoms:**
- Login fails with "unexpected json error" message
- Error text is not visible in browser
- Console shows JSON parsing errors

**Causes:**
1. Server returning non-JSON response
2. PHP errors being output before JSON response
3. Database connection issues
4. Missing required PHP extensions

**Solutions:**

#### 1. Check Server Response
```bash
# Test the login endpoint directly
curl -X POST http://your-domain/api/auth/login.php \
  -H "Content-Type: application/json" \
  -d '{"username":"test","password":"test"}'
```

#### 2. Check PHP Error Logs
```bash
# Check PHP error log
tail -f /var/log/php_errors.log

# Check Apache/Nginx error logs
tail -f /var/log/apache2/error.log
tail -f /var/log/nginx/error.log
```

#### 3. Run Diagnostic Tests
Visit: `http://your-domain/test_login.php`

This will test:
- Database connection
- User table existence
- PHP configuration
- File permissions

#### 4. Check Application Logs
Visit: `http://your-domain/view_logs.php` (admin only)

Or check logs manually:
```bash
# View recent error logs
tail -f logs/error.log | jq '.'

# View recent app logs
tail -f logs/app.log | jq '.'

# View database errors
tail -f logs/database.log | jq '.'
```

### Problem: "Invalid Credentials"

**Symptoms:**
- Login fails with "Invalid username or password"
- User exists but can't log in

**Solutions:**

#### 1. Verify User Exists
```sql
SELECT id, username, email, role FROM users WHERE username = 'your_username';
```

#### 2. Check Password Hash
```sql
SELECT password FROM users WHERE username = 'your_username';
```

#### 3. Reset User Password
```php
<?php
require_once 'api/bootstrap.php';
require_once 'api/classes/User.php';

$user = new User();
$newPassword = 'newpassword123';
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

$db = new Database();
$db->safeQuery(
    "UPDATE users SET password = ? WHERE username = ?",
    [$hashedPassword, 'your_username']
);

echo "Password updated successfully";
?>
```

### Problem: Database Connection Issues

**Symptoms:**
- "Database connection failed" errors
- Login completely fails

**Solutions:**

#### 1. Check Database Configuration
Verify `api/config/Config.php` has correct database settings:
```php
'database' => [
    'host' => 'localhost',
    'username' => 'your_db_user',
    'password' => 'your_db_password',
    'database' => 'your_db_name'
]
```

#### 2. Test Database Connection
```bash
mysql -u your_db_user -p your_db_name
```

#### 3. Check Database Permissions
```sql
GRANT ALL PRIVILEGES ON your_db_name.* TO 'your_db_user'@'localhost';
FLUSH PRIVILEGES;
```

### Problem: Session Issues

**Symptoms:**
- Login succeeds but user gets logged out immediately
- Session not persisting

**Solutions:**

#### 1. Check Session Configuration
```php
// In bootstrap.php, ensure these are set:
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', 1);
```

#### 2. Check Session Directory Permissions
```bash
# Check session save path
php -r "echo session_save_path();"

# Ensure directory is writable
chmod 755 /tmp
```

## Error Visibility Issues

### Problem: Error Messages Not Visible

**Solutions:**

#### 1. Check Browser Console
Open Developer Tools (F12) and check:
- Console tab for JavaScript errors
- Network tab for API response details

#### 2. Enable Debug Mode
In `api/config/Config.php`:
```php
'debug' => true
```

#### 3. Check Toast Notifications
Errors should appear as toast notifications in the top-right corner.

## Common Configuration Issues

### 1. File Permissions
```bash
# Set correct permissions
chmod 755 task-management-system/
chmod 644 task-management-system/api/*.php
chmod 755 task-management-system/logs/
chmod 644 task-management-system/logs/*.log
```

### 2. PHP Extensions
Ensure these extensions are enabled:
- pdo_mysql
- json
- session
- mbstring

### 3. Web Server Configuration

#### Apache (.htaccess)
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

#### Nginx
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

## Debugging Steps

### Step 1: Check Logs
1. Visit `http://your-domain/view_logs.php`
2. Look for recent errors
3. Check all three log files (app.log, error.log, database.log)

### Step 2: Test Database
1. Visit `http://your-domain/test_login.php`
2. Verify all tests pass
3. Check for any error messages

### Step 3: Test API Directly
```bash
# Test login endpoint
curl -X POST http://your-domain/api/auth/login.php \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}' \
  -v
```

### Step 4: Check Browser Network Tab
1. Open Developer Tools
2. Go to Network tab
3. Attempt login
4. Check the response for `/api/auth/login.php`

## Prevention

### 1. Regular Monitoring
- Check logs regularly
- Monitor database connections
- Set up log rotation

### 2. Error Handling
- All API endpoints return consistent JSON
- Proper HTTP status codes
- Detailed logging for debugging

### 3. Security
- Input validation and sanitization
- SQL injection protection
- XSS protection
- CSRF protection

## Getting Help

If you're still experiencing issues:

1. Check the logs using `view_logs.php`
2. Run the diagnostic tests using `test_login.php`
3. Check browser console for JavaScript errors
4. Verify all configuration files are correct
5. Ensure all required PHP extensions are installed

## Log File Locations

- Application logs: `logs/app.log`
- Error logs: `logs/error.log`
- Database logs: `logs/database.log`
- Web viewer: `view_logs.php` (admin only) 