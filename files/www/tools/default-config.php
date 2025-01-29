<?php
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['yaml_file'])) {
        $selectedYaml = $_POST['yaml_file'];
        updateSettingsIni_clash($settingsFile, $selectedYaml);
        // Redirect to prevent form resubmission
        header("Location: /tools/default-config.php");
        exit();
    }
    if (isset($_POST['json_file'])) {
        $selectedJson = $_POST['json_file'];
        updateSettingsIni_sing($settingsFile, $selectedJson);
        // Redirect to prevent form resubmission
        header("Location: /tools/default-config.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOX Config changer</title>
    <!-- Materialize CSS -->
    <link rel="stylesheet" href="../auth/css/materialize.min.css" />
    <style>
        /* Dark mode styles for tabs */
        .tabs-dark {
            background: rgba(40, 40, 55, 0.9); /* Glass-like dark background */
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }
        .tabs-dark .tab a {
            color: #9e9e9e; /* Inactive tab color */
        }
        .tabs-dark .tab a:hover {
            color: #42a5f5; /* Hover effect */
        }
        .tabs-dark .tab a.active {
            color: #ffffff; /* Active tab color */
            font-weight: bold;
        }
        .tabs-dark .indicator {
            background-color: #42a5f5; /* Indicator for active tab */
        }

        /* Tab content styling */
        .dark-tab-content {
            background-color: #1e1e2f; /* Dark background */
            color: #ffffff; /* White text */
            padding: 20px;
            border-radius: 8px;
            margin-top: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        body {
            background-color: #121212; /* Dark mode background */
            color: #ffffff; /* Default text color */
            font-family: 'Roboto', sans-serif;
        }
        .container {
            margin-top: 30px;
        }

        /* Refresh button animation */
        .refresh-button {
            position: relative;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #42a5f5;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* Spinning animation */
        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

    <!-- Tabs -->
    <div class="container">
        <ul class="tabs tabs-dark">
            <li class="tab col s6"><a href="#config" class="active">WARNING!!: STOP BFR FIRST BEFORE SAVE</a></li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div class="container">
        <!-- Config Tab -->
        <div id="config" class="col s12 dark-tab-content">
            <h5>Config clash Section</h5>
            <form method="POST">
                <div class="input-field">
                    <select name="yaml_file" class="browser-default">
                        <?php foreach ($yamlFiles as $yamlFile): ?>
                            <option value="<?= basename($yamlFile) ?>" <?= $currentClashConfigValue === basename($yamlFile) ? 'selected' : '' ?>>
                                <?= basename($yamlFile) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn blue">Save yaml Config</button>
            </form>
            <h5>Config Sing Section</h5>
            <form method="POST">
                <div class="input-field">
                    <select name="json_file" class="browser-default">
                        <?php foreach ($jsonFiles as $jsonFile): ?>
                            <option value="<?= basename($jsonFile) ?>" <?= $currentSingConfigValue === basename($jsonFile) ? 'selected' : '' ?>>
                                <?= basename($jsonFile) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn blue">Save Json Config</button>
            </form>
        </div>
    </div>

    <!-- Materialize JS -->
    <script src="../auth/js/materialize.min.js"></script>
    

</body>
</html>