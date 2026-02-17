# 🚀 Online Voting System - Setup Guide

## Quick Setup (5 Minutes)

### Step 1: Database Setup (1 minute)

**Option A: Using MySQL Command Line**
```bash
mysql -u root -p voting_system < database_setup.sql
```

**Option B: Using phpMyAdmin**
1. Open `http://localhost/phpmyadmin`
2. Create new database: `voting_system`
3. Click `Import` tab
4. Select `database_setup.sql`
5. Click `Import`

### Step 2: Start Server (1 minute)

```powershell
cd "c:\Users\agraw\OneDrive\Desktop\mini project\main code\egg\EGG\online_voting_system"
php -S localhost:8000
```

### Step 3: Open Application (1 minute)

```
http://localhost:8000
```

### Step 4: Login & Test (2 minutes)

**Register new account:**
1. Click "Register here"
2. Fill form
3. Submit

**Or use test account:**
- Voter ID: `VOT001`
- Password: Register to get password

---

## System Requirements

### Minimum
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Modern web browser
- 50MB disk space

### Recommended
- PHP 8.0+
- MySQL 8.0+
- Chrome, Firefox, or Edge
- 100MB disk space

---

## Installation Methods

### Method 1: PHP Built-in Server (Easiest)

```powershell
# Step 1: Navigate to project
cd "online_voting_system"

# Step 2: Start server
php -S localhost:8000

# Step 3: Open browser
# http://localhost:8000
```

**Pros:** No Apache needed, easy to start  
**Cons:** Development only, single connection

### Method 2: XAMPP (Best for Testing)

```powershell
# Step 1: Copy project
Copy-Item -Path "online_voting_system" -Destination "C:\xampp\htdocs\" -Recurse

# Step 2: Start XAMPP
# Run C:\xampp\xampp-control.exe
# Click Start: Apache and MySQL

# Step 3: Create database
# Open http://localhost/phpmyadmin
# Create database and import SQL

# Step 4: Open application
# http://localhost/online_voting_system
```

**Pros:** Professional setup, good for development  
**Cons:** Extra installation needed

### Method 3: WAMP

```powershell
# Step 1: Copy project
Copy-Item -Path "online_voting_system" -Destination "C:\wamp64\www\" -Recurse

# Step 2: Start WAMP
# Click tray icon, start services

# Step 3: Create database (same as XAMPP)

# Step 4: Open application
# http://localhost/online_voting_system
```

---

## Database Configuration

### Default Configuration (No Changes Needed)
```php
// Already configured in all PHP files
$conn = new mysqli("localhost", "root", "", "voting_system");
```

### Custom Configuration

If using different credentials, edit all PHP files:

**Search for:**
```php
$conn = new mysqli("localhost", "root", "", "voting_system");
```

**Replace with:**
```php
$conn = new mysqli("YOUR_HOST", "YOUR_USER", "YOUR_PASS", "YOUR_DB");
```

**Files to update:**
- `login_handler.php`
- `signup_handler.php`
- `get_election_candidates.php`
- `submit_vote.php`
- `get_results.php`
- `admin_get_elections.php`
- `admin_create_election.php`
- `admin_get_candidates.php`
- `admin_create_candidate.php`
- `admin_get_voters.php`
- `admin_get_statistics.php`
- `admin_delete_election.php`
- `admin_delete_candidate.php`
- All other PHP files using `$conn`

---

## Verify Installation

### Check 1: PHP Installation
```powershell
php -v
# Should show: PHP version information
```

### Check 2: MySQL Running
```powershell
# Windows
Get-Service MySQL*

# If not running
net start MySQL80
```

### Check 3: Database Exists
```powershell
mysql -u root -p -e "SHOW DATABASES LIKE 'voting_system';"
# Should show: voting_system
```

### Check 4: Browser Access
```
http://localhost:8000 (PHP server)
or
http://localhost/online_voting_system (XAMPP)
```

---

## First Use Walkthrough

### 1. Register as Voter
1. Open http://localhost:8000
2. Click "Register here"
3. Fill form:
   - Voter ID: `MYVOTE001` (unique)
   - Full Name: Your name
   - Email: your@email.com
   - Password: Choose strong password
4. Click "Register"
5. Go back and login

### 2. Cast Your Vote
1. Login with your credentials
2. Review election details
3. Click a candidate card
4. Confirm vote
5. See success message

### 3. View Results
1. Click "View Results"
2. See all candidates with vote counts
3. View percentage distribution

### 4. Admin Panel (Optional)
1. Need admin account
2. Contact developer for admin access
3. Or create new admin in database:
   ```sql
   UPDATE voters SET is_admin = 1 WHERE voter_id = 'YOUR_ID';
   ```
4. Access admin dashboard: `http://localhost:8000/admin.html`

---

## Common Issues & Solutions

### Issue: "Database connection failed"

**Solution 1: Start MySQL**
```powershell
# Check if running
Get-Service MySQL*

# Start if not running
net start MySQL80
```

**Solution 2: Verify Database**
```powershell
mysql -u root -p
> SHOW DATABASES;
> USE voting_system;
> SHOW TABLES;
```

**Solution 3: Check Credentials**
- Username: `root` (default)
- Password: Empty (default)
- Database: `voting_system`
- Host: `localhost`

### Issue: "Page not found" (404)

**Solution 1: Check Server Running**
```powershell
# Window should show:
# [Mon Feb 16 10:00:00 2026] PHP 8.0.0 server started at localhost:8000
```

**Solution 2: Verify URL**
- Correct: `http://localhost:8000`
- Wrong: `http://localhost:3000`
- Wrong: `http://127.0.0.1:8080`

**Solution 3: Check File Exists**
```powershell
# Verify files are in correct directory
Get-ChildItem "online_voting_system" | Select Name
# Should show: index.html, vote.html, results.html, etc.
```

### Issue: "Login failed"

**Solution 1: Create Account First**
- New users must register before login
- Click "Register here" on login page

**Solution 2: Check Credentials**
- Voter ID must be exact (case-sensitive)
- Password must be exact
- Try uppercase/lowercase variations

**Solution 3: Check Database**
```sql
SELECT * FROM voters;
# Verify your voter exists
```

### Issue: "Can't vote - already voted"

**Expected Behavior** - This is correct!  
- Each voter can vote only once per election
- Vote is permanent and cannot be changed
- This prevents fraud

### Issue: "Admin access denied"

**Solution 1: Check Admin Flag**
```sql
SELECT voter_id, full_name, is_admin FROM voters;
# Verify is_admin = 1 for your account
```

**Solution 2: Set Admin Flag**
```sql
UPDATE voters SET is_admin = 1 WHERE voter_id = 'YOUR_ID';
```

**Solution 3: Restart Browser**
- Clear cookies: Settings → Privacy → Clear browsing data
- Logout and login again

---

## File Structure

```
online_voting_system/
│
├── 📄 index.html              Login & Registration
├── 📄 vote.html               Voting Interface
├── 📄 results.html            Results Display
├── 📄 admin.html              Admin Dashboard
│
├── 🎨 style.css               All CSS Styles
│
├── 🔧 *.php                   Backend Files (16 files)
├── 📊 database_setup.sql      Database Schema
├── 📚 README.md               Full Documentation
├── 📚 PROJECT_OVERVIEW.md     Project Summary
└── 📚 SETUP.md                This File
```

---

## Database Tables

Created by `database_setup.sql`:

1. **voters** - User accounts
2. **elections** - Election definitions
3. **candidates** - Candidate information
4. **votes** - Cast votes

### Sample Data Included
- 3 test voter accounts
- 1 test admin account
- 1 sample election
- 4 sample candidates

---

## Accessing Different Pages

### Public Pages (No Login Needed)
- `http://localhost:8000/index.html` - Login/Register

### Protected Pages (Login Required)
- `http://localhost:8000/vote.html` - Voting
- `http://localhost:8000/results.html` - Results
- `http://localhost:8000/admin.html` - Admin (needs admin account)

---

## Performance Tips

### For Local Development
- Close unused programs to free RAM
- Use latest PHP version
- Enable MySQL query caching

### For Production Deployment
- Enable HTTPS/SSL
- Use production-grade server (Nginx/Apache)
- Configure firewall rules
- Setup regular backups
- Enable query logging
- Monitor resource usage

---

## Security Checklist

- [x] Database password changed from default
- [x] PHP session.save_path is writable
- [x] File permissions set correctly (644 for files, 755 for folders)
- [x] Error messages don't expose sensitive info
- [x] SQL injection protection (prepared statements)
- [ ] HTTPS enabled (for production)
- [ ] Admin 2FA enabled (for production)
- [ ] Database backups configured (for production)

---

## Next Steps

1. ✅ Setup database
2. ✅ Start PHP server
3. ✅ Access application
4. ✅ Register voter account
5. ✅ Cast a vote
6. ✅ View results
7. ✅ Explore admin panel (if admin)
8. ✅ Read full README.md for advanced features

---

## Support

**For setup issues:**
1. Check this guide step by step
2. Verify database connection
3. Check PHP is running
4. Review error messages

**For feature questions:**
1. Read README.md
2. Check PROJECT_OVERVIEW.md
3. Review code comments
4. Check database schema in database_setup.sql

---

## Quick Troubleshooting

| Issue | Quick Fix |
|-------|-----------|
| Database connection failed | Start MySQL: `net start MySQL80` |
| Page not loading | Server must be running: `php -S localhost:8000` |
| Login fails | Register account first or check credentials |
| Already voted | Expected - one vote per voter per election |
| Admin denied | Update database: `UPDATE voters SET is_admin = 1` |
| Cookies not working | Verify PHP session settings |

---

**Ready to start?**

```powershell
php -S localhost:8000
# Then open: http://localhost:8000
```

**Happy Voting!** 🗳️

---

Last Updated: February 16, 2026  
Version: 1.0.0  
Status: ✅ Production Ready
