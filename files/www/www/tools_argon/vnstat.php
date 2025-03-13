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
function convertToMB($size)
{
    $size = trim($size);
    $value = floatval($size);
    $unit = preg_replace('/[^A-Za-z]/', '', $size);

    switch (strtoupper($unit)) {
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

function formatSize($mb)
{
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

function formatDate($date, $type = 'daily')
{
    $timestamp = strtotime($date);
    if ($type === 'daily') {
        return date('F jS, Y', $timestamp);
    } else {
        return date('F Y', $timestamp);
    }
}

// Network Interface Functions
function getAllMobileInterfaces()
{
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

function getAllTetherInterfaces()
{
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

function getInterfaceInfo($interface)
{
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

function parseVnstatOutput($output, $type)
{
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

function parseFiveMinuteData($output)
{
    $lines = explode("\n", $output);
    $result = [];

    foreach ($lines as $line) {
        if (preg_match('/(\d{2}:\d{2})\s+([\d.]+ \w+)\s+\|\s+([\d.]+ \w+)\s+\|\s+([\d.]+ \w+)/', $line, $matches)) {
            $time = $matches[1];
            $download = convertToMB($matches[2]);
            $upload = convertToMB($matches[3]);
            $total = $download + $upload;

            $result[$time] = [
                'download' => $download,
                'upload' => $upload,
                'total' => $total
            ];
        }
    }

    return $result;
}

function parseHourlyData($output)
{
    $lines = explode("\n", $output);
    $result = [];

    foreach ($lines as $line) {
        if (preg_match('/(\d{2}:\d{2})\s+([\d.]+ \w+)\s+\|\s+([\d.]+ \w+)\s+\|\s+([\d.]+ \w+)/', $line, $matches)) {
            $time = $matches[1];
            $download = convertToMB($matches[2]);
            $upload = convertToMB($matches[3]);
            $total = $download + $upload;

            $result[$time] = [
                'download' => $download,
                'upload' => $upload,
                'total' => $total
            ];
        }
    }

    return $result;
}

function getLastSevenDaysData($dailyUsageAll)
{
    $lastSevenDays = array_slice($dailyUsageAll, 0, 7, true);
    $chartData = [];

    foreach ($lastSevenDays as $date => $usage) {
        $downloadValue = convertSizeToGB($usage['download']);
        $uploadValue = convertSizeToGB($usage['upload']);

        $chartData[] = [
            'date' => date('d/m/y', strtotime($date)), // Change date format to dd/mm/yy
            'download' => $downloadValue,
            'upload' => $uploadValue
        ];
    }

    return array_reverse($chartData);
}

function convertSizeToGB($size)
{
    $size = trim($size);
    if (strpos($size, 'MB') !== false) {
        return round(floatval($size) / 1024, 2);
    } elseif (strpos($size, 'GB') !== false) {
        return round(floatval($size), 2);
    } else {
        return round(floatval($size), 2);
    }
}

function parseSize($size)
{
    $size = trim($size);
    if (preg_match('/^[\d.]+/', $size, $matches)) {
        $value = floatval($matches[0]);
        if (strpos($size, 'MB') !== false) {
            return $value / 1024;
        } elseif (strpos($size, 'GB') !== false) {
            return $value;
        }
    }
    return 0;
}

function getCurrentMonthTotal($dailyUsageAll)
{
    $total = 0;
    $currentMonth = date('F');
    $currentYear = date('Y');

    foreach ($dailyUsageAll as $date => $usage) {
        if (strpos($date, "$currentMonth") === 0 && strpos($date, $currentYear) !== false) {
            $total += parseSize($usage['total']);
        }
    }

    return number_format($total, 2) . ' GB';
}

function getTodayTotal($dailyUsageAll)
{
    $today = date('F jS, Y');

    foreach ($dailyUsageAll as $date => $usage) {
        if ($date === $today) {
            return $usage['total'];
        }
    }
    return '0.00 GB';
}

function getYesterdayTotal($dailyUsageAll)
{
    $yesterday = date('F jS, Y', strtotime('-1 day'));

    foreach ($dailyUsageAll as $date => $usage) {
        if ($date === $yesterday) {
            return $usage['total'];
        }
    }
    return '0.00 GB';
}

function filterUsageByDateRange($usageData, $startDate, $endDate)
{
    $filteredData = [];
    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    if ($startDate === false || $endDate === false) {
        return $filteredData; // Return empty array if dates are invalid
    }

    foreach ($usageData as $date => $usage) {
        $currentDate = strtotime($date);
        if ($currentDate >= $startDate && $currentDate <= $endDate) {
            $filteredData[$date] = $usage;
        }
    }

    return $filteredData;
}

// Main Logic
$mobileInterfaces = getAllMobileInterfaces();
$tetherInterfaces = getAllTetherInterfaces();
$allInterfaces = array_merge($mobileInterfaces, $tetherInterfaces);

$dailyUsageAll = [];
$monthlyUsageAll = [];
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

$vnstatFiveMinute = shell_exec($tmuxBin . "vnstat -5 -i " . escapeshellarg($interface) . " 2>&1");
$fiveMinuteData = parseFiveMinuteData($vnstatFiveMinute);

$vnstatHourly = shell_exec($tmuxBin . "vnstat -h -i " . escapeshellarg($interface) . " 2>&1");
$hourlyData = parseHourlyData($vnstatHourly);
$hourlyChartData = prepareHourlyChartData($hourlyData);

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

function prepareChartData($lastSevenDays)
{
    $chartLabels = [];
    $downloadData = [];
    $uploadData = [];

    foreach ($lastSevenDays as $date => $usage) {
        // Format date to dd/mm/yy
        $chartLabels[] = date('d/m/y', strtotime($date));

        // Convert download to GB
        $download = convertToGB($usage['download']);
        $downloadData[] = $download;

        // Convert upload to GB
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
function convertToGB($size)
{
    if (strpos($size, 'KB') !== false) {
        return floatval(str_replace(' KB', '', $size)) / (1024 * 1024);
    } elseif (strpos($size, 'MB') !== false) {
        return floatval(str_replace(' MB', '', $size)) / 1024;
    } elseif (strpos($size, 'GB') !== false) {
        return floatval(str_replace(' GB', '', $size));
    }
    return floatval($size) / (1024 * 1024); // Assume KB if no unit
}

function prepareFiveMinuteChartData($fiveMinuteData)
{
    $chartLabels = [];
    $downloadData = [];
    $uploadData = [];

    foreach ($fiveMinuteData as $time => $usage) {
        $chartLabels[] = $time;
        $downloadData[] = convertToGB($usage['download']);
        $uploadData[] = convertToGB($usage['upload']);
    }

    return [
        'labels' => $chartLabels,
        'downloads' => $downloadData,
        'uploads' => $uploadData
    ];
}

function prepareHourlyChartData($hourlyData)
{
    $chartLabels = [];
    $downloadData = [];
    $uploadData = [];

    foreach ($hourlyData as $time => $usage) {
        $chartLabels[] = $time;
        $downloadData[] = convertToGB($usage['download']);
        $uploadData[] = convertToGB($usage['upload']);
    }

    return [
        'labels' => $chartLabels,
        'downloads' => $downloadData,
        'uploads' => $uploadData
    ];
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

if (isset($_POST['view_usage'])) {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $filteredUsage = filterUsageByDateRange($dailyUsageAll, $startDate, $endDate);

    // Calculate total usage for the filtered data
    $totalDownload = 0;
    $totalUpload = 0;
    $totalCombined = 0;

    foreach ($filteredUsage as $usage) {
        $totalDownload += parseSize($usage['download']);
        $totalUpload += parseSize($usage['upload']);
        $totalCombined += parseSize($usage['total']);
    }

    $totalDownloadFormatted = formatSize($totalDownload * 1024); // Convert GB to MB for formatting
    $totalUploadFormatted = formatSize($totalUpload * 1024); // Convert GB to MB for formatting
    $totalCombinedFormatted = formatSize($totalCombined * 1024); // Convert GB to MB for formatting
}
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
            background-color: #f4f4f4;
            color: #333;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .output-box {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 5px;
            margin-bottom: 20px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #888;
        }

        h2 {
            color: #000;
            margin: 20px 0;
            font-size: 1.5em;
            padding-left: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .interface-count {
            font-size: 0.8em;
            color: #888;
            margin-right: 20px;
        }

        table {
            width: 100%;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
            padding: 0;
            background-color: #fff;
            border-radius: 10px;
            font-size: 0.7em;
            overflow: hidden;
        }

        th {
            text-align: center !important;
            background-color: #4C8EFF;
            color: #fff;
            font-weight: bold;
            padding: 12px;
            border: 1px solid #ddd;
        }

        th:first-child {
            border-top-left-radius: 10px;
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
        }

        table th:not(:first-child),
        table td:not(:first-child) {
            width: 25%;
        }

        td {
            padding: 12px;
            border: 1px solid #ddd;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        td:first-child {
            text-align: left;
        }

        td:not(:first-child) {
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:nth-child(odd) {
            background-color: #fff;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
        }

        .action-button {
            display: inline-block;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .reset-button {
            background-color: #ff4444;
        }

        .start-button {
            background-color: #4CAF50;
        }

        .reset-button:hover {
            background-color: #ff6666;
        }

        .start-button:hover {
            background-color: #45a049;
        }

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
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .modal-buttons {
            margin-top: 20px;
        }

        .modal-buttons button {
            margin: 0 10px;
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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

        .chart-container {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 5px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        canvas {
            width: 100% !important;
            max-height: 400px;
        }

        .chart-title {
            color: #4C8EFF;
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.2em;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .stats-box {
            background-color: #fff;
            border-radius: 10px;
            padding: 5px;
            text-align: center;
        }

        .stats-value {
            font-size: 16px;
            color: #4C8EFF;
            margin: 1px 0;
            font-weight: 700;
        }

        .stats-label {
            color: #888;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .output-box {
                padding: 10px;
                margin-bottom: 15px;
            }

            th,
            td {
                padding: 8px;
                font-size: 0.9em;
            }

            h2 {
                font-size: 1.2em;
                padding-left: 10px;
            }
        }

        /* Dark Mode */
        @media (prefers-color-scheme: dark) {
            body {
                background-color: transparent;
                color: #e0e0e0;
            }

            .output-box {
                background-color: #333;
                border: 1px solid #444;
                color: #e0e0e0;
            }

            .no-data {
                color: #bbb;
            }

            h2 {
                color: #fff;
            }

            table {
                background-color: #333;
            }

            th {
                background-color: #4C8EFF;
                color: #fff;
            }

            th,
            td {
                border: 1px solid #555;
            }

            td {
                color: #e0e0e0;
            }

            tr:nth-child(even) {
                background-color: #424242;
            }

            tr:nth-child(odd) {
                background-color: #333;
            }

            tr:hover {
                background-color: #575757;
            }

            .button-container {
                gap: 15px;
            }

            .action-button {
                transition: background-color 0.3s ease;
            }

            .reset-button {
                background-color: #ff4444;
            }

            .start-button {
                background-color: #4CAF50;
            }

            .reset-button:hover {
                background-color: #ff6666;
            }

            .start-button:hover {
                background-color: #45a049;
            }

            .modal-content {
                background-color: #333;
                color: #fff;
            }

            .modal-buttons button {
                background-color: #555;
                color: #fff;
            }

            .chart-container {
                background-color: #333;
                border: 1px solid #444;
            }

            .chart-title {
                color: #4C8EFF;
            }

            .stats-box {
                background-color: #333;
                border-radius: 10px;
                padding: 5px;
                text-align: center;
            }

            .stats-value {
                color: #4C8EFF;
            }

            .stats-label {
                color: #bbb;
            }

            /* Adjust the layout for mobile in dark mode */
            @media (max-width: 768px) {
                body {
                    padding: 10px;
                }

                .output-box {
                    padding: 10px;
                    margin-bottom: 15px;
                }

                th,
                td {
                    padding: 8px;
                    font-size: 0.9em;
                }

                h2 {
                    font-size: 1.2em;
                    padding-left: 10px;
                }
            }
        }

        /* Add styles for the tab buttons */
        .tab-container {
            display: grid;
            justify-content: space-around;
            margin-bottom: 10px;
            grid-template-columns: repeat(3, 1fr);
        }

        .tab-button {
            background-color: #ffffff00;
            border: none;
            padding: 5px 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            flex-grow: 1;
            text-align: center;
            border-radius: 10px 10px 10px 10px;
            font-size: 14px;
        }

        .tab-button.active {
            background-color: #ffffff00;
            border: 1px solid orange;
        }

        .tab-button:hover {
            background-color: #ffffff00;
        }

        .tabcontent {
            display: none;
        }

        .tabcontent.active {
            display: block;
        }

    </style>
</head>

<body>
    <div class="container">
        <!-- Stats Boxes -->
        <div class="stats-container">
            <div class="stats-box">
                <div class="stats-value"><?php echo htmlspecialchars($monthlyTotal); ?></div>
                <div class="stats-label">Bulan Ini</div>
            </div>
            <div class="stats-box">
                <div class="stats-value"><?php echo htmlspecialchars($todayTotal); ?></div>
                <div class="stats-label">Hari Ini</div>
            </div>
            <div class="stats-box">
                <div class="stats-value"><?php echo htmlspecialchars($yesterdayTotal); ?></div>
                <div class="stats-label">Kemarin</div>
            </div>
        </div>

        <!-- Tab Buttons -->
        <div class="chart-container">
            <div class="tab-container">
                <div class="tab-button active" onclick="showTab('Five-Minute Usage')">Five-Minute Usage</div>
                <div class="tab-button" onclick="showTab('Hourly Usage')">Hourly Usage</div>
                <div class="tab-button" onclick="showTab('Graph of The Last 7 Days')">Last 7 Days</div>
            </div>
            <div id="Five-Minute Usage" class="tabcontent" style="display: block;">
                <div class="chart-container">
                    <canvas id="fiveMinuteUsageChart"></canvas>
                </div>
            </div>
            <div id="Hourly Usage" class="tabcontent" style="display: none;">
                <div class="chart-container">
                    <canvas id="hourlyUsageChart"></canvas>
                </div>
            </div>
            <div id="Graph of The Last 7 Days" class="tabcontent" style="display: none;">
                <div class="chart-container">
                    <canvas id="usageChart"></canvas>
                </div>
            </div>
        </div>

            <!-- Display Filtered Usage Data -->
            <?php if (isset($filteredUsage)): ?>
                <h2>Usage from <?php echo htmlspecialchars($startDate); ?> to <?php echo htmlspecialchars($endDate); ?></h2>
                <?php if (empty($filteredUsage)): ?>
                    <div class="no-data">No usage data available for the selected date range</div>
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
                            <?php foreach ($filteredUsage as $date => $usage): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($date); ?></td>
                                    <td><?php echo htmlspecialchars($usage['download']); ?></td>
                                    <td><?php echo htmlspecialchars($usage['upload']); ?></td>
                                    <td><?php echo htmlspecialchars($usage['total']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td><strong style="font-size: 14px; color: #4C8EFF;">Total</strong></td>
                                <td><strong style="font-size: 14px; color: #4C8EFF;"><?php echo htmlspecialchars($totalDownloadFormatted); ?></strong></td>
                                <td><strong style="font-size: 14px; color: #4C8EFF;"><?php echo htmlspecialchars($totalUploadFormatted); ?></strong></td>
                                <td><strong style="font-size: 14px; color: #4C8EFF;"><?php echo htmlspecialchars($totalCombinedFormatted); ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                <?php endif; ?>
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
                    label: 'Download',
                    data: chartData.downloads,
                    borderColor: '#38b3ff',
                    backgroundColor: 'rgba(56, 179, 255, 0.1)',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Upload',
                    data: chartData.uploads,
                    borderColor: '#6eff8f',
                    backgroundColor: 'rgba(150, 255, 181, 0.3)',
                    fill: true,
                    tension: 0.4,
                    zIndex: 4
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
                            color: '#888'
                        },
                        ticks: {
                            color: '#888',
                            callback: function (value) {
                                if (value >= 1) {
                                    return value.toFixed(2) + ' GB';
                                } else if (value >= 0.001) {
                                    return (value * 1024).toFixed(2) + ' MB';
                                } else {
                                    return (value * 1024 * 1024).toFixed(2) + ' KB';
                                }
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: '#888'
                        },
                        ticks: {
                            color: '#888'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#888'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let value = context.parsed.y;
                                if (value >= 1) {
                                    return context.dataset.label + ': ' + value.toFixed(2) + ' GB';
                                } else if (value >= 0.001) {
                                    return context.dataset.label + ': ' + (value * 1024).toFixed(2) + ' MB';
                                } else {
                                    return context.dataset.label + ': ' + (value * 1024 * 1024).toFixed(2) + ' KB';
                                }
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
        window.onclick = function (event) {
            const resetModal = document.getElementById('resetModal');
            const startModal = document.getElementById('startModal');

            if (event.target === resetModal) {
                hideResetModal();
            }
            if (event.target === startModal) {
                hideStartModal();
            }
        }

        // Five-Minute Usage Chart
        const fiveMinuteCtx = document.getElementById('fiveMinuteUsageChart').getContext('2d');
        const fiveMinuteChartData = <?php echo json_encode(prepareFiveMinuteChartData($fiveMinuteData)); ?>;

        new Chart(fiveMinuteCtx, {
            type: 'line',
            data: {
                labels: fiveMinuteChartData.labels,
                datasets: [{
                    label: 'Download',
                    data: fiveMinuteChartData.downloads,
                    borderColor: '#38b3ff',
                    backgroundColor: 'rgba(56, 179, 255, 0.1)',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Upload',
                    data: fiveMinuteChartData.uploads,
                    borderColor: '#6eff8f',
                    backgroundColor: 'rgba(150, 255, 181, 0.3)',
                    fill: true,
                    tension: 0.4,
                    zIndex: 4
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
                            color: '#888'
                        },
                        ticks: {
                            color: '#888',
                            callback: function (value) {
                                if (value >= 1) {
                                    return value.toFixed(2) + ' TB';
                                } else if (value >= 0.001) {
                                    return (value * 1024).toFixed(2) + ' GB';
                                } else if (value >= 0.000001) {
                                    return (value * 1024 * 1024).toFixed(2) + ' MB';
                                } else {
                                    return (value * 1024 * 1024 * 1024).toFixed(2) + ' KB';
                                }
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: '#888'
                        },
                        ticks: {
                            color: '#888'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#888'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let value = context.parsed.y;
                                if (value >= 1) {
                                    return context.dataset.label + ': ' + value.toFixed(2) + ' TB';
                                } else if (value >= 0.001) {
                                    return context.dataset.label + ': ' + (value * 1024).toFixed(2) + ' GB';
                                } else if (value >= 0.000001) {
                                    return context.dataset.label + ': ' + (value * 1024 * 1024).toFixed(2) + ' MB';
                                } else {
                                    return context.dataset.label + ': ' + (value * 1024 * 1024 * 1024).toFixed(2) + ' KB';
                                }
                            }
                        }
                    }
                }
            }
        });

        // Hourly Usage Chart
        const hourlyCtx = document.getElementById('hourlyUsageChart').getContext('2d');
        const hourlyChartData = <?php echo json_encode($hourlyChartData); ?>;

        new Chart(hourlyCtx, {
            type: 'line',
            data: {
                labels: hourlyChartData.labels,
                datasets: [{
                    label: 'Download',
                    data: hourlyChartData.downloads,
                    borderColor: '#38b3ff',
                    backgroundColor: 'rgba(56, 179, 255, 0.1)',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Upload',
                    data: hourlyChartData.uploads,
                    borderColor: '#6eff8f',
                    backgroundColor: 'rgba(150, 255, 181, 0.3)',
                    fill: true,
                    tension: 0.4,
                    zIndex: 4
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
                            color: '#888'
                        },
                        ticks: {
                            color: '#888',
                            callback: function (value) {
                                if (value >= 1) {
                                    return value.toFixed(2) + ' TB';
                                } else if (value >= 0.001) {
                                    return (value * 1024).toFixed(2) + ' GB';
                                } else if (value >= 0.000001) {
                                    return (value * 1024 * 1024).toFixed(2) + ' MB';
                                } else {
                                    return (value * 1024 * 1024 * 1024).toFixed(2) + ' KB';
                                }
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: '#888'
                        },
                        ticks: {
                            color: '#888'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#888'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let value = context.parsed.y;
                                if (value >= 1) {
                                    return context.dataset.label + ': ' + value.toFixed(2) + ' TB';
                                } else if (value >= 0.001) {
                                    return context.dataset.label + ': ' + (value * 1024).toFixed(2) + ' GB';
                                } else if (value >= 0.000001) {
                                    return context.dataset.label + ': ' + (value * 1024 * 1024).toFixed(2) + ' MB';
                                } else {
                                    return context.dataset.label + ': ' + (value * 1024 * 1024 * 1024).toFixed(2) + ' KB';
                                }
                            }
                        }
                    }
                }
            }
        });

        // Tab functionality
        function showTab(tabName) {
            var i, tabcontent, tabbuttons;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tabbuttons = document.getElementsByClassName("tab-button");
            for (i = 0; i < tabbuttons.length; i++) {
                tabbuttons[i].className = tabbuttons[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            event.currentTarget.className += " active";
        }

        // Set default tab
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelector(".tab-button").click();
        });
    </script>
</body>

</html>