# Resume Maker - Authentication System

A complete user authentication and resume builder system with login, signup, and session management.

## Features

✅ **User Authentication**
- Secure login with password hashing (bcrypt)
- User registration with email validation
- Password strength indicator
- Session-based authentication

✅ **Security Features**
- SQL injection prevention (prepared statements)
- CSRF token support
- Password hashing with bcrypt
- Input validation and sanitization
- Secure session management

✅ **User Experience**
- Responsive design (mobile-friendly)
- Real-time password strength indicator
- Loading states and error messages
- Smooth animations
- One-click logout

## File Structure

```
EGG/re/
├── login.html              # Login form UI
├── login_handler.php       # Login authentication backend
├── signup.html             # Sign-up form UI
├── signup_handler.php      # User registration backend
├── check_auth.php          # Session authentication check
├── logout.php              # Logout handler
├── index.html              # Main dashboard (protected)
├── form.html               # Resume form
├── preview.html            # Resume preview
├── my_resumes.php          # User's resume list
├── save.php                # Save resume
├── edit.php                # Edit resume
├── update.php              # Update resume
├── database_setup.sql      # Database schema
├── style.css               # Styles
├── script.js               # JavaScript logic
└── README.md               # This file
```

## Database Setup

### 1. Create Database
```sql
CREATE DATABASE resume_db;
USE resume_db;
```

### 2. Run Setup Script
Execute the `database_setup.sql` file:
```sql
SOURCE database_setup.sql;
```

Or manually create the tables:
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE resumes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    skills LONGTEXT,
    experience LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## Installation

1. **Place files in web server directory**
   ```
   htdocs/egg/re/ (for XAMPP)
   www/egg/re/ (for WAMP)
   ```

2. **Update database credentials** (if different)
   - Edit `login_handler.php`
   - Edit `signup_handler.php`
   - Edit `my_resumes.php`
   - Edit `save.php`
   - Edit `update.php`
   - Change: `new mysqli("localhost", "root", "", "resume_db")`

3. **Ensure PHP has write permissions** for session files

## Usage

### Login
1. Go to `login.html`
2. Enter username and password
3. Click "Login"
4. Redirected to dashboard on success

### Sign Up
1. Go to `signup.html` (or click signup link from login)
2. Fill in username, email, password
3. Password strength indicator shows real-time feedback
4. Click "Create Account"
5. Redirected to login page

### Create Resume
1. Login to your account
2. Select a template (Classic or Modern)
3. Fill in resume details
4. Preview and download as PDF

## Security Best Practices

✅ **What's Implemented:**
- Prepared statements prevent SQL injection
- bcrypt password hashing
- Session validation before actions
- Input length validation
- Email format validation
- Username pattern validation
- Password strength requirements

**⚠️ Recommendations for Production:**
1. Use HTTPS/SSL certificate
2. Enable CSRF tokens
3. Add rate limiting for login attempts
4. Implement email verification
5. Add 2FA (two-factor authentication)
6. Use environment variables for database credentials
7. Add logging and monitoring
8. Regular security audits

## API Endpoints

### Authentication

**POST /login_handler.php**
```json
{
    "username": "string",
    "password": "string"
}
```
Response:
```json
{
    "success": true,
    "message": "Login successful"
}
```

**POST /signup_handler.php**
```json
{
    "username": "string",
    "email": "string",
    "password": "string"
}
```
Response:
```json
{
    "success": true,
    "message": "Account created successfully"
}
```

**GET /check_auth.php**
Response:
```json
{
    "authenticated": true,
    "user_id": 1,
    "username": "username"
}
```

**GET /logout.php**
Response:
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

## Troubleshooting

### "Database connection failed"
- Check MySQL is running
- Verify database credentials
- Check database name is correct

### "Wrong login!"
- Verify username and password are correct
- Check user exists in database
- Try resetting password (if feature added)

### Session not persisting
- Check PHP session.save_path is writable
- Verify cookies are enabled
- Check session timeout settings

### CORS issues (for fetch requests)
- Add headers to PHP files:
  ```php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
  ```

## Testing

### Test Login
1. Navigate to `login.html`
2. Create account via signup
3. Login with new credentials
4. Verify redirect to dashboard

### Test Resume Creation
1. After login, select a template
2. Fill in resume information
3. Save resume
4. Verify in "My Resumes"
5. Edit and update

## Support

For issues or questions:
1. Check error messages in browser console
2. Verify all files are in correct location
3. Check database connection
4. Review PHP error logs

## License

This is a mini project for educational purposes.
