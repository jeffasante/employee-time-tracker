<?php
require_once 'config.php';

// if already logged in, redirect to dashboard
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

// handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        // prepare and execute query to check credentials
        $stmt = $conn->prepare('SELECT id, email, password, name FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // verify password
        if ($user && password_verify($password, $user['password'])) {
            // login successful, set session variable
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'];
            header('Location: dashboard.php'); // redirect to dashboard
            exit();
        } else {
            $error = 'Invalid email or password';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Time Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Time Tracker</h1>
            <p class="subtitle">Sign in to continue</p>

             <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="index.php">
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="Email address" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>

               <button type="submit" class="btn btn-primary">Sign In</button>
            </form>

            <div class="demo-info">
                <p><strong>Demo Credentials</strong></p>
                <p>demo@example.com</p>
                <p>demo123</p>
            </div>
        </div>
    </div>
</body>
</html>