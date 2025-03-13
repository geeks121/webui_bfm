<?php
$p = $_SERVER['HTTP_HOST'];
$x = explode(':', $p);
$host = $x[0];
$start = "/data/clash/scripts/clash.service -s && /data/clash/scripts/clash.iptables -s";
$stop = "/data/clash/scripts/clash.iptables -k && /data/clash/scripts/clash.service -k";
$restart = "/data/clash/scripts/clash.iptables -k && /data/clash/scripts/clash.service -k && /data/clash/scripts/clash.service -s && /data/clash/scripts/clash.iptables -s";
$clashlogs = "/data/clash/run/run.logs";

// Shell execution functions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action == 'start') {
        shell_exec($start);
    } elseif ($action == 'stop') {
        shell_exec($stop);
    } elseif ($action == 'restart') {
        shell_exec($restart);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clash For Magisk</title>
    <link rel="stylesheet" href="../css&fonts/logs_exec.css">
</head>
<body>
    <div class="container">
        <a href="http://<?php echo $host; ?>:9090/ui/#/overview" target="_blank">
            <button class="dashboard-button">Dashboard</button>
        </a>
        <div class="button-container">
            <form method="POST">
                <button type="submit" name="action" value="start">Start</button>
                <button type="submit" name="action" value="restart">Restart</button>
                <button type="submit" name="action" value="stop">Stop</button>
            </form>
        </div>

        <!-- Logs Card -->
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
  <!-- Loading Spinner -->
  <div id="loading-container" class="loading-container" style="display:none;">
    <span class="svg-spinners--bars-rotate-fade" style="margin-right: 8px;"></span>
    <span>Loading...</span>
  </div>
 <script>
    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('loading-container').style.display = 'flex'; // Show spinner
    });
 </script>
</body>
</html>