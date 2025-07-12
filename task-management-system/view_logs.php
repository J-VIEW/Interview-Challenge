<?php
/**
 * Simple Log Viewer for Task Management System
 * 
 * This script provides a web interface to view log files.
 * For security, this should only be accessible to administrators.
 */

// Basic security check - in production, add proper authentication
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo "Access denied. Admin privileges required.";
    exit;
}

$logDir = __DIR__ . '/logs/';
$logFiles = [
    'app.log' => 'Application Events',
    'error.log' => 'Error Logs',
    'database.log' => 'Database Errors'
];

$selectedLog = $_GET['log'] ?? 'error.log';
$lines = $_GET['lines'] ?? 50;

if (!array_key_exists($selectedLog, $logFiles)) {
    $selectedLog = 'error.log';
}

$logFile = $logDir . $selectedLog;
$logContent = [];

if (file_exists($logFile)) {
    $lines = min(max((int)$lines, 10), 500); // Limit between 10 and 500 lines
    $logContent = array_slice(file($logFile), -$lines);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Viewer - Task Management System</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: #333;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .controls {
            padding: 15px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        .controls select, .controls input {
            padding: 5px 10px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .controls button {
            padding: 5px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .controls button:hover {
            background: #0056b3;
        }
        .log-content {
            padding: 20px;
            max-height: 600px;
            overflow-y: auto;
            background: #1e1e1e;
            color: #d4d4d4;
        }
        .log-entry {
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 4px;
            background: #2d2d2d;
            border-left: 3px solid #007bff;
        }
        .log-entry.error {
            border-left-color: #dc3545;
            background: #2d1e1e;
        }
        .log-entry.warning {
            border-left-color: #ffc107;
            background: #2d2a1e;
        }
        .log-entry.success {
            border-left-color: #28a745;
            background: #1e2d1e;
        }
        .timestamp {
            color: #888;
            font-size: 0.9em;
        }
        .level {
            font-weight: bold;
            margin-left: 10px;
        }
        .level.ERROR {
            color: #ff6b6b;
        }
        .level.WARNING {
            color: #ffd93d;
        }
        .level.INFO {
            color: #6bcf7f;
        }
        .message {
            margin-top: 5px;
            color: #fff;
        }
        .context {
            margin-top: 5px;
            color: #aaa;
            font-size: 0.9em;
        }
        .refresh-btn {
            background: #28a745;
        }
        .refresh-btn:hover {
            background: #1e7e34;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Log Viewer</h1>
            <div>
                <a href="../public/" style="color: white; text-decoration: none;">← Back to Dashboard</a>
            </div>
        </div>
        
        <div class="controls">
            <form method="GET" style="display: inline;">
                <select name="log">
                    <?php foreach ($logFiles as $file => $description): ?>
                        <option value="<?= htmlspecialchars($file) ?>" <?= $selectedLog === $file ? 'selected' : '' ?>>
                            <?= htmlspecialchars($description) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <input type="number" name="lines" value="<?= htmlspecialchars($lines) ?>" min="10" max="500" placeholder="Lines">
                
                <button type="submit">View Log</button>
                <button type="button" class="refresh-btn" onclick="location.reload()">Refresh</button>
            </form>
        </div>
        
        <div class="log-content">
            <?php if (empty($logContent)): ?>
                <div style="color: #888; text-align: center; padding: 40px;">
                    No log entries found or log file doesn't exist.
                </div>
            <?php else: ?>
                <?php foreach (array_reverse($logContent) as $line): ?>
                    <?php
                    $entry = json_decode(trim($line), true);
                    if ($entry) {
                        $level = strtolower($entry['level'] ?? 'info');
                        $timestamp = $entry['timestamp'] ?? 'Unknown';
                        $message = $entry['message'] ?? 'No message';
                        $context = $entry['context'] ?? [];
                    } else {
                        $level = 'info';
                        $timestamp = 'Unknown';
                        $message = htmlspecialchars(trim($line));
                        $context = [];
                    }
                    ?>
                    <div class="log-entry <?= $level ?>">
                        <div>
                            <span class="timestamp"><?= htmlspecialchars($timestamp) ?></span>
                            <span class="level <?= strtoupper($level) ?>"><?= strtoupper($level) ?></span>
                        </div>
                        <div class="message"><?= htmlspecialchars($message) ?></div>
                        <?php if (!empty($context)): ?>
                            <div class="context">
                                <strong>Context:</strong> <?= htmlspecialchars(json_encode($context, JSON_PRETTY_PRINT)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Auto-refresh every 30 seconds
        setTimeout(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html> 