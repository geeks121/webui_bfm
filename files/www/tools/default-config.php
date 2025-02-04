<?php
session_start();
$clashlogs = "/data/adb/box/run/runs.log";
// Path to YAML folder and settings.ini file
$yamlFolder = '/data/adb/box/clash';
$jsonFolder = '/data/adb/box/sing-box';
$settingsFile = '/data/adb/box/settings.ini';

// Function to read YAML files from folder
function getYamlFiles($folder) {
    $yamlFiles = glob("$folder/*.yaml");
    return $yamlFiles;
}

// Function to read json files from folder
function getJsonFiles($folder) {
    $jsonFiles = glob("$folder/*.json");
    return $jsonFiles;
}

// Function to read settings.ini and get current name_clash_config value
function getCurrentClashConfigValue($settingsFile) {
    $content = file_get_contents($settingsFile);
    preg_match('/name_clash_config="([^"]+)"/', $content, $matches);
    $currentValue = isset($matches[1]) ? $matches[1] : '';
    return $currentValue;
}

// Function to read settings.ini and get current name_sing_config value
function getCurrentSingConfigValue($settingsFile) {
    $content = file_get_contents($settingsFile);
    preg_match('/name_sing_config="([^"]+)"/', $content, $matches);
    $currentValue = isset($matches[1]) ? $matches[1] : '';
    return $currentValue;
}

// Function to update settings.ini with new name_clash_config value
function updateSettingsIni_clash($settingsFile, $newValue) {
    $content = file_get_contents($settingsFile);
    $newContent = preg_replace('/name_clash_config="([^"]+)"/', 'name_clash_config="'.$newValue.'"', $content);
    file_put_contents($settingsFile, $newContent);
}

// Function to update settings.ini with new name_sing_config value
function updateSettingsIni_sing($settingsFile, $newValue) {
    $content = file_get_contents($settingsFile);
    $newContent = preg_replace('/name_sing_config="([^"]+)"/', 'name_sing_config="'.$newValue.'"', $content);
    file_put_contents($settingsFile, $newContent);
}

// Get YAML files from folder
$yamlFiles = getYamlFiles($yamlFolder);
// Get Json files from folder
$jsonFiles = getJsonFiles($jsonFolder);

// Get current name_clash_config value
$currentClashConfigValue = getCurrentClashConfigValue($settingsFile);
// Get current name_sing_config value
$currentSingConfigValue = getCurrentSingConfigValue($settingsFile);

// Check if we should show notification
$showNotification = false;
if (isset($_SESSION['show_notification'])) {
    $showNotification = true;
    unset($_SESSION['show_notification']);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['yaml_file'])) {
        $selectedYaml = $_POST['yaml_file'];
        updateSettingsIni_clash($settingsFile, $selectedYaml);
        $_SESSION['show_notification'] = true;
        
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode(['success' => true]);
            exit;
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    if (isset($_POST['json_file'])) {
        $selectedJson = $_POST['json_file'];
        updateSettingsIni_sing($settingsFile, $selectedJson);
        $_SESSION['show_notification'] = true;
        
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode(['success' => true]);
            exit;
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Box UI</title>
    <link rel="stylesheet" href="../auth/css/materialize.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --dark-bg: #0a0d11;
            --dark-card: #1a1f26;
            --dark-input: #212936;
            --light-bg: #f8f9fa;
            --light-card: #ffffff;
            --light-input: #f1f3f5;
            --text-primary: #ffffff;
            --text-dark: #1a1f26;
            --accent-blue: #2196f3;
            --danger-red: #ff4757;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--text-primary);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            min-height: 100vh;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        body.light-mode {
            background-color: var(--light-bg);
            color: var(--text-dark);
        }

        .main-container {
            padding: 16px;
            max-width: 800px;
            margin: 0 auto;
        }

        .header-card {
            background-color: var(--dark-card);
            border-radius: 1rem;
            padding: 20px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s ease;
        }

        .light-mode .header-card {
            background-color: var(--light-card);
        }

        .config-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .warning-banner {
            background-color: var(--danger-red);
            color: white;
            padding: 12px;
            text-align: center;
            border-radius: 1rem;
            margin-bottom: 16px;
            font-size: 0.95rem;
        }

        .config-section {
            background-color: var(--dark-card);
            border-radius: 1rem;
            padding: 16px;
            margin-bottom: 16px;
            transition: background-color 0.3s ease;
        }

        .light-mode .config-section {
            background-color: var(--light-card);
        }

        .section-title {
            color: var(--accent-blue);
            font-size: 0.95rem;
            margin-bottom: 12px;
        }

        .select-wrapper {
            background-color: var(--dark-input);
            border-radius: 1rem;
            margin-bottom: 12px;
            transition: background-color 0.3s ease;
        }

        .light-mode .select-wrapper {
            background-color: var(--light-input);
        }

        select.browser-default {
            width: 100%;
            background-color: transparent;
            border: none;
            color: inherit;
            padding: 12px;
            font-size: 0.95rem;
            outline: none;
        }

        .btn-save {
            background-color: var(--accent-blue);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.9rem;
            text-transform: uppercase;
            transition: background-color 0.3s ease;
        }

        .dark-mode-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .dark-mode-label {
            font-size: 1rem;
            color: #94a3b8;
        }
        
        .theme-toggle-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            color: #94a3b8;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .theme-toggle-btn:hover {
            background-color: rgba(148, 163, 184, 0.1);
        }

        .light-mode .theme-toggle-btn {
            color: #1a1f26;
        }

        .theme-toggle-btn i {
            font-size: 1.2rem;
        }

        #successNotification {
            position: fixed;
            bottom: 80px; /* Moved up from bottom */
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--dark-card);
            color: white;
            padding: 12px 17px; /* Increased padding */
            border-radius: 14px; /* Increased border radius */
            display: none;
            align-items: center;
            gap: 12px; /* Increased gap */
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3); /* Enhanced shadow */
            animation: fadeInOut 3s ease-in-out;
            min-width: 250px; /* Added minimum width */
            text-align: center;
            justify-content: center;
        }
        
        /* Add theme-specific styles */
        .light-mode #successNotification {
            background-color: var(--light-card);
            color: var(--text-dark);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        @keyframes fadeInOut {
            0% { opacity: 0; }
            15% { opacity: 1; }
            85% { opacity: 1; }
            100% { opacity: 0; }
        }
        /* Base styles refinements */
        :root {
            --max-width-desktop: 800px;
            --padding-mobile: 12px;
            --padding-desktop: 24px;
        }
        
        .main-container {
            padding: var(--padding-mobile);
            max-width: var(--max-width-desktop);
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }
        
        .header-card {
            padding: 16px;
            margin-bottom: 16px;
            flex-direction: column;
            gap: 12px;
        }
        
        .config-section {
            padding: 16px;
            margin-bottom: 16px;
        }
        
        .select-wrapper {
            width: 100%;
        }
        
        .btn-save {
            width: 100%;
            margin-top: 12px;
            padding: 12px;
            height: auto;
            display: block;
        }
        
        /* Tablet and up */
        @media screen and (min-width: 768px) {
            .main-container {
                padding: var(--padding-desktop);
            }
        
            .header-card {
                padding: 20px;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        
            .config-section {
                padding: 24px;
            }
        
            .btn-save {
                width: auto;
                min-width: 200px;
                margin-top: 16px;
            }
        }
        
        /* Desktop specific */
        @media screen and (min-width: 1024px) {
            .main-container {
                padding: var(--padding-desktop);
            }
        
            .config-section {
                padding: 32px;
            }
        
            select.browser-default {
                font-size: 1rem;
                padding: 14px;
            }
        
            .config-title {
                font-size: 1.75rem;
            }
        
            .dark-mode-container {
                gap: 16px;
            }
        
            .dark-mode-label {
                font-size: 1.1rem;
            }
        }
        
        /* Ensure proper display on very small screens */
        @media screen and (max-width: 360px) {
            .header-card {
                padding: 12px;
            }
        
            .config-title {
                font-size: 1.25rem;
            }
        
            .config-section {
                padding: 12px;
            }
        
            .dark-mode-label {
                font-size: 0.9rem;
            }
        }
        
        /* Notification responsiveness */
        #successNotification {
            width: calc(100% - 32px);
            max-width: 200px;
            bottom: 70px;
        }
        
        @media screen and (min-width: 768px) {
            #successNotification {
                bottom: 70px;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="header-card">
            <span class="config-title">Change Configuration</span>
            <div class="dark-mode-container">
                <span class="dark-mode-label">Dark/Light Mode</span>
                <button class="theme-toggle-btn" id="themeToggle">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>

        <div class="warning-banner">
            WARNING!!! STOP BFR FIRST BEFORE SAVE
        </div>

        <div class="config-section">
            <div class="section-title">Config clash Section</div>
            <form class="config-form">
                <div class="select-wrapper">
                    <select name="yaml_file" class="browser-default">
                        <?php foreach ($yamlFiles as $yamlFile): ?>
                            <option value="<?= basename($yamlFile) ?>" <?= $currentClashConfigValue === basename($yamlFile) ? 'selected' : '' ?>>
                                <?= basename($yamlFile) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-save">Save yaml Config</button>
            </form>
        </div>

        <div class="config-section">
            <div class="section-title">Config Sing Section</div>
            <form class="config-form">
                <div class="select-wrapper">
                    <select name="json_file" class="browser-default">
                        <?php foreach ($jsonFiles as $jsonFile): ?>
                            <option value="<?= basename($jsonFile) ?>" <?= $currentSingConfigValue === basename($jsonFile) ? 'selected' : '' ?>>
                                <?= basename($jsonFile) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-save">Save Json Config</button>
            </form>
        </div>
    </div>

    <div id="successNotification">
        <i class="fas fa-check" style="color: var(--accent-blue);"></i>
        <span>Changes saved successfully!</span>
    </div>

    <script src="../auth/js/materialize.min.js"></script>
    <script>
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;
        const toggleIcon = themeToggle.querySelector('i');
        const successNotification = document.getElementById('successNotification');
        
        // Get theme from localStorage or default to dark
        let isDark = localStorage.getItem('theme') !== 'light';
        
        function setDarkMode(isDarkMode) {
            if (isDarkMode) {
                body.classList.remove('light-mode');
                toggleIcon.className = 'fas fa-moon';
            } else {
                body.classList.add('light-mode');
                toggleIcon.className = 'fas fa-sun';
            }
            // Save theme preference
            localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
        }
        
        themeToggle.addEventListener('click', () => {
            isDark = !isDark;
            setDarkMode(isDark);
        });
        
        // Initialize theme on page load
        setDarkMode(isDark);

        // Handle form submissions
        document.querySelectorAll('.config-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Store current theme
                const currentTheme = localStorage.getItem('theme');
                
                successNotification.style.display = 'flex';
                
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
                        setTimeout(() => {
                            // Preserve theme when reloading
                            localStorage.setItem('theme', currentTheme);
                            window.location.reload();
                        }, 2000);
                    }
                });
            });
        });

        // Show notification on page load if needed
        <?php if ($showNotification): ?>
        window.addEventListener('load', function() {
            successNotification.style.display = 'flex';
            setTimeout(() => {
                successNotification.style.display = 'none';
            }, 3000);
        });
        <?php endif; ?>
    </script>
</body>
</html>
