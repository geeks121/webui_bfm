<?php
session_start();

require_once '/data/adb/php7/files/www/auth/auth_functions.php';
if (isset($_SESSION['login_disabled']) && $_SESSION['login_disabled'] === true) {
} else {
    checkUserLogin();
}

$credentials = include 'credentials.php';
$stored_username = $credentials['username'];
$stored_hashed_password = $credentials['hashed_password'];

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Validate current password using the same method as login
    {
        if ($new_password === $confirm_new_password) {
            // Hash new password
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update credentials.php file
            $credentials_content = "<?php\n";
            $credentials_content .= "if (basename(__FILE__) == basename(\$_SERVER['PHP_SELF'])) {\n";
            $credentials_content .= "    header('Location: /');\n";
            $credentials_content .= "    exit;\n";
            $credentials_content .= "}\n";
            $credentials_content .= "return [\n";
            $credentials_content .= "    'username' => '" . addslashes($new_username) . "',\n";
            $credentials_content .= "    'hashed_password' => '" . addslashes($new_hashed_password) . "',\n";
            $credentials_content .= "];\n";

            file_put_contents('credentials.php', $credentials_content);

            $success = 'Username and password have been updated successfully.';
        } else {
            $error = 'New passwords do not match.';
        }
    } 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: transparent;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;
            height: 100%;
            flex-direction: column;
        }
        .card-a {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: calc(100% - 80px);
            text-align: left;
            margin-top: 70px;
        }
        h2 {
            color: #343a40;
            font-size: 20px;
            margin-bottom: 30px;
            font-weight: 700;
        }
        p {
            color: #000;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }
        
        label {
            font-size: 15px;
            font-weight: 600;
            color: #555;
            display: block;
            margin-bottom: 10px;
        }
        .input-container {
            position: relative;
            margin-bottom: 20px;
        }
        input {
            top: 50%;
            width: calc(100% - 33px);
            padding: 13px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f8f9fa;
            color: #333;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
            margin-left: 5px;
        }
        input:focus {
            border-color: #6379f4;
        }
        .toggle-password {
            position: absolute;
            right: 0px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 0.6em;
            color: white;
            background-color: #8897aa;
            width: 43px;
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0 5px 5px 0;
        }
        .btn {
            display: block;
            width: 40%;
            padding: 12px;
            background-color: #6379f4;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            margin: 20px auto 5px;
        }
        .btn:hover {
            background-color: #5064c9;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
        header {
            padding: 0;
            text-align: center;
            position: relative;
            width: 100%;
        }
        .new-container {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 40px);
            height: 50px;
            padding: 10px;
            box-sizing: border-box;
            background-color: #ffffff;
            color: #000;
            text-align: center;
            z-index: 1;
            border-radius: 5px;
        }
        .new-container p {
            text-align: left;
            font-size: 1.1em;
            color: #555;
            margin-top: 3px;
            margin-left: 10px;
            font-weight: bold;
        }
@media (prefers-color-scheme: dark) {
    body {
        background-color: transparent;
        color: transparent;
    }
    .card-a {
        background-color: #2a2a2a;
        color: #e0e0e0;
        box-shadow: 4px 4px 6px rgba(0, 0, 0, 0.3);
    }
    h2 {
        color: #f1f1f1;
    }
    p {
        color: #f1f1f1;
    }
    label {
        color: #ddd;
    }
    input {
        background-color: #444;
        color: #ccc;
        border: 1px solid #666;
    }
    input:focus {
        border: 1px solid #474f72;
    }
    .btn {
        background-color: #474f72;
    }
    .btn:hover {
        background-color: #2e344e;
    }
    .new-container, .new-container p {
        background-color: #2a2a2a;
        color: #e0e0e0;
    }
}
@media (min-width: 800px) {
    select {
        width: 20%;
    }
    .btn {
        width: 25%;
    }
}
.oui--asterisk {
  display: inline-block;
  width: 15px;
  height: 15px;
  --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='%23000' d='M6.928 8.468L4 7.618l.446-1.427L7.375 7.25L7.287 4h1.484l-.097 3.296l2.88-1.039L12 7.693l-2.977.86l1.92 2.56L9.741 12L7.937 9.28l-1.745 2.654l-1.213-.86z'/%3E%3C/svg%3E");
  background-color: currentColor;
  -webkit-mask-image: var(--svg);
  mask-image: var(--svg);
  -webkit-mask-repeat: no-repeat;
  mask-repeat: no-repeat;
  -webkit-mask-size: 100% 100%;
  mask-size: 100% 100%;
}
    </style>
</head>
<body>
    <header>
        <div class="new-container">
            <p>Administration</p>
        </div>
    </header>
    <div class="card-a">
        <h2>WebUI Password</h2>
        <p>Changes the administrator password for accessing the device</p>
        <form method="post" action="change_password.php">
            <label for="design">New Username</label>
            <div class="input-container">
                <input type="text" name="new_username" id="new_username" required>
            </div>
            <label for="design">New Password</label>
            <div class="input-container">
                <input type="password" name="new_password" id="new_password" required>
                <span class="toggle-password" onclick="togglePasswordVisibility('new_password')"><i class="oui--asterisk"></i></span>
            </div>
            <label for="design">Confirm New Password</label>
            <div class="input-container">
                <input type="password" name="confirm_new_password" id="confirm_new_password" required>
                <span class="toggle-password" onclick="togglePasswordVisibility('confirm_new_password')"><i class="oui--asterisk"></i></span>
            </div>
            <button type="submit" class="btn waves-effect waves-light">Save</button>
        </form>
      <?php if ($error) echo "<p class='error'>$error</p>"; ?>
      <?php if ($success) echo "<p class='success'>$success</p>"; ?>
    </div>

<script>
    function togglePasswordVisibility(id) {
        var field = document.getElementById(id);
        field.type = field.type === "password" ? "text" : "password";
    }
document.addEventListener("DOMContentLoaded", async function () {
    const newContainer = document.querySelector(".new-container");

    if (!document.referrer) {
        newContainer.style.display = "none";
        return;
    }

    try {
        const response = await fetch(document.referrer);
        const text = await response.text();
        const titleMatch = text.match(/<title>(.*?)<\/title>/);
        const previousTitle = titleMatch ? titleMatch[1] : "";

        if (previousTitle === "Argon") {
            newContainer.style.display = "block"; // Tampilkan jika title sebelumnya "Argon"
        } else {
            newContainer.style.display = "none"; // Sembunyikan jika tidak
        }
    } catch (error) {
        console.error("Gagal mengambil title halaman sebelumnya:", error);
        newContainer.style.display = "none"; // Default disembunyikan jika error
    }
});
</script>

</body>
</html>