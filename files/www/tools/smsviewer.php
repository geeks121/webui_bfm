<?php
// Function to execute su command and return the output
function executeSuCommand($command) {
    $output = [];
    exec("su -c \"$command\"", $output);
    return $output;
}

session_start();

// Store current page URL in session as the intended redirect URL
$_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: /auth/login.php');
    exit;
}

// Function to get SMS messages from the Android device
function getSmsMessages() {
    // Execute su command to query SMS content provider
    $command = "content query --uri content://sms --projection address,body,date";
    $output = executeSuCommand($command);

    if (empty($output)) {
        echo "No output from su command.";
        return [];
    }

    $messages = [];
    foreach ($output as $line) {
        if (preg_match('/address=(.*?), body=(.*?), date=(\d+)/', $line, $matches)) {
            $messages[] = [
                'address' => $matches[1],
                'body' => $matches[2],
                'date' => date('Y-m-d H:i:s', $matches[3] / 1000)
            ];
        }
    }

    return $messages;
}

// Function to get unique senders from messages
function getUniqueSenders($messages) {
    $senders = array_unique(array_column($messages, 'address'));
    return $senders;
}

// Function to filter messages by sender
function filterMessagesBySender($messages, $sender) {
    return array_filter($messages, function($message) use ($sender) {
        return stripos($message['address'], $sender) !== false;
    });
}

// Function to search messages by keyword
function searchMessages($messages, $keyword) {
    return array_filter($messages, function($message) use ($keyword) {
        return stripos($message['body'], $keyword) !== false;
    });
}

// Get selected sender and search query from form submission
$selectedSender = isset($_POST['sender']) ? trim($_POST['sender']) : '';
$searchQuery = isset($_POST['search']) ? trim($_POST['search']) : '';

// Get current page number
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 10;

// Get SMS messages
$smsMessages = getSmsMessages();

// Get unique senders
$uniqueSenders = getUniqueSenders($smsMessages);

// Apply sender filter if selected
if ($selectedSender !== '') {
    $smsMessages = filterMessagesBySender($smsMessages, $selectedSender);
}

// Apply search query if provided
if ($searchQuery !== '') {
    $smsMessages = searchMessages($smsMessages, $searchQuery);
}

// Calculate total pages
$totalMessages = count($smsMessages);
$totalPages = ceil($totalMessages / $itemsPerPage);

// Get the messages for the current page
$offset = ($currentPage - 1) * $itemsPerPage;
$currentMessages = array_slice($smsMessages, $offset, $itemsPerPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SIMPLE SMS VIEWER</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            color: #000;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: background-color 0.3s, color 0.3s;
        }
        .dark-mode {
            background-color: #121212;
            color: #ffffff;
        }
        .container {
            max-width: 600px;
            width: 100%;
            background-color: #fff;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            box-sizing: border-box;
            transition: background-color 0.3s, color 0.3s;
        }
        .dark-mode .container {
            background-color: #333;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .message-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .message-item {
            background-color: #fafafa;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
            transition: background-color 0.3s, color 0.3s;
        }
        .dark-mode .message-item {
            background-color: #444;
            border-color: #555;
        }
        .message-item:hover {
            background-color: #f0f0f0;
        }
        .dark-mode .message-item:hover {
            background-color: #555;
        }
        .message-item .sender {
            font-weight: bold;
            color: #1a73e8;
        }
        .dark-mode .message-item .sender {
            color: #82b1ff;
        }
        .message-item .message-body {
            margin-top: 5px;
        }
        .message-item .date {
            font-size: 0.8em;
            color: #888;
        }
        .dark-mode .message-item .date {
            color: #bbb;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group select,
        .form-group input[type="text"] {
            width: 100%;
            padding: 8px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group button {
            padding: 8px 16px;
            font-size: 1em;
            background-color: #1a73e8;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #0d47a1;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            padding: 8px 16px;
            margin: 0 4px;
            text-decoration: none;
            background-color: #1a73e8;
            color: #fff;
            border-radius: 4px;
        }
        .pagination a:hover {
            background-color: #0d47a1;
        }
        .pagination .active {
            background-color: #0d47a1;
        }
        .pagination .disabled {
            background-color: #ccc;
            pointer-events: none;
        }
        .dark-mode .pagination a {
            background-color: #555;
        }
        .dark-mode .pagination a:hover {
            background-color: #333;
        }
        .dark-mode .pagination .active {
            background-color: #333;
        }
        .dark-mode .pagination .disabled {
            background-color: #444;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Simple SMS Viewer</h1>
            <label>
                Dark Mode
                <input type="checkbox" id="dark-mode-toggle">
            </label>
        </div>

        <form method="post" action="">
            <div class="form-group">
                <label for="sender">Select Sender:</label>
                <select id="sender" name="sender">
                    <option value="">All Senders</option>
                    <?php foreach ($uniqueSenders as $sender): ?>
                        <option value="<?= htmlspecialchars($sender) ?>" <?= ($selectedSender === $sender) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sender) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="search">Search Messages:</label>
                <input type="text" id="search" name="search" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Enter keyword">
            </div>

            <button type="submit">Apply Filters</button>
        </form>

        <ul class="message-list">
            <?php if (!empty($currentMessages)): ?>
                <?php foreach ($currentMessages as $sms): ?>
                    <li class="message-item">
                        <div class="sender"><?= htmlspecialchars($sms['address']) ?></div>
                        <div class="message-body"><?= nl2br(htmlspecialchars($sms['body'])) ?></div>
                        <div class="date"><?= htmlspecialchars($sms['date']) ?></div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="message-item">
                    No messages found.
                </li>
            <?php endif; ?>
        </ul>

        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?= $currentPage - 1 ?>">Previous</a>
            <?php else: ?>
                <a href="#" class="disabled">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= ($currentPage == $i) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?= $currentPage + 1 ?>">Next</a>
            <?php else: ?>
                <a href="#" class="disabled">Next</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Check for saved dark mode preference
        const darkModeToggle = document.getElementById('dark-mode-toggle');
        const isDarkMode = localStorage.getItem('dark-mode') === 'enabled';
        if (isDarkMode) {
            document.body.classList.add('dark-mode');
            darkModeToggle.checked = true;
        }

        // Toggle dark mode
        darkModeToggle.addEventListener('change', () => {
            document.body.classList.toggle('dark-mode');
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('dark-mode', 'enabled');
            } else {
                localStorage.removeItem('dark-mode');
            }
        });
    </script>
</body>
</html>
