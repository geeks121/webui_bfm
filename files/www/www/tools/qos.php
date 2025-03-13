<?php
function isBlocked($ip) {
    $output = shell_exec("iptables -L FORWARD -v -n --line-numbers | grep $ip");
    return !empty($output);
}

$devices = shell_exec('ip neigh');
$connectedDevices = [];
if ($devices) {
    $lines = explode("\n", $devices);
    foreach ($lines as $line) {
        if (preg_match('/(\d+\.\d+\.\d+\.\d+)\s+dev\s+([^\s]+)\s+lladdr\s+(\S+)/', $line, $matches)) {
            $connectedDevices[] = [
                'ip' => $matches[1],
                'interface' => $matches[2],
                'mac' => $matches[3]
            ];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $ip = escapeshellarg($_POST['ipAddress']);  // Sanitize input
    $action = $_POST['action'];

    if ($action === 'block') {
        shell_exec("iptables -I FORWARD -s $ip -j DROP");
    } elseif ($action === 'unblock') {
        shell_exec("iptables -D FORWARD -s $ip -j DROP");
        shell_exec("iptables -D FORWARD -s $ip -j REJECT");
        shell_exec("iptables -D FORWARD -s $ip -d 0.0.0.0/0 -j DROP");
    }
}

// Apply the limits if the form for speed is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['speedMbps'])) {
    $speed = $_POST['speedMbps'] * 125; // Convert Mbps to KB/s
    $ip = $_POST['ipAddress'];
    shell_exec('tc qdisc del dev wlan0 root 2>/dev/null');
    shell_exec('tc qdisc add dev wlan0 root handle 1: htb default 12');
    shell_exec("tc class add dev wlan0 parent 1: classid 1:1 htb rate ${speed}kbps");
    shell_exec("tc class add dev wlan0 parent 1: classid 1:2 htb rate ${speed}kbps");
    shell_exec("tc filter add dev wlan0 protocol ip parent 1:0 prio 1 u32 match ip dst ${ip} flowid 1:1");
    shell_exec("tc filter add dev wlan0 protocol ip parent 1:0 prio 1 u32 match ip src ${ip} flowid 1:2");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandwidth Limiter</title>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
    <style>
/* Dark mode applied by default */
body {
    font-family: Arial, sans-serif;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 0;
    background-color: #121212; /* Dark background */
    color: #ffffff; /* White text */
}

.container {
    background: #1e1e1e; /* Dark gray container */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.5);
    width: calc(100% - 80px);
    margin: 10px 0;
}

.label {
    display: block;
    font-weight: bold;
    font-size: 12px;
    margin-top: 15px;
    margin-bottom: 9px;
    color: #ddd; /* Light gray text */
}

.slider-container {
    display: flex;
    align-items: center;
    position: relative;
    width: 100%;
}

.slider-wrapper {
    position: relative;
    width: 75%;
}

input[type="range"] {
    width: 100%;
    -webkit-appearance: none;
    background: transparent;
    position: relative;
    z-index: 2;
    outline: none;
}

input[type="range"]::-webkit-slider-runnable-track {
    width: 100%;
    height: 6px;
    border-radius: 5px;
    background: #444; /* Darker track */
}

input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 25px;
    height: 20px;
    background: transparent;
    cursor: pointer;
    position: relative;
    z-index: 3;
    margin-top: -7px;
}

.progress-bar {
    position: absolute;
    height: 6px;
    background: #4285F5;
    border-radius: 5px;
    top: 60%;
    left: 0;
    transform: translateY(-50%);
    z-index: 4;
}

.slider-thumb {
    position: absolute;
    width: 25px;
    height: 20px;
    background: #4285F5;
    border-radius: 3px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    pointer-events: none;
    z-index: 4;
    top: 50%;
    transform: translateY(-50%);
}

.icon-container {
    display: flex;
    font-size: 10px;
    gap: 0px;
}

input[type="text"] {
    width: 35%;
    height: 22px;
    margin-top: 5px;
    padding: 5px;
    border: 1px solid #555; /* Darker border */
    border-radius: 5px;
    background: #222; /* Darker input field */
    color: white;
}

input[type="text"]:focus {
    outline: 1px solid #4285F9;
}

button {
    width: 30%;
    margin-top: 60px;
    padding: 10px;
    background-color: #4285F5;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
    float: right;
    white-space: nowrap;
}

button:hover {
    background-color: #0050c0;
}

#speedValue, #uploadValue {
    position: absolute;
    font-size: 12px;
    font-weight: 550;
    right: 0;
}

table {
    width: calc(100% + 20px);
    height: 100%;
    border-collapse: collapse;
    margin: -10px;
    table-layout: fixed;
}

thead {
    background-color: #090b0f;
    color: #fff;
}

th, td {
    padding: 9px 12px;
    height: 5px;
    border: 1px solid #444; /* Darker border */
    text-align: center;
    font-size: 10px;
    color: white;
}

tbody tr:nth-child(even) {
    background-color: #2a2a2a; /* Dark row background */
}

tbody tr:hover {
    background-color: #3a3a3a; /* Hover effect */
}

.block-btn:hover {
    background-color: #c82333;
}

td form {
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
}

.block-btn {
    background-color: #dc3545;
    color: white;
    padding: 5px 20px;
    border: none;
    border-radius: 4px;
    font-size: 10px;
    margin: -1px -5px -1px -5px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    text-align: center;
}

    </style>
</head>
<body>
    <div class="container">
        <form method="POST">
            <label class="label">DOWNLOAD + UPLOAD</label>
            <div class="slider-container">
                <div class="slider-wrapper">
                    <div class="progress-bar" id="speedProgress"></div>
                    <input type="range" id="speed" name="speedMbps" min="1" max="100" value="20">
                    <div class="slider-thumb" id="speedThumb">
                        <div class="icon-container">
                        <span class="iconify" data-icon="mdi:chevron-left"></span>
                        <span class="iconify" data-icon="mdi:chevron-right"></span>
                    </div>
                  </div>
                </div>
                <span id="speedValue">20 Mbps</span>
            </div>

            <label class="label">IP Address</label>
            <input type="text" id="ip" name="ipAddress" value="192.168.">

            <button type="submit">Apply limits</button>
        </form>
    </div>
    
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>IP Address</th>
                    <th>Interface</th>
                    <th>Mac</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($connectedDevices as $index => $device): ?>
                    <?php
                    $ip = $device['ip'];
                    $blocked = isBlocked($ip);  // Cek apakah IP diblokir
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($device['ip']) ?></td>
                        <td><?= htmlspecialchars($device['interface']) ?></td>
                        <td><?= htmlspecialchars($device['mac']) ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="ipAddress" value="<?= htmlspecialchars($device['ip']) ?>">
                                <button type="submit" class="block-btn">
                                    <?= $blocked ? 'Unblock' : 'Block' ?>
                                </button>
                                <input type="hidden" name="action" value="<?= $blocked ? 'unblock' : 'block' ?>">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let speedSlider = document.getElementById("speed");
        let speedValue = document.getElementById("speedValue");
        let speedThumb = document.getElementById("speedThumb");
        let speedProgress = document.getElementById("speedProgress");


        // Function to update the slider thumb position and value
        function updateSlider(slider, valueDisplay, thumb, progressBar) {
            valueDisplay.textContent = slider.value + " Mbps";
            let percent = (slider.value - slider.min) / (slider.max - slider.min) * 100;
            progressBar.style.width = percent + "%";
            thumb.style.left = `calc(${percent}% - 12px)`;  // Adjust for the thumb width
        }

        // Event listeners for slider input
        speedSlider.addEventListener("input", function () {
            updateSlider(speedSlider, speedValue, speedThumb, speedProgress);
        });

        // Initialize sliders on page load
        updateSlider(speedSlider, speedValue, speedThumb, speedProgress);
    });
</script>
</body>
</html>