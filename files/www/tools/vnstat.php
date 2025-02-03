<?php
// Configuration
$tmuxBin = "/data/data/com.termux/files/usr/bin/";
$vnstatDbPath = "/data/data/com.termux/files/usr/var/lib/vnstat/vnstat.db";

// Handle Reset Action
if (isset($_POST['reset_vnstat'])) {
    if (file_exists($vnstatDbPath)) {
        unlink($vnstatDbPath);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Handle Start VNStat Action
if (isset($_POST['start_vnstat'])) {
    shell_exec("/data/data/com.termux/files/usr/bin/vnstatd -d");
    sleep(1);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Utility Functions
function convertToMB($size) {
    $size = trim($size);
    $value = floatval($size);
    $unit = preg_replace('/[^A-Za-z]/', '', $size);
    
    switch(strtoupper($unit)) {
        case 'KIB':
        case 'KB': 
            return $value / 1024;
        case 'MIB':
        case 'MB': 
            return $value;
        case 'GIB':
        case 'GB': 
            return $value * 1024;
        case 'TIB':
        case 'TB': 
            return $value * 1024 * 1024;
        default: 
            return $value / (1024 * 1024);
    }
}

function formatSize($mb) {
    if ($mb >= 1048576) {
        return round($mb / 1048576, 2) . " TB";
    } elseif ($mb >= 1024) {
        return round($mb / 1024, 2) . " GB";
    } elseif ($mb >= 1) {
        return round($mb, 2) . " MB";
    } else {
        return round($mb * 1024, 2) . " KB";
    }
}

function formatDate($date, $type = 'daily') {
    $timestamp = strtotime($date);
    if ($type === 'daily') {
        return date('F jS, Y', $timestamp);
    } else {
        return date('F Y', $timestamp);
    }
}

// Network Interface Functions
function getAllMobileInterfaces() {
    $interfaces = [];
    $ifconfig = shell_exec("/data/data/com.termux/files/usr/bin/ifconfig -a");
    
    // MediaTek (Xiaomi, OPPO, Vivo, Realme, dll)
    if (preg_match_all('/ccmni\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    if (preg_match_all('/cc\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    
    // Qualcomm (Samsung, Google, OnePlus, Sony, dll)
    if (preg_match_all('/rmnet_data\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    if (preg_match_all('/rmnet\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    
    // Huawei/Honor
    if (preg_match_all('/hwwan\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    if (preg_match_all('/hw_data\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    
    // Generic Android dan versi baru
    if (preg_match_all('/pdp\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    if (preg_match_all('/wwan\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    if (preg_match_all('/data\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    
    // Spreadtrum/Unisoc (Andromax, Advan, dll)
    if (preg_match_all('/seth_lte\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    if (preg_match_all('/sprd\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    
    // Interface tambahan untuk kompatibilitas
    if (preg_match_all('/ppp\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    if (preg_match_all('/cell\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    if (preg_match_all('/mobile\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    
    sort($interfaces);
    return $interfaces;
}

function getAllTetherInterfaces() {
    $interfaces = [];
    $ifconfig = shell_exec("/data/data/com.termux/files/usr/bin/ifconfig -a");
    
    // WiFi Tethering/Hotspot
    if (preg_match_all('/wlan\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    if (preg_match_all('/ap\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    
    // MediaTek Tethering
    if (preg_match_all('/ccmni-lan/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    if (preg_match_all('/cc-lan\d*/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    
    // USB Tethering
    if (preg_match_all('/rndis\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    if (preg_match_all('/usb\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    
    // Bluetooth Tethering
    if (preg_match_all('/bt-pan/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    if (preg_match_all('/bnep\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    
    // Ethernet Tethering
    if (preg_match_all('/eth\d+/', $ifconfig, $matches)) {
        $interfaces = array_merge($interfaces, array_unique($matches[0]));
    }
    
    sort($interfaces);
    return $interfaces;
}

function getInterfaceInfo($interface) {
    $stats = shell_exec($GLOBALS['tmuxBin'] . "vnstat -i " . escapeshellarg($interface) . " --oneline 2>&1");
    if ($stats) {
        $parts = explode(';', $stats);
        if (count($parts) >= 6) {
            $download = isset($parts[3]) ? $parts[3] : '0 B';
            $upload = isset($parts[4]) ? $parts[4] : '0 B';
            
            $downloadMB = convertToMB($download);
            $uploadMB = convertToMB($upload);
            $totalMB = $downloadMB + $uploadMB;
            
            return [
                'download' => formatSize($downloadMB),
                'upload' => formatSize($uploadMB),
                'total' => formatSize($totalMB),
                'has_data' => ($totalMB > 0),
                'raw_download' => $downloadMB,
                'raw_upload' => $uploadMB,
                'raw_total' => $totalMB
            ];
        }
    }
    return [
        'download' => '0 KB',
        'upload' => '0 KB',
        'total' => '0 KB',
        'has_data' => false,
        'raw_download' => 0,
        'raw_upload' => 0,
        'raw_total' => 0
    ];
}

function parseVnstatOutput($output, $type) {
    $lines = explode("\n", $output);
    $result = [];
    
    foreach ($lines as $line) {
        if ($type === 'daily') {
            if (preg_match('/(\d{4}-\d{2}-\d{2})\s+([\d.]+ \w+)\s+\|\s+([\d.]+ \w+)\s+\|\s+([\d.]+ \w+)/', $line, $matches)) {
                $date = $matches[1];
                if (!isset($result[$date])) {
                    $result[$date] = [
                        'download' => 0,
                        'upload' => 0,
                        'total' => 0
                    ];
                }
                
                $download = convertToMB($matches[2]);
                $upload = convertToMB($matches[3]);
                
                $result[$date]['download'] += $download;
                $result[$date]['upload'] += $upload;
                $result[$date]['total'] = $result[$date]['download'] + $result[$date]['upload'];
            }
        }
    }
    
    return $result;
}

function getLastSevenDaysData($dailyUsageAll) {
    $lastSevenDays = array_slice($dailyUsageAll, 0, 7, true);
    $chartData = [];
    
    foreach ($lastSevenDays as $date => $usage) {
        $downloadValue = convertSizeToGB($usage['download']);
        $uploadValue = convertSizeToGB($usage['upload']);
        
        $chartData[] = [
            'date' => date('Y-m-d', strtotime($date)),
            'download' => $downloadValue,
            'upload' => $uploadValue
        ];
    }
    
    return array_reverse($chartData);
}

function convertSizeToGB($size) {
    $size = trim($size);
    if (strpos($size, 'MB') !== false) {
        return round(floatval($size) / 1024, 2);
    } elseif (strpos($size, 'GB') !== false) {
        return round(floatval($size), 2);
    } else {
        return round(floatval($size), 2);
    }
}

function formatSizeAutoUnit($bytes) {
    $units = ['KB', 'MB', 'GB', 'TB'];
    $size = $bytes;
    $unit_index = 0;
    
    while ($size >= 1024 && $unit_index < count($units) - 1) {
        $size /= 1024;
        $unit_index++;
    }
    
    return round($size, 2) . ' ' . $units[$unit_index];
}

function getCurrentMonthTotal($dailyUsageAll) {
    $total = 0;
    $currentMonth = date('F');
    $currentYear = date('Y');
    
    foreach ($dailyUsageAll as $date => $usage) {
        if (strpos($date, "$currentYear") !== false) {
            // Convert all values to KB for consistent calculation
            $total += convertToKB($usage['total']);
        }
    }
    
    return formatSizeAutoUnit($total);
}

function getTodayTotal($dailyUsageAll) {
    $today = date('F jS, Y');
    
    foreach ($dailyUsageAll as $date => $usage) {
        if ($date === $today) {
            // Convert to KB and then format with appropriate unit
            return formatSizeAutoUnit(convertToKB($usage['total']));
        }
    }
    return '0 KB';
}

function getYesterdayTotal($dailyUsageAll) {
    $yesterday = date('F jS, Y', strtotime('-1 day'));
    
    foreach ($dailyUsageAll as $date => $usage) {
        if ($date === $yesterday) {
            // Convert to KB and then format with appropriate unit
            return formatSizeAutoUnit(convertToKB($usage['total']));
        }
    }
    return '0 KB';
}

function convertToKB($size) {
    $size = trim($size);
    $value = floatval(preg_replace('/[^0-9.]/', '', $size));
    
    if (strpos($size, 'TB') !== false) {
        return $value * 1024 * 1024 * 1024;
    } elseif (strpos($size, 'GB') !== false) {
        return $value * 1024 * 1024;
    } elseif (strpos($size, 'MB') !== false) {
        return $value * 1024;
    } elseif (strpos($size, 'KB') !== false) {
        return $value;
    }
    return $value; // Assume KB if no unit specified
}

// Main Logic
$mobileInterfaces = getAllMobileInterfaces();
$tetherInterfaces = getAllTetherInterfaces();
$allInterfaces = array_merge($mobileInterfaces, $tetherInterfaces);

$dailyUsageAll = [];
$monthlyUsageAll = [];
$activeInterfacesInfo = [];

// Process all interfaces
foreach ($allInterfaces as $interface) {
    $info = getInterfaceInfo($interface);
    if ($info['has_data']) {
        $activeInterfacesInfo[$interface] = $info;
    }

    // Get daily stats
    $vnstatDaily = shell_exec($tmuxBin . "vnstat -d -i " . escapeshellarg($interface) . " 2>&1");
    $dailyUsage = parseVnstatOutput($vnstatDaily, 'daily');
    
    // Aggregate daily usage
    foreach ($dailyUsage as $date => $usage) {
        if (!isset($dailyUsageAll[$date])) {
            $dailyUsageAll[$date] = ['download' => 0, 'upload' => 0, 'total' => 0];
        }
        $dailyUsageAll[$date]['download'] += $usage['download'];
        $dailyUsageAll[$date]['upload'] += $usage['upload'];
        $dailyUsageAll[$date]['total'] = $dailyUsageAll[$date]['download'] + $dailyUsageAll[$date]['upload'];
    }
}

// Aggregate daily data into monthly data
foreach ($dailyUsageAll as $date => $usage) {
    $monthKey = substr($date, 0, 7);
    if (!isset($monthlyUsageAll[$monthKey])) {
        $monthlyUsageAll[$monthKey] = ['download' => 0, 'upload' => 0, 'total' => 0];
    }
    $monthlyUsageAll[$monthKey]['download'] += $usage['download'];
    $monthlyUsageAll[$monthKey]['upload'] += $usage['upload'];
    $monthlyUsageAll[$monthKey]['total'] = $monthlyUsageAll[$monthKey]['download'] + $monthlyUsageAll[$monthKey]['upload'];
}

// Format daily usage
$formattedDailyUsage = [];
foreach ($dailyUsageAll as $date => $usage) {
    $formattedDate = formatDate($date, 'daily');
    $formattedDailyUsage[$formattedDate] = [
        'download' => formatSize($usage['download']),
        'upload' => formatSize($usage['upload']),
        'total' => formatSize($usage['total'])
    ];
}

// Format monthly usage
$formattedMonthlyUsage = [];
foreach ($monthlyUsageAll as $date => $usage) {
    $formattedDate = formatDate($date, 'monthly');
    $formattedMonthlyUsage[$formattedDate] = [
        'download' => formatSize($usage['download']),
        'upload' => formatSize($usage['upload']),
        'total' => formatSize($usage['total'])
    ];
}

// Sort by date
krsort($formattedDailyUsage);
krsort($formattedMonthlyUsage);

// Replace the original arrays with formatted ones
$dailyUsageAll = $formattedDailyUsage;
$monthlyUsageAll = $formattedMonthlyUsage;

function prepareChartData($lastSevenDays) {
    $chartLabels = [];
    $downloadData = [];
    $uploadData = [];
    
    foreach ($lastSevenDays as $date => $usage) {
        // Format tanggal untuk label
        $chartLabels[] = date('Y-m-d', strtotime($date));
        
        // Konversi download ke GB
        $download = convertToGB($usage['download']);
        $downloadData[] = $download;
        
        // Konversi upload ke GB
        $upload = convertToGB($usage['upload']);
        $uploadData[] = $upload;
    }
    
    return [
        'labels' => $chartLabels,
        'downloads' => $downloadData,
        'uploads' => $uploadData
    ];
}

// Fungsi bantuan untuk konversi ke GB
function convertToGB($size) {
    if (strpos($size, 'MB') !== false) {
        return floatval(str_replace(' MB', '', $size)) / 1024;
    } elseif (strpos($size, 'GB') !== false) {
        return floatval(str_replace(' GB', '', $size));
    }
    return floatval($size) / 1024; // Asumsikan MB jika tidak ada unit
}

$lastSevenDays = getLastSevenDaysData($dailyUsageAll);
$chartLabels = array_column($lastSevenDays, 'date');
$downloadData = array_column($lastSevenDays, 'download');
$uploadData = array_column($lastSevenDays, 'upload');
$monthlyTotal = getCurrentMonthTotal($dailyUsageAll);
$todayTotal = getTodayTotal($dailyUsageAll);
$yesterdayTotal = getYesterdayTotal($dailyUsageAll);

$chartDataJson = json_encode([
    'labels' => $chartLabels,
    'downloads' => $downloadData,
    'uploads' => $uploadData

]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Network Usage Monitor</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <style>
        body {
            background-color: #1e1e1e;
            color: #f1f1f1;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .output-box {
            background-color: #333;
            border: 1px solid #444;
            padding: 25px;
            margin-bottom: 30px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .no-data {
            text-align: center;
            padding: 30px;
            color: #888;
            font-size: 16px;
        }
        
        h2 {
            color: #ffa500;
            margin: 25px 0;
            font-size: 1.8em;
            padding-left: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .interface-count {
            font-size: 0.9em;
            color: #888;
            margin-right: 25px;
        }
        
        /* Stats Boxes */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .stats-box {
            background-color: #333;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            transition: transform 0.2s;
        }
        
        .stats-box:hover {
            transform: translateY(-2px);
        }
        
        .stats-label {
            color: white;
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
            font-weight: 500;
        }
        
        .stats-value {
            font-size: 32px;
            color: #ffa624;
            font-weight: bold;
            margin: 0;
            line-height: 1.2;
        }
        
        /* Chart Styles */
        .chart-container {
            background-color: #333;
            border: 1px solid #444;
            padding: 30px;
            margin-bottom: 30px;
            border-radius: 15px;
            height: 500px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        canvas {
            width: 100% !important;
            height: 100% !important;
        }
        
        /* Table Styles */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
            background-color: #333;
            border-radius: 10px;
            overflow: hidden;
        }
        
        th {
            text-align: center !important;
            background-color: #444;
            color: #ffa500;
            font-weight: bold;
            padding: 15px;
            border: 1px solid #444;
        }
        
        th:first-child {
            border-top-left-radius: 10px;
            text-align: left !important;
        }
        
        th:last-child {
            border-top-right-radius: 10px;
        }
        
        tr:last-child td:first-child {
            border-bottom-left-radius: 10px;
        }
        
        tr:last-child td:last-child {
            border-bottom-right-radius: 10px;
        }
        
        table th:first-child,
        table td:first-child {
            width: 25%;
            text-align: left;
        }
        
        table th:not(:first-child),
        table td:not(:first-child) {
            width: 25%;
            text-align: center;
        }
        
        td {
            padding: 15px;
            border: 1px solid #444;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        tr:nth-child(even) {
            background-color: #2a2a2a;
        }
        
        tr:nth-child(odd) {
            background-color: #333;
        }
        
        tr:hover {
            background-color: #3a3a3a;
        }
        
        /* Button Styles */
        .button-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 30px 0;
        }
        
        .action-button {
            display: inline-block;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .reset-button {
            background-color: #ff4444;
        }
        
        .start-button {
            background-color: #4CAF50;
        }
        
        .reset-button:hover {
            background-color: #ff6666;
            transform: translateY(-2px);
        }
        
        .start-button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #333;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            min-width: 300px;
        }
        
        .modal-buttons {
            margin-top: 25px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .modal-buttons button {
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .confirm-reset {
            background-color: #ff4444;
            color: white;
        }
        
        .confirm-start {
            background-color: #4CAF50;
            color: white;
        }
        
        .cancel-button {
            background-color: #666;
            color: white;
        }
        
        .modal-buttons button:hover {
            transform: translateY(-2px);
        }
        
        /* Large Desktop (1440px and up) */
        @media (min-width: 1440px) {
            .container {
                max-width: 1400px;
                padding: 0 30px;
            }
            
            .stats-value {
                font-size: 36px;
            }
            
            .chart-container {
                height: 600px;
            }
            
            h2 {
                font-size: 2em;
            }
        }
        
        /* Desktop (1024px to 1439px) */
        @media (max-width: 1439px) {
            .container {
                max-width: 1200px;
                padding: 0 20px;
            }
            
            .chart-container {
                height: 500px;
            }
            
            .stats-value {
                font-size: 32px;
            }
        }
        
        /* Tablet (768px to 1023px) */
        @media (max-width: 1023px) {
            .container {
                padding: 0 15px;
            }
            
            .stats-container {
                gap: 15px;
            }
            
            .stats-box {
                padding: 20px;
            }
            
            .chart-container {
                height: 400px;
                padding: 20px;
            }
            
            .stats-value {
                font-size: 28px;
            }
            
            h2 {
                font-size: 1.5em;
                padding-left: 15px;
            }
            
            .output-box {
                padding: 20px;
            }
        }
        
        /* Mobile Large (480px to 767px) */
        @media (max-width: 767px) {
            body {
                padding: 10px;
            }
            
            .container {
                padding: 0 10px;
            }
            
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
            
            .stats-box {
                padding: 15px;
            }
            
            .stats-value {
                font-size: 24px;
            }
            
            .stats-label {
                font-size: 12px;
            }
            
            .chart-container {
                height: 300px;
                padding: 15px;
            }
            
            .output-box {
                padding: 15px;
                margin-bottom: 20px;
            }
            
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
            
            th, td {
                padding: 10px;
                font-size: 14px;
            }
            
            .button-container {
                flex-direction: column;
                gap: 15px;
            }
            
            .action-button {
                width: 100%;
                padding: 12px;
                font-size: 14px;
            }
            
            .modal-content {
                width: 90%;
                min-width: auto;
                padding: 20px;
            }
        }
        
        /* Mobile Small (479px and below) */
        @media (max-width: 479px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .stats-box {
                padding: 12px;
            }
            
            .stats-value {
                font-size: 22px;
            }
            
            .chart-container {
                height: 250px;
                padding: 10px;
            }
            
            h2 {
                font-size: 1.2em;
                padding-left: 10px;
            }
            
            th, td {
                padding: 8px;
                font-size: 13px;
            }
            
            .interface-count {
                font-size: 0.8em;
            }
            
            .modal-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .modal-buttons button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Stats Boxes -->
        <div class="stats-container">
            <div class="stats-box">
                <div class="stats-label">THIS MONTH</div>
                <div class="stats-value"><?php echo htmlspecialchars($monthlyTotal); ?></div>
            </div>
            <div class="stats-box">
                <div class="stats-label">TODAY</div>
                <div class="stats-value"><?php echo htmlspecialchars($todayTotal); ?></div>
            </div>
            <div class="stats-box">
                <div class="stats-label">YESTERDAY</div>
                <div class="stats-value"><?php echo htmlspecialchars($yesterdayTotal); ?></div>
            </div>
        </div>
               
        <!-- Chart Section -->
        <h2>Graph of The Last 7 Days</h2>
        <div class="chart-container">
            <canvas id="usageChart"></canvas>
        </div>
        
        <!-- Active Interfaces Section -->
        <h2>
            Active Interfaces in Today
            <span class="interface-count"><?php echo count($activeInterfacesInfo); ?> active</span>
        </h2>
        <div class="output-box">
            <?php if (empty($activeInterfacesInfo)): ?>
                <div class="no-data">No active interfaces with data</div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Interface</th>
                        <th>Download</th>
                        <th>Upload</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activeInterfacesInfo as $interface => $info): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($interface); ?></td>
                        <td><?php echo htmlspecialchars($info['download']); ?></td>
                        <td><?php echo htmlspecialchars($info['upload']); ?></td>
                        <td><?php echo htmlspecialchars($info['total']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <!-- Daily Usage Section -->
        <h2>Daily Combined Usage</h2>
        <div class="output-box">
            <?php if (empty($dailyUsageAll)): ?>
                <div class="no-data">No daily usage data available</div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Download</th>
                        <th>Upload</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dailyUsageAll as $date => $usage): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($date); ?></td>
                        <td><?php echo htmlspecialchars($usage['download']); ?></td>
                        <td><?php echo htmlspecialchars($usage['upload']); ?></td>
                        <td><?php echo htmlspecialchars($usage['total']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <!-- Monthly Usage Section -->
        <h2>Monthly Combined Usage</h2>
        <div class="output-box">
            <?php if (empty($monthlyUsageAll)): ?>
                <div class="no-data">No monthly usage data available</div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Download</th>
                        <th>Upload</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($monthlyUsageAll as $date => $usage): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($date); ?></td>
                        <td><?php echo htmlspecialchars($usage['download']); ?></td>
                        <td><?php echo htmlspecialchars($usage['upload']); ?></td>
                        <td><?php echo htmlspecialchars($usage['total']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <div class="button-container">
            <button class="action-button reset-button" onclick="showResetConfirmation()">Reset VNStat Data</button>
            <button class="action-button start-button" onclick="showStartConfirmation()">Start VNStat</button>
        </div>

        <!-- Reset Confirmation Modal -->
        <div id="resetModal" class="modal">
            <div class="modal-content">
                <p>Are you sure you want to reset VNStat data?</p>
                <div class="modal-buttons">
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="reset_vnstat" value="1">
                        <button type="submit" class="confirm-reset">Reset</button>
                    </form>
                    <button onclick="hideResetModal()" class="cancel-button">Cancel</button>
                </div>
            </div>
        </div>

        <!-- Start Confirmation Modal -->
        <div id="startModal" class="modal">
            <div class="modal-content">
                <p>Are you sure you want to start VNStat?</p>
                <div class="modal-buttons">
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="start_vnstat" value="1">
                        <button type="submit" class="confirm-start">Start</button>
                    </form>
                    <button onclick="hideStartModal()" class="cancel-button">Cancel</button>
                </div>
            </div>
        </div>
    </div>

<script>
        const ctx = document.getElementById('usageChart').getContext('2d');
        const chartData = <?php echo $chartDataJson; ?>;
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Download (GB)',
                    data: chartData.downloads,
                    borderColor: '#ffa500',
                    backgroundColor: 'rgba(255, 165, 0, 0.1)',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Upload (GB)',
                    data: chartData.uploads,
                    borderColor: '#00bcd4',
                    backgroundColor: 'rgba(0, 188, 212, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: '#fff',
                            callback: function(value) {
                                return value.toFixed(2) + ' GB';
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: '#fff'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#fff'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + ' GB';
                            }
                        }
                    }
                }
            }
        });
       // Modal Functions
        function showResetConfirmation() {
            document.getElementById('resetModal').style.display = 'block';
        }

        function hideResetModal() {
            document.getElementById('resetModal').style.display = 'none';
        }

        function showStartConfirmation() {
            document.getElementById('startModal').style.display = 'block';
        }

        function hideStartModal() {
            document.getElementById('startModal').style.display = 'none';
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const resetModal = document.getElementById('resetModal');
            const startModal = document.getElementById('startModal');
            
            if (event.target === resetModal) {
                hideResetModal();
            }
            if (event.target === startModal) {
                hideStartModal();
            }
        }
    </script>
</body>
</html>
