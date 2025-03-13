<?php
$clashlogs = "/cache/magisk.log";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magisk Logs</title>
    <link rel="stylesheet" href="css&fonts/logs_exec.css">
</head>
<header>
    <div class="new-container">
        <p>Magisk Logs</p>
    </div>
    <div class="header-top">
        <h1>o</h1>
    </div>
    <div class="header-bottom">
        <h1>o</h1>
    </div>
</header>
<body>
    <div class="b-container">
        <div class="logs-card">
            <div class="logs-header">
                <span>Log Entries</span>
            </div>
            <div class="logs-container">
                <?php
                $file = fopen("$clashlogs", "r");
                while (!feof($file)) {
                    $log = str_replace('"', '', fgets($file));
                    echo '<div class="log-entry">' . htmlspecialchars($log) . '</div>';
                }
                fclose($file);
                ?>
            </div>
        </div>
    </div>

</body>
</html>