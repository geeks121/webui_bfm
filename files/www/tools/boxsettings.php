<?php
// Function to update settings.ini with new values
function update_settings_ini($settings, $new_settings) {
    // Read the existing settings.ini file into an array
    $lines = file($settings);

    if ($lines === false) {
        // Handle file read error
        return false;
    }

    // Loop through each line to find and update the relevant settings
    foreach ($new_settings as $key => $value) {
        $found = false;
        // Check if the key exists in settings.ini and update it
        foreach ($lines as $index => $line) {
            if (strpos($line, $key . '=') !== false) {
                $lines[$index] = $key . '="' . $value . '"' . PHP_EOL;
                $found = true;
                break;
            }
        }
        // If the key does not exist, add it
        if (!$found) {
            $lines[] = $key . '="' . $value . '"' . PHP_EOL;
        }
    }

    // Write the updated lines back to the settings.ini file
    if (file_put_contents($settings, implode('', $lines)) !== false) {
        return true; // Successfully updated settings.ini
    } else {
        return false; // Failed to update settings.ini
    }
}

// Function to generate options for network_mode dropdown
function generate_network_mode_options($current_value) {
    $options = array(
        'redirect' => 'Redirect: tcp + udp[direct]',
        'tproxy' => 'TProxy: tcp + udp',
        'mixed' => 'Mixed: redirect[tcp] + tun[udp]',
        'enhance' => 'Enhance: redirect[tcp] + tproxy[udp]',
        'tun' => 'Tun: tcp + udp (auto-route)'
    );

    foreach ($options as $value => $label) {
        $selected = ($current_value == $value) ? 'selected' : '';
        echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
    }
}

// Sample current settings (replace with actual logic to fetch from settings.ini)
$current_settings = array(
    'network_mode' => 'tproxy',
    'bin_name' => 'clash',
    'port_detect' => 'false',
    'tproxy_port' => '9898',
    'redir_port' => '9797',
    'xclash_option' => 'mihomo',
    'ipv6' => 'false',
    'proxy_mode' => 'blacklist',
    'cgroup_memcg' => 'false',
    'memcg_limit' => '25M',
    'cgroup_cpuset' => 'false',
    'cgroup_blkio' => 'false',
    'run_crontab' => 'false',
    'update_geo' => 'false',
    'renew' => 'false',
    'update_subscription' => 'false',
    'subscription_url_clash' => 'https://nodefree.org/dy/$(date +%Y)/$(date +%m)/$(date +%Y%m%d).yaml',
    'name_clash_config' => 'config.yaml'
);

// Check if the form is submitted and process the update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_settings'])) {
    // Retrieve and sanitize the new settings values
    $new_settings = array(
        'network_mode' => isset($_POST['network_mode']) ? htmlspecialchars($_POST['network_mode']) : '',
        'bin_name' => isset($_POST['bin_name']) ? htmlspecialchars($_POST['bin_name']) : '',
        'port_detect' => isset($_POST['port_detect']) ? htmlspecialchars($_POST['port_detect']) : 'false',
        'tproxy_port' => isset($_POST['tproxy_port']) ? htmlspecialchars($_POST['tproxy_port']) : '',
        'redir_port' => isset($_POST['redir_port']) ? htmlspecialchars($_POST['redir_port']) : '',
        'xclash_option' => isset($_POST['xclash_option']) ? htmlspecialchars($_POST['xclash_option']) : '',
        'ipv6' => isset($_POST['ipv6']) ? htmlspecialchars($_POST['ipv6']) : 'false',
        'proxy_mode' => isset($_POST['proxy_mode']) ? htmlspecialchars($_POST['proxy_mode']) : '',
        'cgroup_memcg' => isset($_POST['cgroup_memcg']) ? htmlspecialchars($_POST['cgroup_memcg']) : 'false',
        'memcg_limit' => isset($_POST['memcg_limit']) ? htmlspecialchars($_POST['memcg_limit']) : '',
        'cgroup_cpuset' => isset($_POST['cgroup_cpuset']) ? htmlspecialchars($_POST['cgroup_cpuset']) : 'false',
        'cgroup_blkio' => isset($_POST['cgroup_blkio']) ? htmlspecialchars($_POST['cgroup_blkio']) : 'false',
        'run_crontab' => isset($_POST['run_crontab']) ? htmlspecialchars($_POST['run_crontab']) : 'false',
        'update_geo' => isset($_POST['update_geo']) ? htmlspecialchars($_POST['update_geo']) : 'false',
        'renew' => isset($_POST['renew']) ? htmlspecialchars($_POST['renew']) : 'false',
        'update_subscription' => isset($_POST['update_subscription']) ? htmlspecialchars($_POST['update_subscription']) : 'false',
        'subscription_url_clash' => isset($_POST['subscription_url_clash']) ? htmlspecialchars($_POST['subscription_url_clash']) : '',
        'name_clash_config' => isset($_POST['name_clash_config']) ? htmlspecialchars($_POST['name_clash_config']) : ''
    );

    // Update settings.ini with the new values
    $updated = update_settings_ini('/data/adb/box/settings.ini', $new_settings);

    if ($updated) {
        $message = 'Settings updated successfully!';
    } else {
        $message = 'Error updating settings. Please try again.';
    }

    // Update $current_settings with the new values after update
    $current_settings = array_merge($current_settings, $new_settings);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOX Settings</title>
    <!-- Materialize CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Custom CSS for Dark Mode -->
    <style>
        body {
            background-color: #263238; /* Dark grey background */
            color: #FFFFFF; /* White text */
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        .card {
            background-color: #37474F; /* Darker background for card */
            padding: 20px;
            margin-bottom: 20px;
        }
        .card-title {
            color: #FFFFFF; /* White text */
        }
        .input-field input[type="text"],
        .input-field input[type="number"],
        .input-field select,
        .input-field label {
            color: #FFFFFF; /* White text */
        }
        .input-field input[type="text"]:focus,
        .input-field input[type="number"]:focus,
        .input-field select:focus {
            border-bottom: 1px solid #4CAF50; /* Green border on focus */
            box-shadow: 0 1px 0 0 #4CAF50; /* Green shadow on focus */
        }
        .input-field select {
            background-color: #37474F; /* Darker background for select */
        }
        .input-field .select-wrapper input.select-dropdown {
            color: #FFFFFF; /* White text for select dropdown */
        }
        .input-field .select-wrapper ul.dropdown-content {
            background-color: #37474F; /* Darker background for dropdown content */
        }
        .input-field .select-wrapper ul.dropdown-content li > span {
            color: #FFFFFF; /* White text for dropdown items */
        }
        .btn {
            background-color: #4CAF50; /* Green Material Design color */
        }
        .btn:hover {
            background-color: #388E3C; /* Darker green on hover */
        }
        .btn-margin {
            margin-right: 20px; /* Adjust as needed */
            margin-bottom: 20px; /* Optional: Adds vertical spacing */
        }
        .button-container {
    text-align: center;
    margin-top: 10px; /* Adjust as needed for vertical spacing */
}
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h3 class="card-title">BOX Settings</h3>

            <?php if (isset($message)) : ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="input-field">
                    <select id="network_mode" name="network_mode">
                        <?php generate_network_mode_options($current_settings['network_mode']); ?>
                    </select>
                    <label for="network_mode">Network Mode</label>
                </div>

                <div class="input-field">
                    <select id="bin_name" name="bin_name">
                        <option value="clash" <?php echo ($current_settings['bin_name'] == 'clash') ? 'selected' : ''; ?>>Clash</option>
                        <option value="clash_meta" <?php echo ($current_settings['bin_name'] == 'clash_meta') ? 'selected' : ''; ?>>Clash Meta</option>
                    </select>
                    <label for="bin_name">BIN Name</label>
                </div>

                <div class="input-field">
                    <select id="xclash_option" name="xclash_option">
                        <option value="mihomo" <?php echo ($current_settings['xclash_option'] == 'mihomo') ? 'selected' : ''; ?>>MiHomo</option>
                        <option value="openclash" <?php echo ($current_settings['xclash_option'] == 'openclash') ? 'selected' : ''; ?>>OpenClash</option>
                    </select>
                    <label for="xclash_option">XClash Option</label>
                </div>

                <div class="input-field">
                    <select id="proxy_mode" name="proxy_mode">
                        <option value="blacklist" <?php echo ($current_settings['proxy_mode'] == 'blacklist') ? 'selected' : ''; ?>>Blacklist</option>
                        <option value="whitelist" <?php echo ($current_settings['proxy_mode'] == 'whitelist') ? 'selected' : ''; ?>>Whitelist</option>
                    </select>
                    <label for="proxy_mode">Proxy Mode</label>
                </div>

                <div class="input-field">
                    <select id="port_detect" name="port_detect">
                        <option value="true" <?php echo ($current_settings['port_detect'] == 'true') ? 'selected' : ''; ?>>True</option>
                        <option value="false" <?php echo ($current_settings['port_detect'] == 'false') ? 'selected' : ''; ?>>False</option>
                    </select>
                    <label for="port_detect">Port Detect</label>
                </div>

                <div class="input-field">
                    <input type="number" id="tproxy_port" name="tproxy_port" value="<?php echo $current_settings['tproxy_port']; ?>">
                    <label for="tproxy_port">TProxy Port</label>
                </div>

                <div class="input-field">
                    <input type="number" id="redir_port" name="redir_port" value="<?php echo $current_settings['redir_port']; ?>">
                    <label for="redir_port">Redir Port</label>
                </div>

                <div class="input-field">
                    <select id="ipv6" name="ipv6">
                        <option value="true" <?php echo ($current_settings['ipv6'] == 'true') ? 'selected' : ''; ?>>True</option>
                        <option value="false" <?php echo ($current_settings['ipv6'] == 'false') ? 'selected' : ''; ?>>False</option>
                    </select>
                    <label for="ipv6">IPv6</label>
                </div>



                <div class="input-field">
                    <select id="cgroup_memcg" name="cgroup_memcg">
                        <option value="true" <?php echo ($current_settings['cgroup_memcg'] == 'true') ? 'selected' : ''; ?>>True</option>
                        <option value="false" <?php echo ($current_settings['cgroup_memcg'] == 'false') ? 'selected' : ''; ?>>False</option>
                    </select>
                    <label for="cgroup_memcg">CGroup MemCG</label>
                </div>

                <div class="input-field">
                    <input type="text" id="memcg_limit" name="memcg_limit" value="<?php echo $current_settings['memcg_limit']; ?>">
                    <label for="memcg_limit">MemCG Limit</label>
                </div>

                <div class="input-field">
                    <select id="cgroup_cpuset" name="cgroup_cpuset">
                        <option value="true" <?php echo ($current_settings['cgroup_cpuset'] == 'true') ? 'selected' : ''; ?>>True</option>
                        <option value="false" <?php echo ($current_settings['cgroup_cpuset'] == 'false') ? 'selected' : ''; ?>>False</option>
                    </select>
                    <label for="cgroup_cpuset">CGroup Cpuset</label>
                </div>

                <div class="input-field">
                    <select id="cgroup_blkio" name="cgroup_blkio">
                        <option value="true" <?php echo ($current_settings['cgroup_blkio'] == 'true') ? 'selected' : ''; ?>>True</option>
                        <option value="false" <?php echo ($current_settings['cgroup_blkio'] == 'false') ? 'selected' : ''; ?>>False</option>
                    </select>
                    <label for="cgroup_blkio">CGroup BlkIO</label>
                </div>

                <div class="input-field">
                    <select id="run_crontab" name="run_crontab">
                        <option value="true" <?php echo ($current_settings['run_crontab'] == 'true') ? 'selected' : ''; ?>>True</option>
                        <option value="false" <?php echo ($current_settings['run_crontab'] == 'false') ? 'selected' : ''; ?>>False</option>
                    </select>
                    <label for="run_crontab">Run Crontab</label>
                </div>

                <div class="input-field">
                    <select id="update_geo" name="update_geo">
                        <option value="true" <?php echo ($current_settings['update_geo'] == 'true') ? 'selected' : ''; ?>>True</option>
                        <option value="false" <?php echo ($current_settings['update_geo'] == 'false') ? 'selected' : ''; ?>>False</option>
                    </select>
                    <label for="update_geo">Update Geo</label>
                </div>

                <div class="input-field">
                    <select id="renew" name="renew">
                        <option value="true" <?php echo ($current_settings['renew'] == 'true') ? 'selected' : ''; ?>>True</option>
                        <option value="false" <?php echo ($current_settings['renew'] == 'false') ? 'selected' : ''; ?>>False</option>
                    </select>
                    <label for="renew">Renew</label>
                </div>

                <div class="input-field">
                    <select id="update_subscription" name="update_subscription">
                        <option value="true" <?php echo ($current_settings['update_subscription'] == 'true') ? 'selected' : ''; ?>>True</option>
                        <option value="false" <?php echo ($current_settings['update_subscription'] == 'false') ? 'selected' : ''; ?>>False</option>
                    </select>
                    <label for="update_subscription">Update Subscription</label>
                </div>

                <div class="input-field">
                    <input type="text" id="subscription_url_clash" name="subscription_url_clash" value="<?php echo $current_settings['subscription_url_clash']; ?>">
                    <label for="subscription_url_clash">Subscription URL Clash</label>
                </div>

                <div class="input-field">
                    <input type="text" id="name_clash_config" name="name_clash_config" value="<?php echo $current_settings['name_clash_config']; ?>">
                    <label for="name_clash_config">Name Clash Config</label>
                </div>

<div class="button-container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <button class="btn waves-effect waves-light btn-margin" type="submit" name="save_settings">Save Settings
            <i class="material-icons right">save</i>
        </button>
    </form>
</div>

<!--<div class="button-container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <button class="btn waves-effect waves-light btn-margin" type="submit" name="backup_settings">Backup Settings
            <i class="material-icons right">backup</i>
        </button>
        <button class="btn waves-effect waves-light btn-margin" type="submit" name="restore_settings">Restore Settings
            <i class="material-icons right">restore</i>
        </button>
    </form>
</div>-->




    <!-- Materialize JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('select');
            var instances = M.FormSelect.init(elems);
        });
    </script>
</body>
</html>
