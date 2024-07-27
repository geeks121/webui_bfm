<?php
// Function: makeTitle
function makeTitle($title) {
    echo "<h2>$title</h2>";
}

// Function to extract LTE signal values from the input string
function extractActiveLteSignalValues($input) {
    $lteValues = [];
    if (preg_match_all('/CellSignalStrengthLte: rssi=([-\d]+) rsrp=([-\d]+) rsrq=([-\d]+) rssnr=([-\d]+) .*? level=([0-9]+)/', $input, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            // Only consider the LTE signal if the level is greater than 1 (indicating active use)
            if ((int)$match[5] > 0) {
                $lteValues[] = [
                    'rssi' => (int)$match[1],
                    'rsrp' => (int)$match[2],
                    'rsrq' => (int)$match[3],
                    'rssnr' => (int)$match[4],
                ];
            }
        }
    }
    return $lteValues;
}
// Function to determine the quality of the LTE signal
function assessLteSignalQuality($lteValues) {
    $qualityList = [];
    foreach ($lteValues as $lte) {
        $quality = [];
        
        // Assess RSRP
        if ($lte['rsrp'] >= -80) {
            $quality['rsrp'] = 'Excellent';
        } elseif ($lte['rsrp'] >= -90) {
            $quality['rsrp'] = 'Good';
        } elseif ($lte['rsrp'] >= -100) {
            $quality['rsrp'] = 'Moderate';
        } elseif ($lte['rsrp'] >= -110) {
            $quality['rsrp'] = 'Poor';
        } else {
            $quality['rsrp'] = 'BAD!';
        }

        // Assess RSRQ
        if ($lte['rsrq'] >= -3) {
            $quality['rsrq'] = 'Excellent';
        } elseif ($lte['rsrq'] >= -10) {
            $quality['rsrq'] = 'Good';
        } elseif ($lte['rsrq'] >= -15) {
            $quality['rsrq'] = 'Moderate';
        } elseif ($lte['rsrq'] >= -20) {
            $quality['rsrq'] = 'Poor';
        } else {
            $quality['rsrq'] = 'BAD!';
        }

        // Assess RSSNR
        if ($lte['rssnr'] >= 20) {
            $quality['rssnr'] = 'Excellent';
        } elseif ($lte['rssnr'] >= 10) {
            $quality['rssnr'] = 'Good';
        } elseif ($lte['rssnr'] >= 0) {
            $quality['rssnr'] = 'Moderate';
        } elseif ($lte['rssnr'] >= -5) {
            $quality['rssnr'] = 'Poor';
        } else {
            $quality['rssnr'] = 'BAD!';
        }

        // Calculate overall quality as an average of the individual qualities
        $rsrpQuality = (($lte['rsrp'] + 140) / 96) * 100;
        $rsrqQuality = (($lte['rsrq'] + 20) / 17) * 100;
        $rssnrQuality = (($lte['rssnr'] + 5) / 35) * 100;
        $overallQuality = ($rsrpQuality + $rsrqQuality + $rssnrQuality) / 3;

        $quality['overall'] = round($overallQuality, 2);

        $qualityList[] = $quality;
    }
    return $qualityList;
}
function dataStatusCheck($state) {
    switch ($state) {
        case 0:
            return "Idle";
        case 1:
            return "Connecting";
        case 2:
            return "Connected";
        case 3:
            return "Disconnecting";
        case 4:
            return "Disconnected";
    }
}
function checkSignal(){
    $input = shell_exec("dumpsys telephony.registry | grep -E 'mSignalStrength='");
    // Extract LTE signal values for active SIM slots
    $lteValues = extractActiveLteSignalValues($input);
    // Assess LTE signal quality for active SIM slots
    $qualityList = assessLteSignalQuality($lteValues);
    
    $sim_operator = shell_exec('getprop gsm.sim.operator.alpha');
    $sim_operator = trim($sim_operator);
    $sims = explode(',', $sim_operator);
    
    $data_type = shell_exec('getprop gsm.network.type');
    $data_type = trim($data_type);
    $datyp = explode(',', $data_type);
    
    $idonow = shell_exec('dumpsys telephony.registry | grep -E "mDataConnectionState="');
    preg_match_all('/mDataConnectionState=(\d+)/', $idonow, $conmatches);
    $dataConnection = [];
    foreach ($conmatches[1] as $state) {
        $dataConnection[] = (int)$state;
    }
    foreach ($sims as $slot => $sim_op) {
        if (mb_strlen(trim($sim_op)) !== 0) {
            echo "<tr><td>Provider SIM" . ($slot + 1) . "</td><td>" . $sim_op . "</td></tr>";
            echo "<tr><td>Data Status</td><td>" . dataStatusCheck($dataConnection[$slot]) . " (" . $datyp[$slot] . ")</td></tr>";
            if (strtoupper($datyp[$slot]) == 'LTE') {
                foreach ($lteValues as $index => $values) {
                    echo "<tr><td>LteRSRP</td><td>" . $values['rsrp'] . " dBm (" . $qualityList[$index]['rsrp'] . ")</td></tr>";
                    echo "<tr><td>LteRSRQ</td><td>" . $values['rsrq'] . " dB (" . $qualityList[$index]['rsrq'] . ")</td></tr>";
                    echo "<tr><td>LteRSSNR</td><td>" . $values['rssnr'] . " dB (" . $qualityList[$index]['rssnr'] . ")</td></tr>";
                    echo "<tr><td>Signal Quality</td><td>" . $qualityList[$index]['overall'] . "% / 100%</td></tr>";
                }
            } else {
                echo "<tr><td>Signal Quality</td><td>Not Available</td></tr>";
            }
        }
    }
}

// Function: memory
function memory() {
    $total_memory_kb = shell_exec('cat /proc/meminfo | grep MemTotal | awk \'{print $2}\'');
    $total_memory_gb = intval(trim($total_memory_kb)) / 1024 / 1024; // Convert to GB
    $total_memory_gb_rounded = round($total_memory_gb, 2);
    $total_memory_mb_rounded = round($total_memory_gb * 1024, 2);

    $free_memory_kb = shell_exec('cat /proc/meminfo | grep MemFree | awk \'{print $2}\'');
    $free_memory_gb = intval(trim($free_memory_kb)) / 1024 / 1024; // Convert to GB
    $free_memory_gb_rounded = round($free_memory_gb, 2);
    $free_memory_mb_rounded = round($free_memory_gb * 1024, 2);

    $buffers_memory_kb = shell_exec('cat /proc/meminfo | grep Buffers | awk \'{print $2}\'');
    $buffers_memory_gb = intval(trim($buffers_memory_kb)) / 1024 / 1024; // Convert to GB
    $buffers_memory_gb_rounded = round($buffers_memory_gb, 2);
    $buffers_memory_mb_rounded = round($buffers_memory_gb * 1024, 2);

    $cached_memory_kb = shell_exec('cat /proc/meminfo | grep ^Cached | awk \'{print $2}\'');
    $cached_memory_gb = intval(trim($cached_memory_kb)) / 1024 / 1024; // Convert to GB
    $cached_memory_gb_rounded = round($cached_memory_gb, 2);
    $cached_memory_mb_rounded = round($cached_memory_gb * 1024, 2);

    $used_memory_gb = $total_memory_gb_rounded - $free_memory_gb_rounded - $buffers_memory_gb_rounded - $cached_memory_gb_rounded;
    $used_memory_mb = $total_memory_mb_rounded - $free_memory_mb_rounded - $buffers_memory_mb_rounded - $cached_memory_mb_rounded;
    $used_memory_percent = round(($used_memory_gb / $total_memory_gb_rounded) * 100);

    echo "<tr><td>RAM Usage</td><td>";
    if ($used_memory_gb >= 1) {
        echo "$used_memory_gb GB / ";
    } else {
        echo "$used_memory_mb MB / ";
    }
    if ($total_memory_gb_rounded >= 1) {
        echo "$total_memory_gb_rounded GB ($used_memory_percent%)";
    } else {
        echo "$total_memory_mb_rounded MB ($used_memory_percent%)";
    }
    echo "</td></tr>";
    
    // Fetching temperature information from /sys/class/thermal/thermal_zone0/temp
    $temperature = shell_exec('cat /sys/class/thermal/thermal_zone0/temp');
    $temperature = round(intval(trim($temperature)) / 1000, 1); // Convert to Celsius
    echo "<tr><td>Temperature</td><td>$temperature °C</td></tr>";
}
// Function: systemInfo
function systemInfo() {
    $android_version = shell_exec('getprop ro.build.version.release');
    $android_version = trim($android_version);
    $os = "Android $android_version";
    $distro = ""; // Customize for your environment if needed
    $hostname = php_uname('n');
    $kernel_info = php_uname('r');
    $uptime = shell_exec('cat /proc/uptime');
    $uptime = explode(' ', $uptime);
    $uptime_seconds = intval(trim($uptime[0]));
    $uptime_minutes = intval($uptime_seconds / 60 % 60);
    $uptime_hours = intval($uptime_seconds / 60 / 60 % 24);
    $uptime_days = intval($uptime_seconds / 60 / 60 / 24);
    
    date_default_timezone_set('Asia/Jakarta');
    $current_date = date('Y-m-d H:i:s');

    $device_model = shell_exec('getprop ro.product.model');
    $device_model = trim($device_model);

    echo "<tr><td>Device Model</td><td>$device_model</td></tr>";
    echo "<tr><td>OS</td><td>$os $distro</td></tr>";
    echo "<tr><td>Hostname</td><td>$hostname</td></tr>";
    echo "<tr><td>Kernel</td><td>$kernel_info</td></tr>";
    echo "<tr><td>Uptime</td><td>$uptime_days days, $uptime_hours hours, $uptime_minutes minutes</td></tr>";
    echo "<tr><td>Current date</td><td>$current_date</td></tr>";
    memory();
}

// Function: battery
function battery() {
    $ac_powered = shell_exec('dumpsys battery | grep AC | cut -d \':\' -f2');
    $battery_level = shell_exec('dumpsys battery | grep level | cut -d \':\' -f2');
    $battery_status = shell_exec('dumpsys battery | grep status | cut -d \':\' -f2');
    $battery_current = shell_exec('cat /sys/class/power_supply/battery/current_now');
    if (strlen(trim($battery_current)) >= 5) {
        $battery_current = round(shell_exec('cat /sys/class/power_supply/battery/current_now') / 1000);
    }
    $battery_voltage = round(shell_exec('cat /sys/class/power_supply/battery/voltage_now') / 1000000, 2);
    $battery_temperature = shell_exec('dumpsys battery | grep temperature | cut -d \':\' -f2') / 10;
    // $ac_powered = trim($ac_powered);
    
    echo "<tr><td>Charging</td><td>$ac_powered</td></tr>";
    echo "<tr><td>Status</td><td>$battery_status</td></tr>";
    echo "<tr><td>Level</td><td>$battery_level%</td></tr>";
    echo "<tr><td>Current</td><td>$battery_current mA</td></tr>";
    echo "<tr><td>Voltage</td><td>$battery_voltage V</td></tr>";
    echo "<tr><td>Temperature</td><td>$battery_temperature °C</td></tr>";
}

// Function: load_average
function load_average() {
    $cpu_nb = shell_exec('cat /proc/cpuinfo | grep "^processor" | wc -l');
    $cpu_nb = intval(trim($cpu_nb));

    $loadavg = shell_exec('cat /proc/loadavg');
    $loadavg_arr = explode(' ', $loadavg);

    $load_1 = floatval($loadavg_arr[0]);
    $load_2 = floatval($loadavg_arr[1]);
    $load_3 = floatval($loadavg_arr[2]);

    $load_1_percent = round(($load_1 / $cpu_nb) * 100);
    $load_2_percent = round(($load_2 / $cpu_nb) * 100);
    $load_3_percent = round(($load_3 / $cpu_nb) * 100);

    echo "<tr><td>Load Average (1 min)</td><td>$load_1_percent% ($load_1)</td></tr>";
    echo "<tr><td>Load Average (5 min)</td><td>$load_2_percent% ($load_2)</td></tr>";
    echo "<tr><td>Load Average (15 min)</td><td>$load_3_percent% ($load_3)</td></tr>";
}

// Function: network
function network() {
    // Get IP addresses
    $ip_addresses = shell_exec('ip address show | grep "inet " | grep -v "127.0.0.1" | awk \'{print $2}\' | cut -f1 -d"/"');
    $ip_addresses = explode("\n", trim($ip_addresses));

    // Get Gateway
    $gateway = shell_exec('ip route | awk \'/default/ {print $3}\'');
    $gateway = trim($gateway);
    
    // Output IP addresses
    echo "<tr><td>Network IP Address <br> IP Gateway</td><td>";
    foreach ($ip_addresses as $ip_address) {
        echo "$ip_address <br>";
    }
    echo "</td></tr>";
    
    $checkClient = shell_exec('dumpsys wifi | grep "Client"');
    // Regex untuk mengekstrak jumlah perangkat yang terhubung
    preg_match('/\.size\(\): (\d+)/', $checkClient, $matches);
    
    // Mengecek apakah ada hasil yang ditemukan
    if (isset($matches[1])) {
        $connectedClients = $matches[1];
        echo "<tr><td>Device Connected</td><td>$connectedClients Device's</td></tr>";
    }
}

// Function: cpu
function cpu() {
    $cpu_info = shell_exec('cat /proc/cpuinfo | grep -i "^model name" | awk -F": " \'{print $2}\' | head -1 | sed \'s/ \+/ /g\'');
    $cpu_freq = shell_exec('cat /proc/cpuinfo | grep -i "^cpu MHz" | awk -F": " \'{print $2}\' | head -1');
    $cpu_freq = intval(trim($cpu_freq));

    if (empty($cpu_freq)) {
        $cpu_freq = shell_exec('cat /sys/devices/system/cpu/cpu0/cpufreq/cpuinfo_max_freq');
        $cpu_freq = intval(trim($cpu_freq)) / 1000;
    }

    $cpu_cache = shell_exec('cat /proc/cpuinfo | grep -i "^cache size" | awk -F": " \'{print $2}\' | head -1');
    $cpu_bogomips = shell_exec('cat /proc/cpuinfo | grep -i "^bogomips" | awk -F": " \'{print $2}\' | head -1');

    $cpu_use = shell_exec('vmstat 1 2 | tail -1 | awk \'{printf "usr: %s%% sys: %s", $13, $14}\'');
    $cpu_used = trim($cpu_use);
    
    echo "<tr><td>CPU Model</td><td>$cpu_info</td></tr>";
    echo "<tr><td>CPU Frequency</td><td>$cpu_freq MHz</td></tr>";
    echo "<tr><td>CPU Bogomips</td><td>$cpu_bogomips</td></tr>";
    echo "<tr><td>CPU Usage</td><td>$cpu_used%</td></tr>";
}

// Function: swap
function swap() {
    $swap_total_kb = shell_exec('cat /proc/meminfo | grep SwapTotal | awk \'{print $2}\'');
    $swap_total_gb = intval(trim($swap_total_kb)) / 1024 / 1024; // Convert to GB
    $swap_total_gb_rounded = round($swap_total_gb);

    $swap_free_kb = shell_exec('cat /proc/meminfo | grep SwapFree | awk \'{print $2}\'');
    $swap_free_gb = intval(trim($swap_free_kb)) / 1024 / 1024; // Convert to GB
    $swap_free_gb_rounded = round($swap_free_gb);

    if ($swap_total_gb_rounded > 0) {
        $swap_used_gb = $swap_total_gb_rounded - $swap_free_gb_rounded;
        $swap_used_percent = round(($swap_used_gb / $swap_total_gb_rounded) * 100);

        echo "<tr><td>Total Swap</td><td>$swap_total_gb_rounded GB</td></tr>";
        echo "<tr><td>Used Swap</td><td>$swap_used_gb GB ($swap_used_percent%)</td></tr>";
    } else {
        echo "<tr><td>Total Swap</td><td>Not Available</td></tr>";
        echo "<tr><td>Used Swap</td><td>Not Available</td></tr>";
    }
}

// Function: disk_usage
// Function: disk_usage
function disk_usage() {
    $disk_usage = shell_exec('df -h | grep "/dev"');

    $disk_usage_lines = explode("\n", trim($disk_usage));

    foreach ($disk_usage_lines as $line) {
        $line_parts = preg_split('/\s+/', $line);
        $filesystem = $line_parts[0];
        $size = $line_parts[1];
        $used = $line_parts[2];
        $available = $line_parts[3];
        $used_percent = $line_parts[4];

        echo "<tr><td>Disk ($filesystem)</td><td>$used / $size ($used_percent used)</td></tr>";
    }
}

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Android Info</title>
    <style>
        body {
            background-color: #333333; /* Dark background */
            color: #ffffff; /* Light text */
            font-family: Arial, sans-serif; /* Example font */
            text-align: center; /* Center align text */
        }
        h2 {
            color: #ffffff; /* White text */
        }
        table {
            width: 90%; /* Adjust table width as needed */
            margin: 0 auto; /* Center align table */
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ffffff; /* White borders */
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #555555; /* Dark grey header */
        }
        tr:nth-child(even) {
            background-color: #666666; /* Darker grey */
        }
    </style>
</head>
<body>

<h1></h1>

<table id="info-table">
    <?php
    // Call PHP functions to generate table rows
    makeTitle("System-Info");
    systemInfo();
    ?>
</table>

<table id="info-table">
    <?php
    // Call PHP functions to generate table rows
    makeTitle("Battery-Info");
    battery();
    ?>
</table>

<table id="info-table">
    <?php
    // Call PHP functions to generate table rows
    makeTitle("Network-Info");
    network();
    checkSignal();
    ?>
</table>

<table id="info-table">
    <?php
    // Call PHP functions to generate table rows
    makeTitle("Cpu-Info");
    cpu();
    load_average();
    ?>
</table>

<table id="info-table">
    <?php
    // Call PHP functions to generate table rows
    makeTitle("Disk-Info");
    swap();
    disk_usage();
    ?>
</table>

</body>
</html>
