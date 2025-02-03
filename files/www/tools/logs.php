<?php
$clashlogs = "/data/adb/box/run/runs.log";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>BOX Logs</title>
    <style>
        :root {
            --primary-color: #2196F3;
            --success-color: #4CAF50;
            --bg-light: #f5f7fa;
            --text-light: #2c3e50;
            --card-light: #ffffff;
            --border-light: #edf2f7;
            --header-light: #f8fafc;
            
            --bg-dark: #1a1b1e;
            --text-dark: #e4e7eb;
            --card-dark: #25262b;
            --border-dark: #2c2e33;
            --header-dark: #1f2023;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 24px;
            background-color: var(--bg-light);
            color: var(--text-light);
            transition: all 0.3s ease;
            line-height: 1.5;
            min-height: 100vh;
        }

        body.dark-mode {
            background-color: var(--bg-dark);
            color: var(--text-dark);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px;
            height: calc(100vh - 48px);
            display: flex;
            flex-direction: column;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding: 16px 24px;
            background-color: var(--header-light);
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .dark-mode .header {
            background-color: var(--header-dark);
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        .button-container {
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9375rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            height: 40px;
        }

        .btn-icon {
            padding: 0;
            width: 40px;
            height: 40px;
            background: transparent;
            color: var(--text-light);
            border: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-icon i {
            font-size: 16px;
            line-height: 1;
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dark-mode .btn-icon {
            color: var(--text-dark);
            border-color: var(--border-dark);
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            padding: 8px 20px;
        }

        .logs-card {
            background-color: var(--card-light);
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .dark-mode .logs-card {
            background-color: var(--card-dark);
        }

        .logs-header {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border-light);
            font-weight: 500;
            font-size: 1rem;
            color: var(--text-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--header-light);
        }

        .dark-mode .logs-header {
            border-color: var(--border-dark);
            color: var(--text-dark);
            background-color: var(--header-dark);
        }

        .logs-container {
            flex: 1;
            overflow-y: auto;
            padding: 16px 24px;
        }

        .log-entry {
            padding: 12px 16px;
            margin-bottom: 8px;
            border-radius: 6px;
            background-color: var(--bg-light);
            font-family: 'Monaco', 'Consolas', monospace;
            font-size: 13px;
            line-height: 1.5;
            white-space: pre-wrap;
            word-break: break-all;
        }

        .dark-mode .log-entry {
            background-color: rgba(0, 0, 0, 0.2);
        }

        .notification {
            position: fixed;
            top: 100px;
            left: 50%;
            transform: translateX(-50%);
            padding: 12px 24px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1000;
            font-size: 0.9375rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .notification.show {
            opacity: 1;
        }

        .logs-container::-webkit-scrollbar {
            width: 8px;
        }

        .logs-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .logs-container::-webkit-scrollbar-thumb {
            background-color: var(--border-light);
            border-radius: 4px;
        }

        .dark-mode .logs-container::-webkit-scrollbar-thumb {
            background-color: var(--border-dark);
        }

        /* Tablet Adjustments */
        @media (max-width: 1024px) {
            .container {
                max-width: 900px;
            }

            .header {
                padding: 14px 20px;
            }

            .logs-header {
                padding: 14px 20px;
            }

            .logs-container {
                padding: 14px 20px;
            }

            .log-entry {
                padding: 10px 14px;
                font-size: 12px;
            }
        }

        /* Mobile Adjustments */
        @media (max-width: 640px) {
            body {
                padding: 12px;
            }

            .container {
                padding: 0 8px;
                height: calc(100vh - 24px);
            }

            .header {
                padding: 12px 16px;
                margin-bottom: 16px;
            }

            .header h1 {
                font-size: 1.25rem;
            }

            .btn {
                padding: 6px 12px;
                font-size: 0.875rem;
                height: 36px;
            }

            .btn-icon {
                width: 36px;
                height: 36px;
                padding: 6px;
            }

            .btn-primary {
                padding: 6px 14px;
            }

            .logs-header {
                padding: 12px 16px;
                font-size: 0.9375rem;
            }

            .logs-container {
                padding: 12px 16px;
            }

            .log-entry {
                padding: 8px 12px;
                margin-bottom: 6px;
                font-size: 11px;
                line-height: 1.4;
            }

            .notification {
                top: 80px;
                padding: 10px 16px;
                font-size: 0.875rem;
            }

            .button-container {
                gap: 8px;
            }
        }

        /* Small Mobile Adjustments */
        @media (max-width: 360px) {
            body {
                padding: 8px;
            }

            .container {
                padding: 0 4px;
            }

            .header {
                padding: 10px 12px;
            }

            .header h1 {
                font-size: 1.125rem;
            }

            .btn {
                padding: 4px 10px;
                font-size: 0.8125rem;
                height: 32px;
            }

            .btn-icon {
                width: 32px;
                height: 32px;
                padding: 4px;
            }

            .logs-container {
                padding: 8px 12px;
            }

            .log-entry {
                padding: 6px 10px;
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>BOX Logs</h1>
            <div class="button-container">
                <button class="btn btn-icon" onclick="toggleTheme()" title="Toggle theme">
                    <i class="fas fa-sun"></i>
                </button>
                <button class="btn btn-primary" onclick="refreshLogs()">
                    <i class="fas fa-sync-alt"></i>
                    Refresh
                </button>
            </div>
        </div>

        <div class="logs-card">
            <div class="logs-header">
                <span>Log Entries</span>
                <span id="currentTime"></span>
            </div>
            <div class="logs-container">
                <?php
                $file = fopen("$clashlogs", "r");
                while (!feof($file)) {
                    $log = str_replace('"', '', fgets($file));
                    echo '<div class="log-entry">' . htmlspecialchars($log) . '</div>';
                }
                fclose($file);
                ?>
            </div>
        </div>
    </div>

    <div class="notification" id="notification">
        <i class="fas fa-check-circle"></i>
        Logs refreshed
    </div>

    <script>
        let isDarkMode = localStorage.getItem('darkMode') === 'true';
        
        function toggleTheme() {
            isDarkMode = !isDarkMode;
            localStorage.setItem('darkMode', isDarkMode);
            updateTheme();
        }

        function updateTheme() {
            document.body.classList.toggle('dark-mode', isDarkMode);
            const themeIcon = document.querySelector('.btn-icon i');
            themeIcon.className = isDarkMode ? 'fas fa-moon' : 'fas fa-sun';
        }

        function showNotification() {
            const notification = document.getElementById('notification');
            notification.classList.add('show');
            setTimeout(() => {
                notification.classList.remove('show');
            }, 2000);
        }

        function refreshLogs() {
            showNotification();
            setTimeout(() => {
                location.reload();
            }, 300);
        }

        function updateCurrentTime() {
            const now = new Date();
            const options = { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: false 
            };
            document.getElementById('currentTime').textContent = now.toLocaleTimeString([], options);
        }

        if (isDarkMode) {
            updateTheme();
        }

        if (performance.getEntriesByType("navigation")[0].type === "reload") {
            showNotification();
        }

        updateCurrentTime();
        setInterval(updateCurrentTime, 1000);
    </script>
</body>
</html>
