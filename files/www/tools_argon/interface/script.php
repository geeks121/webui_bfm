<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $interface = $_POST['interface'];
    $action_type = $_POST['action_type'];

    // Default commands for each interface type
    $commands = [
        'wlan' => [
            'enable' => "service call tethering 4 null s16 random",
            'disable' => "su -c ifconfig wlan0 down",
            'status' => "ip a show"
        ],
        'rndis' => [
            'enable' => "su -c svc usb setFunctions rndis",
            'disable' => "su -c svc usb setFunctions mtp",
            'status' => "ip a show"
        ],
        'eth' => [
            'enable' => "su -c ifconfig eth0 up",
            'disable' => "su -c ifconfig eth0 down",
            'status' => "ip a show"
        ]
    ];

    // Handle saving custom commands
    if ($action_type == 'edit') {
        $enable_command = $_POST['enable_command'];
        $disable_command = $_POST['disable_command'];

        // Save custom commands to files
        if ($interface) {
            file_put_contents($interface . '_enable_command.txt', $enable_command);
            file_put_contents($interface . '_disable_command.txt', $disable_command);
        }
    }

    // Handle reset action (deleting the custom command files)
    if ($action_type == 'reset') {
        $enable_file = $interface . '_enable_command.txt';
        $disable_file = $interface . '_disable_command.txt';

        // Remove custom command files
        if (file_exists($enable_file)) {
            unlink($enable_file);
        }
        if (file_exists($disable_file)) {
            unlink($disable_file);
        }
    }

    // Handle other actions (status, enable, disable)
    if ($action_type == 'status') {
        $status = getInterfaceStatus($interface);
        echo json_encode($status);
    } else {
        // Only run the enable or disable command if that's the action requested
        $command = '';
        if ($action_type == 'enable') {
            // Use custom or default enable command
            $command = file_exists($interface . '_enable_command.txt') 
                        ? file_get_contents($interface . '_enable_command.txt') 
                        : $commands[$interface]['enable'];
        } elseif ($action_type == 'disable') {
            // Use custom or default disable command
            $command = file_exists($interface . '_disable_command.txt') 
                        ? file_get_contents($interface . '_disable_command.txt') 
                        : $commands[$interface]['disable'];
        }

        // Execute the command only if it's not empty
        if ($command) {
            shell_exec($command);
        }
    }
}

function getInterfaceStatus($interfacePrefix) {
    // Initialize result variables
    $status = 'Offline';
    $ipAddress = 'N/A';
    $rxBytes = 0;
    $txBytes = 0;
    $macAddress = 'N/A';

    // List all network interfaces and filter based on the prefix (e.g., wlan, eth, rndis)
    $interfaces = shell_exec("ip -o link show | awk -F': ' '{print $2}'");
    $interfaceNames = explode("\n", trim($interfaces));

    $interfaceFound = false;

    // Check for interfaces starting with the given prefix
    foreach ($interfaceNames as $interface) {
        if (strpos($interface, $interfacePrefix) !== false) {
            $interfaceFound = true;
            break;
        }
    }

    // If a valid interface with the given prefix is found, get its details
    if ($interfaceFound) {
        $command = "ip a show {$interface}";
        $statusOutput = shell_exec($command);

        if (strpos($statusOutput, 'UP') !== false) {
            $status = 'Active';
        }

        // Get the IP address associated with the interface
        $ipCommand = "ip addr show {$interface} | grep 'inet ' | awk '{print $2}'";
        $ipAddress = trim(shell_exec($ipCommand));

        // Get the RX and TX byte counts
        $rxTxCommand = "cat /sys/class/net/{$interface}/statistics/rx_bytes && echo ' ' && cat /sys/class/net/{$interface}/statistics/tx_bytes";
        $rxTxData = shell_exec($rxTxCommand);
        if ($rxTxData) {
            $rxTx = explode(" ", trim($rxTxData));
            $rxBytes = isset($rxTx[0]) ? $rxTx[0] : 0;
            $txBytes = isset($rxTx[1]) ? $rxTx[1] : 0;
        }

        // Convert bytes to GB
        $bytesToGB = 1073741824; // 1 GB = 1,073,741,824 bytes
        $rxGB = number_format($rxBytes / $bytesToGB, 2);
        $txGB = number_format($txBytes / $bytesToGB, 2);

        // Get the MAC address
        $macCommand = "cat /sys/class/net/{$interface}/address";
        $macAddress = trim(shell_exec($macCommand));
    }

    return [
        'status' => $status,
        'mac' => $macAddress,
        'rx' => $rxGB . ' GB',
        'tx' => $txGB . ' GB',
        'ip' => $ipAddress
    ];
}
