<!DOCTYPE html>
<html>
<head>
    <title>Shell Command</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .container {
            width: 90%;
            max-width: 600px;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        form, .buttons {
            margin-bottom: 10px;
        }
        label, input, button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        input {
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .buttons button {
            width: calc(33.333% - 8px);
            margin-right: 8px;
        }
        .buttons button:last-child {
            margin-right: 0;
        }
        #log {
            border: 1px solid #ccc;
            padding: 10px;
            height: 200px;
            overflow-y: scroll;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
        @media (max-width: 600px) {
            .buttons button {
                width: 100%;
                margin-bottom: 10px;
            }
            .buttons button:last-child {
                margin-bottom: 0;
            }
        }
    </style>
    <script>
        function setCommand(command) {
            document.getElementById("command").value = command;
        }
        function clearLog() {
            document.getElementById("log").innerHTML = "";
        }
    </script>
</head>
<body>
    <div class="container">
        <form method="post">
            <label for="command">Shell Command:</label>
            <input type="text" id="command" name="command" placeholder="Enter shell command" required>
            <button type="submit">Execute</button>
        </form>
        <div class="buttons">
            <button onclick="setCommand('su -c /data/adb/box/scripts/box.service start &&  su -c /data/adb/box/scripts/box.iptables enable')">Start BFR</button>
            <button onclick="setCommand('su -c /data/adb/box/scripts/box.iptables disable && su -c /data/adb/box/scripts/box.service stop')">STOP BFR</button>
            <button onclick="clearLog()">Clear Log</button>
	    <button onclick="setCommand('clear')">clear commad</button>
        </div>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $command = escapeshellcmd($_POST["command"]);
            $output = shell_exec($command);
            echo "<div id='log'><pre>$output</pre></div>";
        }
        ?>
        
    </div>
</body>
</html>
