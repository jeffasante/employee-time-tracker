# Simple Employee Time Tracker - Minimal Structure

```
time-tracker/
│
├── config.php              # Database connection
├── database.sql            # Database setup
│
├── index.php               # Login page
├── dashboard.php           # Main page (clock in/out + view hours)
├── logout.php              # Logout
│
├── style.css               # All styles
└── script.js               # All JavaScript
```

## Simple Database (database.sql)

```sql
-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL
);

-- Time entries table
CREATE TABLE time_entries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    clock_in DATETIME NOT NULL,
    clock_out DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert demo user (password: demo123)
INSERT INTO users (email, password, name) VALUES 
('demo@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Demo User');
```

## Core Features (Just 3 things)

1. **Login** - Simple email/password
2. **Clock In/Out** - One button that toggles
3. **View Hours** - See this week's total hours

## What You'll Build

- **index.php** - Login form (50 lines)
- **dashboard.php** - Clock in/out button + timesheet table (100 lines)
- **config.php** - Database connection (10 lines)
- **style.css** - Basic clean styling (50 lines)
- **script.js** - Handle clock in/out via AJAX (30 lines)

**Total: ~250 lines of code**

This can be built in **2-3 hours** and fully demonstrates:
- PHP backend logic
- MySQL database interaction
- JavaScript frontend
- Clean, working application

Want me to build this small version for you?