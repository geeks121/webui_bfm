<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    // Redirect to the intended page if set, otherwise to index.php
    $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'index.php';
    header("Location: $redirect_url");
    exit;
}

// Hard-coded credentials
$stored_username = 'admin';
// Replace this with the hashed password generated above
$stored_hashed_password = '$2y$10$VC7N65gPAESPJWYv9JTMDeSj92pYtIzh5Pb5piGc3HHYotpx41a7K';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate credentials
    if ($username === $stored_username && password_verify($password, $stored_hashed_password)) {
        $_SESSION['user_id'] = session_id();
        $_SESSION['username'] = $username;

        // Redirect based on the intended URL or default to index.php
        $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'index.php';
        unset($_SESSION['redirect_url']); // Clear the redirect URL after use
        header("Location: $redirect_url");
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <!-- Import Materialize CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
    <!-- Custom Styles for Dark Mode -->
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            width: 300px;
            text-align: center;
        }
        .input-field input[type=text], .input-field input[type=password] {
            color: #ffffff;
        }
        .input-field label {
            color: #ffffff;
        }
        .input-field input[type=text]:focus + label,
        .input-field input[type=password]:focus + label {
            color: #26a69a !important;
        }
        .input-field input[type=text]:focus,
        .input-field input[type=password]:focus {
            border-bottom: 1px solid #26a69a !important;
            box-shadow: 0 1px 0 0 #26a69a !important;
        }
        .error {
            color: red;
            margin-bottom: 20px;
        }
        .powered-by {
            color: #9e9e9e;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h5>Welcome to BFR WebUI</h5>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post" action="login.php">
            <div class="input-field">
                <input type="text" name="username" id="username" required>
                <label for="username">Username</label>
            </div>
            <div class="input-field">
                <input type="password" name="password" id="password" required>
                <label for="password">Password</label>
                <p>
                    <label>
                        <input type="checkbox" onclick="togglePassword()"> <!-- Toggle function added -->
                        <span>Show Password</span>
                    </label>
                </p>
            </div>
            <button type="submit" class="btn waves-effect waves-light teal lighten-2">Login</button>
        </form>
        <div class="powered-by">powered by ZAPBER</div>
    </div>
    <!-- Import Materialize JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <!-- Custom JavaScript for Show Password -->
    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>
</body>
</html>