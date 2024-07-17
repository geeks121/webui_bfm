<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'start_box') {
            shell_exec('su -c /data/adb/box/scripts/box.service start && su -c /data/adb/box/scripts/box.iptables enable');
        } elseif ($action === 'stop_box') {
            shell_exec('su -c /data/adb/box/scripts/box.iptables disable && su -c /data/adb/box/scripts/box.service stop');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Control</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
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
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>Device Control</h1>
        <form method="post">
            <input type="hidden" name="action" value="start_box">
            <button type="submit" class="button">
                <i class="fas fa-play"></i> Start BOX
            </button>
        </form>
        <form method="post">
            <input type="hidden" name="action" value="stop_box">
            <button type="submit" class="button button-blue">
                <i class="fas fa-stop"></i> Stop BOX
            </button>
        </form>
    </div>
</body>
</html>
