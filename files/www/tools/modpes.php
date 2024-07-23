<!DOCTYPE html>
<html>
<head>
    <title>Airplane BOX UI</title>
    <!-- Materialize CSS -->
    <link rel="stylesheet" href="../auth/css/materialize.min.css" />
    <!-- Materialize Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        body {
            background-color: #121212; /* Dark mode background color */
            color: #ffffff; /* White text color */
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            margin: 0; /* Remove default body margin */
        }
        .container {
            margin-top: 20px;
        }
        .card {
            background-color: #1e1e1e; /* Dark mode card background color */
            color: #ffffff; /* White text color */
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            margin: 0; /* Remove default body margin */
        }
        .btn {
            margin: 10px;
            width: 100%; /* Make button width responsive */
            max-width: 300px; /* Max width for larger screens */
            text-align: center; /* Center text inside the button */
            display: flex; /* Use flex to align text within the button */
            justify-content: center; /* Center text horizontally inside the button */
            align-items: center; /* Center text vertically inside the button */
        }
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .checkbox-group label {
            width: auto; /* Allow checkbox group labels to have automatic width */
            color: #ffffff; /* White text color */
        }
        @media (max-width: 600px) {
            .checkbox-group label {
                width: 50%;
            }
        }
        @media (max-width: 400px) {
            .checkbox-group label {
                width: 100%;
            }
        }
        .input-field select {
            background-color: #333333; /* Dark mode background color for dropdown */
            color: #ffffff; /* White text color for dropdown */
        }
        .input-field select option {
            color: #000000; /* Black text color for dropdown options */
        }
        .centered {
            text-align: center; /* Center text */
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Initialize variables for pre-checking
        $checked_wifi = $checked_cell = $checked_bluetooth = $checked_nfc = $checked_wimax = false;
        $network_choice = 'hotspot'; // Default value
        $airplane_mode_enabled = false;

        // Detect current state of radio settings
        $current_radios = shell_exec("su -c 'settings get global airplane_mode_radios'");
        $current_radios = explode(',', trim($current_radios));
        
        //$checked_wifi = in_array('wifi', $current_radios);
        $checked_cell = in_array('cell', $current_radios);
        $checked_bluetooth = in_array('bluetooth', $current_radios);
        //$checked_nfc = in_array('nfc', $current_radios);
        //$checked_wimax = in_array('wimax', $current_radios);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['action']) && $_POST['action'] === 'disable_airplane_mode') {
                // Disable airplane mode
                shell_exec("su -c 'settings put global airplane_mode_on 0'");
                shell_exec("su -c 'am broadcast -a android.intent.action.AIRPLANE_MODE --ez state false'");

                // Ensure radios are still enabled based on previous settings
                $enabled_radios = isset($_POST['enabled_radios']) ? json_decode($_POST['enabled_radios'], true) : [];
                $radios_str = implode(',', $enabled_radios);
                shell_exec("su -c 'settings put global airplane_mode_radios \"$radios_str\"'");

                echo "<p class='green-text'>Airplane mode disabled.</p>";
                $airplane_mode_enabled = false;
            } elseif (isset($_POST['action']) && $_POST['action'] === 'enable_airplane_mode') {
                $enabled_radios = [];

                // Collect selected radios
               // if (isset($_POST['wifi'])) {
               //     $enabled_radios[] = 'wifi';
               //     $checked_wifi = true;
               // }
                if (isset($_POST['cell'])) {
                    $enabled_radios[] = 'cell';
                    $checked_cell = true;
                }
                if (isset($_POST['bluetooth'])) {
                    $enabled_radios[] = 'bluetooth';
                    $checked_bluetooth = true;
                }
               // if (isset($_POST['nfc'])) {
               //     $enabled_radios[] = 'nfc';
               //     $checked_nfc = true;
               // }
               // if (isset($_POST['wimax'])) {
                //    $enabled_radios[] = 'wimax';
                //    $checked_wimax = true;
                //}

                $radios_str = implode(',', $enabled_radios);

                // Collect choice for WiFi or Hotspot
                $network_choice = $_POST['network_choice'] ?? 'hotspot'; // Default to hotspot

                // Whitelist hardware radios to stay on
                shell_exec("su -c 'settings put global airplane_mode_radios \"$radios_str\"'");

                // Enable airplane mode
                shell_exec("su -c 'settings put global airplane_mode_on 1'");
                shell_exec("su -c 'am broadcast -a android.intent.action.AIRPLANE_MODE --ez state true'");

                // Handle network choice
                if ($network_choice === 'wifi') {
                    // Enable WiFi only
                    shell_exec("su -c 'svc wifi enable'");
                    shell_exec("su -c 'svc wifi sethotspotenabled false'"); // Disable hotspot
                } elseif ($network_choice === 'hotspot') {
                    // Enable hotspot only
                    shell_exec("su -c 'svc wifi sethotspotenabled true'");
                    shell_exec("su -c 'svc wifi disable'"); // set enable to Ensure WiFi is on
                }

                echo "<p class='green-text'>Airplane mode enabled with whitelisted radios. Network choice: $network_choice.</p>";

                // Automatically turn off airplane mode after 5 seconds
                echo "<script>
                        setTimeout(function() {
                            var xhttp = new XMLHttpRequest();
                            xhttp.open('POST', '', true);
                            xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                            xhttp.send('action=disable_airplane_mode&enabled_radios=" . urlencode(json_encode($enabled_radios)) . "');
                        }, 5000);
                      </script>";
                
                $airplane_mode_enabled = true;
            } elseif (isset($_POST['action']) && $_POST['action'] === 'update_radios') {
                // Update individual radios based on the user’s choice
                if (isset($_POST['bluetooth_control'])) {
                    shell_exec("su -c 'svc bluetooth enable'");
                } else {
                    shell_exec("su -c 'svc bluetooth disable'");
                }

                if (isset($_POST['wifi_control'])) {
                    shell_exec("su -c 'svc wifi enable'");
                } else {
                    shell_exec("su -c 'svc wifi disable'");
                }

               // if (isset($_POST['nfc_control'])) {
               //     shell_exec("su -c 'nfc enable'");
               // } else {
                //    shell_exec("su -c 'nfc disable'");
                //}

                echo "<p class='green-text'>Radio settings updated.</p>";
            }
        }
        ?>

        <h1 class="centered">Airplane BOX UI</h1>
        <div class="card">
            <div class="card-content">
                <form action="" method="post">
                    <h5>Select Radios to Keep On while airplane mode on:</h5>
                    <div class="checkbox-group">
                        <!--<label>
                            <input type="checkbox" name="wifi" <?php echo $checked_wifi ? 'checked' : ''; ?> />
                            <span>WiFi</span>
                        </label>-->
                        <label>
                            <input type="checkbox" name="cell" <?php echo $checked_cell ? 'checked' : ''; ?> />
                            <span>Cell</span>
                        </label>
                        <label>
                            <input type="checkbox" name="bluetooth" <?php echo $checked_bluetooth ? 'checked' : ''; ?> />
                            <span>Bluetooth</span>
                        </label>
                        <!--<label>
                            <input type="checkbox" name="nfc" <?php echo $checked_nfc ? 'checked' : ''; ?> />
                            <span>NFC</span>
                        </label>-->
                        <!--<label>
                            <input type="checkbox" name="wimax" <?php echo $checked_wimax ? 'checked' : ''; ?> />
                            <span>WiMAX</span>
                        </label>-->
                    </div>

                    <h5>Keep Hotspot Network Enable ?:</h5>
                    <div class="checkbox-group">
                        <!--<label>
                            <input type="checkbox" name="network_choice" value="wifi" <?php echo $network_choice === 'wifi' ? 'checked' : ''; ?> />
                            <span>WiFi Only</span>
                        </label>-->
                        <label>
                            <input type="checkbox" name="network_choice" value="hotspot" <?php echo $network_choice === 'hotspot' ? 'checked' : ''; ?> />
                            <span>Hotspot Only</span>
                        </label>
                    </div>

                    <div class="centered">
                        <button type="submit" name="action" value="enable_airplane_mode" class="btn green">
                            <i class="material-icons left">flight_takeoff</i>Enable Airplane Mode
                        </button>
                        <button type="submit" name="action" value="disable_airplane_mode" class="btn red">
                            <i class="material-icons left">flight_land</i>Disable Airplane Mode
                        </button>
                    </div>
                </form>
                <form action="" method="post">
                    <h5>Update Radios Individually:</h5>
                    <div class="checkbox-group">
                        <label>
                            <input type="checkbox" name="bluetooth_control" <?php echo shell_exec("su -c 'svc bluetooth status'") === 'enabled' ? 'checked' : ''; ?> />
                            <span>Bluetooth</span>
                        </label>
                        <label>
                            <input type="checkbox" name="wifi_control" <?php echo shell_exec("su -c 'svc wifi status'") === 'enabled' ? 'checked' : ''; ?> />
                            <span>WiFi</span>
                        </label>
                       <!-- <label>
                            <input type="checkbox" name="nfc_control" <?php echo shell_exec("su -c 'svc nfc status'") === 'enabled' ? 'checked' : ''; ?> />
                            <span>NFC</span>
                        </label>-->
                    </div>
                    <div class="centered">
                        <button type="submit" name="action" value="update_radios" class="btn blue">
                            <i class="material-icons left">settings</i>Update Radios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Materialize JavaScript -->
    <script src="../auth/js/materialize.min.js"></script>
</body>
</html>
