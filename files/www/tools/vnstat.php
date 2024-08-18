<?php
// Start vnstat daemon in the background using nohup
//shell_exec('nohup sudo vnstatd -n > /dev/null 2>&1 &');

// Delay for daemon to start
sleep(2);

// Run vnstat for daily and monthly usage with root
$vnstatDailyOutput = shell_exec('su -c /data/data/com.termux/files/usr/bin/vnstat -d -i rmnet_data1 2>&1');
$vnstatMonthlyOutput = shell_exec('su -c /data/data/com.termux/files/usr/bin/vnstat -m -i rmnet_data1 2>&1');

// Function to parse vnstat output and extract required data
function parseVnstatOutput($output, $type) {
    $lines = explode("\n", $output);
    $result = '';

    foreach ($lines as $line) {
        // Daily usage parsing
        if ($type === 'daily') {
            if (preg_match('/(\d{4}-\d{2}-\d{2})\s+([\d.]+ \w+)\s+\|\s+([\d.]+ \w+)\s+\|\s+([\d.]+ \w+)/', $line, $matches)) {
                $result .= '<tr>';
                $result .= '<td>' . htmlspecialchars($matches[1]) . '</td>';
                $result .= '<td>' . htmlspecialchars($matches[2]) . '</td>';
                $result .= '<td>' . htmlspecialchars($matches[3]) . '</td>';
                $result .= '<td>' . htmlspecialchars($matches[4]) . '</td>';
                $result .= '</tr>';
            }
        }

        // Monthly usage parsing
        if ($type === 'monthly') {
            if (preg_match('/(\d{4}-\d{2})\s+([\d.]+ \w+)\s+\|\s+([\d.]+ \w+)\s+\|\s+([\d.]+ \w+)/', $line, $matches)) {
                $result .= '<tr>';
                $result .= '<td>' . htmlspecialchars($matches[1]) . '</td>';
                $result .= '<td>' . htmlspecialchars($matches[2]) . '</td>';
                $result .= '<td>' . htmlspecialchars($matches[3]) . '</td>';
                $result .= '<td>' . htmlspecialchars($matches[4]) . '</td>';
                $result .= '</tr>';
            }
        }
    }

    return $result;
}

// Parse the output for daily and monthly usage
$dailyUsage = parseVnstatOutput($vnstatDailyOutput, 'daily');
$monthlyUsage = parseVnstatOutput($vnstatMonthlyOutput, 'monthly');

// HTML for displaying the results
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vnstat Usage</title>
    <style>
        body {
            background-color: #2c2c2c;
            color: #f1f1f1;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .output-box {
            background-color: #333;
            border: 1px solid #444;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            overflow-x: auto; /* Enables horizontal scrolling if table is too wide */
        }
        h2 {
            color: #ffa500;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: center; /* Center-align text in headers and cells */
            border: 1px solid #444;
        }
        th {
            background-color: #444;
            color: #ffa500;
        }
        tr:nth-child(even) {
            background-color: #2a2a2a;
        }
        tr:nth-child(odd) {
            background-color: #333;
        }
    </style>
</head>
<body>
    <h2>Daily Usage</h2>
    <div class="output-box">
        <table>
            <thead>
                <tr>
                    <th>Day</th>
                    <th>RX</th>
                    <th>TX</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $dailyUsage; ?>
            </tbody>
        </table>
    </div>

    <h2>Monthly Usage</h2>
    <div class="output-box">
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>RX</th>
                    <th>TX</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $monthlyUsage; ?>
            </tbody>
        </table>
    </div>
</body>
</html>