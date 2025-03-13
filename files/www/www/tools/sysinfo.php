<?php
// Function: makeTitle
function makeTitle($title) {
    echo "<h2>$title</h2>";
}

// Function to avoid unwanted/wrong value from telephony.registry
function filterArray($array) {
    foreach ($array as $key => $values) {
        foreach ($values as $innerKey => $value) {
            if (abs($value) > 999) {
                unset($array[$key]);
                break;
            }
        }
    }
    return array_values($array);
}
// Function to extract LTE signal values from the input string
function extractActiveLteSignalValues($input) {
    $lteValues = [];
    if (preg_match_all('/CellSignalStrengthLte: rssi=([-\d]+) rsrp=([-\d]+) rsrq=([-\d]+) rssnr=([-\d]+) .*? level=([1-9]+)/', $input, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            // Only consider the LTE signal if the level is greater than 1 (indicating active use)
            // if ((int)$match[5] > 0) {
            $lteValues[] = [
                'rssi' => (int)$match[1],
                'rsrp' => (int)$match[2],
                'rsrq' => (int)$match[3],
                'rssnr' => (int)$match[4],
            ];
            // }
        }
    }
    return filterArray($lteValues);
}
// Function to determine the quality of the LTE signal
function assessLteSignalQuality($lteValues) {
    $qualityList = [];
    foreach ($lteValues as $lte) {
        $quality = [];

        // Assess RSSI
        if ($lte['rssi'] >= -75) {
            $quality['rssi'] = 'Excellent';
        } elseif ($lte['rssi'] >= -95) {
            $quality['rssi'] = 'Good';
        } elseif ($lte['rssi'] >= -110) {
            $quality['rssi'] = 'Fair';
        } else {
            $quality['rssi'] = 'Bad';
        }

        
        // Assess RSRP
        if ($lte['rsrp'] >= -75) {
            $quality['rsrp'] = 'Excellent';
        } elseif ($lte['rsrp'] >= -95) {
            $quality['rsrp'] = 'Good';
        } elseif ($lte['rsrp'] >= -110) {
            $quality['rsrp'] = 'Fair';
        } else {
            $quality['rsrp'] = 'Bad';
        }

        // Assess RSRQ
        if ($lte['rsrq'] >= -8) {
            $quality['rsrq'] = 'Excellent';
        } elseif ($lte['rsrq'] >= -12) {
            $quality['rsrq'] = 'Good';
        } elseif ($lte['rsrq'] >= -16) {
            $quality['rsrq'] = 'Fair';
        } else {
            $quality['rsrq'] = 'Bad';
        }

        // Assess RSSNR
        if ($lte['rssnr'] >= 20) {
            $quality['rssnr'] = 'Excellent';
        } elseif ($lte['rssnr'] >= 13) {
            $quality['rssnr'] = 'Good';
        } elseif ($lte['rssnr'] >= 0) {
            $quality['rssnr'] = 'Fair';
        } else {
            $quality['rssnr'] = 'Bad';
        }

        // Calculate overall quality as an average of the individual qualities
        $rsrpQuality = (($lte['rssi'] + 140) / 96) * 100;
        $rsrpQuality = (($lte['rsrp'] + 140) / 96) * 100;
        $rsrqQuality = (($lte['rsrq'] + 20) / 17) * 100;
        $rssnrQuality = (($lte['rssnr'] + 10) / 40) * 100;
        $overallQuality = ($rsrpQuality + $rsrqQuality + $rssnrQuality) / 3;

        $quality['overall'] = round(($overallQuality / 20), 2);

        $qualityList[] = $quality;
    }
    return $qualityList;
}

// Fungsi untuk menampilkan rating dengan emoji bulan seperti sistem bintang
function displayMoonRating($score) {
    // Emoji untuk penuh, setengah, dan kosong
    $fMoon = '🌕';   // Emoji bulan purnama (penuh)
    $gMoon = '🌖'; // Emoji bulan gibbous (lebih dari setengah)
    $hMoon = '🌗';    // Emoji bulan setengah
    $qMoon = '🌘'; // Emoji bulan sabit kecil (kurang dari setengah)
    $eMoon = '🌑';   // Emoji bulan baru (kosong)

    // Membatasi skor dalam rentang 1-5
    $score = max(1, min(5, $score));
    
    // Menentukan jumlah emoji penuh, setengah, dan kosong
    $fullMoons = floor($score); // Bulan purnama penuh
    $fraction = $score - $fullMoons; // Pecahan dari skor
    
    $gibbousMoons = 0;
    $halfMoons = 0;
    $quarterMoons = 0;
    
    // Menentukan emoji bulan setengah berdasarkan pecahan
    if ($fraction >= 0.9) {
        $fullMoons++;
    } elseif ($fraction >= 0.7) {
        $gibbousMoons = 1;
    } elseif ($fraction >= 0.4) {
        $halfMoons = 1;
    } elseif ($fraction >= 0.1) {
        $quarterMoons = 1;
    }
    
    $emptyMoons = 5 - $fullMoons - $gibbousMoons - $halfMoons - $quarterMoons; // Bulan kosong

    // Membuat string rating dengan urutan yang benar
    $rating = str_repeat($fMoon, $fullMoons) 
            . str_repeat($gMoon, $gibbousMoons) 
            . str_repeat($hMoon, $halfMoons) 
            . str_repeat($qMoon, $quarterMoons)
            . str_repeat($eMoon, $emptyMoons);
    
    return $rating;
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
    $telephony = shell_exec("dumpsys telephony.registry | grep -E 'mSignalStrength='");
    // Extract LTE signal values for active SIM slots
    $lteValues = extractActiveLteSignalValues($telephony);
    // Assess LTE signal quality for active SIM slots
    $qualityList = assessLteSignalQuality($lteValues);
    
    $sim_operator = shell_exec('getprop gsm.sim.operator.alpha');
    $sims = explode(',', trim($sim_operator));

    $bts_pci = shell_exec('dumpsys telephony.registry | grep -i "mServiceState" | grep -Eo "Pci=[0-9]+" | head -n1 | cut -d= -f2');
    $pci = explode(',', trim($bts_pci));
    
    $data_type = shell_exec('getprop gsm.network.type');
    $datyp = explode(',', trim($data_type));
    
    $idonow = shell_exec('dumpsys telephony.registry | grep -E "mDataConnectionState="');
    preg_match_all('/mDataConnectionState=(-?\d+)/', $idonow, $conmatches);
    $dataConnection = [];
    foreach ($conmatches[1] as $state) {
        if ((int)$state < 0) {
            $dataConnection[] = 0;
        } else {
            $dataConnection[] = (int)$state;
        }
    }
    
    $i = 0;
    foreach ($sims as $slot => $sim_op) {
        if (mb_strlen(trim($sim_op)) !== 0) {
echo "<div class='container'>";
echo '  <div class="row">';
// Card for Provider SIM
echo "<div class='card-00'>";
echo "    <i class='fas fa-sim-card'></i>";
echo "    <h3>Provider SIM " . ($slot + 1) . "</h3>";
echo "    <p>" . strtoupper($sim_op) . "</p>";
echo "</div>";
// Card for Provider PCI
echo "<div class='card-00'>";
echo "    <i class='fas fa-sim-card'></i>";
echo "    <h3>BTS PCI " . ($slot + 1) . "</h3>";
echo "    <p>" . strtoupper($bts_pci) . "</p>";
echo "</div>";

// Card for Network Type
echo "<div class='card-00'>";
echo "    <i class='fas fa-network-wired'></i>";
echo "    <h3>Network Type</h3>";
echo "    <p>" . $datyp[$slot] . " (" . dataStatusCheck($dataConnection[$slot]) . ")</p>";
echo "</div>";
echo '  </div>';
// Conditional Cards for LTE data
    echo '  <div class="row">';
if (strtoupper($datyp[$slot]) == 'LTE') {
    echo "<div class='card-00'>";
    echo "    <i class='fas fa-signal'></i>";
    echo "    <h3>LteRSSI</h3>";
    echo "    <p>" . $lteValues[$i]['rssi'] . " dBm (" . $qualityList[$i]['rssi'] . ")</p>";
    echo "</div>";
    
    echo "<div class='card-00'>";
    echo "    <i class='fas fa-signal'></i>";
    echo "    <h3>LteRSRP</h3>";
    echo "    <p>" . $lteValues[$i]['rsrp'] . " dBm (" . $qualityList[$i]['rsrp'] . ")</p>";
    echo "</div>";

    echo "<div class='card-00'>";
    echo "    <i class='fas fa-signal'></i>";
    echo "    <h3>LteRSRQ</h3>";
    echo "    <p>" . $lteValues[$i]['rsrq'] . " dB (" . $qualityList[$i]['rsrq'] . ")</p>";
    echo "</div>";

    echo "<div class='card-00'>";
    echo "    <i class='fas fa-signal'></i>";
    echo "    <h3>LteSINR</h3>";
    echo "    <p>" . $lteValues[$i]['rssnr'] . " dB (" . $qualityList[$i]['rssnr'] . ")</p>";
    echo "</div>";

    echo "<div class='card-00'>";
    echo "    <i class='fas fa-star'></i>"; // Use an appropriate icon for signal quality
    echo "    <h3>Signal Quality</h3>";
    echo "    <p>" . displayMoonRating($qualityList[$i]['overall']) . "<br>(" . $qualityList[$i]['overall'] . ")</p>";
    echo "</div>";

    $i++;
} else {
    echo "<div class='card-00'>";
    echo "    <i class='fas fa-exclamation-triangle'></i>"; // Use an appropriate icon for "Not Available"
    echo "    <h3>Signal Quality</h3>";
    echo "    <p>Not Available</p>";
    echo "</div>";
    echo "</div>";
}

echo "</div>";

        }
    }
}

// Functions for System-info
function memory() {
    $total_memory_kb = shell_exec('cat /proc/meminfo | grep MemTotal | awk \'{print $2}\'');
    $total_memory_gb = intval(trim($total_memory_kb)) / 1024 / 1024; // Convert to GB
    $total_memory_gb_rounded = round($total_memory_gb, 1);
    $total_memory_mb_rounded = round($total_memory_gb * 1024, 1);

    $free_memory_kb = shell_exec('cat /proc/meminfo | grep MemFree | awk \'{print $2}\'');
    $free_memory_gb = intval(trim($free_memory_kb)) / 1024 / 1024; // Convert to GB
    $free_memory_gb_rounded = round($free_memory_gb, 1);
    $free_memory_mb_rounded = round($free_memory_gb * 1024, 1);

    $buffers_memory_kb = shell_exec('cat /proc/meminfo | grep Buffers | awk \'{print $2}\'');
    $buffers_memory_gb = intval(trim($buffers_memory_kb)) / 1024 / 1024; // Convert to GB
    $buffers_memory_gb_rounded = round($buffers_memory_gb, 1);
    $buffers_memory_mb_rounded = round($buffers_memory_gb * 1024, 1);

    $cached_memory_kb = shell_exec('cat /proc/meminfo | grep ^Cached | awk \'{print $2}\'');
    $cached_memory_gb = intval(trim($cached_memory_kb)) / 1024 / 1024; // Convert to GB
    $cached_memory_gb_rounded = round($cached_memory_gb, 1);
    $cached_memory_mb_rounded = round($cached_memory_gb * 1024, 1);
    
    $used_memory_gb = $total_memory_gb_rounded - $free_memory_gb_rounded - $buffers_memory_gb_rounded - $cached_memory_gb_rounded;
    $used_memory_mb = $total_memory_mb_rounded - $free_memory_mb_rounded - $buffers_memory_mb_rounded - $cached_memory_mb_rounded;
    $used_memory_percent = round(($used_memory_gb / $total_memory_gb_rounded) * 100);
    
    $available_memory_gb = $free_memory_gb_rounded + $buffers_memory_gb_rounded + $cached_memory_gb_rounded;
    $available_memory_mb = $free_memory_mb_rounded + $buffers_memory_mb_rounded + $cached_memory_mb_rounded;

echo "<div class='container'>";
echo '  <div class="row">';
// Card for RAM Usage
echo "<div class='card-00'>";
echo "    <i class='fas fa-memory'></i>"; // Icon for RAM
echo "    <h3>RAM Usage</h3>";
echo "    <p>";
if ($used_memory_gb >= 1) {
    echo "$used_memory_gb GB / ";
} else {
    echo "$used_memory_mb MB / ";
}
if ($total_memory_gb_rounded >= 1) {
    echo "$total_memory_gb_rounded GB ($used_memory_percent%) ";
} else {
    echo "$total_memory_mb_rounded MB ($used_memory_percent%) ";
}
if ($available_memory_gb >= 1) {
    echo "<br>Free: $available_memory_gb GB";
} else {
    echo "<br>Free: $available_memory_mb MB";
}
echo "    </p>";
echo "</div>";
echo "</div>";
echo "</div>";

    
    // Fetching temperature information from /sys/class/thermal/thermal_zone0/temp
    // $temperature = shell_exec('cat /sys/class/thermal/thermal_zone0/temp');
    // $temperature = round(intval(trim($temperature)) / 1000, 1); // Convert to Celsius
    // echo "<tr><td>Temperature</td><td>$temperature °C</td></tr>";
}
function getCpuUsage() {
    // Read the /proc/stat file
    $stats1 = file('/proc/stat');
    $cpuLine1 = $stats1[0]; // The first line contains CPU stats
    // Extract numeric values from the line
    $values1 = array_map('intval', preg_split('/\s+/', trim($cpuLine1)));
    list($cpu, $user1, $nice1, $system1, $idle1) = array_slice($values1, 0, 5);

    // Sleep for 0.5 second to measure CPU usage
    usleep(500000);

    // Read the /proc/stat file again
    $stats2 = file('/proc/stat');
    $cpuLine2 = $stats2[0]; // The first line contains CPU stats
    // Extract numeric values from the line
    $values2 = array_map('intval', preg_split('/\s+/', trim($cpuLine2)));
    list($cpu, $user2, $nice2, $system2, $idle2) = array_slice($values2, 0, 5);

    // Calculate the differences
    $total1 = $user1 + $nice1 + $system1 + $idle1;
    $total2 = $user2 + $nice2 + $system2 + $idle2;
    if ($total2 === $total1) {
        // Avoid division by zero
        return 0;
    }
    $idleDiff = $idle2 - $idle1;
    $totalDiff = $total2 - $total1;
    // Calculate CPU usage percentage
    $cpuUsage = ($totalDiff - $idleDiff) / $totalDiff * 100;

    return $cpuUsage;
}
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
    
    $cpu_used = round(getCpuUsage(), 2);
    
echo <<<HTML
<div class="container">
  <div class="row">
    <div class="card-01">
        <i class="fas fa-mobile-alt"></i>
        <h3>Device Model</h3>
        <p>{$device_model}</p>
    </div>
    
    <div class="card-01">
        <i class="fab fa-android"></i>
        <h3>OS</h3>
        <p>{$os} {$distro}</p>
    </div>
    <div class="card-01">
        <i class="fas fa-network-wired"></i>
        <h3>Hostname</h3>
        <p>{$hostname}</p>
    </div>
    </div>
    <div class="row">

    <div class="card-00">
        <i class="fas fa-clock"></i>
        <h3>Uptime</h3>
        <p>{$uptime_days} days, {$uptime_hours} hours, {$uptime_minutes} minutes</p>
    </div>
    <div class="card-00">
        <i class="fas fa-calendar-alt"></i>
        <h3>Current date</h3>
        <p>{$current_date}</p>
    </div>
    <div class="card-00">
        <i class="fas fa-microchip"></i>
        <h3>CPU Usage</h3>
        <p>{$cpu_used}% / 100%</p>
    </div>
    </div>
    <div class="row">
    <div class="card-000">
        <i class="fas fa-microchip"></i>
        <h3>Kernel</h3>
        <p>{$kernel_info}</p>
    </div>
</div>
HTML;


}

// Function: battery
function batStatusCheck($state) {
    switch ($state) {
        case 1:
            return "Unknown";
        case 2:
            return "Charging";
        case 3:
            return "Discharging";
        case 4:
            return "Not charging";
        case 5:
            return "Full";
    }
}
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
    
echo '<div class="container">';
echo '  <div class="row">';
echo '    <div class="card-01">';
echo '        <i class="fas fa-plug"></i>';
echo '        <h3>AC Powered</h3>';
echo '        <p>' . strtoupper(htmlspecialchars($ac_powered)) . '</p>';
echo '    </div>';
echo '    <div class="card-01">';
echo '        <i class="fas fa-battery-full"></i>';
echo '        <h3>Status</h3>';
echo '        <p>' . htmlspecialchars(batStatusCheck($battery_status)) . '</p>';
echo '    </div>';
echo '    <div class="card-01">';
echo '        <i class="fas fa-battery-three-quarters"></i>';
echo '        <h3>Level</h3>';
echo '        <p>' . htmlspecialchars($battery_level) . '%</p>';
echo '    </div>';
echo '  </div>';
echo '  <div class="row">';
echo '    <div class="card-01">';
echo '        <i class="fas fa-bolt"></i>';
echo '        <h3>Current</h3>';
echo '        <p>' . htmlspecialchars($battery_current) . ' mA</p>';
echo '    </div>';
echo '    <div class="card-01">';
echo '        <i class="fas fa-bolt"></i>'; // Use an appropriate icon or custom icon
echo '        <h3>Voltage</h3>';
echo '        <p>' . htmlspecialchars($battery_voltage) . ' V</p>';
echo '    </div>';
echo '    <div class="card-01">';
echo '        <i class="fas fa-thermometer-half"></i>';
echo '        <h3>Temperature</h3>';
echo '        <p>' . htmlspecialchars($battery_temperature) . ' °C</p>';
echo '    </div>';
echo '  </div>';
echo '</div>';

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

echo <<<HTML
<div class="container">
    <div class="row">
    <div class="card-00">
        <i class="fas fa-tachometer-alt"></i>
        <h3>Load Average (1 min)</h3>
        <p>{$load_1_percent}% ({$load_1})</p>
    </div>
    <div class="card-00">
        <i class="fas fa-tachometer-alt"></i>
        <h3>Load Average (5 min)</h3>
        <p>{$load_2_percent}% ({$load_2})</p>
    </div>
    <div class="card-00">
        <i class="fas fa-tachometer-alt"></i>
        <h3>Load Average (15 min)</h3>
        <p>{$load_3_percent}% ({$load_3})</p>
    </div>
    </div>
</div>
HTML;

}

// Function: network
function network() {
    // Get IP addresses
    $ip_addresses = shell_exec('ip address show | grep "inet " | grep -v "127.0.0.1" | awk \'{print $2}\' | cut -f1 -d"/"');
    $ip_addresses = explode("\n", trim($ip_addresses));

    // Get Gateway
    $gateway = shell_exec('ip route | awk \'/default/ {print $3}\'');
    $gateway = trim($gateway);
    
    // Default DNS IP addresses
    $dumpDns = shell_exec('dumpsys connectivity | grep "DnsAddresses:" | sed -n \'s/.*DnsAddresses: \[ \([^,]*\),.*/\1/p\' | tr -d \'/\'');
    // Regular expression to match both IPv4 and IPv6 addresses    
    preg_match_all('/([0-9]{1,3}\.){3}[0-9]{1,3}|([a-fA-F0-9:]+:+)+[a-fA-F0-9]+/', $dumpDns, $dnsIP);
    $dnsIPs = $dnsIP[0];
    
    // Output IP addresses
    echo "<div class='container'>";
    echo '  <div class="row">';
    echo "  <div class='card-01'>";
    echo "    <i class='fas fa-network-wired'></i>"; // Icon for IP address and gateway
    echo "    <h3>IP Gateway</h3>";
    echo "    <p>";
    if (empty($ip_addresses) && empty($gateway)) {
        echo "Unavailable";
    } else {
        foreach ($ip_addresses as $ip_address) {
            echo "$ip_address <br>";
        }
        if (!empty($gateway)) {
            echo "Gateway: $gateway";
        }
    }
    echo "    </p>";
    echo "</div>";

    echo "<div class='card-01'>";
    echo "    <i class='fas fa-server'></i>"; // Icon for DNS Provider
    echo "    <h3>DNS Provider IP Address</h3>";
    echo "    <p>";
    if (empty($dnsIPs)) {
        echo "Unavailable";
    } else {
        foreach ($dnsIPs as $dns_address) {
            echo "$dns_address <br>";
        }
    }
    echo "    </p>";
    echo "</div>";

    $checkClient = shell_exec('dumpsys wifi | grep "Client"');
    // Regex to extract the number of connected devices
    preg_match('/\.size\(\): (\d+)/', $checkClient, $matches);
    
    echo "<div class='card-01'>";
    echo "    <i class='fas fa-users'></i>"; // Icon for connected devices
    echo "    <h3>Device Connected</h3>";
    echo "    <p>";
    if (isset($matches[1])) {
        $connectedClients = $matches[1];
        echo "$connectedClients Device's";
    } else {
        echo "No Data";
    }
    echo "    </p>";
    echo "</div>";
    echo '    </div>';
    echo "</div>";
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
    
echo '<div class="container">';
echo '  <div class="row">';
echo '<div class="card-01">';
echo '<i class="fas fa-microchip"></i>'; // Add Font Awesome icon for CPU
echo '<h3>CPU Model</h3>';
echo '<p>' . htmlspecialchars($cpu_info) . '</p>';
echo '</div>';

echo '<div class="card-01">';
echo '<i class="fas fa-tachometer-alt"></i>'; // Add Font Awesome icon for Frequency
echo '<h3>CPU Frequency</h3>';
echo '<p>' . htmlspecialchars($cpu_freq) . ' MHz</p>';
echo '</div>';

echo '<div class="card-01">';
echo '<i class="fas fa-gear"></i>'; // Add Font Awesome icon for Bogomips
echo '<h3>CPU Bogomips</h3>';
echo '<p>' . htmlspecialchars($cpu_bogomips) . '</p>';
echo '</div>';
echo '</div>';
echo '</div>';

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

echo "<div class='container'>";
echo '  <div class="row">';
// Card for Total Swap
echo "<div class='card-01'>";
echo "    <i class='fas fa-memory'></i>"; // Icon for memory or swap
echo "    <h3>Total Swap</h3>";
echo "    <p>";
if (isset($swap_total_gb_rounded)) {
    echo "$swap_total_gb_rounded GB";
} else {
    echo "Not Available";
}
echo "    </p>";
echo "</div>";

// Card for Used Swap
echo "<div class='card-01'>";
echo "    <i class='fas fa-tachometer-alt'></i>"; // Icon for usage or swap
echo "    <h3>Used Swap</h3>";
echo "    <p>";
if (isset($swap_used_gb)) {
    echo "$swap_used_gb GB ($swap_used_percent%)";
} else {
    echo "Not Available";
}
echo "    </p>";
echo "</div>";
echo "</div>";
echo "</div>";

    }
}

function disk_usage() {
    $disk_usage = shell_exec('df -h | grep "/dev/root"');
    $disk_usage_lines = explode("\n", trim($disk_usage));

    echo "<div class='container'>"; // Start card container
    echo '  <div class="row">';
    foreach ($disk_usage_lines as $line) {
        $line_parts = preg_split('/\s+/', $line);
        $filesystem = $line_parts[0];
        $size = $line_parts[1];
        $used = $line_parts[2];
        $available = $line_parts[3];
        $used_percent = $line_parts[4];

        // Card for Disk Usage
        echo "<div class='card-000'>";
        echo "    <i class='fas fa-hdd'></i>"; // Icon for disk
        echo "    <div class='card-content'>";
        echo "        <h3>Disk ($filesystem)</h3>";
        echo "        <p>$used / $size ($used_percent% used)</p>";
        echo "    </div>";
        echo "</div>";
    }
    echo "</div>";
    echo "</div>"; // End card container
}


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Info</title>
    <!--<link rel="stylesheet" href="style.css"-->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
      * {
        box-sizing: border-box;
      }
  
      body {
        font-family: "Open Sans", sans-serif;
        background: #080808;
        color: white;
        text-align: center;
        margin: 0;
        padding: 0;
      }
      .logo {
        text-align: center;
        margin: 10px auto;
      }
      .logo img {
        width: 200px; /* Adjust width to make the logo smaller */
        height: auto;
      }
      #main {
        position: relative;
        list-style: none;
        background: #080808;
        font-weight: 400;
        font-size: 0;
        text-transform: uppercase;
        display: inline-block;
        padding: 0;
        margin: 1px auto;
        height: 55px; /* Adjust height to match tab height */
      }
  
      #main li {
        font-size: 0.8rem;
        display: inline-block;
        position: relative;
        padding: 15px 20px;
        cursor: pointer;
        z-index: 5;
        min-width: 120px;
        height: 100%; /* Make sure li items take full height */
        line-height: 32px; /* Vertically center text in the li items */
        margin: 0;
      }
  
      .drop {
        overflow: hidden;
        list-style: none;
        position: absolute;
        padding: 0;
        width: 100%;
        left: 0;
        top: 60px; /* Position below the nav bar */
      }
  
      .drop div {
        -webkit-transform: translate(0, -100%);
        -moz-transform: translate(0, -100%);
        -ms-transform: translate(0, -100%);
        transform: translate(0, -100%);
        -webkit-transition: all 0.5s 0.1s;
        -moz-transition: all 0.5s 0.1s;
        -ms-transition: all 0.5s 0.1s;
        transition: all 0.5s 0.1s;
        position: relative;
      }
  
      .drop li {
        display: block;
        padding: 0;
        width: 100%;
        background: #374954 !important;
      }
  
      #marker {
        height: 6px;
        background: #3E8760 !important;
        position: absolute;
        bottom: 0;
        width: 120px;
        z-index: 2;
        -webkit-transition: all 0.35s;
        -moz-transition: all 0.35s;
        -ms-transition: all 0.35s;
        transition: all 0.35s;
      }
  
      #main li:nth-child(1):hover ul div {
        -webkit-transform: translate(0, 0);
        -moz-transform: translate(0, 0);
        -ms-transform: translate(0, 0);
        transform: translate(0, 0);
      }
  
      #main li:nth-child(1):hover ~ #marker {
        -webkit-transform: translate(0px, 0);
        -moz-transform: translate(0px, 0);
        -ms-transform: translate(0px, 0);
        transform: translate(0px, 0);
      }
  
      #main li:nth-child(2):hover ul div {
        -webkit-transform: translate(0, 0);
        -moz-transform: translate(0, 0);
        -ms-transform: translate(0, 0);
        transform: translate(0, 0);
      }
  
      #main li:nth-child(2):hover ~ #marker {
        -webkit-transform: translate(120px, 0);
        -moz-transform: translate(120px, 0);
        -ms-transform: translate(120px, 0);
        transform: translate(120px, 0);
      }
  
      #main li:nth-child(3):hover ul div {
        -webkit-transform: translate(0, 0);
        -moz-transform: translate(0, 0);
        -ms-transform: translate(0, 0);
        transform: translate(0, 0);
      }
  
      #main li:nth-child(3):hover ~ #marker {
        -webkit-transform: translate(240px, 0);
        -moz-transform: translate(240px, 0);
        -ms-transform: translate(240px, 0);
        transform: translate(240px, 0);
      }
  
      #main li:nth-child(4):hover ul div {
        -webkit-transform: translate(0, 0);
        -moz-transform: translate(0, 0);
        -ms-transform: translate(0, 0);
        transform: translate(0, 0);
      }
  
      #main li:nth-child(4):hover ~ #marker {
        -webkit-transform: translate(360px, 0);
        -moz-transform: translate(360px, 0);
        -ms-transform: translate(360px, 0);
        transform: translate(360px, 0);
      }
  
      .tab-content {
        display: none;
        width: 100%;
        
      }
  
      .tab-content iframe {
        width: 100%;
        height: calc(100vh - 60px); /* Adjust based on header/footer height */
        border: none;
      }
      

      /* card */
      body{
  margin: 0;
  padding: 0;
  height: 100vh;
}

.container{
  margin: 20px;
}

.row{
  width: 100%;
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
}

.card-000{
  background: #0a0e0d;
  text-align: center;
  position: relative;
  padding: 20px;
  align-items: center;
  flex: 1;
  max-width: 500px;
  height: 150px;
  margin: 10px;
  border-radius: 5px;
  box-shadow: 0 0 5px rgba(94, 89, 89, 0.2);
}

.card-00{
  background: #0a0e0d;
  text-align: center;
  position: relative;
  padding: 20px;
  align-items: center;
  flex: 1;
  max-width: 300px;
  height: 150px;
  margin: 10px;
  border-radius: 5px;
  box-shadow: 0 0 5px rgba(94, 89, 89, 0.2);
}

.card-01{
  background: #03D29F;
  text-align: center;
  align-items: center;
  position: relative;
  padding: 20px;
  flex: 1;
  max-width: 300px;
  height: 150px;
  margin: 10px;
  border-radius: 5px;
  box-shadow: 0 0 5px rgba(94, 89, 89, 0.2);
}

.card-02{
  background: #0470ddd0;
  text-align: center;
  position: relative;
    padding: 20px;
  flex: 1;
  max-width: 460px;
  height: 200px;
  margin: 10px;
  border-radius: 5px;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
}

.card-03{
  background: #FF7675;
  position: relative;
  padding: 20px;
  flex: 1;
  max-width: 940px;
  height: 300px;
  margin: 10px;
  border-radius: 5px;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
}

@media (max-width:800px){

  .card-00{
    flex: 100%;
    max-width: 600px;
  }
  .card-01{
    flex: 100%;
    max-width: 600px;
  }

  .card-02{
    flex: 100%;
    max-width: 600px;
  }

  .card-03{
    flex: 100%;
    max-width: 600px;
  }
}

    </style>
  </head>

  <body>
    <div class="logo">
      <img src="../webui/assets/img/logo.png" alt="Logo">
  </div>
  
  <ul id="main">
      <li onclick="showTab('device')">System</li>
      <li onclick="showTab('battery')">Battery</li>
      <li onclick="showTab('network')">Networks</li>
      <!--<li onclick="toggleSubmenu()">Config
          <ul class="drop">
              <div id="config">
                  <li onclick="showTab('clash')">Clash</li>
                  <li onclick="showTab('sing-box')">Sing-Box</li>
              </div>
          </ul>
      </li>-->
      <li onclick="showTab('cpu')">CPU</li>
      <li onclick="showTab('disk')">DISK INFO</li>
      <div id="marker"></div>
  </ul>
  
  <div id="device" class="tab-content">
    <table>
        <?php systemInfo(); ?>
    </table>

  </div>
  <div id="battery" class="tab-content">
    <table>
        <?php battery(); ?>
    </table>
  </div>
  <div id="network" class="tab-content">
  <table>
                    <?php network(); checkSignal(); ?>
                </table>
  </div>
  <div id="cpu" class="tab-content">
  <?php
    cpu();
    load_average();
    ?>
  </div>
  <div id="disk" class="tab-content">
  <?php
    swap();
    memory();
    disk_usage();
    ?>
  </div>

    <script>
      function showTab(tabId) {
    // Hide all tab contents
    var tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(function(content) {
        content.style.display = 'none';
    });

    // Show the selected tab content
    var selectedTab = document.getElementById(tabId);
    selectedTab.style.display = 'block';
}

function toggleSubmenu() {
    var submenu = document.getElementById('config');
    if (submenu.style.display === 'block') {
        submenu.style.display = 'none';
    } else {
        submenu.style.display = 'block';
    }
}

// Initial tab setup: show the first tab
showTab('device');

    </script>

  </body>
</html>
