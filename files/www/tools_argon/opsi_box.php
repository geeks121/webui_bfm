<?php
$p = $_SERVER['HTTP_HOST'];
$x = explode(':', $p);
$host = $x[0];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Box For Root</title>
    <link rel="stylesheet" href="css&fonts/opsi.css">
</head>
<body>
<header>
    <div class="new-container">
        <p onclick="showTab('BFR')">Box For Root</p>
        <p onclick="showTab('SERVICES')">Services</p>
        <p onclick="showTab('CHANGECONFIG')">Change Config</p>
        <p onclick="showTab('YAML')">Config.yaml</p>
        <p onclick="showTab('JSON')">Config.json</p>
        <p onclick="showTab('AKUN')">Akun</p>
    </div>
    <div class="header-top">
        <h1>o</h1>
    </div>
    <div class="header-bottom">
        <h1>o</h1>
    </div>
</header>

<div class="container">
    <div id="BFR" class="tab-content active">
        <iframe id="bfr" loading="lazy"></iframe>
    </div>
    <div id="SERVICES" class="tab-content">
        <iframe id="services" loading="lazy"></iframe>
    </div>
    <div id="CHANGECONFIG" class="tab-content">
        <iframe id="changeconfig" loading="lazy"></iframe>
    </div>
    <div id="YAML" class="tab-content">
        <iframe id="yaml" loading="lazy"></iframe>
    </div>
    <div id="JSON" class="tab-content">
        <iframe id="json" loading="lazy"></iframe>
    </div>
    <div id="AKUN" class="tab-content">
        <iframe id="akun" loading="lazy"></iframe>
    </div>
</div>

<script>
function showTab(tabName) {
    var tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(function(tab) {
        tab.classList.remove('active');
    });
    var tabLinks = document.querySelectorAll('.new-container p');
    tabLinks.forEach(function(link) {
        link.classList.remove('active-tab');
    });
    var activeTab = document.getElementById(tabName);
    if (activeTab) {
        activeTab.classList.add('active');
    }
    var activeLink = document.querySelector('p[onclick="showTab(\'' + tabName + '\')"]');
    if (activeLink) {
        activeLink.classList.add('active-tab');
    }

    // Load iframe src dynamically when tab is active
    if (tabName === 'BFR' && !document.getElementById('bfr').src) {
        document.getElementById('bfr').src = 'bfr&cfm/bfr.php';
    }
    if (tabName === 'SERVICES' && !document.getElementById('services').src) {
        document.getElementById('services').src = 'bfr&cfm/boxsettings.php';
    }
    if (tabName === 'CHANGECONFIG' && !document.getElementById('changeconfig').src) {
        document.getElementById('changeconfig').src = '../tools/default-config.php';
    }
    if (tabName === 'YAML' && !document.getElementById('yaml').src) {
        document.getElementById('yaml').src = 'http://<?php echo $host; ?>/tiny/index.php?p=data%2Fadb%2Fbox%2Fclash&view=config.yaml';
    }
    if (tabName === 'JSON' && !document.getElementById('json').src) {
        document.getElementById('json').src = 'http://<?php echo $host; ?>/tiny/index.php?p=data%2Fadb%2Fbox%2Fsing-box&view=config.json';
    }
    if (tabName === 'AKUN' && !document.getElementById('akun').src) {
        document.getElementById('akun').src = 'http://<?php echo $host; ?>/tiny/index.php?p=data%2Fadb%2Fbox%2Fisiakun&view=akun.yaml';
    }
}

// Set default active tab
document.addEventListener("DOMContentLoaded", function() {
    showTab('BFR');
});
</script>

</body>
</html>