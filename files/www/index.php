<?php
$jsonFilePath = 'select_theme/theme.json';

if (file_exists($jsonFilePath)) {
    $jsonData = file_get_contents($jsonFilePath);
    $data = json_decode($jsonData, true);
    $path = isset($data['path']) ? $data['path'] : 'default';
} else {
    $path = 'default';
}

switch ($path) {
    case 'argon':
        include('argon.php');
        break;
    case 'box-ui-old':
        include('box-ui-old.php');
        break;
    default:
        include('default.php');
        break;
}
?>