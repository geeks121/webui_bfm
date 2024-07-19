<?php
// File path
$ini_file_path = '/data/adb/box/settings.ini';

// Function to read the settings.ini file
function parse_settings_ini($file_path) {
    $lines = file($file_path, FILE_IGNORE_NEW_LINES);
    $settings = [];
    foreach ($lines as $line) {
        if (trim($line) === '' || $line[0] == ';' || $line[0] == '#') {
            $settings[] = $line; // Keep blank lines and comments
        } elseif (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            // Remove surrounding quotes if present
            if (preg_match('/^"(.*)"$/', $value, $matches)) {
                $value = $matches[1]; // Remove surrounding quotes
            } elseif (preg_match("/^'(.*)'$/", $value, $matches)) {
                $value = $matches[1]; // Remove surrounding single quotes
            }
            $settings[$key] = $value;
        } else {
            $settings[] = $line; // Keep lines without '=' intact
        }
    }
    return $settings;
}

// Read settings.ini file
$settings = parse_settings_ini($ini_file_path);

// List of settings categories
$bool_list = [
    'port_detect',
    'ipv6',
    'cgroup_cpuset',
    'cgroup_blkio',
    'cgroup_memcg',
    'run_crontab',
    'update_geo',
    'renew',
    'update_subscription'
];

$form_list = [
    'tproxy_port',
    'redir_port',
    'memcg_limit',
    'subscription_url_clash',
    'name_clash_config',
    'name_sing_config'
];

$dropdown_list = [
    'bin_name' => ['clash', 'sing-box', 'xray', 'v2fly'],
    'xclash_option' => ['mihomo', 'premium'],
    'network_mode' => ['redirect', 'tproxy', 'mixed', 'enhance', 'tun'],
    'proxy_mode' => ['blacklist', 'whitelist']
];

$dropdown_titles = [
    'xclash_option' => 'Clash Option',
    'bin_name' => 'Select Core',
    'xclash_option' => 'Clash Option',
    'network_mode' => 'Network Mode',
    'proxy_mode' => 'Proxy Mode',
    'port_detect' => 'Port Detection',
    'cgroup_cpuset' => 'Cpuset',
    'cgroup_blkio' => 'Blkio',
    'cgroup_memcg' => 'Memcg',
    'run_crontab' => 'Crontab',
    'update_geo' => 'Geox update',
    'update_subscription' => 'Update Subscription',
    'tproxy_port' => 'Tproxy Port',
    'redir_port' => 'Redir port',
    'memcg_limit' => 'Memcg Limit',
    'subscription_url_clash' => 'URL for subrcription clash',
    'name_clash_config' => 'Default clash Config File',
    'cgroup_blkio' => 'Default Sing-box Config File',
    'subscription_url_clash' => 'Subscription Clash URL'
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lines = file($ini_file_path, FILE_IGNORE_NEW_LINES);
    $new_settings = [];
    foreach ($lines as $line) {
        if (trim($line) === '' || $line[0] == ';' || $line[0] == '#') {
            $new_settings[] = $line; // Keep blank lines and comments
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $original_value = trim($value);

            if (isset($_POST[$key])) {
                $new_value = $_POST[$key];
                // Preserve quotes around values if originally quoted
                if (preg_match('/^".*"$/', $original_value)) {
                    $new_value = '"' . trim($new_value, '"') . '"';
                }
                if (preg_match("/^'.*'$/", $original_value)) {
                    $new_value = "'" . trim($new_value, "'") . "'";
                }
                $new_settings[] = "$key=$new_value";
            } else {
                $new_settings[] = "$key=$original_value";
            }
        } else {
            $new_settings[] = $line; // Keep lines without '=' intact
        }
    }

    file_put_contents($ini_file_path, implode("\n", $new_settings) . "\n");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings Configuration</title>
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #1e1e1e;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
        }
        .form-group select, .form-group input, .form-group span {
            width: calc(100% - 18px);
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #444;
            background-color: #333;
            color: #fff;
            display: inline-block;
            vertical-align: middle;
            box-sizing: border-box;
        }
        .form-group select {
            width: 100%;
        }
        .save-button {
            text-align: center;
            margin-top: 20px;
        }
        .save-button button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .save-button button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Set BOX Configuration</h2>
        <form method="POST">
            <?php foreach ($dropdown_list as $item => $options): ?>
                <div class="form-group">
                    <label for="<?= $item ?>"><?= isset($dropdown_titles[$item]) ? $dropdown_titles[$item] : ucfirst($item) ?></label>
                    <select name="<?= $item ?>" id="<?= $item ?>">
                        <?php foreach ($options as $option): ?>
                            <option value="<?= $option ?>" <?= $settings[$item] === $option || $settings[$item] === "\"$option\"" ? 'selected' : '' ?>><?= $option ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endforeach; ?>

            <?php foreach ($bool_list as $item): ?>
                <div class="form-group">
                    <label for="<?= $item ?>"><?= ucfirst(str_replace('_', ' ', $item)) ?></label>
                    <select name="<?= $item ?>" id="<?= $item ?>">
                        <option value="true" <?= $settings[$item] === 'true' || $settings[$item] === '"true"' ? 'selected' : '' ?>>True</option>
                        <option value="false" <?= $settings[$item] === 'false' || $settings[$item] === '"false"' ? 'selected' : '' ?>>False</option>
                    </select>
                </div>
            <?php endforeach; ?>

            <?php foreach ($form_list as $item): ?>
                <div class="form-group">
                    <label for="<?= $item ?>"><?= isset($dropdown_titles[$item]) ? $dropdown_titles[$item] : ucfirst(str_replace('_', ' ', $item)) ?></label>
                    <input type="text" name="<?= $item ?>" id="<?= $item ?>" value="<?= htmlspecialchars($settings[$item]) ?>">
                </div>
            <?php endforeach; ?>

            <div class="save-button">
                <button type="submit">Save</button>
            </div>
        </form>
    </div>
</body>
</html>