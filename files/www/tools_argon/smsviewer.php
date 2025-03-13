<?php
// Function to execute su command and return the output
function executeSuCommand($command) {
    $output = [];
    exec("su -c \"$command\"", $output);
    return $output;
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern SMS Viewer</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --bg-light: #f8fafc;
            --bg-dark: #0a0c10;
            --text-dark: #1e293b;
            --text-light: #f8fafc;
            --card-light: #ffffff;
            --card-dark: #1e293b;
            --border-light: #e2e8f0;
            --border-dark: #334155;
        }

        body {
            background-color: tranparet;
            color: var(--text-dark);
            transition: background-color 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        body.dark {
            background-color: tranparet;
            color: var(--text-light);
        }

        /* Base styles */
        .container {
            width: calc(100% - 20px);
            height: 100%;
            padding: 10px;
            border-radius: 12px;
            margin-bottom: 20px;
            margin-top: 10px;
        }

        .filters {
            display: grid;
            gap: 1rem;
            margin-bottom: 1rem;
            padding: 1.5rem;
            background: var(--card-light);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .dark .filters {
            background: var(--card-dark);
        }

        .form-group {
            display: grid;
            gap: 0.5rem;
        }

        .form-group label {
            font-weight: 500;
        }

        select, input[type="text"] {
            padding: 0.75rem;
            border: 1px solid var(--border-light);
            border-radius: 0.5rem;
            background: var(--bg-light);
            color: var(--text-dark);
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .dark select, .dark input[type="text"] {
            background: var(--bg-dark);
            border-color: var(--border-dark);
            color: var(--text-light);
        }

        button {
            padding: 0.75rem 1.5rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        button:hover {
            background: var(--primary-dark);
        }

        .message-list {
            display: grid;
            gap: 1rem;
            list-style: none;
        }

        .message {
            padding: 1.3rem;
            background: var(--card-light);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            transition: transform 0.2s ease;
        }

        .dark .message {
            background: var(--card-dark);
        }

        .message:hover {
            transform: translateY(-2px);
        }
        
        .message-sender {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
            font-size: 0.8rem; 
        }

        .message-content {
            line-height: 1.4;
            margin-bottom: 0.75rem;
            font-size: 0.85rem; 
        }

        .message-date {
            font-size: 0.65rem; 
            color: #64748b;
        }
        .dark .message-date {
            color: #94a3b8;
        }

        .toggle-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .theme-toggle {
            position: relative;
            width: 3rem;
            height: 1.5rem;
            border-radius: 1rem;
            background: #64748b;
            cursor: pointer;
        }

        .dark .theme-toggle {
            background: #6366f1;
        }

        .theme-toggle::after {
            content: '';
            position: absolute;
            left: 0.25rem;
            top: 0.25rem;
            width: 1rem;
            height: 1rem;
            background: white;
            border-radius: 50%;
            transition: transform 0.2s ease;
        }

        .dark .theme-toggle::after {
            transform: translateX(1.5rem);
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.25rem;
            margin-top: 2rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 1rem;
            flex-wrap: wrap;
        }

        .pagination a {
            padding: 0.4rem 0.8rem;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 0.5rem;
            transition: background-color 0.2s ease;
            font-size: 0.8rem; /* Smaller font size */
            min-width: 2rem;
            text-align: center;
        }

        .pagination a:hover {
            background: var(--primary-dark);
        }
    header {
      padding: 0;
      text-align: center;
      position: relative;
      width: 100%;
    }
    .header-top {
      background-color: transparent;
      padding: 5px;
    }
    .header-bottom {
      background-color: transparent;
      padding: 5px;
      color: transparent;
    }
    header h1 {
      margin: 0;
      font-size: 0.8em;
      color: transparent;
    }
    .new-container {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      margin-bottom: 100px;
      border-radius: 5px;
      width: calc(100% - 40px);
      height: 100%;
      padding: 10px;
      box-sizing: border-box;
      background-color: #ffffff;
      color: #000;
      text-align: center;
      z-index: 2;
    }
    .new-container p {
      text-align: left;
      font-size: 1.1em;
      color: #555;
      margin-top: 3px;
      margin-left: 10px;
      font-weight: bold;
    }
    
    .new-container.dark {
      background-color: #2a2a2a;
      color: #e0e0e0;
    }
    
    .new-container.dark p {
      background-color: #2a2a2a;
      color: #e0e0e0;
    }

        @media (max-width: 768px) {
            .pagination {
                gap: 0.2rem;
            }
            
            .pagination a {
                padding: 0.3rem 0.6rem;
                font-size: 0.75rem;
                min-width: 1.8rem;
            }
        }
       
        .pagination a:hover {
            background: var(--primary-dark);
        }

        .pagination .disabled {
            background: #cbd5e1;
            pointer-events: none;
        }

        .dark .pagination .disabled {
            background: #475569;
        }

        .pagination .active {
            background: var(--primary-dark);
        }

        /* Desktop styles (>= 1024px) */
        @media (min-width: 1024px) {
            .filters {
                display: grid;
                grid-template-columns: 1fr 1fr auto;
                gap: 2rem;
                padding: 2rem;
                margin-bottom: 2rem;
            }
        
            .message {
                padding: 1.5rem 2rem;
                margin-bottom: 1.5rem;
            }
        
            .message-sender {
                font-size: 1rem;
            }
        
            .message-content {
                font-size: 1rem;
                line-height: 1.6;
            }
        
            .message-date {
                font-size: 0.8rem;
            }
        
            .pagination {
                margin-top: 3rem;
                gap: 0.5rem;
            }
        
            .pagination a {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
                min-width: 2.5rem;
            }
        }
        
        /* Tablet styles (768px - 1023px) */
        @media (min-width: 768px) and (max-width: 1023px) {
            .container {
                width: 90%;
            }
        
            .filters {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                padding: 1.5rem;
            }
        
            .message {
                padding: 1.3rem 1.8rem;
            }
        
            .message-sender {
                font-size: 0.9rem;
            }
        
            .message-content {
                font-size: 0.9rem;
            }
        }
        
        /* Mobile styles (< 768px) */
        @media (max-width: 767px) {
            .container {
                width: 100%;
                margin: 1rem auto;
                padding: 0 0.8rem;
            }
        
            .toggle-wrapper {
                width: 100%;
                justify-content: center;
                align-items: center;
                gap: 0.5rem;
            }
        
            .toggle-wrapper span {
                font-size: 0.9rem;
            }
        
            /* Filters and other elements */
            .filters {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding: 1rem;
                margin-bottom: 1rem;
            }
        
            .form-group {
                gap: 0.3rem;
            }
        
            select, input[type="text"] {
                padding: 0.6rem;
                font-size: 0.9rem;
                width: 100%;
            }
        
            button {
                padding: 0.6rem 1.2rem;
                width: 100%;
            }
        
            .message {
                padding: 1rem;
                margin-bottom: 0,1rem;
            }
        
            .message-sender {
                font-size: 0.8rem;
                margin-bottom: 0.3rem;
            }
        
            .message-content {
                font-size: 0.85rem;
                line-height: 1.4;
                margin-bottom: 0.5rem;
            }
        
            .message-date {
                font-size: 0.7rem;
            }
        
            .pagination {
                margin-top: 1.5rem;
                gap: 0.3rem;
            }
        
            .pagination a {
                padding: 0.4rem 0.8rem;
                font-size: 0.75rem;
                min-width: 2rem;
            }
        }
    </style>
</head>
<header>
    <div class="new-container">
        <p>SMS Viewer</p>
    </div>
    <div class="header-top">
        <h1>p</h1>
    </div>
    <div class="header-bottom">
        <h1>p</h1>
    </div>
</header>
<body class="<?['darkMode'] === 'true' ? 'dark' : ''; ?>">
    <div class="container">
        <form method="post" action="" class="filters">
            <div class="form-group">
                <label for="sender">Select Sender</label>
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
                <label for="search">Search Messages</label>
                <input type="text" id="search" name="search" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Enter keywords...">
            </div>

            <button type="submit">Apply Filters</button>
        </form>

        <ul class="message-list">
            <?php if (!empty($currentMessages)): ?>
                <?php foreach ($currentMessages as $sms): ?>
                    <li class="message">
                        <div class="message-sender"><?= htmlspecialchars($sms['address']) ?></div>
                        <div class="message-content"><?= nl2br(htmlspecialchars($sms['body'])) ?></div>
                        <div class="message-date"><?= htmlspecialchars($sms['date']) ?></div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="message">
                    <div class="message-content">No messages found.</div>
                </li>
            <?php endif; ?>
        </ul>

        <div class="pagination">
            <?php
                // Calculate the range of page numbers to display
                $range = 10;
                $half_range = floor($range / 2);
                
                // Calculate start and end page numbers
                if ($totalPages <= $range) {
                    $start_page = 1;
                    $end_page = $totalPages;
                } else {
                    if ($currentPage <= $half_range) {
                        $start_page = 1;
                        $end_page = $range;
                    } elseif ($currentPage > ($totalPages - $half_range)) {
                        $start_page = $totalPages - $range + 1;
                        $end_page = $totalPages;
                    } else {
                        $start_page = $currentPage - $half_range;
                        $end_page = $currentPage + $half_range - 1;
                    }
                }
            ?>
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?= $currentPage - 1 ?><?= $selectedSender ? '&sender=' . urlencode($selectedSender) : '' ?><?= $searchQuery ? '&search=' . urlencode($searchQuery) : '' ?>">Previous</a>
            <?php else: ?>
                <a href="#" class="disabled">Previous</a>
            <?php endif; ?>
        
            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                <a href="?page=<?= $i ?><?= $selectedSender ? '&sender=' . urlencode($selectedSender) : '' ?><?= $searchQuery ? '&search=' . urlencode($searchQuery) : '' ?>" 
                   class="<?= ($currentPage == $i) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        
            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?= $currentPage + 1 ?><?= $selectedSender ? '&sender=' . urlencode($selectedSender) : '' ?><?= $searchQuery ? '&search=' . urlencode($searchQuery) : '' ?>">Next</a>
            <?php else: ?>
                <a href="#" class="disabled">Next</a>
            <?php endif; ?>
        </div>

    <script>
const body = document.body;
const newContainer = document.querySelector('.new-container');

// Check system preference for dark mode
const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;

// Apply the theme based on system preference
if (prefersDarkMode) {
    body.classList.add('dark');
    newContainer.classList.add('dark');
} else {
    body.classList.remove('dark');
    newContainer.classList.remove('dark');
}

// Optional: Listen for changes in the system theme preference and update accordingly
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (e.matches) {
        body.classList.add('dark');
        newContainer.classList.add('dark');
    } else {
        body.classList.remove('dark');
        newContainer.classList.remove('dark');
    }
});

    </script>
</body>
</html>