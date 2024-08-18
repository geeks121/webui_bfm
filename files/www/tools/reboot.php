<?php

require_once '/data/adb/php7/files/www/auth/auth_functions.php';

// If login is disabled, set the current page but do not redirect to login
if (isset($_SESSION['login_disabled']) && $_SESSION['login_disabled'] === true) {
    // Login is disabled, handle accordingly
    // You can show a message or just let the user stay on the page
    //echo "<p>Login is currently disabled.</p>";
} else {
    // Proceed to check if the user is logged in
    checkUserLogin();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'reboot') {
            shell_exec('su -c reboot');
        } elseif ($action === 'reboot_recovery') {
            shell_exec('su -c reboot recovery');
        }
    }
}
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /auth/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Control</title>
    <!-- Fonts and icons -->
    <script src="../kaiadmin/assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["../kaiadmin/assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #121212;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
        }
        .button {
            width: 200px;
            height: 60px;
            background-color: #4caf50;
            border: none;
            color: #ffffff;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 20px;
            font-weight: 500;
            margin: 20px 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s, box-shadow 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .button:hover {
            background-color: #45a049;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .button-blue {
            background-color: #2196f3;
        }
        .button-blue:hover {
            background-color: #1976d2;
        }
        .button i {
            margin-right: 10px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #2a2a2a;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
            border-radius: 10px;
        }
        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: #ffffff;
            text-decoration: none;
            cursor: pointer;
        }
        .button-green {
            background-color: #4caf50;
            width: 100px;
            height: 40px;
            margin: 10px;
            border: none;
            color: #ffffff;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s, box-shadow 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .button-green:hover {
            background-color: #45a049;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .button-red {
            background-color: #f44336;
            width: 100px;
            height: 40px;
            margin: 10px;
            border: none;
            color: #ffffff;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s, box-shadow 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .button-red:hover {
            background-color: #d32f2f;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .modal-buttons {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Device Control</h1>
        <button class="button" onclick="showModal('reboot')">
            <i class="fas fa-power-off"></i> Reboot Device
        </button>
        <button class="button button-blue" onclick="showModal('reboot_recovery')">
            <i class="fas fa-sync"></i> Reboot to Recovery
        </button>
    </div>

    <div id="reboot-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('reboot')">&times;</span>
            <p>Are you sure you want to reboot the device?</p>
            <form method="post">
                <input type="hidden" name="action" value="reboot">
                <div class="modal-buttons">
                    <button type="submit" class="button-green">Yes</button>
                    <button type="button" class="button-red" onclick="closeModal('reboot')">No</button>
                </div>
            </form>
        </div>
    </div>

    <div id="reboot_recovery-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('reboot_recovery')">&times;</span>
            <p>Are you sure you want to reboot to recovery?</p>
            <form method="post">
                <input type="hidden" name="action" value="reboot_recovery">
                <div class="modal-buttons">
                    <button type="submit" class="button-green">Yes</button>
                    <button type="button" class="button-red" onclick="closeModal('reboot_recovery')">No</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showModal(action) {
            document.getElementById(action + '-modal').style.display = 'flex';
        }

        function closeModal(action) {
            document.getElementById(action + '-modal').style.display = 'none';
        }
    </script>
</body>
</html>
