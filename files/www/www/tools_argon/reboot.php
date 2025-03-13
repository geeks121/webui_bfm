<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reboot or Turn Off</title>
    <style>
        p {
            font-size: 14px;
            color: #333;
            margin: 0;
            text-align: left;
            padding-left: 5px;
        }
        * {
            -webkit-user-select: none; /* Safari */
            -moz-user-select: none; /* Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none; /* Standard */
            -webkit-tap-highlight-color: transparent; /* Hilangkan efek tap di mobile */
        }
        .line {
            width: 100%;
            max-width: 320px;
            height: 1px;
            background-color: #ccc;
            margin: 10px 0 20px;
            margin-left: 6px;
        }
        .buttons {
            display: flex;
            gap: 10px;
            width: 100%;
            max-width: 320px;
            justify-content: flex-start;
            padding-left: 6px;
        }
        button {
            font-size: 14px;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .reboot {
            background-color: #ff5722;
            color: white;
            width: 140px;
        }
        .turnoff {
            background-color: #ff9800;
            color: white;
            width: 100px;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 30%;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            max-width: 350px;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            text-align: center;

        }
        .modal-buttons {
            display: flex;
            justify-content: space-around;
        }
        .modal-buttons button {
            width: 80px;
        }
    body {
      font-family: Arial, sans-serif;
      background-color: transparent;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: center;
      margin: 0;
      padding-left: 20px;
      padding-right: 20px;
    }
    header {
      padding: 0;
      text-align: center;
      position: relative;
      width: 100%;
    }
    .header-top {
      background-color: #transparent;
      padding: 5px;
    }
    .header-bottom {
      background-color: transparent;
      padding: 5px;
    }
    header h1 {
      margin: 0;
      font-size: 0.8em;
      color: #transparent;
    }
    .new-container {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      margin-bottom: 100px;
      border-radius: 5px;
      width: 100%;
      height: 100%;
      padding: 10px;
      box-sizing: border-box;
      background-color: #ffffff;
      color: #000;
      text-align: center;
      z-index: 2;
    }
    .new-container p {
      text-align: left;
      font-size: 1em;
      color: #555;
      margin-top: 3px;
      margin-left: 10px;
      font-weight: bold;
    }
    .container {
      border-radius: 12px;
      padding: 10px;
      margin-bottom: 20px;
      margin-top: 10px;
      width: 90%;
      background-color: #ffffff;
      height: 100%;
    }

/* Dark mode styles */
@media (prefers-color-scheme: dark) {
    body, p {
        background-color: transparent;
        color: #e0e0e0; /* Dark mode text color */
    }

    header h1 {
        color: #e0e0e0; /* Dark mode header color */
    }

    .new-container, .new-container p {
        background-color: #2a2a2a;
        color: #e0e0e0;
    }

    .container {
        background-color: #2a2a2a;
        color: #e0e0e0;
    }

    .line {
        background-color: #e0e0e0; /* Dark mode line color */
    }


    .modal-content {
        background-color: #333;
        color: #e0e0e0;
    }

}

    </style>
</head>
<body>
<header>
    <div class="new-container">
        <p>Reboot</p>
    </div>
    <div class="header-top">
        <h1>o</h1>
    </div>
    <div class="header-bottom">
        <h1>o</h1>
    </div>
</header>
    <p style="margin-top: 30px;">Reboots or turns off the operating system of your device</p>
    <div class="line"></div>
    <div class="buttons">
        <button class="reboot" onclick="confirmAction('reboot')">Perform Reboot</button>
        <button class="turnoff" onclick="confirmAction('turn_off')">Turn Off</button>
    </div>

    <!-- Modal Container -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to perform this action?</p>
            <div class="modal-buttons">
                <button onclick="executeAction(true)">Yes</button>
                <button onclick="executeAction(false)">No</button>
            </div>
        </div>
    </div>

    <script>
        let action = '';

        // Show confirmation modal
        function confirmAction(selectedAction) {
            action = selectedAction;
            document.getElementById('confirmationModal').style.display = 'flex';
        }

        // Handle action execution or cancellation
        function executeAction(isConfirmed) {
            if (isConfirmed) {
                // Create and submit the form to execute the action
                const form = document.createElement('form');
                form.method = 'POST';
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'action';
                input.value = action;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
            // Close the modal
            document.getElementById('confirmationModal').style.display = 'none';
        }
    </script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            if ($action === 'reboot') {
                shell_exec('su -c reboot');
                echo "<script>alert('Rebooting device...');</script>";
            } elseif ($action === 'turn_off') {
                shell_exec('su -c reboot -p');
                echo "<script>alert('Turning off device...');</script>";
            }
        }
    }
    ?>
</body>
</html>
