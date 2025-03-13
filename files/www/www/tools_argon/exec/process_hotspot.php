<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$inputFile = 'saved_input.json';

// Initialize variables
$newSsid = '';
$newPassword = '';

// Process POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newSsid = trim($_POST['ssid']);
    $newPassword = trim($_POST['password']);

    // Save input to file
    saveInput($newSsid, $newPassword);
}

// Load input from file if exists
if (file_exists($inputFile)) {
    $data = json_decode(file_get_contents($inputFile), true);
    $newSsid = $data['ssid'] ?? $newSsid;
    $newPassword = $data['password'] ?? $newPassword;
}

// Validate SSID and Password
if (validateInput($newSsid, $newPassword)) {
    changeHotspot($newSsid, $newPassword);
} else {
    echo "Invalid SSID or Password.";
}

// Function to save input
function saveInput($ssid, $password) {
    $data = ['ssid' => $ssid, 'password' => $password];
    file_put_contents('saved_input.json', json_encode($data));
}

// Function to validate input
function validateInput($ssid, $password) {
    return preg_match('/^[\w-]{1,32}$/', $ssid) && preg_match('/^[\w-]{8,64}$/', $password);
}

// Function to change hotspot
function changeHotspot($newSsid, $newPassword) {
    $newSsid = escapeshellarg($newSsid);
    $newPassword = escapeshellarg($newPassword);

    // Combined command for changing SSID and password
    $command = "
        su -c 'svc power stayon true && am start -n com.android.settings/.TetherSettings && input keyevent 66 && input keyevent 66 && input keyevent 20 && input keyevent 66 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input text $newSsid && input keyevent 20 && input keyevent 22 && input keyevent 66 && input keyevent 20 && input keyevent 20 && input keyevent 66 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input keyevent 67 && input text $newPassword && input keyevent 20 && input keyevent 22 && input keyevent 66 && am start -W -c android.intent.category.HOME -a android.intent.action.MAIN && am force-stop com.android.settings'
    ";

    exec($command . ' 2>&1', $output, $returnVar);
    logOutput($output);

    if ($returnVar === 0) {
        echo "SSID dan Password berhasil diubah.";
    } else {
        echo "Gagal mengubah SSID dan Password." . htmlspecialchars(implode("\n", $output)) . "";
    }

    // Add IP address
    $addIpCommand = "su -c 'ip addr add 192.168.43.1/24 dev wlan0'";
    exec($addIpCommand . ' 2>&1', $ipOutput, $ipReturnVar);
    logOutput($ipOutput);

    if ($ipReturnVar === 0) {
        echo "IP address berhasil ditambahkan: 192.168.43.1/24";
    } else {
        echo "Gagal menambahkan IP address. " . htmlspecialchars(implode("\n", $ipOutput)) . "";
    }
}

// Function to log output for debugging
function logOutput($output) {
    file_put_contents('command_output.log', implode("\n", $output) . "\n", FILE_APPEND);
}
?>
