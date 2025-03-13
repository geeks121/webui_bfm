<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ip = $_POST['ip'] ?? '192.168.43.2';
    $subnet = $_POST['subnet'] ?? '24';
    $interface = $_POST['interface'] ?? 'wlan1';

    $command = "su -c 'ip address add {$ip}/{$subnet} dev {$interface}'";
    $output = shell_exec($command);
    
    if (empty($output)) {
        $output = "Command executed successfully: {$command}";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Set IP Address</title>
    <!-- Import Materialize CSS and JS from the specified paths -->
    <link rel="stylesheet" href="../auth/css/materialize.min.css" />
    <script src="../auth/js/materialize.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body, .container, .input-field label, .btn {
            color: white !important;
        }
        .input-field input {
            color: white !important;
        }
        .input-field input::placeholder {
            color: #bdbdbd !important;
        }
        .card-panel {
            color: black !important;
        }
    </style>
</head>
<body class="grey darken-4">

<div class="container">
    <h3 class="center-align">Set IP Address</h3>

    <?php if (isset($output)): ?>
        <div class='card-panel green lighten-4 green-text text-darken-4'>
            <?php echo nl2br(htmlspecialchars($output)); ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="row">
            <div class="input-field col s12">
                <input id="ip" name="ip" type="text" class="validate" value="<?php echo isset($ip) ? $ip : '192.168.43.2'; ?>" placeholder="192.168.43.2">
                <label for="ip" class="active">IP Address</label>
            </div>
            <div class="input-field col s12">
                <input id="subnet" name="subnet" type="text" class="validate" value="<?php echo isset($subnet) ? $subnet : '24'; ?>" placeholder="24">
                <label for="subnet" class="active">Subnet Mask</label>
            </div>
            <div class="input-field col s12">
                <input id="interface" name="interface" type="text" class="validate" value="<?php echo isset($interface) ? $interface : 'wlan1'; ?>" placeholder="wlan1">
                <label for="interface" class="active">Network Interface</label>
            </div>
        </div>
        <div class="row center-align">
            <button class="btn waves-effect waves-light" type="submit" name="action">Run</button>
        </div>
    </form>
</div>

</body>
</html>
