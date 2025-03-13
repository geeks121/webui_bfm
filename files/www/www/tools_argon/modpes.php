<!DOCTYPE html>
<html lang="id">
<head>
    <title>Airplane Argon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="../tools/ocgen/data/fontawesome6/css/all.css" />
    <style>
* {
  -webkit-user-select: none; /* Safari */
  -moz-user-select: none; /* Firefox */
  -ms-user-select: none; /* Internet Explorer/Edge */
  user-select: none; /* Standard */
  -webkit-tap-highlight-color: transparent; /* Hilangkan efek tap di mobile */
}
.mdi--airplane-landing {
  display: inline-block;
  margin-top: -4px;
  width: 20px;
  height: 20px;
  --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23000' d='M2.5 19h19v2h-19zm7.18-5.73l4.35 1.16l5.31 1.42c.8.21 1.62-.26 1.84-1.06c.21-.79-.26-1.62-1.06-1.84l-5.31-1.42l-2.76-9.03l-1.93-.5v8.28L5.15 8.95l-.93-2.32l-1.45-.39v5.17l1.6.43z' stroke-width='0.3' stroke='%23000'/%3E%3C/svg%3E");
  background-color: currentColor;
  -webkit-mask-image: var(--svg);
  mask-image: var(--svg);
  -webkit-mask-repeat: no-repeat;
  mask-repeat: no-repeat;
  -webkit-mask-size: 100% 100%;
  mask-size: 100% 100%;
}

.mdi--airplane-takeoff {
  display: inline-block;
  margin-top: -4px;
  width: 20px;
  height: 20px;
  --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23000' d='M2.5 19h19v2h-19zm19.57-9.36c-.21-.8-1.04-1.28-1.84-1.06L14.92 10L8 3.57l-1.91.51l4.14 7.17l-4.97 1.33l-1.97-1.54l-1.45.39l1.82 3.16l.77 1.33l1.6-.42l5.31-1.43l4.35-1.16L21 11.5c.81-.24 1.28-1.06 1.07-1.86' stroke-width='0.3' stroke='%23000'/%3E%3C/svg%3E");
  background-color: currentColor;
  -webkit-mask-image: var(--svg);
  mask-image: var(--svg);
  -webkit-mask-repeat: no-repeat;
  mask-repeat: no-repeat;
  -webkit-mask-size: 100% 100%;
  mask-size: 100% 100%;
}

.material-symbols--settings-rounded {
  display: inline-block;
  margin-top: -2px;
  width: 18px;
  height: 18px;
  --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23000' d='M10.825 22q-.675 0-1.162-.45t-.588-1.1L8.85 18.8q-.325-.125-.612-.3t-.563-.375l-1.55.65q-.625.275-1.25.05t-.975-.8l-1.175-2.05q-.35-.575-.2-1.225t.675-1.075l1.325-1Q4.5 12.5 4.5 12.337v-.675q0-.162.025-.337l-1.325-1Q2.675 9.9 2.525 9.25t.2-1.225L3.9 5.975q.35-.575.975-.8t1.25.05l1.55.65q.275-.2.575-.375t.6-.3l.225-1.65q.1-.65.588-1.1T10.825 2h2.35q.675 0 1.163.45t.587 1.1l.225 1.65q.325.125.613.3t.562.375l1.55-.65q.625-.275 1.25-.05t.975.8l1.175 2.05q.35.575.2 1.225t-.675 1.075l-1.325 1q.025.175.025.338v.674q0 .163-.05.338l1.325 1q.525.425.675 1.075t-.2 1.225l-1.2 2.05q-.35.575-.975.8t-1.25-.05l-1.5-.65q-.275.2-.575.375t-.6.3l-.225 1.65q-.1.65-.587 1.1t-1.163.45zm1.225-6.5q1.45 0 2.475-1.025T15.55 12t-1.025-2.475T12.05 8.5q-1.475 0-2.488 1.025T8.55 12t1.013 2.475T12.05 15.5' stroke-width='0.3' stroke='%23000'/%3E%3C/svg%3E");
  background-color: currentColor;
  -webkit-mask-image: var(--svg);
  mask-image: var(--svg);
  -webkit-mask-repeat: no-repeat;
  mask-repeat: no-repeat;
  -webkit-mask-size: 100% 100%;
  mask-size: 100% 100%;
}
        :root {
            --bg-color: #ffffff;
            --text-color: #333333;
            --card-bg: #f5f5f5;
            --hover-color: #e0e0e0;
        }
        body {
            visibility: hidden;
            display: flex;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body[data-theme="dark"] {
            --bg-color: #121212;
            --text-color: #ffffff;
            --card-bg: #1e1e1e;
            --hover-color: #2d2d2d;
            --checkbox-wrapper-span-text-color: #ffffff;
        }

        body[data-theme="light"] {
            background-color: #f4f4f4;
            color: var(--text-color);
        }

        .container {
            padding: 0px;
            width: 95%;
            margin: 0 auto;
        }

        .card {
            background-color: var(--card-bg);
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .title {
            font-size: 2.4rem;
            text-align: center;
            margin: 20px 0 40px;
            font-weight: 500;
            color: var(--text-color);
        }

        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 2;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 12px;
            background-color: var(--hover-color);
            cursor: pointer;
            margin: 10px 0;
        }

        .checkbox {
          position: relative;
          height: 30px;
          width: 80px;
          background: #2e394d;
          border-radius: 30px;
        }

        .checkbox-input {
          position: absolute;
          height: 100%;
          width: 100%;
          outline: none;
          z-index: 1;
          -webkit-appearance: none;
        }

        .checkbox-icons::before {
          position: absolute;
          content: "\f00d";
          font-family: "Font Awesome 5 Free";
          font-weight: 900;
          color: white;
          height: 27px;
          width: 27px;
          background: #c34a4a;
          border-radius: 50%;
          left: 2px;
          top: 50%;
          transform: translateY(-50%);
          font-size: 17px;
          display: flex;
          align-items: center;
          justify-content: center;
          transition: 0.8s;
        }

        .checkbox-input:checked + label .checkbox-icons::before {
          background: #8bc34a;
          transform: translateY(-50%) rotate(360deg);
          left: calc(100% - 29px);
          content: "\f00c";
        }

        .checkbox-wrapper span {
            font-weight: 550;
            display: inline-block;
            margin-bottom: 0px;
            margin-left: 10px;
            color: var(--checkbox-wrapper-span-text-color, #000); /* Menambahkan fallback jika var tidak ada */
        }

        .btn {
            margin-top: 30px !important;
            margin: 15px;
            border: none;
            border-radius: 12px;
            text-transform: none;
            font-weight: 500;
            font-size: 1.1rem;
            height: 48px;
            line-height: 48px;
            padding: 0 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn.green {
            background-color: #4CAF50; /* Hijau */
            color: white;
        }

        .btn.red {
            background-color: #F44336; /* Merah */
            margin-top: 0px !important;
            color: white;
        }
        
        .btn.blue {
            background-color: #2196F3; /* Biru */
            color: white;
        }

        .btn i {
            font-size: 20px;
        }

        .section-title {
            font-size: 1.8rem;
            margin: 30px 0 20px;
            font-weight: 500;
            color: var(--text-color);
        }

        .success-message {
            color: #4CAF50;
            text-align: center;
            margin: 15px 0;
            padding: 15px;
            border-radius: 12px;
            background-color: rgba(76, 175, 80, 0.1);
            font-size: 1.1rem;
        }

        @media (max-width: 600px) {
            .title {
                font-size: 2rem;
                margin: 15px 0 30px;
            }
            
            .btn {
                width: 100%;
                margin: 10px 0;
                height: 44px;
                line-height: 44px;
                font-size: 1rem;
            }

            .checkbox-group {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .section-title {
                font-size: 1.5rem;
                margin: 25px 0 15px;
            }

            .card {
                padding: 20px;
                margin: 20px 0;
            }

            .checkbox-wrapper {
                padding: 12px;
            }

            [type="checkbox"] + span {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body <?php echo isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark' ? 'data-theme="dark"' : ''; ?>>

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

        <div class="card">
            <form action="" method="post">
               <h2 class="section-title">Select radios to keep on while airplane mode on :</h2>
                <div class="checkbox-group">
                    <div class="checkbox-wrapper">
                        <div class="checkbox">
                            <input type="checkbox" id="cell-checkbox" class="checkbox-input" name="cell" <?php echo $checked_cell ? 'checked' : ''; ?> />
                            <label for="cell-checkbox">
                                <div class="checkbox-icons"></div>
                            </label>
                        </div>
                        <span>Cell</span>
                    </div>
                </div>
      
                <div class="checkbox-group">
                    <div class="checkbox-wrapper">
                        <div class="checkbox">
                            <input type="checkbox" id="bluetooth-checkbox" class="checkbox-input" name="bluetooth" <?php echo $checked_bluetooth ? 'checked' : ''; ?> />
                            <label for="bluetooth-checkbox">
                                <div class="checkbox-icons"></div>
                            </label>
                        </div>
                        <span>Bluetooth</span>
                    </div>
                </div>

                <h2 class="section-title">Keep hotspot network enable? :</h2>
                <div class="checkbox-group">
                    <div class="checkbox-wrapper">
                        <div class="checkbox">
                            <input type="checkbox" id="hotspot-checkbox" class="checkbox-input" name="hotspot" <?php echo $network_choice === 'hotspot' ? 'checked' : ''; ?> />
                            <label for="hotspot-checkbox">
                                <div class="checkbox-icons"></div>
                            </label>
                        </div>
                        <span>Hotspot Only</span>
                    </div>
                </div>

                <div class="center-align">
                      <button type="submit" name="action" value="enable_airplane_mode" class="btn green">
                          <i class="mdi--airplane-takeoff"></i>
                          Enable Airplane Mode
                      </button>
                      <button type="submit" name="action" value="disable_airplane_mode" class="btn red">
                          <i class="mdi--airplane-landing"></i>
                          Disable Airplane Mode
                      </button>
                </div>
            </form>
        </div>

        <div class="card">
            <form action="" method="post">
               <h2 class="section-title">Update radios individually :</h2>
                <div class="checkbox-group">
                    <div class="checkbox-wrapper">
                        <div class="checkbox">
                            <input type="checkbox" id="bluetooth_control-checkbox" class="checkbox-input" name="bluetooth_control" <?php echo shell_exec("su -c 'svc bluetooth status'") === 'enabled' ? 'checked' : ''; ?> />
                            <label for="bluetooth_control-checkbox">
                                <div class="checkbox-icons"></div>
                            </label>
                        </div>
                        <span>Bluetooth</span>
                    </div>
                </div>
                    
                    
                <div class="checkbox-group">
                    <div class="checkbox-wrapper">
                        <div class="checkbox">
                            <input type="checkbox" id="wifi_control-checkbox" class="checkbox-input" name="wifi_control" <?php echo shell_exec("su -c 'svc wifi status'") === 'enabled' ? 'checked' : ''; ?> />
                            <label for="wifi_control-checkbox">
                                <div class="checkbox-icons"></div>
                            </label>
                        </div>
                        <span>WiFi</span>
                    </div>
                </div>
                    
                <div class="center-align">
                    <button type="submit" name="action" value="update_radios" class="btn blue">
                        <i class="material-symbols--settings-rounded"></i>
                        Update Radios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Menentukan tema berdasarkan preferensi pengguna
        const userPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        // Setel tema awal
        if (userPrefersDark) {
            document.body.setAttribute('data-theme', 'dark');
        } else {
            document.body.setAttribute('data-theme', 'light');
        }

        // Setelah tema diterapkan, tampilkan konten
        document.body.style.visibility = 'visible';

        // Menangani perubahan preferensi tema setelahnya
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (e.matches) {
                document.body.setAttribute('data-theme', 'dark');
            } else {
                document.body.setAttribute('data-theme', 'light');
            }
        });
    </script>
</body>
</html>