<?php
session_start();

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

// Load the current configuration
$config = json_decode(file_get_contents('config.json'), true);

// Handle form submission to update the configuration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $config['LOGIN_ENABLED'] = isset($_POST['login_enabled']);
    
    // Save the updated configuration back to the JSON file
    file_put_contents('config.json', json_encode($config, JSON_PRETTY_PRINT));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../tools/ocgen/data/fontawesome6/css/all.css" />
    <title>Manage Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: transparent;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0;
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

        .container {
            border-radius: 12px;
            padding: 10px;
            margin-bottom: 20px;
            margin-top: 40px;
            width: calc(100% - 40px);
            height: 100%;
        }

        .section-title {
            margin-top: 10px;
            font-size: 17px;
            color: #000;
        }

        .card {
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            background-color: #f5f5f5;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .title {
            font-size: 2.4rem;
            text-align: center;
            margin: 20px 0 40px;
            font-weight: 500;
            color: #000;
        }

        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin: 10px 2;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 12px;
            background-color: #e0e0e0;
            cursor: pointer;
            margin: 10px -5px;
        }

        .checkbox {
            position: relative;
            height: 30px;
            width: 80px;
            background: #2e394d;
            border-radius: 30px;
        }

        .checkbox-input {
            position: absolute;
            height: 100%;
            width: 100%;
            outline: none;
            z-index: 1;
            -webkit-appearance: none;
        }

        .checkbox-icons::before {
            position: absolute;
            content: "\f00d";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: white;
            height: 27px;
            width: 27px;
            background: #c34a4a;
            border-radius: 50%;
            left: 2px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 17px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.8s;
        }

        .checkbox-input:checked + label .checkbox-icons::before {
            background: #8bc34a;
            transform: translateY(-50%) rotate(360deg);
            left: calc(100% - 29px);
            content: "\f00c";
        }

        .checkbox-wrapper span {
            font-weight: 550;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 0px;
            margin: 3px;
            color: #000;
        }

        .btn {
            margin-top: 30px !important;
            margin: 15px;
            border: none;
            border-radius: 12px;
            text-transform: none;
            font-weight: 500;
            font-size: 1.1rem;
            height: 46px;
            line-height: 46px;
            padding: 0 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn.green {
            background-color: #4CAF50; /* Hijau */
            color: white;
        }

        .btn i {
            font-size: 20px;
        }
 
        @media (max-width: 600px) {
            .title {
                font-size: 1.5rem;
                margin: 15px 0 30px;
            }
            
            .btn {
                width: 100%;
                margin: 10px 0;
                height: 40px;
                line-height: 40px;
                font-size: 1rem;
            }

            .checkbox-group {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .section-title {
                font-size: 1.5rem;
                margin: 25px 0 15px;
            }

            .card {
                padding: 20px;
                margin: 20px 0;
            }

            .checkbox-wrapper {
                padding: 12px;
            }

            [type="checkbox"] + span {
                font-size: 1rem;
            }
        }
        
@media (prefers-color-scheme: dark) {
  body {
    background-color: transparent;
    color: white;
  }

  .new-container, .new-container p {
    background-color: #2a2a2a;
    color: #e0e0e0;
  }

  .container {
    background-color: transparent;
  }

  .section-title, .title, .checkbox-wrapper span {
    color: #fff;
  }

  .card {
    background-color: #1e1e1e;
  }
  
  .checkbox-wrapper {
    background-color: #2d2d2d;
  }
}



    </style>
</head>
<body>

    <header>
        <div class="new-container">
            <p>Manage Login</p>
        </div>
    </header>

<div class="container">
    <div class="card">
        <form action="" method="post">
            <h2 class="section-title">Enable / Disable Login</h2>
            <div class="checkbox-group">
                <div class="checkbox-wrapper">
                    <span>Login Disabled</span>
                    <div class="checkbox">
                        <input type="checkbox" name="login_enabled" class="checkbox-input" id="login_enabled" <?php echo $config['LOGIN_ENABLED'] ? 'checked' : ''; ?>>
                        <label for="login_enabled">
                            <div class="checkbox-icons"></div>
                        </label>
                    </div>
                    <span>Login Enabled</span>
                </div>
            </div>

            <div class="center-align">
                <button type="submit" class="btn green">Save Changes</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
