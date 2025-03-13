<?php
$clashlogs = "/data/adb/box/run/runs.log";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        /* Dark mode text color for logs */
        body.dark-mode {
            background-color: #121212;
            color: white;
        }
        .refresh-button {
            background-color: grey;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
        }
    </style>
    <title>Clash Logs</title>
</head>
<body>
    <button class="refresh-button" onclick="location.reload();">Refresh</button>
    <div class="card">
        <div class="card-header">
            Clash Logs
        </div>
        <ul class="list-group">
            <?php
            $file = fopen("$clashlogs", "r");
            while (!feof($file)) {
                $log = str_replace('"', '', fgets($file));
                echo nl2br($log);
            }
            fclose($file);
            ?>
        </ul>
    </div>
    <script>
        // Function to check and apply dark mode
        function applyDarkMode() {
            const isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (isDarkMode) {
                document.body.classList.add('dark-mode');
            }
        }

        // Apply dark mode on page load
        window.onload = applyDarkMode;
    </script>
</body>
</html>
