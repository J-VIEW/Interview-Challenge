# Logging System Documentation

This directory contains log files for the Task Management System. The system uses structured JSON logging for better debugging and monitoring.

## Log Files

### 1. `app.log`
Contains general application events like:
- User logins/logouts
- Task operations
- User management actions
- Application startup events

### 2. `error.log`
Contains error-level logs with detailed context:
- PHP errors and exceptions
- API errors
- Validation failures
- Security events

### 3. `database.log`
Contains database-specific errors:
- Connection failures
- Query errors
- Transaction issues

## Log Format

All logs are in JSON format with the following structure:

```json
{
  "timestamp": "2024-01-15 10:30:45",
  "level": "ERROR",
  "message": "Error description",
  "ip": "192.168.1.100",
  "user_id": "123",
  "user_agent": "Mozilla/5.0...",
  "request_uri": "/api/auth/login.php",
  "request_method": "POST",
  "context": {
    "additional": "information"
  }
}
```

## Viewing Logs

### Command Line
```bash
# View recent app logs
tail -f logs/app.log | jq '.'

# View recent error logs
tail -f logs/error.log | jq '.'

# Search for specific errors
grep "login" logs/error.log | jq '.'

# View logs from last hour
find logs/ -name "*.log" -exec grep "$(date -d '1 hour ago' '+%Y-%m-%d %H')" {} \;
```

### Log Rotation
Consider setting up log rotation to prevent log files from growing too large:

```bash
# Add to crontab for daily rotation
0 0 * * * /usr/sbin/logrotate /path/to/logrotate.conf
```

## Debugging Login Issues

When troubleshooting login problems:

1. Check `error.log` for authentication errors
2. Look for database connection issues in `database.log`
3. Verify user events in `app.log`

Example search commands:
```bash
# Find login failures
grep "user_login_failed" logs/app.log

# Find database connection errors
grep "Database connection failed" logs/database.log

# Find JSON parsing errors
grep "Invalid JSON" logs/error.log
```

## Security Notes

- Log files may contain sensitive information
- Ensure proper file permissions (644 or 640)
- Consider encrypting logs in production
- Implement log retention policies 