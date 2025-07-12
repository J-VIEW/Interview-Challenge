<?php
/**
 * Login Test Script
 * 
 * This script tests the login functionality and helps debug issues.
 * Run this from the command line or web browser.
 */

require_once __DIR__ . '/api/bootstrap.php';
require_once __DIR__ . '/api/classes/User.php';

echo "<h1>Login Test Results</h1>\n";

// Test 1: Check if database connection works
echo "<h2>Test 1: Database Connection</h2>\n";
try {
    $db = new Database();
    $connection = $db->getConnection();
    if ($connection) {
        echo "✅ Database connection successful<br>\n";
    } else {
        echo "❌ Database connection failed<br>\n";
    }
} catch (Exception $e) {
    echo "❌ Database connection error: " . htmlspecialchars($e->getMessage()) . "<br>\n";
}

// Test 2: Check if users table exists and has data
echo "<h2>Test 2: Users Table</h2>\n";
try {
    $db = new Database();
    $users = $db->safeFetchAll("SELECT COUNT(*) as count FROM users");
    if ($users && isset($users[0]['count'])) {
        echo "✅ Users table exists with " . $users[0]['count'] . " users<br>\n";
        
        // Show sample users (without passwords)
        $sampleUsers = $db->safeFetchAll("SELECT id, username, email, role FROM users LIMIT 5");
        if ($sampleUsers) {
            echo "<h3>Sample Users:</h3>\n";
            echo "<table border='1' style='border-collapse: collapse;'>\n";
            echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th></tr>\n";
            foreach ($sampleUsers as $user) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($user['id']) . "</td>";
                echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                echo "<td>" . htmlspecialchars($user['role']) . "</td>";
                echo "</tr>\n";
            }
            echo "</table><br>\n";
        }
    } else {
        echo "❌ Users table is empty or doesn't exist<br>\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking users table: " . htmlspecialchars($e->getMessage()) . "<br>\n";
}

// Test 3: Test login functionality
echo "<h2>Test 3: Login Functionality</h2>\n";
try {
    $user = new User();
    
    // Get a test user
    $db = new Database();
    $testUser = $db->safeFetch("SELECT username, email FROM users LIMIT 1");
    
    if ($testUser) {
        echo "Testing with user: " . htmlspecialchars($testUser['username']) . "<br>\n";
        
        // Test with wrong password
        $result = $user->login($testUser['username'], 'wrongpassword');
        if (!$result) {
            echo "✅ Login correctly rejected wrong password<br>\n";
        } else {
            echo "❌ Login incorrectly accepted wrong password<br>\n";
        }
        
        // Note: We can't test with correct password without knowing it
        echo "ℹ️ To test with correct password, you'll need to know the actual password<br>\n";
    } else {
        echo "❌ No users found to test with<br>\n";
    }
} catch (Exception $e) {
    echo "❌ Login test error: " . htmlspecialchars($e->getMessage()) . "<br>\n";
}

// Test 4: Check log files
echo "<h2>Test 4: Log Files</h2>\n";
$logFiles = ['app.log', 'error.log', 'database.log'];
foreach ($logFiles as $logFile) {
    $logPath = __DIR__ . '/logs/' . $logFile;
    if (file_exists($logPath)) {
        $size = filesize($logPath);
        $lines = count(file($logPath));
        echo "✅ $logFile exists ($size bytes, $lines lines)<br>\n";
    } else {
        echo "❌ $logFile does not exist<br>\n";
    }
}

// Test 5: Check PHP configuration
echo "<h2>Test 5: PHP Configuration</h2>\n";
echo "PHP Version: " . phpversion() . "<br>\n";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? '✅ Available' : '❌ Not available') . "<br>\n";
echo "JSON: " . (extension_loaded('json') ? '✅ Available' : '❌ Not available') . "<br>\n";
echo "Session: " . (extension_loaded('session') ? '✅ Available' : '❌ Not available') . "<br>\n";

// Test 6: Check file permissions
echo "<h2>Test 6: File Permissions</h2>\n";
$logsDir = __DIR__ . '/logs/';
if (is_dir($logsDir)) {
    echo "✅ Logs directory exists<br>\n";
    if (is_writable($logsDir)) {
        echo "✅ Logs directory is writable<br>\n";
    } else {
        echo "❌ Logs directory is not writable<br>\n";
    }
} else {
    echo "❌ Logs directory does not exist<br>\n";
}

echo "<h2>Test Complete</h2>\n";
echo "<p>If you're experiencing login issues, check the error logs at: <a href='view_logs.php' target='_blank'>View Logs</a></p>\n";
?> 