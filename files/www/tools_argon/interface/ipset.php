<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ip = $_POST['ip'] ?? '192.168.43.1';
    $interface = $_POST['interface'] ?? 'wlan0';

    $command = "su -c 'ip addr add {$ip}/24 dev {$interface}'";
    $output = shell_exec($command);
    
    if (empty($output)) {
        $output = "Command executed successfully: {$command}";
    }
    echo $output; // Echo the result to send it back as a response
    exit; // Stop further processing
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface Management</title>
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: transparent;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin: 0;
    min-height: 2vh;
}
        .edit-form {
            display: block; /* Make the form visible by default */
            position: fixed;
            z-index: 2;
            left: 50%;
            top: 30%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: auto;
            background-color: white;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .edit-form h3 {
            margin-bottom: 20px;
            text-align: center;
            font-size: 1.2em;
            font-weight: 550;
        }

        .edit-form p {
            margin: 10px 0;
            text-align: left;
            font-size: 0.9em;
            font-weight: 500;
        }

        .edit-form textarea {
            width: 93%;
            padding: 8px;
            border-radius: 3px;
            border: 1px solid #ccc;
            font-size: 14px;
            margin-bottom: 1px;
            height: 20px;
        }

        .edit-form button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s ease;
            margin-top: 30px;
            margin-bottom: 20px
        }

        .edit-form button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .feedback {
            margin-top: 10px;
            text-align: center;
            font-size: 1em;
            font-weight: 500;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
        @media (prefers-color-scheme: dark) {
    body {
        background-color: transparent;
        color: #e0e0e0;
    }

    .edit-form {
        background-color: #333;
        color: #e0e0e0;
    }

    .edit-form button {
        background-color: #006bb3;
    }

    .edit-form button:hover {
        background-color: #0056b3;
    }
}
    </style>
</head>
<body>

    <form id="network-form" method="post">
        <div id="network" class="edit-form">
            <h3>Set IP Address</h3>
            <p>Interface</p>
            <textarea id="interface" name="interface" class="validate" placeholder="wlan0"><?php echo htmlspecialchars($interface ?? 'wlan0'); ?></textarea>

            <p>IP Address</p>
            <textarea id="ip" name="ip" class="validate" placeholder="192.168.43.1"><?php echo htmlspecialchars($ip ?? '192.168.43.1'); ?></textarea>

            <button type="submit">Save</button>
        </div>
    </form>

    <div id="feedback" class="feedback"></div>

    <script>
        document.getElementById('network-form').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this); // Collect the form data
            var xhr = new XMLHttpRequest();
            
            xhr.open("POST", "", true); // Send the request to the same page

            xhr.onload = function() {
                if (xhr.status === 200) {
                    // On success, display the server's response
                    var response = xhr.responseText;
                    var feedbackElement = document.getElementById('feedback');
                    
                    if (response.includes("Command executed successfully")) {
                        feedbackElement.textContent = "Success: " + response;
                        feedbackElement.className = "feedback success";
                    } else {
                        feedbackElement.textContent = "Error: " + response;
                        feedbackElement.className = "feedback error";
                    }
                } else {
                    // If the request failed
                    document.getElementById('feedback').textContent = 'Error communicating with the server.';
                    document.getElementById('feedback').className = 'feedback error';
                }
            };

            xhr.onerror = function() {
                document.getElementById('feedback').textContent = 'Error sending the request.';
                document.getElementById('feedback').className = 'feedback error';
            };

            xhr.send(formData); // Send the form data asynchronously
        });
    </script>

</body>
</html>
