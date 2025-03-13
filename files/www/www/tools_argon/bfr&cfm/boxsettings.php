<?php
// Original file path and functions remain the same
$ini_file_path = '/data/adb/box/settings.ini';

function parse_settings_ini($file_path) {
    $lines = file($file_path, FILE_IGNORE_NEW_LINES);
    $settings = [];
    foreach ($lines as $line) {
        if (trim($line) === '' || $line[0] == ';' || $line[0] == '#') {
            $settings[] = $line;
        } elseif (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (preg_match('/^"(.*)"$/', $value, $matches)) {
                $value = $matches[1];
            } elseif (preg_match("/^'(.*)'$/", $value, $matches)) {
                $value = $matches[1];
            }
            $settings[$key] = $value;
        } else {
            $settings[] = $line;
        }
    }
    return $settings;
}

// Read settings
$settings = parse_settings_ini($ini_file_path);

// Settings categories remain the same
$bool_list = [
    'port_detect', 'ipv6', 'cgroup_cpuset', 'cgroup_blkio',
    'cgroup_memcg', 'run_crontab', 'update_geo', 'renew',
    'update_subscription'
];

$form_list = [
    'tproxy_port', 'redir_port', 'memcg_limit',
    'subscription_url_clash', 'name_clash_config', 'name_sing_config'
];

$dropdown_list = [
    'bin_name' => ['clash', 'sing-box', 'xray', 'v2fly'],
    'xclash_option' => ['mihomo', 'premium'],
    'network_mode' => ['redirect', 'tproxy', 'mixed', 'enhance', 'tun'],
    'proxy_mode' => ['blacklist', 'whitelist']
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lines = file($ini_file_path, FILE_IGNORE_NEW_LINES);
    $new_settings = [];
    foreach ($lines as $line) {
        if (trim($line) === '' || $line[0] == ';' || $line[0] == '#') {
            $new_settings[] = $line;
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $original_value = trim($value);

            if (isset($_POST[$key])) {
                $new_value = $_POST[$key];
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
            $new_settings[] = $line;
        }
    }

    file_put_contents($ini_file_path, implode("\n", $new_settings) . "\n");
    
    // Instead of redirecting, send a success response
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode(['success' => true]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOX Configuration</title>
    <style>
        :root {
            --bg-dark: #0a0c10;
            --card-dark: #1a1f26;
            --input-dark: #0f172a;
            --text-dark: #ffffff;
            --text-secondary-dark: #9ca3af;
            --border-dark: #2d3139;
            --bg-light: #f3f4f6;
            --card-light: #ffffff;
            --input-light: #f9fafb;
            --text-light: #111827;
            --text-secondary-light: #6b7280;
            --border-light: #e5e7eb;
            --primary: #6366f1;
            --primary-hover: #4f46e5;
        }
        
        * {
            -webkit-user-select: none; /* Safari */
            -moz-user-select: none; /* Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none; /* Standard */
            -webkit-tap-highlight-color: transparent; /* Hilangkan efek tap di mobile */
        }

        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body.dark-mode {
            background-color: transparent;
            color: var(--text-dark);
        }

        body.light-mode {
            background-color: transparent;
            color: var(--text-light);
        }
        

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 16px;
            box-sizing: border-box;
        }

        .header {
            padding: 33px;
            border-radius: 1rem;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dark-mode .header {
            background-color: var(--card-dark);
        }

        .light-mode .header {
            background-color: var(--card-light);
        }

        .header h2 {
            margin: 0;
            font-size: 1.5rem; /* Ukuran teks yang lebih besar */
            font-weight: 600;
        }

        .form-group {
            padding: 12px 16px;
            border-radius: 1rem;
            margin-bottom: 12px;
            box-sizing: border-box;
        }

        .dark-mode .form-group {
            background-color: var(--card-dark);
        }

        .light-mode .form-group {
            background-color: var(--card-light);
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-size: 0.93rem;
            color: var(--text-secondary-dark);
        }
        
        .dark-mode .form-group label {
            color: var(--text-dark);
        }

        .light-mode .form-group label {
            color: var(--text-light);
        }        

        .form-group select,
        .form-group input {
            width: 100%;
            padding: 7px 12px;
            border-radius: 10px;
            border: 1px solid transparent;
            font-size: 0.8rem;
            box-sizing: border-box;
            margin: 0;
        }

        .dark-mode .form-group select,
        .dark-mode .form-group input {
            background-color: #212936;
            color: var(--text-dark);
            border-color: var(--border-dark);
        }

        .light-mode .form-group select,
        .light-mode .form-group input {
            background-color: var(--input-light);
            color: var(--text-light);
            border-color: var(--border-light);
        }

        .form-group select:focus,
        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .save-button {
            padding: 10px 0;
            text-align: center;
        }

        .save-button button {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 1rem;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.95rem;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .save-button button:hover {
            background-color: var(--primary-hover);
        }
        
        .popup {
            position: fixed;
            bottom: 80px;  /* Diubah dari 20px ke 80px untuk posisi lebih tinggi */
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            visibility: hidden;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .popup.show {
            visibility: visible;
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        
        .popup-content {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 24px;
            border-radius: 18px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            justify-content: center;
        }
                
        .dark-mode .popup-content {
            background-color: var(--card-dark);
            color: var(--text-dark);
        }
        
        .light-mode .popup-content {
            background-color: var(--card-light);
            color: var(--text-light);
        }
        
        .popup-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            background-color: var(--primary);
            color: white;
            border-radius: 50%;
            font-size: 14px;
        }
        
        .popup-message {
            font-size: 0.8rem;
            font-weight: 500;
        }

        .theme-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 4px;
        }
        
        .theme-toggle span {
            font-size: 0.9rem;
            color: inherit;
        }
        
        .switch {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 24px;
            margin: 0;
        }
        
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
            margin: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #64748b;
            border-radius: 24px;
            border: 2px solid transparent;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            border-radius: 50%;
        }
        
        input:checked + .slider {
            background-color: var(--primary);
        }
        
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        
        /* Efek hover */
        .slider:hover {
            background-color: #475569;
        }
        
        input:checked + .slider:hover {
            background-color: var(--primary-hover);
        }
        
        /* Responsive adjustments */
        @media screen and (min-width: 768px) {
            .container {
                max-width: 800px;
                padding: 20px;
            }
        
            .header {
                padding: 25px;
            }
        
            .form-group {
                padding: 16px 20px;
            }
        }
        
        @media screen and (max-width: 767px) {
            .container {
                width: 100%;
                padding: 10px;
            }
        
            .header {
                padding: 15px;
                flex-direction: column;
                gap: 10px;
            }
        
            .theme-toggle {
                margin-top: 10px;
            }
        
            .form-group {
                padding: 10px;
            }
        
            .form-group select,
            .form-group input {
                padding: 8px;
            }
        
            .save-button button {
                padding: 10px 20px;
            }
        }
        
        @media screen and (max-width: 480px) {
            .container {
                padding: 8px;
            }
        
            .header {
                padding: 20px;
            }
        
            .form-group {
                margin-bottom: 8px;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <form method="POST">
            <?php foreach ($dropdown_list as $item => $options): ?>
                <div class="form-group">
                    <label for="<?= $item ?>"><?= ucfirst(str_replace('_', ' ', $item)) ?></label>
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
                    <label for="<?= $item ?>"><?= ucfirst(str_replace('_', ' ', $item)) ?></label>
                    <input type="text" name="<?= $item ?>" id="<?= $item ?>" value="<?= htmlspecialchars($settings[$item]) ?>">
                </div>
            <?php endforeach; ?>

            <div class="save-button">
                <button type="submit">Save Changes</button>
            </div>
        </form>
    </div>
    <div id="popup" class="popup">
        <div class="popup-content">
            <div class="popup-icon">✓</div>
            <div class="popup-message">Changes saved successfully!</div>
        </div>
    </div>

    <script>
        // Detect system preference for dark mode
        const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;

        // Apply the appropriate theme on load based on the system preference
        const body = document.body;

        if (prefersDarkMode) {
            body.classList.add('dark-mode');
        } else {
            body.classList.add('light-mode');
        }

        // Optional: Allow users to manually toggle theme
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('change', () => {
                body.classList.toggle('dark-mode');
                body.classList.toggle('light-mode');
            });
        }

        // Form submission handling
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();

            fetch(window.location.href, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const popup = document.getElementById('popup');
                    popup.classList.add('show');
                    
                    // Hide popup after 3 seconds
                    setTimeout(() => {
                        popup.classList.remove('show');
                    }, 3000);
                }
            })
            .catch(error => console.error('Error:', error));
        });        
    </script>
</body>
