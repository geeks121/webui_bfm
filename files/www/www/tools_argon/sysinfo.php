<?php
// Right Card
$deviceName = shell_exec('getprop ro.product.manufacturer').' '.shell_exec('getprop ro.product.model').' ('.shell_exec('getprop ro.product.device').')';
$uptime = shell_exec('cat /proc/uptime');
$uptime = floatval(explode(' ', $uptime)[0]); // Mengambil nilai uptime dalam detik
$days = floor($uptime / 86400);
$hours = floor(($uptime % 86400) / 3600);
$minutes = floor(($uptime % 3600) / 60);
$uptimeFormatted = "$days hari, $hours jam, $minutes menit";
$os = shell_exec('getprop ro.build.version.release');
// Left Card
$temperature = shell_exec('dumpsys battery');
preg_match('/temperature:\s(\d+)/', $temperature, $matches);
preg_match('/level:\s(\d+)/', $temperature, $levelMatches);
preg_match('/AC\s*powered:\s*(true|false)/', $temperature, $chargingMatches);
if (isset($matches[1], $levelMatches[1], $chargingMatches[1])) {
    $temperatureValue = $matches[1];
    $temperatureFormatted = number_format($temperatureValue / 10, 1);
    $level = $levelMatches[1];
    $chargingStatus = strtolower($chargingMatches[1]) === 'true' ? 'Charging' : 'Discharging';
}
$battery_voltage_raw = shell_exec('cat /sys/class/power_supply/battery/voltage_now');
$battery_voltage = number_format($battery_voltage_raw / 1000000, 2) . 'V';
// Network 
$networkInterfaces = [];
$interfaces = shell_exec("ls /sys/class/net");
foreach (explode("\n", $interfaces) as $interface) {
    if (empty($interface)) continue;
    $ip = shell_exec("ip addr show $interface | grep 'inet ' | awk '{print $2}'");
    $rx = shell_exec("cat /sys/class/net/$interface/statistics/rx_bytes");
    $tx = shell_exec("cat /sys/class/net/$interface/statistics/tx_bytes");
    $receivedMB = round($rx / 1024 / 1024, 2);
    $transmittedMB = round($tx / 1024 / 1024, 2);
    $receivedGB = round($rx / 1024 / 1024 / 1024, 2);
    $transmittedGB = round($tx / 1024 / 1024 / 1024, 2);
    if (!empty($ip)) {
        $networkInterfaces[] = [
            'name' => $interface,
            'ip' => $ip ?: '-',
            'received' => ($receivedGB >= 1 ? $receivedGB . ' GB' : $receivedMB . ' MB'),
            'transmitted' => ($transmittedGB >= 1 ? $transmittedGB . ' GB' : $transmittedMB . ' MB')
        ];
    }
}

// Cpu
$cpu = shell_exec('cat /proc/cpuinfo');
preg_match('/Hardware\s+:\s+([^\n]+)/', $cpu, $hardware);
preg_match_all('/processor\s+/', $cpu, $processors);
$hardwareResult = isset($hardware[1]) ? trim(preg_replace('/^(Qualcomm Technologies, Inc|MediaTek|Broadcom)\s*/', '', $hardware[1])) : 'Not found';  // Ambil model hardware tanpa vendor
$coreCount = isset($processors[0]) ? count($processors[0]) : 'Not found';  // Menghitung jumlah core berdasarkan entri "processor"
$result = [
    'Hardware' => $hardwareResult,
    'Core Count' => $coreCount
];
// SIM Card
$sim_operator = shell_exec('getprop gsm.sim.operator.alpha');
$bts_pci = shell_exec('dumpsys telephony.registry | grep -i "mServiceState" | grep -Eo "Pci=[0-9]+" | head -n1 | cut -d= -f2');
$pci = explode(',', trim($bts_pci));

// Capture the output from the shell command
$sim_quality = shell_exec('dumpsys telephony.registry | grep -E "mSignalStrength="');
if (preg_match_all('/CellSignalStrengthLte: rssi=([-\d]+) rsrp=([-\d]+) rsrq=([-\d]+) rssnr=([-\d]+) .*? level=([1-9]+)/', $sim_quality, $matches, PREG_SET_ORDER)) {
    // Loop through each match and process the data
    foreach ($matches as $match) {
        // Assign the values to variables
        $rssi = isset($match[1]) ? (int)$match[1] : 'N/A';
        $rsrp = isset($match[2]) ? (int)$match[2] : 'N/A';
        $rsrq = isset($match[3]) ? (int)$match[3] : 'N/A';
        $rssnr = isset($match[4]) ? (int)$match[4] : 'N/A';
  }
}
// GPU
$gpuModel = shell_exec("cat /sys/kernel/gpu/gpu_model");
$gpuOpenGL = shell_exec("grep Hardware  /proc/cpuinfo");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>System Dashboard</title>
  <link rel="stylesheet" href="css&fonts/sysinfo.css">
    <style>
.dashboard-container {
  width: 100vw; /* Full width of the viewport */

  flex-direction: column; /* Stack content vertically */
  justify-content: center; /* Center vertically */
  align-items: center; /* Center horizontally */

  padding: 10px;
}

  </style>
</head>
<body>


    <header>
        <div class="new-container">
            <p>Status</p>
        </div>
    </header>
  <!-- First Section (System Dashboard) -->
<div class="container">
  <div class="chart" id="memoryChart" data-label="Memory"></div>
    <div class="details">
        <div class="progress-bar">
            <div class="bar">
                <div class="bar-inner"></div>
            </div>
      <div class="bar-label">
        <span>
          <b style="margin-right: 40px;">RAM</b>
          <span class="used-memory-percent"></span> 
          <span class="total-memory"></span>
        </span>
      </div>
      </div>
      <div class="progress-bar">
        <div class="bar">
          <div class="bar-inner"></div>
        </div>
        <div class="bar-label">
        <span>
          <b style="margin-right: 38px;">Swaps</b>
          <span class="used-swap-percent"></span>
          <span class="total-swap"></span>
        </span>
        </div>
      </div>
      <div class="extra-info">
        <span style="flex: 1; text-align: left;">SwapCached <span class="total-swapcache"></span></span>
        <span style="flex: 1; text-align: center;">Dirty  <span class="total-dirty"></span></span>
      </div>
    </div>
  </div>

  <div class="gpu-container">
  <div class="gpu-chart" id="gpuChart" data-label="GPU"></div>
    <div class="gpu-load">
      <p><b class="gpu-freq"><b>---- MHZ</b></b></p>
      <p class="gpu-loads"> Load %</p>
      <p><?= $gpuModel ?></p>
      <p><?= $gpuOpenGL ?></p>
    </div>
  </div>

  <!-- test Third Section (Dashboard) -->
  <div class="dashboard-container">
    <div class="row">
      <div class="column sim-info">
        <div class="section-title">SYSTEM</div>
        <div>
        <span class="raphael--android"></span>
        <span>Android </span><span class="status-value"><?= $os ?></span>
      </div>
      <div>
        <span class="ph--clock-countdown-fill"></span>
        <span>Up time</span>&nbsp;<span class="status-value"><?= $uptimeFormatted ?></span>
      </div>
      <div>
        <span class="ic--twotone-phone-iphone"></span>
        <span class="status-value"> <?= $deviceName ?></span>
      </div>
      </div>
      <div class="divider"></div>
      <div class="column">
        <div class="section-title">BATTERY</div>
        <div>
        <span class="fluent--usb-plug-20-filled"></span>
        <span class="status-value"><?= $chargingStatus ?></span>
      </div>
      <div>
        <span class="tabler--battery-vertical-2-filled"></span>
        <span class="status-value"><?= $level ?>%</span>&nbsp;<span><?= $battery_voltage ?></span>
      </div>
      <div>
        <span class="mdi--fire"></span>
        <span class="status-value"><?= $temperatureFormatted ?>°C</span>
      </div>
      </div>
    </div>
  </div>  

  <!-- Third Section (Dashboard) -->
  <div class="dashboard-container">
    <div class="row">
      <div class="column sim-info">
        <div class="section-title">SIM</div>
        <p>Operator: <?= $sim_operator ?></p>
        <p>PCI: <?= $bts_pci ?></p>
        <p>RSSI: <?= $rssi ?></p>
        <p>RSRP: <?= $rsrp ?></p>
        <p>RSRQ: <?= $rsrq ?></p>
        <p>SINR: <?= $rssnr ?></p>
      </div>
      <div class="divider"></div>
      <div class="column">
        <div class="section-title">CPU</div>
        <div class="cpu-container">
          <div class="cpu-bar">
            <?php
              $cpuCores = 10; // Maksimal 10 bar
              for ($i = 0; $i < $cpuCores; $i++) {
              echo '<div class="low" style="height: 0%"></div>'; // Bar kosong
              }
            ?>
          </div>
          <p class="cpu-info"><?= trim($hardwareResult) ?> (<?= $coreCount ?>)</p>
          <p class="cpu-load"> Load %</p>
        </div>
      </div>
    </div>
    <div>
      <div class="network-title">Network</div>
      <table>
        <thead>
          <tr>
            <th>Interface</th>
            <th>IP</th>
            <th>Receive</th>
            <th>Transmit</th>
          </tr>
        </thead>
      <tbody>
        <?php foreach ($networkInterfaces as $interface): ?>
          <tr>
            <td><?= $interface['name'] ?></td>
            <td><?= $interface['ip'] ?></td>
            <td><?= $interface['received'] ?></td>
            <td><?= $interface['transmitted'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      </table>
    </div>
  </div>
  


 <script>
let currentBarIndex = 0;
let bars = [];

function updateMemoryStatus() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'exec/helpers.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var data = JSON.parse(xhr.responseText);  // Parsing response JSON
                console.log(data); // Debugging log
                
                var usedMemoryPercent = data.used_memory_percent;
                var usedSwapPercent = data.used_swap_percent;
                var gpuFreq = parseFloat(data.gpuFreq);
                var gpuLoad = parseFloat(data.gpuLoad);
                var load = parseFloat(data.active); // Active CPU load
                
                // Handle invalid values
                if (isNaN(gpuFreq)) gpuFreq = 0;
                if (isNaN(gpuLoad)) gpuLoad = 0;
                if (isNaN(load)) load = 0;
                
                // Update memory and swap info
                if (document.querySelector('.total-memory')) {
                    document.querySelector('.total-memory').textContent = '(' + data.total_memory + ')';
                }

                if (document.querySelector('.free-memory')) {
                    document.querySelector('.free-memory').textContent = 'Free Memory: ' + data.free_memory;
                }
                
                if (document.querySelector('.used-memory-percent')) {
                    document.querySelector('.used-memory-percent').textContent = usedMemoryPercent + '%';
                }
                
                if (document.querySelector('.swap-free')) {
                    document.querySelector('.swap-free').textContent =  data.swap_free;
                }
                
                if (document.querySelector('.total-swap')) {
                    document.querySelector('.total-swap').textContent = '(' + data.total_swap + ')';
                }
                
                if (document.querySelector('.used-swap-percent')) {
                    document.querySelector('.used-swap-percent').textContent = usedSwapPercent + '%';
                }
                
                if (document.querySelector('.total-swapcache')) {
                    document.querySelector('.total-swapcache').textContent =  data.total_swapcache;
                }
                
                if (document.querySelector('.total-dirty')) {
                    document.querySelector('.total-dirty').textContent =  data.total_dirty;
                }
                
                if (document.querySelector('.gpu-freq')) {
                    document.querySelector('.gpu-freq').textContent =  gpuFreq + ' MHz';
                }

                if (document.querySelector('.gpu-loads')) {
                    document.querySelector('.gpu-loads').textContent = 'Load: ' + gpuLoad + '%';
                }
                
                if (document.querySelector('.cpu-load')) {
                    document.querySelector('.cpu-load').textContent = 'Load: ' + load + '%';
                }
                           
                // Update memory chart
// Cek apakah pengguna memilih mode gelap
var isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

// Update memory chart
var memoryChart = document.querySelector('#memoryChart');
memoryChart.style.background = `conic-gradient(${isDarkMode ? '#4c96ff' : '#4c96ff'} 0% ${usedMemoryPercent}%, ${isDarkMode ? '#555555' : '#e6e6e6'} ${usedMemoryPercent}% 100%)`;

// Update GPU chart
var gpuChart = document.querySelector('#gpuChart');
gpuChart.style.background = `conic-gradient(${isDarkMode ? '#ff9f43' : '#ff9f43'} 0% ${gpuLoad}%, ${isDarkMode ? '#555555' : '#e6e6e6'} ${gpuLoad}% 100%)`;

// Optional: Watch for changes in system color scheme preference
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    var isDarkMode = e.matches;
    
    // Update charts when mode changes
    memoryChart.style.background = `conic-gradient(${isDarkMode ? '#4c96ff' : '#4c96ff'} 0% ${usedMemoryPercent}%, ${isDarkMode ? '#555555' : '#e6e6e6'} ${usedMemoryPercent}% 100%)`;
    gpuChart.style.background = `conic-gradient(${isDarkMode ? '#ff9f43' : '#ff9f43'} 0% ${gpuLoad}%, ${isDarkMode ? '#555555' : '#e6e6e6'} ${gpuLoad}% 100%)`;
});

                
                var barContainer = document.querySelector('.cpu-bar');
                
                if (bars.length >= 10) {
                    barContainer.removeChild(bars[0]);
                    bars.shift();  // Remove the first bar
                }
                var newBar = document.createElement('div');
                newBar.className = 'low';
                newBar.style.height = load + '%';
                if (load <= 25) {
                    newBar.className = 'low';
                } else if (load <= 50) {
                    newBar.className = 'medium';
                } else if (load <= 75) {
                    newBar.className = 'high';
                } else {
                    newBar.className = 'critical';
                }
                bars.push(newBar);
                barContainer.appendChild(newBar);
                // Update memory and swap bars
                var barElements = document.querySelectorAll('.bar-inner');
                if (barElements.length >= 2) {
                    barElements[0].style.width = usedMemoryPercent + '%';
                    barElements[1].style.width = usedSwapPercent + '%';
                }
                // Debugging: Show values in the console
                console.log("Total Memory: ", data.total_memory);
                console.log("Free Memory: ", data.free_memory);
                console.log("Used Memory Percent: ", usedMemoryPercent);
                console.log("Swap Free: ", data.swap_free);
                console.log("Total Swap: ", data.total_swap);
                console.log("Used Swap Percent: ", usedSwapPercent);
                console.log("Total Swapcache: ", data.total_swapcache);
                console.log("Total Dirty: ", data.total_dirty);
                console.log("GPU Freq: ", gpuFreq);
                console.log("GPU Load: ", gpuLoad);
                console.log("CPU Active Load: ", load);
            } catch (error) {
                console.error('Error parsing JSON response:', error);
            }
        } else {
            console.error('Request failed with status:', xhr.status);
        }
    };
    xhr.onerror = function() {
        console.error('Request failed due to network error.');
    };
    xhr.send();
}
setInterval(updateMemoryStatus, 1500); // Update every 1.5 seconds
updateMemoryStatus();  // Initial call
 </script>
</body>
</html>